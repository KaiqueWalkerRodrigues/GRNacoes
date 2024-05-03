<?php

class Mensagem {

    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(int $id_mensagem){
        $sql = $this->pdo->prepare('SELECT * FROM mensagens WHERE id_mensagem = :id_mensagem AND deleted_at IS NULL');        
        $sql->bindParam(':id_mensagem', $id_mensagem);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    public function cadastrar(array $dados)
    {
        $id_usuario = $dados['id_usuario'];
        $mensagem = $dados['mensagem'];
        $id_chat = $dados['id_chat'];

        print_r($mensagem.' - '.$id_chat.' - '.$id_usuario);
        die();
        
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO mensagens 
                                    (id_mensagem, id_chat, id_usuario, mensagem, created_at, updated_at)
                                    VALUES
                                    (:id_mensagem, :id_chat, :id_usuario, :mensagem, :created_at, :updated_at)
                                ');
        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':id_mensagem', $id_mensagem);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':id_chat', $id_chat);
        $sql->bindParam(':mensagem', $mensagem);
        $sql->bindParam(':created_at', $created_at);
        $sql->bindParam(':updated_at', $updated_at);

        $sql->execute();
        //Fim envia a mensagem

        if ($sql->execute()) {
            $descricao = "Enviou uma mensagem no chat de ID: $id_mensagem";

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
        } else {
            // Tratar falha na execução da query, se necessário
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

        $id_mensagem = $dados['id_mensagem'];
        $mensagem = $dados['mensagem'];
        $updated_at = $agora; 

        $sql->bindParam(':id_mensagem', $id_mensagem);
        $sql->bindParam(':mensagem', $mensagem);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou uma mensagem no chat de ID: {$dados['id_mensagem']}";

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
        } else {
            // Tratar falha na execução da query, se necessário
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
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
}

?>
