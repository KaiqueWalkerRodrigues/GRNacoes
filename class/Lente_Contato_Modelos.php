<?php

class Lente_contato_Modelo {

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
     * Listar todos os modelos
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare("
            SELECT * FROM lente_contato_modelos WHERE deleted_at IS NULL
        ");        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        // Retorna os dados
        return $dados;
    }      

    /**
     * Cadastrar um novo modelo
     * @param Array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        // Sanitizar e preparar os dados
        $id_fornecedor = intval($dados['id_fornecedor']);
        $codigo_simah = strtoupper(trim($dados['codigo_simah']));
        $modelo = ucwords(strtolower(trim($dados['modelo'])));
        $unidade = strtoupper(trim($dados['unidade']));
        $valor_custo = floatval(str_replace(',', '.', $_POST['valor_custo']));
        $valor_venda = floatval(str_replace(',', '.', $_POST['valor_venda']));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('
            INSERT INTO lente_contato_modelos 
                (id_fornecedor, codigo_simah, modelo, unidade, valor_custo, valor_venda, created_at, updated_at)
            VALUES
                (:id_fornecedor, :codigo_simah, :modelo, :unidade, :valor_custo, :valor_venda, :created_at, :updated_at)
        ');

        $sql->bindParam(':id_fornecedor', $id_fornecedor);                  
        $sql->bindParam(':codigo_simah', $codigo_simah);          
        $sql->bindParam(':modelo', $modelo);          
        $sql->bindParam(':unidade', $unidade);          
        $sql->bindParam(':valor_custo', $valor_custo);          
        $sql->bindParam(':valor_venda', $valor_venda);          
        $sql->bindParam(':created_at', $agora);          
        $sql->bindParam(':updated_at', $agora);          

        if ($sql->execute()) {
            $modelo_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Modelo: $codigo_simah ($modelo_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Modelo Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Modelo!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um modelo
     * @param int $id_lente_contato_modelo
     * @return object
     * @example $variavel = $Obj->mostrar($id_lente_contato_modelo);
     */
    public function mostrar(int $id_lente_contato_modelo){
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('
            SELECT * FROM lente_contato_modelos WHERE id_lente_contato_modelo = :id_lente_contato_modelo
        ');
        $sql->bindParam(':id_lente_contato_modelo', $id_lente_contato_modelo);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        // Como será retornado apenas UM registro, usamos fetch.
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado modelo
     *
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        // Sanitizar e preparar os dados
        $id_lente_contato_modelo = intval($dados['id_lente_contato_modelo']);
        $id_fornecedor = intval($dados['id_fornecedor']);
        $codigo_simah = strtoupper(trim($dados['codigo_simah']));
        $modelo = ucwords(strtolower(trim($dados['modelo'])));
        $unidade = strtoupper(trim($dados['unidade']));
        $valor_custo = floatval(str_replace(',', '.', $_POST['valor_custo']));
        $valor_venda = floatval(str_replace(',', '.', $_POST['valor_venda']));
        $updated_at = date("Y-m-d H:i:s"); 
        $usuario_logado = $dados['usuario_logado'];

        $sql = $this->pdo->prepare('
            UPDATE lente_contato_modelos SET
                id_fornecedor = :id_fornecedor,
                codigo_simah = :codigo_simah,
                modelo = :modelo,
                unidade = :unidade,
                valor_custo = :valor_custo,
                valor_venda = :valor_venda,
                updated_at = :updated_at 
            WHERE 
                id_lente_contato_modelo = :id_lente_contato_modelo
        ');

        $sql->bindParam(':id_lente_contato_modelo', $id_lente_contato_modelo);
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':codigo_simah', $codigo_simah);
        $sql->bindParam(':modelo', $modelo);
        $sql->bindParam(':unidade', $unidade);
        $sql->bindParam(':valor_custo', $valor_custo);
        $sql->bindParam(':valor_venda', $valor_venda);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Modelo: $codigo_simah ($id_lente_contato_modelo)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Modelo Editado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Modelo!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        }
    }

    /**
     * Deleta um modelo (soft delete)
     *
     * @param integer $id_lente_contato_modelo
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_lente_contato_modelo, $usuario_logado){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->query("UPDATE lente_contato_modelos SET deleted_at = '$agora' WHERE id_lente_contato_modelo = $id_lente_contato_modelo");

        if ($sql->execute()) {
            $descricao = "Deletou o Modelo $ ($id_lente_contato_modelo)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Modelo Deletado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Modelo!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/modelos';
            </script>";
            exit;
        }
    }

}

?>
