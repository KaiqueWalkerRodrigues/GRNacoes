<?php

class Medicos {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * listar todos os médicos
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM medicos WHERE deleted_at IS NULL ORDER BY nome');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * cadastra um novo médico
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados)
    {
        $nome  = ucwords(strtolower(trim($dados['nome'])));
        $crm = strtoupper(trim($dados['crm']));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO medicos 
                                    (nome, crm, ativo, created_at, updated_at)
                                    VALUES
                                    (:nome, :crm, :ativo, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;
        $ativo = 1;

        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':crm', $crm);
        $sql->bindParam(':ativo', $ativo);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $medico_id = $this->pdo->lastInsertId();

            $descricao = "Cadastrou o médico: $nome ($medico_id)";
            
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
            
            return header('Location:/GRNacoes/configuracoes/medicos');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    /**
     * Retorna os dados de um médico
     * @param int $id_medico
     * @return object
     * @example $variavel = $Obj->mostrar($id_medico);
     */
    public function mostrar(int $id_medico)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM medicos WHERE id_medico = :id_medico LIMIT 1');
        $sql->bindParam(':id_medico', $id_medico);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza os dados de um médico
     *
     * @param array $dados   
     * @return int id - do médico
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE medicos SET
            nome = :nome,
            crm = :crm,
            updated_at = :updated_at 
        WHERE id_medico = :id_medico
        ");

        $agora = date("Y-m-d H:i:s");

        $id_medico = $dados['id_medico'];
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $crm = strtoupper(trim($dados['crm']));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_medico',$id_medico);
        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':crm',$crm);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o médico: $nome ($id_medico)";
            
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

    /**
     * Desativa um médico
     *
     * @param int $id_medico
     * @param int $usuario_logado
     * @return void
     */
    public function desativar(int $id_medico, $usuario_logado)
    {
        $consulta_medico = $this->pdo->prepare('SELECT nome FROM medicos WHERE id_medico = :id_medico');
        $consulta_medico->bindParam(':id_medico', $id_medico);
        $consulta_medico->execute();
        $resultado_medico = $consulta_medico->fetch(PDO::FETCH_ASSOC);

        if ($resultado_medico) {
            $nome_medico = $resultado_medico['nome'];
        } else {
            $nome_medico = "Médico Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE medicos SET deleted_at = :deleted_at WHERE id_medico = :id_medico');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_medico', $id_medico);

        if ($sql->execute()) {
            $descricao = "Desativou o médico $nome_medico($id_medico)";
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

            return header('location:/GRNacoes/configuracoes/medicos');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
}

?>
