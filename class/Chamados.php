<?php

class Chamados {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM chamados WHERE deleted_at IS NULL ORDER BY created_at DESC');        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }      

    public function cadastrar(Array $dados)
    {
        $titulo  = ucwords(strtolower(trim($dados['titulo'])));
        $status = 1;
        $id_usuario = $dados['id_usuario'];
        $id_setor = $dados['id_setor'];
        $urgencia = $dados['urgencia'];
        $descricao = trim($dados['descricao']);
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO chamados 
                                    (titulo, status, id_usuario, id_setor, urgencia, descricao, created_at, updated_at)
                                    VALUES
                                    (:titulo, :status, :id_usuario, :id_setor, :urgencia, :descricao, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':titulo', $titulo);          
        $sql->bindParam(':status', $status);          
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':id_setor', $id_setor);
        $sql->bindParam(':urgencia', $urgencia);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $chamado_id = $this->pdo->lastInsertId();

            $descricao_log = "Cadastrou o chamado: $titulo ($chamado_id)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':descricao', $descricao_log); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('Location:/GRNacoes/chamados/meus_chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function mostrar(int $id_chamado)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM chamados WHERE id_chamado = :id_chamado LIMIT 1');
        $sql->bindParam(':id_chamado', $id_chamado);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE chamados SET
            titulo = :titulo,
            status = :status,
            id_setor = :id_setor,
            urgencia = :urgencia,
            descricao = :descricao,
            updated_at = :updated_at 
        WHERE id_chamado = :id_chamado
        ");

        $agora = date("Y-m-d H:i:s");

        $id_chamado = $dados['id_chamado'];
        $status = $dados['status'];
        $titulo = ucwords(strtolower(trim($dados['titulo'])));
        $id_setor = $dados['id_setor'];
        $urgencia = $dados['urgencia'];
        $descricao = trim($dados['descricao']);
        $updated_at = $agora; 
        $id_usuario = $dados['id_usuario'];

        $sql->bindParam(':id_chamado',$id_chamado);
        $sql->bindParam(':titulo',$titulo);
        $sql->bindParam(':status',$status);
        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam(':urgencia',$urgencia);
        $sql->bindParam(':descricao',$descricao);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao_log = "Editou o chamado: $titulo ($id_chamado)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':descricao', $descricao_log); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_chamado, $id_usuario)
    {
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE chamados SET deleted_at = :deleted_at WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            $descricao_log = "Desativou o chamado $nome_chamado($id_chamado)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->bindParam(':descricao', $descricao_log);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
}

?>
