<?php

class Mensagem {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    public function listar(int $conversa_id){
        $sql = $this->pdo->prepare('
            SELECT m.* 
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            WHERE cm.id_conversa = :conversa_id AND m.deleted_at IS NULL
            ORDER BY m.created_at DESC
        ');
        $sql->bindParam(':conversa_id', $conversa_id);
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

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

            // Log da ação
            $descricao = "Enviou uma mensagem na conversa de ID: {$dados['id_conversa']}";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Enviar Mensagem';

            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();
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

        if ($sql->execute()) {
            $descricao = "Editou uma mensagem de ID: {$dados['id_mensagem']} na conversa de ID: {$dados['id_conversa']}";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Editar Mensagem';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();
        }
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

        if ($sql->execute()) {
            $descricao = "Excluiu uma mensagem de ID: $id_mensagem";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Excluir Mensagem';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $deleted_at);
            $sql->execute();
        }
    }
}

?>
