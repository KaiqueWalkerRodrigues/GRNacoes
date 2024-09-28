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

            // Inserir a relação na tabela 'conversas_mensagens'
            $sql = $this->pdo->prepare('INSERT INTO conversas_mensagens 
                                        (id_conversa, id_mensagem)
                                        VALUES
                                        (:id_conversa, :id_mensagem)
                                    ');
            $sql->bindParam(':id_conversa', $dados['id_conversa']);
            $sql->bindParam(':id_mensagem', $id_mensagem);
            $sql->execute();

            $url = 'Location:/GRNacoes/conversa?id='.$dados['id_conversa'];
            return header($url);
        }
    }

    public function cadastrarNoChamado(array $dados)
    {
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

            $url = 'Location:/GRNacoes/chamados/?id='.$dados['id_chamado'];
            return header($url);
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
        

}

    include_once('Conexao.php');
    $Mensagem = new Mensagem();

    if (isset($_POST['id_avatar_destinatario'])) {
        $Mensagem->cadastrar($_POST);
        header('Location: /GRNacoes/chat?id=' . $_POST['id_conversa'] . '&id_destinatario=' . $_POST['id_destinatario']);
        exit();
    }    
    if (isset($_POST['id_destinatario_avatar'])) {
        $Mensagem->cadastrarNoChamado($_POST);
        header('Location: /GRNacoes/chamados/chat_chamado?id=' . $_POST['id_chamado'] . '&id_destinatario_avatar=' . $_POST['id_destinatario_avatar']);
        exit();
    }    
?>
