<?php

class Lente_contato_classificacao {

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
     * Listar todas as classificações
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM lente_contato_classificacoes WHERE deleted_at IS NULL ORDER BY classificacao');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastrar uma nova classificação
     * @param Array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        $classificacao  = ucwords(strtolower(trim($dados['classificacao'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO lente_contato_classificacoes 
                                    (classificacao, created_at, updated_at)
                                    VALUES
                                    (:classificacao, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':classificacao', $classificacao);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $classificacao_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou a Classificação: $classificacao ($classificacao_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Classificação Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar a Classificação!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma classificação
     * @param int $id_lente_contato_classificacao
     * @return object
     * @example $variavel = $Obj->mostrar($id_lente_contato_classificacao);
     */
    public function mostrar(int $id_lente_contato_classificacao){
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('SELECT * FROM lente_contato_classificacoes WHERE id_lente_contato_classificacao = :id_lente_contato_classificacao LIMIT 1');
        $sql->bindParam(':id_lente_contato_classificacao', $id_lente_contato_classificacao);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        // Como será retornado apenas UM registro, usamos fetch.
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza uma determinada classificação
     *
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE lente_contato_classificacoes SET
            classificacao = :classificacao,
            updated_at = :updated_at 
        WHERE id_lente_contato_classificacao = :id_lente_contato_classificacao
        ");

        $agora = date("Y-m-d H:i:s");

        $id_lente_contato_classificacao = $dados['id_lente_contato_classificacao'];
        $classificacao = ucwords(strtolower(trim($dados['classificacao'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_lente_contato_classificacao', $id_lente_contato_classificacao);
        $sql->bindParam(':classificacao', $classificacao);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou a Classificação: $classificacao ($id_lente_contato_classificacao)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Classificação Editada com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar a Classificação!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        }
    }

    /**
     * Deleta uma classificação (soft delete)
     *
     * @param integer $id_lente_contato_classificacao
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_lente_contato_classificacao, $usuario_logado){
        // Consulta para obter o nome da classificação
        $consulta_classificacao = $this->pdo->prepare('SELECT classificacao FROM lente_contato_classificacoes WHERE id_lente_contato_classificacao = :id_lente_contato_classificacao');
        $consulta_classificacao->bindParam(':id_lente_contato_classificacao', $id_lente_contato_classificacao);
        $consulta_classificacao->execute();
        $resultado_classificacao = $consulta_classificacao->fetch(PDO::FETCH_ASSOC);

        // Verifica se a classificação foi encontrada
        if ($resultado_classificacao) {
            $nome_classificacao = $resultado_classificacao['classificacao'];
        } else {
            // Se a classificação não for encontrada, use um nome genérico
            $nome_classificacao = "Classificação Desconhecida";
        }

        // Atualiza o registro com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE lente_contato_classificacoes SET deleted_at = :deleted_at WHERE id_lente_contato_classificacao = :id_lente_contato_classificacao');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_lente_contato_classificacao', $id_lente_contato_classificacao);

        if ($sql->execute()) {
            $descricao = "Deletou a Classificação $nome_classificacao ($id_lente_contato_classificacao)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Classificação Deletada com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Classificação!');
                window.location.href = '" . URL . "/lente_contato/configuracoes/classificacoes';
            </script>";
            exit;
        }
    }

}

?>
