<?php

class Cargo {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * listar todos os cargos
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM cargos WHERE deleted_at IS NULL ORDER BY cargo');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * cadastra um novo cargo
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados)
{{
    $cargo  = ucwords(strtolower(trim($dados['cargo'])));
    $usuario_logado = $dados['usuario_logado'];
    $agora = date("Y-m-d H:i:s");

    $sql = $this->pdo->prepare('INSERT INTO cargos 
                                (cargo, created_at, updated_at)
                                VALUES
                                (:cargo, :created_at, :updated_at)
                            ');

    $created_at  = $agora;
    $updated_at  = $agora;

    $sql->bindParam(':cargo', $cargo);          
    $sql->bindParam(':created_at', $created_at);          
    $sql->bindParam(':updated_at', $updated_at);          

    if ($sql->execute()) {
        $cargo_id = $this->pdo->lastInsertId();

        $descricao = "Cadastrou o cargo: $cargo ($cargo_id)";
        
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
        
        return header('Location:/GRNacoes/configuracoes/cargos');
    } else {
        // Tratar falha na execução da query, se necessário
    }
}}

    /**
     * Retorna os dados de um ITEM
     * @param int $id_do_item
     * @return object
     * @example $variavel = $Obj->mostrar($id_do_item);
     */
    public function mostrar(int $id_cargo)
    {
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM cargos WHERE id_cargo = :id_cargo LIMIT 1');
        $sql->bindParam(':id_cargo', $id_cargo);
    	// Executar a consulta
    	$sql->execute();
    	// Pega os dados retornados
        // Como será retornado apenas UM tabela usamos fetch. para
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza um determinado usuario
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE cargos SET
            cargo = :cargo,
            updated_at = :updated_at 
        WHERE id_cargo = :id_cargo
        ");

        $agora = date("Y-m-d H:i:s");

        $id_cargo = $dados['id_cargo'];
        $cargo = ucwords(strtolower(trim($dados['cargo'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_cargo',$id_cargo);
        $sql->bindParam(':cargo',$cargo);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o cargo: $cargo ($id_cargo)";
            
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
     * Excluir ITEM
     *
     * @param integer $id_usuario
     * @return void (esse metodo não retorna nada)
     */
    public function desativar(int $id_cargo, $usuario_logado)
    {
        // Consulta para obter o nome do cargo
        $consulta_cargo = $this->pdo->prepare('SELECT cargo FROM cargos WHERE id_cargo = :id_cargo');
        $consulta_cargo->bindParam(':id_cargo', $id_cargo);
        $consulta_cargo->execute();
        $resultado_cargo = $consulta_cargo->fetch(PDO::FETCH_ASSOC);

        // Verifica se o cargo foi encontrado
        if ($resultado_cargo) {
            $nome_cargo = $resultado_cargo['cargo'];
        } else {
            // Se o cargo não for encontrado, use um nome genérico
            $nome_cargo = "Cargo Desconhecido";
        }

        // Atualiza o registro de cargo com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE cargos SET deleted_at = :deleted_at WHERE id_cargo = :id_cargo');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_cargo', $id_cargo);

        if ($sql->execute()) {
            $descricao = "Desativou o cargo $nome_cargo($id_cargo)";
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

            // Redireciona para a página de configurações de cargos
            return header('location:/GRNacoes/configuracoes/cargos');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
    public function nomeCargo(int $id_cargo)
    {
        $sql = $this->pdo->prepare('SELECT cargo FROM cargos WHERE id_cargo = :id_cargo');
        $sql->bindParam(':id_cargo',$id_cargo);
        $sql->execute();

        $cargo = $sql->fetch(PDO::FETCH_OBJ);
    
        return $cargo->cargo;
    }

 }

?>