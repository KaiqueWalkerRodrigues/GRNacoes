<?php

class Catarata_Lente {

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
     * listar todos os Lentes
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_lentes WHERE deleted_at IS NULL ORDER BY lente');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * cadastra um novo Lente
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados){
        $Lente  = ucwords(strtolower(trim($dados['lente'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO catarata_lentes 
                                    (Lente, created_at, updated_at)
                                    VALUES
                                    (:Lente, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':Lente', $Lente);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $Lente_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou a lente: $Lente ($Lente_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Lente Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Lente!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
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
    public function mostrar(int $id_lente){
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM catarata_lentes WHERE id_lente = :id_lente LIMIT 1');
        $sql->bindParam(':id_lente', $id_lente);
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
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE catarata_lentes SET
            Lente = :Lente,
            updated_at = :updated_at 
        WHERE id_catarata_lente = :id_lente
        ");

        $agora = date("Y-m-d H:i:s");

        $id_lente = $dados['id_lente'];
        $Lente = trim($dados['lente']);
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_lente',$id_lente);
        $sql->bindParam(':Lente',$Lente);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou a lente: $Lente ($id_lente)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Lente Editado com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Lente!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
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
    public function deletar(int $id_lente, $usuario_logado){
        // Consulta para obter o nome do Lente
        $consulta_Lente = $this->pdo->prepare('SELECT Lente FROM catarata_lentes WHERE id_catarata_lente = :id_lente');
        $consulta_Lente->bindParam(':id_lente', $id_lente);
        $consulta_Lente->execute();
        $resultado_Lente = $consulta_Lente->fetch(PDO::FETCH_ASSOC);

        // Verifica se o Lente foi encontrado
        if ($resultado_Lente) {
            $nome_Lente = $resultado_Lente['Lente'];
        } else {
            // Se o Lente não for encontrado, use um nome genérico
            $nome_Lente = "Lente Desconhecido";
        }

        // Atualiza o registro de Lente com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE catarata_lentes SET deleted_at = :deleted_at WHERE id_catarata_lente = :id_lente');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_lente', $id_lente);

        if ($sql->execute()) {
            $descricao = "Deletou o Lente $nome_Lente ($id_lente)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Lente Deletado com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Lente!');
                window.location.href = '" . URL . "/cirurgias/catarata/configuracoes/lentes';
            </script>";
            exit;
        }
    }

 }

?>