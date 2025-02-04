<?php

class Lente_contato_fornecedor {

    # ATRIBUTOS	
    public $pdo;
    
    // Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    // Registrar Logs(Ações) do Sistema.
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
     * Listar todos os contatos de fornecedores
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM lente_contato_fornecedores WHERE deleted_at IS NULL ORDER BY fornecedor');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastrar um novo contato de fornecedor
     * @param Array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        $fornecedor  = ucwords(strtolower(trim($dados['fornecedor'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO lente_contato_fornecedores 
                                    (fornecedor, created_at, updated_at)
                                    VALUES
                                    (:fornecedor, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':fornecedor', $fornecedor);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $fornecedor_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Fornecedor: $fornecedor ($fornecedor_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Fornecedor Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Fornecedor!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um contato de fornecedor
     * @param int $id_lente_contato_fornecedor
     * @return object
     * @example $variavel = $Obj->mostrar($id_lente_contato_fornecedor);
     */
    public function mostrar(int $id_lente_contato_fornecedor){
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('SELECT * FROM lente_contato_fornecedores WHERE id_lente_contato_fornecedor = :id_lente_contato_fornecedor LIMIT 1');
        $sql->bindParam(':id_lente_contato_fornecedor', $id_lente_contato_fornecedor);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        // Como será retornado apenas UM registro, usamos fetch.
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado contato de fornecedor
     *
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE lente_contato_fornecedores SET
            fornecedor = :fornecedor,
            updated_at = :updated_at 
        WHERE id_lente_contato_fornecedor = :id_lente_contato_fornecedor
        ");

        $agora = date("Y-m-d H:i:s");

        $id_lente_contato_fornecedor = $dados['id_lente_contato_fornecedor'];
        $fornecedor = ucwords(strtolower(trim($dados['fornecedor'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_lente_contato_fornecedor', $id_lente_contato_fornecedor);
        $sql->bindParam(':fornecedor', $fornecedor);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Fornecedor: $fornecedor ($id_lente_contato_fornecedor)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Fornecedor Editado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Fornecedor!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        }
    }

    /**
     * Deleta um contato de fornecedor (soft delete)
     *
     * @param integer $id_lente_contato_fornecedor
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_lente_contato_fornecedor, $usuario_logado){
        // Consulta para obter o nome do fornecedor
        $consulta_fornecedor = $this->pdo->prepare('SELECT fornecedor FROM lente_contato_fornecedores WHERE id_lente_contato_fornecedor = :id_lente_contato_fornecedor');
        $consulta_fornecedor->bindParam(':id_lente_contato_fornecedor', $id_lente_contato_fornecedor);
        $consulta_fornecedor->execute();
        $resultado_fornecedor = $consulta_fornecedor->fetch(PDO::FETCH_ASSOC);

        // Verifica se o fornecedor foi encontrado
        if ($resultado_fornecedor) {
            $nome_fornecedor = $resultado_fornecedor['fornecedor'];
        } else {
            // Se o fornecedor não for encontrado, use um nome genérico
            $nome_fornecedor = "Fornecedor Desconhecido";
        }

        // Atualiza o registro com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE lente_contato_fornecedor SET deleted_at = :deleted_at WHERE id_lente_contato_fornecedor = :id_lente_contato_fornecedor');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_lente_contato_fornecedor', $id_lente_contato_fornecedor);

        if ($sql->execute()) {
            $descricao = "Deletou o Fornecedor $nome_fornecedor ($id_lente_contato_fornecedor)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Fornecedor Deletado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Fornecedor!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/fornecedores';
            </script>";
            exit;
        }
    }

}

?>
