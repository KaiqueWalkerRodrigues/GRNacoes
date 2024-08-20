<?php

class Setor {

    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM setores WHERE deleted_at IS NULL ORDER BY setor ASC');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    public function cadastrar(Array $dados)
    {
        $setor  = ucwords(strtolower(trim($dados['setor'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO setores 
                                    (setor, created_at, updated_at)
                                    VALUES
                                    (:setor, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':setor', $setor);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $setor_id = $this->pdo->lastInsertId();

            $descricao = "Cadastrou o setor: $setor ($setor_id)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $usuario_logado); 
            $sql->bindParam(':descricao', $descricao); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('Location:/GRNacoes/configuracoes/setores');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function mostrar(int $id_setor)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM setores WHERE id_setor = :id_setor LIMIT 1');
        $sql->bindParam(':id_setor', $id_setor);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE setores SET
            setor = :setor,
            updated_at = :updated_at 
        WHERE id_setor = :id_setor
        ");

        $agora = date("Y-m-d H:i:s");

        $id_setor = $dados['id_setor'];
        $setor = ucwords(strtolower(trim($dados['setor'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam(':setor',$setor);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o setor: $setor ($id_setor)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $usuario_logado); 
            $sql->bindParam(':descricao', $descricao); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_setor, $usuario_logado)
    {
        $consulta_setor = $this->pdo->prepare('SELECT setor FROM setores WHERE id_setor = :id_setor');
        $consulta_setor->bindParam(':id_setor', $id_setor);
        $consulta_setor->execute();
        $resultado_setor = $consulta_setor->fetch(PDO::FETCH_ASSOC);

        if ($resultado_setor) {
            $nome_setor = $resultado_setor['setor'];
        } else {
            $nome_setor = "Setor Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE setores SET deleted_at = :deleted_at WHERE id_setor = :id_setor');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_setor', $id_setor);

        if ($sql->execute()) {
            $descricao = "Desativou o setor $nome_setor($id_setor)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $usuario_logado);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('location:/GRNacoes/configuracoes/setores');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }


    public function nomeSetor(int $id_setor)
    {
        $sql = $this->pdo->prepare('SELECT setor FROM setores WHERE id_setor = :id_setor');
        $sql->bindParam(':id_setor',$id_setor);
        $sql->execute();

        $setor = $sql->fetch(PDO::FETCH_OBJ);
    
        return $setor->setor;
    }
}

?>
