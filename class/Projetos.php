<?php

class Projeto {

    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM projetos WHERE deleted_at IS NULL');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    public function cadastrar(Array $dados)
    {

        //Cadastra o Projeto
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO projetos 
                                    (titulo, id_usuario, status, data_conclusao, descricao, created_at, updated_at)
                                    VALUES
                                    (:titulo, :id_usuario, :status, :data_conclusao, :descricao, :created_at, :updated_at)
                                ');

        $titulo  = $dados['titulo'];
        $id_usuario  = $dados['id_usuario'];
        $status  = 1;
        $data_conclusao  = $dados['data_conclusao'];
        $descricao  = $dados['descricao']; 
        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':titulo', $titulo);          
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':status', $status);
        $sql->bindParam(':data_conclusao', $data_conclusao);
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);      

        if ($sql->execute()) {

            $descricaoLog = "Cadastrou o projeto de Título: $titulo e Descrição: $descricao"; // Adicionado descrição do log

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (id_usuario, acao, descricao, data)
                                        VALUES
                                        (:id_usuario, :acao, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':descricao', $descricaoLog); // Adicionado descrição do log
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('location:/GRNacoes/projetos');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function mostrar(int $id_projeto)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM projetos WHERE id_projeto = :id_projeto LIMIT 1');
        $sql->bindParam(':id_projeto', $id_projeto);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE projetos SET
            titulo = :titulo,
            id_usuario = :id_usuario,
            status = :status,
            data_conclusao = :data_conclusao,
            descricao = :descricao, 
            updated_at = :updated_at 
        WHERE id_projeto = :id_projeto
        ");

        $agora = date("Y-m-d H:i:s");

        $id_projeto = $dados['id_projeto'];
        $titulo = $dados['titulo'];
        $id_usuario = $dados['id_usuario']; // Adicionado id_usuario aqui
        $status = $dados['status'];
        $data_conclusao = $dados['data_conclusao'];
        $descricao = $dados['descricao']; 
        $updated_at = $agora; 

        $sql->bindParam(':id_projeto', $id_projeto);
        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':id_usuario', $id_usuario); // Adicionado id_usuario aqui
        $sql->bindParam(':status', $status);
        $sql->bindParam(':data_conclusao', $data_conclusao);
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricaoLog = "Editou o projeto de ID: $id_projeto e Descrição: $descricao"; // Adicionado descrição do log

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':descricao', $descricaoLog); // Adicionado descrição do log
            $sql->bindParam(':data', $agora); 
            $sql->bindParam(':id_usuario', $id_usuario); // Adicionado id_usuario aqui
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_projeto,$id_usuario)
    {
        $agora = date("Y-m-d H:i:s"); // Adicionado aqui para obter o momento atual

        $sql = $this->pdo->prepare('UPDATE projetos SET deleted_at = :deleted_at WHERE id_projeto = :id_projeto');
        $sql->bindParam(':deleted_at',$agora);
        $sql->bindParam(':id_projeto',$id_projeto);

        if ($sql->execute()) {
            $descricao = "Desativou o projeto de ID: $id_projeto";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario); // Adicionado id_usuario aqui
            $sql->execute();

            return header('location:/GRNacoes/projetos');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

}

?>
