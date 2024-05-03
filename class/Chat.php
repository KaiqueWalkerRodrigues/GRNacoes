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

    public function cadastrar(Array $dados)
    {

        //Cadastra o Chat
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO chats 
                                    (created_at, updated_at)
                                    VALUES
                                    (:created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);      

        $sql->execute();
        //Fim cadastra o chat

        //Cadastra os usuarios no chat
        $id_chat = $this->pdo->lastInsertId();;
        
        $sql = $this->pdo->prepare('INSERT INTO chats_usuarios 
                                    (id_usuario, id_chat)
                                    VALUES
                                    (:id_usuario, :id_chat)
                                ');

        $id_usuario = $dados['id_usuario'];

        $sql->bindParam(':id_usuario', $id_usuario);          
        $sql->bindParam(':id_chat', $id_chat);   
        
        $sql->execute();

        //2° Usuario

        $sql = $this->pdo->prepare('INSERT INTO chats_usuarios 
                                    (id_usuario, id_chat)
                                    VALUES
                                    (:id_usuario, :id_chat)
                                ');

        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_usuario', $usuario_logado);          
        $sql->bindParam(':id_chat', $id_chat);   
        
        $sql->execute();
        //Fim cadastra os usuarios no chat

        if ($sql->execute()) {

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
        } else {
            // Tratar falha na execução da query, se necessário
        }
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

        $id_chat = $dados['id_chat'];
        $updated_at = $agora; 

        $sql->bindParam(':id_chat',$id_chat);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o chat de ID: $id_chat";

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
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_chat)
    {
        $consulta_chat = $this->pdo->prepare('SELECT id_chat FROM chats WHERE id_chat = :id_chat');
        $consulta_chat->bindParam(':id_chat', $id_chat);
        $consulta_chat->execute();
        $resultado_chat = $consulta_chat->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chat) {
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
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
}

?>
