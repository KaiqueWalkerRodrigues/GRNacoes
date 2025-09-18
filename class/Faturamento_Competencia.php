<?php

class Faturamento_Competencia {

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
     * Listar todas as competencias
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM faturamento_competencias WHERE deleted_at IS NULL');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }         

    /**
     * Cadastrar nova competencia
     * @param array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados)
    {
        $nome = $dados['nome'];
        $periodo_inicio = $dados['periodo_inicio'];
        $periodo_fim = $dados['periodo_fim'];
        $mes_pagamento = $dados['mes_pagamento'];
        $usuario_logado = $dados['usuario_logado'];

        $data_pagamento = $mes_pagamento.'-01';

        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO faturamento_competencias 
                                    (nome, periodo_inicio, periodo_fim, mes_pagamento, created_at, updated_at)
                                    VALUES
                                    (:nome, :periodo_inicio, :periodo_fim, :mes_pagamento, :created_at, :updated_at)
                                ');

        $sql->bindParam(':nome', $nome); 
        $sql->bindParam(':periodo_inicio', $periodo_inicio); 
        $sql->bindParam(':periodo_fim', $periodo_fim); 
        $sql->bindParam(':mes_pagamento', $data_pagamento); 
        $sql->bindParam(':created_at', $agora);          
        $sql->bindParam(':updated_at', $agora);          

        if ($sql->execute()) {
            $id_competencia = $this->pdo->lastInsertId();
            $descricao = "Cadastrou nova competencia: $nome ($id_competencia)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Competências Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;

        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar a Competencia!');
                window.location.href = '" . URL . "/faturamento/competencias';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma competencia
     * @param int $id_faturamento_competencia
     * @return object
     * @example $variavel = $Obj->mostrar($id_financeiro_boleto);
     */
    public function mostrar(int $id_faturamento_competencia)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM faturamento_competencias WHERE id_faturamento_competencia = :id_faturamento_competencia LIMIT 1');
        $sql->bindParam(':id_faturamento_competencia', $id_faturamento_competencia);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Editar uma competencia
     * @param array $dados
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE faturamento_competencias SET
            nome = :nome,
            periodo_inicio = :periodo_inicio,
            periodo_fim = :periodo_fim,
            mes_pagamento = :mes_pagamento,
            updated_at = :updated_at
        WHERE id_faturamento_competencia = :id_faturamento_competencia
        ");

        $agora = date("Y-m-d H:i:s");

        $id_faturamento_competencia = $dados['id_faturamento_competencia'];
        $nome = $dados['nome'];
        $periodo_inicio = $dados['periodo_inicio'];
        $periodo_fim = $dados['periodo_fim'];
        $mes_pagamento = $dados['mes_pagamento'];
        $usuario_logado = $dados['usuario_logado'];

        $data_pagamento = $mes_pagamento.'-01';

        $sql->bindParam(':id_faturamento_competencia', $id_faturamento_competencia);
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':periodo_inicio', $periodo_inicio);
        $sql->bindParam(':periodo_fim', $periodo_fim);
        $sql->bindParam(':mes_pagamento', $data_pagamento);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Editou a competência: $nome ($id_faturamento_competencia)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Competência editada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_faturamento_competencia';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar a competência!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_faturamento_competencia';
            </script>";
            exit;
        }
    }

    /**
     * Deletar uma competencia
     * @param int $id_faturamento_competencia
     * @param int $usuario_logado
     * @return void
     */
    public function deletar(int $id_faturamento_competencia, $usuario_logado)
    {
        $consulta = $this->pdo->prepare('SELECT nome FROM faturamento_competencias WHERE id_faturamento_competencia = :id_faturamento_competencia');
        $consulta->bindParam(':id_faturamento_competencia', $id_faturamento_competencia);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $nome = $resultado ? $resultado['nome'] : "Competência desconhecida";

        $sql = $this->pdo->prepare('UPDATE faturamento_competencias SET deleted_at = :deleted_at WHERE id_faturamento_competencia = :id_faturamento_competencia');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_faturamento_competencia', $id_faturamento_competencia);

        if ($sql->execute()) {
            $descricao = "Deletou a competência $nome ($id_faturamento_competencia)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Competência deletada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencias';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar a competência!');
                window.location.href = '" . URL . "/faturamento/competencias';
            </script>";
            exit;
        }
    }

}

?>
