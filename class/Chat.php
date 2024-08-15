<?php

class Chat {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM chats WHERE deleted_at IS NULL');
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }

    public function cadastrar(array $dados)
    {
        // Cadastra o Chat
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO chats 
                                    (created_at, updated_at)
                                    VALUES
                                    (:created_at, :updated_at)
                                ');

        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        $sql->execute();

        $id_chat = $this->pdo->lastInsertId();

        // Cadastra os usuários no chat
        $sql = $this->pdo->prepare('INSERT INTO chats_usuarios 
                                    (id_usuario, id_chat)
                                    VALUES
                                    (:id_usuario, :id_chat)
                                ');

        $sql->bindParam(':id_chat', $id_chat);

        // Primeiro usuário
        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->execute();

        // Segundo usuário
        $sql->bindParam(':id_usuario', $dados['usuario_logado']);
        $sql->execute();

        // Log
        $descricao = "Cadastrou o chat de ID: $id_chat";
        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data)
                                    VALUES
                                    (:acao, :descricao, :data)
                                ');

        $acao = 'Cadastrar';

        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->execute();

        return header('Location:/caminho/para/chats');
    }

    public function mostrar(int $id_chat)
    {
        $sql = $this->pdo->prepare('SELECT * FROM chats WHERE id_chat = :id_chat LIMIT 1');
        $sql->bindParam(':id_chat', $id_chat);
        $sql->execute();
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE chats SET
            updated_at = :updated_at 
        WHERE id_chat = :id_chat
        ");

        $agora = date("Y-m-d H:i:s");

        $sql->bindParam(':id_chat', $dados['id_chat']);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Editou o chat de ID: {$dados['id_chat']}";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();
        }
    }

    public function desativar(int $id_chat)
    {
        $sql = $this->pdo->prepare('UPDATE chats 
                                    SET 
                                    deleted_at = :deleted_at 
                                    WHERE id_chat = :id_chat
                                    ');

        $agora = date("Y-m-d H:i:s");

        $sql->bindParam(':id_chat', $id_chat);
        $sql->bindParam(':deleted_at', $agora);

        if ($sql->execute()) {
            $descricao = "Desativou o chat de ID: $id_chat";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('location:/caminho/para/chats');
        }
    }
}

?>
