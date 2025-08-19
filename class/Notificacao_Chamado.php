<?php

class Notificacao_Chamado {

    public $pdo;

    // Construir conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    // Registrar Logs (Ações) do Sistema.
    private function addLog($acao, $descricao, $id_usuario){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario)
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
    }

    public function listar(?int $id_setor = null, ?int $id_usuario_destino = null, int $limit = 5, ?int $apenas_nao_lidas_para_usuario = null)
    {
        $limit = max(1, (int)$limit);

        $sql = "SELECT n.id_notificacao_chamado, n.id_chamado, n.id_usuario, n.id_setor, n.tipo, n.texto, n.created_at
                FROM notificacoes_chamados n";

        $where  = ["n.deleted_at IS NULL"];
        $params = [];

        // Filtro de destino (mantido do seu código original)
        if (!empty($id_usuario_destino) && !empty($id_setor)) {
            $where[] = "(n.id_usuario = :id_usuario_destino OR n.id_setor = :id_setor_destino)";
            $params[':id_usuario_destino'] = (int)$id_usuario_destino;
            $params[':id_setor_destino']   = (int)$id_setor;
        } elseif (!empty($id_usuario_destino)) {
            $where[] = "n.id_usuario = :id_usuario_destino";
            $params[':id_usuario_destino'] = (int)$id_usuario_destino;
        } elseif (!empty($id_setor)) {
            $where[] = "n.id_setor = :id_setor_destino";
            $params[':id_setor_destino']   = (int)$id_setor;
        }

        // Se for para trazer SOMENTE não lidas para um usuário específico
        if (!empty($apenas_nao_lidas_para_usuario)) {
            $sql .= " LEFT JOIN notificacoes_chamados_usuarios nu
                    ON nu.id_notificacao = n.id_notificacao_chamado
                    AND nu.id_usuario = :usuario_visualizador";
            $params[':usuario_visualizador'] = (int)$apenas_nao_lidas_para_usuario;

            // Se não há registro em nu, então é não lida por esse usuário
            $where[] = "nu.id_notificacao IS NULL";
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY n.created_at DESC LIMIT {$limit}";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $k => $v) {
            $type = is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($k, $v, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function cadastrar(array $dados)
    {
        $agora           = date("Y-m-d H:i:s");
        $id_chamado      = isset($dados['id_chamado']) ? (int)$dados['id_chamado'] : null;

        // id_usuario pode ser opcional (tipo 0). Se não vier, deixa NULL.
        $id_usuario = (isset($dados['id_usuario']) && $dados['id_usuario'] !== '' )
            ? (int)$dados['id_usuario']
            : null;

        // id_setor pode ser opcional
        $id_setor = (isset($dados['id_setor']) && $dados['id_setor'] !== '' )
            ? (int)$dados['id_setor']
            : null;

        // TEXTO é string!
        $texto = isset($dados['texto']) ? (string)$dados['texto'] : '';

        // tipo pode ser numerico (0/1/2). Se você quiser manter numerico, não use ucwords.
        $tipo  = isset($dados['tipo']) ? (string)$dados['tipo'] : '0';

        $usuario_logado  = isset($dados['usuario_logado']) ? (int)$dados['usuario_logado'] : null;

        // Validações básicas
        if (empty($id_chamado)) {
            throw new Exception("id_chamado é obrigatório.");
        }

        $sql = $this->pdo->prepare('INSERT INTO notificacoes_chamados
                                    (id_chamado, id_usuario, id_setor, texto, tipo, created_at, updated_at)
                                    VALUES
                                    (:id_chamado, :id_usuario, :id_setor, :texto, :tipo, :created_at, :updated_at)');

        // Sempre INT
        $sql->bindValue(':id_chamado', $id_chamado, PDO::PARAM_INT);

        // Se for NULL, bind como NULL; senão, INT
        if (is_null($id_usuario)) {
            $sql->bindValue(':id_usuario', null, PDO::PARAM_NULL);
        } else {
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        }

        if (is_null($id_setor)) {
            $sql->bindValue(':id_setor', null, PDO::PARAM_NULL);
        } else {
            $sql->bindValue(':id_setor', $id_setor, PDO::PARAM_INT);
        }

        // Texto é string
        $sql->bindValue(':texto', $texto, PDO::PARAM_STR);

        // Tipo como string (se você usa 0/1/2, pode gravar como VARCHAR ou mudar coluna para TINYINT)
        $sql->bindValue(':tipo', $tipo, PDO::PARAM_STR);

        $sql->bindValue(':created_at', $agora);
        $sql->bindValue(':updated_at', $agora);

        if ($sql->execute()) {
            $id_notificacao_chamado = $this->pdo->lastInsertId();

            $descricao = "Enviou uma Notificação de Chamado: $tipo (ID $id_notificacao_chamado) para o Chamado $id_chamado";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Notificação cadastrada com sucesso!');
                window.location.href = '" . URL . "/chamados/notificacoes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cadastrar a Notificação!');
                window.location.href = '" . URL . "/chamados/notificacoes';
            </script>";
            exit;
        }
    }


    // Mostrar informações de uma Notificação
    public function mostrar(int $id_notificacao_chamado)
    {
        $sql = $this->pdo->prepare('SELECT * FROM notificacoes_chamados 
                                    WHERE id_notificacao_chamado = :id LIMIT 1');
        $sql->bindParam(':id', $id_notificacao_chamado, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function marcarComoLida(int $id_notificacao, int $id_usuario, ?int $usuario_logado = null)
    {
        if ($id_notificacao <= 0 || $id_usuario <= 0) {
            throw new Exception("Parâmetros inválidos para marcar como lida.");
        }

        // Evita duplicados (depende da UNIQUE uk_notif_user)
        $sql = $this->pdo->prepare('INSERT IGNORE INTO notificacoes_chamados_usuarios
                                (id_notificacao, id_usuario, created_at)
                                VALUES (:id_notificacao, :id_usuario, NOW())');
        $sql->bindValue(':id_notificacao', $id_notificacao, PDO::PARAM_INT);
        $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $ok = $sql->execute();

        // Log opcional
        $usuarioLog = $usuario_logado ?? $id_usuario;
        $this->addLog("Marcar Como Lida",
            "Notificação {$id_notificacao} marcada como lida pelo usuário {$id_usuario}",
            $usuarioLog
        );

        return $ok;
    }

    // Editar Notificação de Chamado
    // public function editar(array $dados)
    // {
    //     $agora                  = date("Y-m-d H:i:s");
    //     $id                     = intval($dados['id_notificacao_chamado']);
    //     $id_chamado             = intval($dados['id_chamado']);
    //     $id_usuario             = intval($dados['id_usuario']);
    //     $id_setor               = !empty($dados['id_setor']) ? intval($dados['id_setor']) : null;
    //     $texto                   = trim($dados['texto']);
    //     $tipo                   = ucwords(strtolower(trim($dados['tipo'])));
    //     $usuario_logado         = intval($dados['usuario_logado']);

    //     $sql = $this->pdo->prepare("UPDATE notificacoes_chamados SET
    //             id_chamado = :id_chamado,
    //             id_usuario = :id_usuario,
    //             id_setor   = :id_setor,
    //             tipo       = :tipo,
    //             texto       = :texto,
    //             updated_at = :updated_at
    //         WHERE id_notificacao_chamado = :id");

    //     $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
    //     $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    //     $sql->bindParam(':id_setor', $id_setor, PDO::PARAM_INT);
    //     $sql->bindParam(':texto', $texto);
    //     $sql->bindParam(':tipo', $tipo);
    //     $sql->bindParam(':updated_at', $agora);
    //     $sql->bindParam(':id', $id, PDO::PARAM_INT);

    //     if ($sql->execute()) {
    //         $descricao = "Editou Notificação $id (Tipo: $tipo) do Chamado $id_chamado";
    //         $this->addLog("Editar", $descricao, $usuario_logado);

    //         echo "
    //         <script>
    //             alert('Notificação editada com sucesso!');
    //             window.location.href = '" . URL . "/chamados/notificacoes';
    //         </script>";
    //         exit;
    //     } else {
    //         echo "
    //         <script>
    //             alert('Não foi possível editar a Notificação!');
    //             window.location.href = '" . URL . "/chamados/notificacoes';
    //         </script>";
    //         exit;
    //     }
    // }

    // Deletar (soft delete) uma Notificação
    // public function deletar(int $id_notificacao_chamado, int $usuario_logado)
    // {
    //     // Busca dados para log (opcional)
    //     $consulta = $this->pdo->prepare('SELECT id_chamado, tipo 
    //                                      FROM notificacoes_chamados 
    //                                      WHERE id_notificacao_chamado = :id');
    //     $consulta->bindParam(':id', $id_notificacao_chamado, PDO::PARAM_INT);
    //     $consulta->execute();
    //     $registro = $consulta->fetch(PDO::FETCH_ASSOC);

    //     $agora = date("Y-m-d H:i:s");
    //     $sql = $this->pdo->prepare('UPDATE notificacoes_chamados 
    //                                 SET deleted_at = :deleted_at 
    //                                 WHERE id_notificacao_chamado = :id');
    //     $sql->bindParam(':deleted_at', $agora);
    //     $sql->bindParam(':id', $id_notificacao_chamado, PDO::PARAM_INT);

    //     if ($sql->execute()) {
    //         $tipo_desc   = $registro ? $registro['tipo'] : 'Desconhecido';
    //         $id_chamado  = $registro ? $registro['id_chamado'] : 'N/D';
    //         $descricao   = "Deletou Notificação $id_notificacao_chamado (Tipo: $tipo_desc) do Chamado $id_chamado";
    //         $this->addLog("Deletar", $descricao, $usuario_logado);

    //         echo "
    //         <script>
    //             alert('Notificação deletada com sucesso!');
    //             window.location.href = '" . URL . "/chamados/notificacoes';
    //         </script>";
    //         exit;
    //     } else {
    //         echo "
    //         <script>
    //             alert('Não foi possível deletar a Notificação!');
    //             window.location.href = '" . URL . "/chamados/notificacoes';
    //         </script>";
    //         exit;
    //     }
    // }
}

?>
