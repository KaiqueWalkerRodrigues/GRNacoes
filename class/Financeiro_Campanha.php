<?php

class Financeiro_Campanha {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    //Método para registrar logs
    private function addLog($acao, $descricao, $id_usuario){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario) 
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)
                                ');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
    }

    /**
     * Listar todas as campanhas financeiras
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM financeiro_campanhas WHERE deleted_at IS NULL ORDER BY nome DESC');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    /**
     * Cadastrar uma nova campanha financeira
     * @param array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados)
    {
        $nome  = ucwords(strtolower(trim($dados['nome'])));
        $periodo_inicio = $dados['periodo_inicio'];
        $periodo_fim = $dados['periodo_fim'];
        $data_pagamento = $dados['data_pagamento'];
        $data_pagamento_pos = $dados['data_pagamento_pos'];
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        try {
            $sql = $this->pdo->prepare('INSERT INTO financeiro_campanhas 
                                        (nome, periodo_inicio, periodo_fim, data_pagamento, data_pagamento_pos, created_at, updated_at)
                                        VALUES
                                        (:nome, :periodo_inicio, :periodo_fim, :data_pagamento, :data_pagamento_pos, :created_at, :updated_at)
                                    ');

            $created_at  = $agora;
            $updated_at  = $agora;

            $sql->bindParam(':nome', $nome);          
            $sql->bindParam(':periodo_inicio', $periodo_inicio); 
            $sql->bindParam(':periodo_fim', $periodo_fim); 
            $sql->bindParam(':data_pagamento', $data_pagamento); 
            $sql->bindParam(':data_pagamento_pos', $data_pagamento_pos); 
            $sql->bindParam(':created_at', $created_at);          
            $sql->bindParam(':updated_at', $updated_at);          

            $sql->execute();

            $campanha_id = $this->pdo->lastInsertId();

            $descricao = "Cadastrou a campanha financeira: $nome ($campanha_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Campanha Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanhas';
            </script>";
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código SQLSTATE para violação de restrição de integridade (incluindo chaves duplicadas)
                echo "
                <script>
                    alert('Erro: A campanha \"$nome\" já está cadastrada.');
                    window.history.back();
                </script>";
                exit;
            } else {
                // Para outros erros, exibe a mensagem de erro específica
                echo "
                <script>
                    alert('Erro ao cadastrar a campanha: " . addslashes($e->getMessage()) . "');
                    window.history.back();
                </script>";
                exit;
            }
        }
    }


    /**
     * Retorna os dados de uma campanha financeira
     * @param int $id_financeiro_campanha
     * @return object
     * @example $variavel = $Obj->mostrar($id_financeiro_campanha);
     */
    public function mostrar(int $id_financeiro_campanha)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM financeiro_campanhas WHERE id_financeiro_campanha = :id_financeiro_campanha LIMIT 1');
        $sql->bindParam(':id_financeiro_campanha', $id_financeiro_campanha);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza uma campanha financeira
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $id_financeiro_campanha = $dados['id_financeiro_campanha'];
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $periodo_inicio = $dados['periodo_inicio'];
        $periodo_fim = $dados['periodo_fim'];
        $data_pagamento = $dados['data_pagamento'];
        $data_pagamento_pos = $dados['data_pagamento_pos'];
        $updated_at = date("Y-m-d H:i:s"); 
        $usuario_logado = $dados['usuario_logado'];

        try {
            $sql = $this->pdo->prepare("UPDATE financeiro_campanhas SET
                nome = :nome,
                periodo_inicio = :periodo_inicio,
                periodo_fim = :periodo_fim,
                data_pagamento = :data_pagamento,
                data_pagamento_pos = :data_pagamento_pos,
                updated_at = :updated_at 
            WHERE id_financeiro_campanha = :id_financeiro_campanha
            ");

            $sql->bindParam(':id_financeiro_campanha', $id_financeiro_campanha);
            $sql->bindParam(':nome', $nome);
            $sql->bindParam(':periodo_inicio', $periodo_inicio);
            $sql->bindParam(':periodo_fim', $periodo_fim);
            $sql->bindParam(':data_pagamento', $data_pagamento);
            $sql->bindParam(':data_pagamento_pos', $data_pagamento_pos);
            $sql->bindParam(':updated_at', $updated_at);       

            $sql->execute();

            $descricao = "Editou a campanha financeira: $nome ($id_financeiro_campanha)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Campanha Editada com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanhas';
            </script>";
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código SQLSTATE para violação de restrição de integridade (incluindo chaves duplicadas)
                echo "
                <script>
                    alert('Erro: A campanha \"$nome\" já está cadastrada.');
                    window.history.back();
                </script>";
                exit;
            } else {
                // Para outros erros, exibe a mensagem de erro específica
                echo "
                <script>
                    alert('Erro ao editar a campanha: " . addslashes($e->getMessage()) . "');
                    window.history.back();
                </script>";
                exit;
            }
        }
    }


    /**
     * Deletar uma campanha financeira
     * @param integer $id_financeiro_campanha
     * @return void
     */
    public function deletar(int $id_financeiro_campanha, $usuario_logado)
    {
        $consulta_campanha = $this->pdo->prepare('SELECT nome FROM financeiro_campanhas WHERE id_financeiro_campanha = :id_financeiro_campanha');
        $consulta_campanha->bindParam(':id_financeiro_campanha', $id_financeiro_campanha);
        $consulta_campanha->execute();
        $resultado_campanha = $consulta_campanha->fetch(PDO::FETCH_ASSOC);

        if ($resultado_campanha) {
            $nome_campanha = $resultado_campanha['nome'];
        } else {
            $nome_campanha = "Campanha Desconhecida";
        }

        $sql = $this->pdo->prepare('UPDATE financeiro_campanhas SET deleted_at = :deleted_at WHERE id_financeiro_campanha = :id_financeiro_campanha');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_financeiro_campanha', $id_financeiro_campanha);

        if ($sql->execute()) {
            $descricao = "Desativou a campanha financeira $nome_campanha($id_financeiro_campanha)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Campanha Deletada com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanhas;
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Campanha!');
                window.location.href = '" . URL . "/financeiro/campanhas';
            </script>";
            exit;
        }
    }

}

?>
