<?php

class Mensagem {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    public function listar(int $id_chat){
        $sql = $this->pdo->prepare('SELECT * FROM mensagens WHERE id_chat = :id_chat AND deleted_at IS NULL ORDER BY created_at DESC');
        $sql->bindParam(':id_chat', $id_chat);
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }

    public function cadastrar(array $dados)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO mensagens 
                                    (id_chat, id_usuario, mensagem, created_at, updated_at)
                                    VALUES
                                    (:id_chat, :id_usuario, :mensagem, :created_at, :updated_at)
                                ');

        $sql->bindParam(':id_chat', $dados['id_chat']);
        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->bindParam(':mensagem', $dados['mensagem']);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Enviou uma mensagem no chat de ID: {$dados['id_chat']}";

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
        $sql = $this->pdo->prepare("UPDATE mensagens SET
            mensagem = :mensagem,
            updated_at = :updated_at 
        WHERE id_mensagem = :id_mensagem
        ");

        $agora = date("Y-m-d H:i:s");

        $sql->bindParam(':id_mensagem', $dados['id_mensagem']);
        $sql->bindParam(':mensagem', $dados['mensagem']);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Editou uma mensagem no chat de ID: {$dados['id_chat']}";

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
