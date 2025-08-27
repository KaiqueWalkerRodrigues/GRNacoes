<?php

class Mensagem {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    public function listar(int $conversa_id, int $id_usuario) {
        // Lista as mensagens da conversa
        $sql = $this->pdo->prepare('
            SELECT m.*, 
            CASE 
                WHEN ml.id_mensagem IS NULL THEN 0 
                ELSE 1 
            END AS lida
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
            WHERE cm.id_conversa = :conversa_id AND m.deleted_at IS NULL
            ORDER BY m.created_at ASC
        ');
    
        $sql->bindParam(':conversa_id', $conversa_id);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Marcar mensagens como lidas
        $this->marcarComoLida($id_usuario, $conversa_id);
    
        return $dados;
    }

    public function listarPorChamado(int $id_chamado, int $id_usuario) {
        $sql = $this->pdo->prepare('
            SELECT m.*,
                CASE WHEN ml.id_mensagem IS NULL THEN 0 ELSE 1 END AS lida
            FROM mensagens m
            INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml 
                ON ml.id_mensagem = m.id_mensagem 
                AND ml.id_usuario  = :id_usuario
            WHERE cm.id_chamado = :id_chamado
            AND m.deleted_at IS NULL
            ORDER BY m.created_at ASC
        ');
        $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->execute();

        // Marca como lidas todas as mensagens exibidas
        $this->marcarComoLidaChamado($id_usuario, $id_chamado);

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function mostrar(int $id_mensagem) {
        $sql = $this->pdo->prepare('
            SELECT * 
            FROM mensagens 
            WHERE id_mensagem = :id_mensagem AND deleted_at IS NULL
        ');
        $sql->bindParam(':id_mensagem', $id_mensagem);
        $sql->execute();

        $mensagem = $sql->fetch(PDO::FETCH_OBJ);

        return $mensagem;
    }

    public function cadastrar(array $dados)
    {
        // Permitir: texto, anexos ou ambos
        $temTexto = strlen(trim($dados['mensagem'] ?? '')) > 0;

        // Há anexos?
        $temArquivos = isset($_FILES['attachments']) && !empty($_FILES['attachments']['name']);
        if (is_array($temArquivos)) {
            $temArquivos = array_filter($_FILES['attachments']['name'], function($n){ return $n !== null && $n !== ''; });
            $temArquivos = count($temArquivos) > 0;
        }

        // Se não há texto nem arquivos, não faz nada
        if (!$temTexto && !$temArquivos) {
            return;
        }

        $agora = date("Y-m-d H:i:s");

        // Insere mensagem (campo NOT NULL -> usa '' se vier vazia)
        $sql = $this->pdo->prepare('
            INSERT INTO mensagens (id_usuario, mensagem, created_at, updated_at)
            VALUES (:id_usuario, :mensagem, :created_at, :updated_at)
        ');
        $sql->bindValue(':id_usuario', $dados['id_usuario'], PDO::PARAM_INT);
        $sql->bindValue(':mensagem', $temTexto ? $dados['mensagem'] : '');
        $sql->bindValue(':created_at', $agora);
        $sql->bindValue(':updated_at', $agora);

        if ($sql->execute()) {
            $id_mensagem = (int)$this->pdo->lastInsertId();

            // Vincula à conversa
            if (!empty($dados['id_conversa'])) {
                $sql2 = $this->pdo->prepare('
                    INSERT INTO conversas_mensagens (id_conversa, id_mensagem)
                    VALUES (:id_conversa, :id_mensagem)
                ');
                $sql2->bindValue(':id_conversa', $dados['id_conversa'], PDO::PARAM_INT);
                $sql2->bindValue(':id_mensagem', $id_mensagem, PDO::PARAM_INT);
                $sql2->execute();
            }

            // Salva anexos (se houver)
            $this->salvarArquivos($id_mensagem, $_FILES['attachments'] ?? null);

            // Redireciona de volta ao chat
            header('Location:' . URL . '/chat?id=' . $dados['id_conversa'] . '&id_destinatario=' . $dados['id_destinatario']);
            exit();
        }
    }


    public function cadastrarNoChamado(array $dados)
    {
        // Verifica se a mensagem não está vazia
        if (empty(trim($dados['mensagem']))) {
            return; // Não faz nada se a mensagem estiver vazia
        }

        $agora = date("Y-m-d H:i:s");

        // Inserir a mensagem na tabela 'mensagens'
        $sql = $this->pdo->prepare('INSERT INTO mensagens 
                                    (id_usuario, mensagem, created_at, updated_at)
                                    VALUES
                                    (:id_usuario, :mensagem, :created_at, :updated_at)
                                ');

        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->bindParam(':mensagem', $dados['mensagem']);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $id_mensagem = $this->pdo->lastInsertId();

            // Inserir a relação na tabela 'chamados_mensagens'
            $sql = $this->pdo->prepare('INSERT INTO chamados_mensagens 
                                        (id_chamado, id_mensagem)
                                        VALUES
                                        (:id_chamado, :id_mensagem)
                                    ');
            $sql->bindParam(':id_chamado', $dados['id_chamado']);
            $sql->bindParam(':id_mensagem', $id_mensagem);
            $sql->execute();

            $url = 'Location:'.URL.'/chamados/?id='.$dados['id_chamado'];
            return header($url);
        }
    }

    private function salvarArquivos(int $id_mensagem, ?array $files): void
    {
        if (!$files || !isset($files['name'])) return;

        // Caminho fixo informado
        $uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/GRNacoes/resources/anexos/chats/';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $count      = is_array($files['name']) ? count($files['name']) : 0;
        $maxBytes   = 20 * 1024 * 1024; // 20MB
        $permitidos = [
            'pdf','doc','docx','txt','rtf','odt','xls','xlsx','csv',
            'png','jpg','jpeg','gif','webp','mp4','mov','avi','mkv'
        ];

        for ($i = 0; $i < $count; $i++) {
            $nomeOriginal = $files['name'][$i] ?? null;
            $tmp_name     = $files['tmp_name'][$i] ?? null;
            $error        = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            $size         = $files['size'][$i] ?? 0;

            if (empty($nomeOriginal) || $error !== UPLOAD_ERR_OK) continue;
            if ($size <= 0 || $size > $maxBytes) continue;

            $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidos, true)) continue;

            // Gera nome único para o arquivo no disco (evita conflito + segurança)
            $uniq          = bin2hex(random_bytes(8));
            $arquivoSistema= $uniq . ($ext ? '.' . $ext : '');
            $dest          = $uploadDir . $arquivoSistema;

            if (@move_uploaded_file($tmp_name, $dest)) {
                // Grava metadados (agora com nome original + nome salvo no disco)
                $sql = $this->pdo->prepare('
                    INSERT INTO mensagens_anexos (id_mensagem, nome_original, arquivo_sistema, created_at)
                    VALUES (:id_mensagem, :nome_original, :arquivo_sistema, :created_at)
                ');
                $sql->bindValue(':id_mensagem', $id_mensagem, PDO::PARAM_INT);
                $sql->bindValue(':nome_original', $nomeOriginal);
                $sql->bindValue(':arquivo_sistema', $arquivoSistema);
                $sql->bindValue(':created_at', date('Y-m-d H:i:s'));
                $sql->execute();
            }
        }
    }


    public function editar(array $dados)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare("UPDATE mensagens SET
            mensagem = :mensagem,
            updated_at = :updated_at 
        WHERE id_mensagem = :id_mensagem
        ");

        $sql->bindParam(':id_mensagem', $dados['id_mensagem']);
        $sql->bindParam(':mensagem', $dados['mensagem']);
        $sql->bindParam(':updated_at', $agora);

        $sql->execute();
    }

    public function desativar(int $id_mensagem)
    {
        $sql = $this->pdo->prepare('UPDATE mensagens 
                                    SET 
                                    deleted_at = :deleted_at 
                                    WHERE id_mensagem = :id_mensagem
                                    ');

        $deleted_at = date("Y-m-d H:i:s");

        $sql->bindParam(':id_mensagem', $id_mensagem);
        $sql->bindParam(':deleted_at', $deleted_at);

        $sql->execute();
    }
    
    public function marcarComoLida(int $id_usuario, int $id_conversa) {
        $sql = $this->pdo->prepare('
            INSERT INTO mensagens_lidas (id_usuario, id_mensagem, lida_em)
            SELECT :id_usuario, m.id_mensagem, NOW()
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
            WHERE cm.id_conversa = :id_conversa AND ml.id_mensagem IS NULL
        ');
    
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':id_conversa', $id_conversa);
    
        $sql->execute();
    }    

    public function ultimaMensagem($id_conversa, $id_usuario) {
        $sql = $this->pdo->prepare("
            SELECT m.*, 
            CASE 
                WHEN ml.id_mensagem IS NOT NULL THEN 'lida'
                ELSE 'nao_lida'
            END as status_leitura
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario != :id_usuario
            WHERE cm.id_conversa = :id_conversa
            ORDER BY m.created_at DESC
            LIMIT 1
        ");
        $sql->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->execute();
    
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    function contarNaoLidasPorConversa(int $idConversa, int $idUsuario): int {
        $pdo = Conexao::conexao();
        $sql = $pdo->prepare("
            SELECT COUNT(*) AS qtd
            FROM conversas_mensagens cm
            INNER JOIN mensagens m ON m.id_mensagem = cm.id_mensagem
            LEFT JOIN mensagens_lidas ml
                ON ml.id_mensagem = m.id_mensagem
                AND ml.id_usuario  = :id_usuario       -- leitura por MIM
            WHERE cm.id_conversa = :id_conversa
            AND m.deleted_at IS NULL
            AND m.id_usuario <> :id_usuario            -- mensagens de OUTROS
            AND ml.id_mensagem IS NULL                 -- ainda não li
        ");
        $sql->bindValue(':id_conversa', $idConversa, PDO::PARAM_INT);
        $sql->bindValue(':id_usuario',  $idUsuario,  PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return (int)($row['qtd'] ?? 0);
    }

    public function marcarComoLidaChamado(int $id_usuario, int $id_chamado): void {
        $sql = $this->pdo->prepare('
            INSERT INTO mensagens_lidas (id_usuario, id_mensagem, lida_em)
            SELECT :id_usuario, m.id_mensagem, NOW()
            FROM mensagens m
            INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml 
                ON ml.id_mensagem = m.id_mensagem 
            AND ml.id_usuario  = :id_usuario
            WHERE cm.id_chamado = :id_chamado
            AND m.deleted_at IS NULL
            AND ml.id_mensagem IS NULL
        ');
        $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
        $sql->execute();
    }

}

include_once('Conexao.php');
$Mensagem = new Mensagem();

if (isset($_POST['id_avatar_destinatario'])) {
    $Mensagem->cadastrar($_POST);
    header('Location: '.URL.'/chat?id=' . $_POST['id_conversa'] . '&id_destinatario=' . $_POST['id_destinatario']);
    exit();
}  

if (isset($_POST['id_destinatario_avatar'])) {
    $Mensagem->cadastrarNoChamado($_POST);
    header('Location: '.URL.'/chamados/chat_chamado?id=' . $_POST['id_chamado'] . '&id_destinatario_avatar=' . $_POST['id_destinatario_avatar']);
    exit();
}
?>
