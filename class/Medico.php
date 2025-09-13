<?php

class Medico {

    # ATRIBUTOS	
	public $pdo;
    
    //Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    //Registrar Logs(Ações) do Sistema.
    private function addLog($acao, $descricao, $id_usuario){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario)
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)
                                ');

        $sql->bindParam(':acao', $acao); 
        $sql->bindParam(':id_usuario', $id_usuario); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':data', $agora); 
        $sql->execute();
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
     * listar todos os médicos
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listarAtivos(){
        $sql = $this->pdo->prepare('SELECT * FROM medicos WHERE deleted_at IS NULL AND ativo = 1 ORDER BY nome');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * listar todos os médicos
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listarDesativados(){
        $sql = $this->pdo->prepare('SELECT * FROM medicos WHERE deleted_at IS NULL AND ativo = 0 ORDER BY nome');        
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
        $titulo  = trim($dados['titulo']);
        $titulo .= " ".$dados['nome'];
        $crm = strtoupper(trim($dados['crm']));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO medicos 
                                    (nome, titulo, crm, ativo, created_at, updated_at)
                                    VALUES
                                    (:nome, :titulo, :crm, :ativo, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;
        $ativo = 1;

        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':crm', $crm);
        $sql->bindParam(':ativo', $ativo);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $medico_id = $this->pdo->lastInsertId();
            $descricao = "Cadastrou o médico: $nome ($medico_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);
            
            echo "
            <script>
                alert('Médico Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Médico!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
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
            titulo = :titulo,
            crm = :crm,
            ativo = :ativo,
            updated_at = :updated_at 
        WHERE id_medico = :id_medico
        ");

        $agora = date("Y-m-d H:i:s");

        $id_medico = $dados['id_medico'];
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $titulo = trim($dados['titulo']);
        $titulo .= " ".$dados['nome'];
        $crm = strtoupper(trim($dados['crm']));
        $ativo = $dados['ativo'];
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_medico',$id_medico);
        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':titulo',$titulo);
        $sql->bindParam(':crm',$crm);
        $sql->bindParam(':ativo',$ativo);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o médico: $nome ($id_medico)";
            $this->addLog("Editar",$descricao,$usuario_logado);
            
            echo "
            <script>
                alert('Médico Editado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Médico!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        }
    }

    /**
     * Deletar um médico
     *
     * @param int $id_medico
     * @param int $usuario_logado
     * @return void
     */
    public function deletar(int $id_medico, $usuario_logado)
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
            $descricao = "Deletou o médico $nome_medico($id_medico)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Médico Deletado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Médico!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        }
    }

    //Reativar um Médico Desativo.
    public function reativar(array $dados){
        // Reativar o medico
        $sql = $this->pdo->prepare("UPDATE medicos SET
                                        ativo = 1                          
                                        WHERE id_medico = :id_medico
                                    ");
        $sql->bindParam(':id_medico', $dados['id_medico']);
        $sql->execute();

        // Seleciona o medico reativado
        $sql = $this->pdo->prepare("SELECT * FROM medicos WHERE id_medico = :id_medico AND ativo = 1 AND deleted_at IS NULL");
        $sql->bindParam(':id_medico', $dados['id_medico']);
        $sql->execute();

        $medico_reativado = $sql->fetch(PDO::FETCH_OBJ);

        // Verificar se os medicos foram encontrados
        if ($medico_reativado) {
            // Adicionando log com o nome do medico que reativou e o medico reativado
            $descricao = "Reativou o médico: {$medico_reativado->nome} (ID: {$medico_reativado->id_medico})";
            $this->addLog('Reativar', $descricao, $dados['usuario_logado'] );
            echo "
            <script>
                alert('Médico Reativado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        }else{
            echo "
            <script>
                alert('Não foi possível Reativar o Médico!');
                window.location.href = '" . URL . "/configuracoes/medicos';
            </script>";
            exit;
        }
    }
}

?>
