<?php

class Convenio {

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
     * listar todos os Convenio
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM convenios WHERE deleted_at IS NULL ORDER BY convenio');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Listar todos convenios menos particular
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listarMenosParticular(){
        $sql = $this->pdo->prepare('SELECT * FROM convenios WHERE id_convenio != 1 AND deleted_at IS NULL ORDER BY convenio');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }  


    /**
     * cadastra um novo Convenio
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados){
        $convenio  = ucwords(strtolower(trim($dados['convenio'])));
        $razao_social  = ucwords(strtolower(trim($dados['razao_social'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO convenios 
                                    (convenio, razao_social, created_at, updated_at)
                                    VALUES
                                    (:convenio, :razao_social, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':convenio', $convenio);          
        $sql->bindParam(':razao_social', $razao_social);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $id_convenio = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Convênio: $convenio ($id_convenio)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Convênio Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Convênio!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um ITEM
     * @param int $id_do_item
     * @return object
     * @example $variavel = $Obj->mostrar($id_do_item);
     */
    public function mostrar(int $id_convenio){
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM convenios WHERE id_convenio = :id_convenio LIMIT 1');
        $sql->bindParam(':id_convenio', $id_convenio);
    	// Executar a consulta
    	$sql->execute();
    	// Pega os dados retornados
        // Como será retornado apenas UM tabela usamos fetch. para
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza um determinado convênio
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE convenios SET
            convenio = :convenio,
            razao_social = :razao_social,
            updated_at = :updated_at 
        WHERE id_convenio = :id_convenio
        ");

        $agora = date("Y-m-d H:i:s");

        $id_convenio = $dados['id_convenio'];
        $convenio = ucwords(strtolower(trim($dados['convenio'])));
        $razao_social = ucwords(strtolower(trim($dados['razao_social'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_convenio',$id_convenio);
        $sql->bindParam(':convenio',$convenio);
        $sql->bindParam(':razao_social',$razao_social);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Convênio: $convenio ($id_convenio)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Convênio Editado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Convênio!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        }
    }

    /**
     * Deleta um ITEM
     *
     * @param integer $id_usuario
     * @return void (esse metodo não retorna nada)
     */
    public function deletar(int $id_convenio, $usuario_logado){
        // Consulta para obter o nome do convenio
        $consulta_convenio = $this->pdo->prepare('SELECT convenio FROM convenios WHERE id_convenio = :id_convenio');
        $consulta_convenio->bindParam(':id_convenio', $id_convenio);
        $consulta_convenio->execute();
        $resultado_convenio = $consulta_convenio->fetch(PDO::FETCH_ASSOC);

        // Verifica se o convenio foi encontrado
        if ($resultado_convenio) {
            $nome_convenio = $resultado_convenio['convenio'];
        } else {
            // Se o convenio não for encontrado, use um nome genérico
            $nome_convenio = "Convênio Desconhecido";
        }

        // Atualiza o registro de convenio com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE convenios SET deleted_at = :deleted_at WHERE id_convenio = :id_convenio');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_convenio', $id_convenio);

        if ($sql->execute()) {
            $descricao = "Deletou o Convênio $nome_convenio ($id_convenio)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Convênio Deletado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Convênio!');
                window.location.href = '" . URL . "/configuracoes/convenios';
            </script>";
            exit;
        }
    }

 }

?>