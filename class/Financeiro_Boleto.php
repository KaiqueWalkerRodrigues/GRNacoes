<?php

class Financeiro_Boleto {

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
     * Listar todos os boletos financeiros
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar($id_campanha){
        $sql = $this->pdo->prepare('SELECT * FROM financeiro_boletos WHERE id_campanha = :id_campanha AND deleted_at IS NULL ORDER BY n_boleto');        
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    public function listarAtrasados() {
        $sql = $this->pdo->prepare("
            SELECT 
                fb.*
            FROM 
                financeiro_boletos fb
            INNER JOIN 
                financeiro_campanhas fc 
            ON 
                fb.id_campanha = fc.id_financeiro_campanha
            WHERE 
                fb.data_venda <= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                AND fb.data_pago IS NULL
                AND fb.deleted_at IS NULL
                AND fc.deleted_at IS NULL
            ORDER BY 
                fb.data_venda
        ");
        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }    

    /**
     * Cadastrar um novo boleto financeiro
     * @param array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados)
    {
        $n_boleto  = trim($dados['n_boleto']);
        $id_campanha = $dados['id_campanha'];
        $id_usuario = $dados['id_usuario'];
        $id_empresa = $dados['id_empresa'];
        $cliente = ucwords(strtolower(trim($dados['cliente'])));
        $data_venda = $dados['data_venda'];
        $valor = $dados['valor'];
        $valor_pago = 0;
        $data_pago = null;
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO financeiro_boletos 
                                    (n_boleto, id_campanha, id_usuario, id_empresa, cliente, data_venda, valor, valor_pago, data_pago, created_at, updated_at)
                                    VALUES
                                    (:n_boleto, :id_campanha, :id_usuario, :id_empresa, :cliente, :data_venda, :valor, :valor_pago, :data_pago, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':n_boleto', $n_boleto);          
        $sql->bindParam(':id_campanha', $id_campanha); 
        $sql->bindParam(':id_usuario', $id_usuario); 
        $sql->bindParam(':id_empresa', $id_empresa); 
        $sql->bindParam(':cliente', $cliente); 
        $sql->bindParam(':data_venda', $data_venda); 
        $sql->bindParam(':valor', $valor); 
        $sql->bindParam(':valor_pago', $valor_pago); 
        $sql->bindParam(':data_pago', $data_pago); 
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $boleto_id = $this->pdo->lastInsertId();
            $descricao = "Cadastrou o boleto financeiro: $n_boleto ($boleto_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Boleto Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;

        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Boleto!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um boleto financeiro
     * @param int $id_financeiro_boleto
     * @return object
     * @example $variavel = $Obj->mostrar($id_financeiro_boleto);
     */
    public function mostrar(int $id_financeiro_boleto)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM financeiro_boletos WHERE id_financeiro_boleto = :id_financeiro_boleto LIMIT 1');
        $sql->bindParam(':id_financeiro_boleto', $id_financeiro_boleto);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza um boleto financeiro
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE financeiro_boletos SET
            n_boleto = :n_boleto,
            id_campanha = :id_campanha,
            id_usuario = :id_usuario,
            id_empresa = :id_empresa,
            cliente = :cliente,
            data_venda = :data_venda,
            valor = :valor,
            valor_pago = :valor_pago,
            data_pago = :data_pago,
            updated_at = :updated_at 
        WHERE id_financeiro_boleto = :id_financeiro_boleto
        ");

        $agora = date("Y-m-d H:i:s");

        $id_financeiro_boleto = $dados['id_financeiro_boleto'];
        $n_boleto = trim($dados['n_boleto']);
        $id_campanha = $dados['id_campanha'];
        $id_usuario = $dados['id_usuario'];
        $id_empresa = $dados['id_empresa'];
        $cliente = ucwords(strtolower(trim($dados['cliente'])));
        $data_venda = $dados['data_venda'];
        $valor = $dados['valor'];
        $valor_pago = 0;
        $data_pago = null;
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_financeiro_boleto', $id_financeiro_boleto);
        $sql->bindParam(':n_boleto', $n_boleto);
        $sql->bindParam(':id_campanha', $id_campanha);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':cliente', $cliente);
        $sql->bindParam(':data_venda', $data_venda);
        $sql->bindParam(':valor', $valor);
        $sql->bindParam(':valor_pago', $valor_pago);
        $sql->bindParam(':data_pago', $data_pago);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o boleto financeiro: $n_boleto ($id_financeiro_boleto)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Boleto Editado com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Boleto!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        }
    }

    /**
     * Deletar um boleto financeiro
     * @param integer $id_financeiro_boleto
     * @return void
     */
    public function deletar(int $id_financeiro_boleto, $usuario_logado, $id_campanha)
    {
        $consulta_boleto = $this->pdo->prepare('SELECT n_boleto FROM financeiro_boletos WHERE id_financeiro_boleto = :id_financeiro_boleto');
        $consulta_boleto->bindParam(':id_financeiro_boleto', $id_financeiro_boleto);
        $consulta_boleto->execute();
        $resultado_boleto = $consulta_boleto->fetch(PDO::FETCH_ASSOC);

        if ($resultado_boleto) {
            $n_boleto = $resultado_boleto['n_boleto'];
        } else {
            $n_boleto = "Boleto Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE financeiro_boletos SET deleted_at = :deleted_at WHERE id_financeiro_boleto = :id_financeiro_boleto');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_financeiro_boleto', $id_financeiro_boleto);

        if ($sql->execute()) {
            $descricao = "Deletou o boleto financeiro $n_boleto($id_financeiro_boleto)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Boleto Deletado com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Boleto!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        }
    }

    public function CadastrarValorPago(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE financeiro_boletos SET
            valor_pago = :valor_pago,
            data_pago = :data_pago,
            updated_at = :updated_at
        WHERE id_financeiro_boleto = :id_financeiro_boleto
        ");

        $agora = date("Y-m-d H:i:s");

        $id_financeiro_boleto = $dados['id_financeiro_boleto'];
        $valor_pago = $dados['valor_pago'];
        $data_pago = $dados['data_pago'];

        $sql->bindParam(':id_financeiro_boleto', $id_financeiro_boleto);
        $sql->bindParam(':valor_pago', $valor_pago);
        $sql->bindParam(':data_pago', $data_pago);
        $sql->bindParam(':updated_at', $agora);

        $id_campanha = $dados['id_campanha'];

        if ($sql->execute()) {
            $descricao = "Registrou pagamento do boleto: $id_financeiro_boleto no valor de R$ $valor_pago";
            $this->addLog("Registrar",$descricao,$dados['usuario_logado']);

            echo "
            <script>
                alert('Pagamento do Boleto Registrado com Sucesso!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Registrar o Pagamento do Boleto!');
                window.location.href = '" . URL . "/financeiro/campanha?id=$id_campanha';
            </script>";
            exit;
        }
    }

    public function listarBoletosPorVendedor($id_campanha, $id_vendedor) {
        // Prepara a consulta SQL para listar os boletos de um vendedor em uma campanha específica
        $sql = $this->pdo->prepare('SELECT 
                                        n_boleto, 
                                        id_empresa, 
                                        cliente, 
                                        data_venda, 
                                        valor, 
                                        valor_pago,
                                        data_pago
                                    FROM 
                                        financeiro_boletos 
                                    WHERE 
                                        id_campanha = :id_campanha 
                                        AND id_usuario = :id_vendedor 
                                        AND deleted_at IS NULL 
                                    ORDER BY 
                                        data_venda');
    
        // Atribui os parâmetros à consulta
        $sql->bindParam(':id_campanha', $id_campanha);
        $sql->bindParam(':id_vendedor', $id_vendedor);
        
        // Executa a consulta
        $sql->execute();
    
        // Retorna os resultados como um array de objetos
        $boletos = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os boletos encontrados
        return $boletos;
    }
    

    public function totalPorVendedor($id_campanha,$id_vendedor,$id_empresa){
        $sql = $this->pdo->prepare("SELECT sum(valor) as total FROM financeiro_boletos WHERE id_campanha = :id_campanha AND id_usuario = :id_vendedor AND id_empresa = :id_empresa AND deleted_at IS NULL");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->bindParam(':id_vendedor',$id_vendedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $sql = $this->pdo->prepare("SELECT sum(valor_pago) as total FROM financeiro_boletos WHERE id_campanha = :id_campanha AND id_usuario = :id_vendedor AND id_empresa = :id_empresa AND data_pago <= :data_limite AND deleted_at IS NULL AND data_pago IS NOT NULL");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':data_limite',$data_limite);
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->bindParam(':id_vendedor',$id_vendedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalPosPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $sql = $this->pdo->prepare("SELECT sum(valor_pago) as total FROM financeiro_boletos WHERE id_campanha = :id_campanha AND id_usuario = :id_vendedor AND id_empresa = :id_empresa AND data_pago > :data_limite AND deleted_at IS NULL AND data_pago IS NOT NULL");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':data_limite',$data_limite);
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->bindParam(':id_vendedor',$id_vendedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalNaoConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $valor_total = $this->totalPorVendedor($id_campanha,$id_vendedor,$id_empresa);
        $valor_convertido = $this->totalConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite);

        $resultado = $valor_total-$valor_convertido;
       return $resultado;
    }

    public function totalPorcentagemNaoConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $valor_total = $this->totalPorVendedor($id_campanha,$id_vendedor,$id_empresa);
        $total_nao_convertido = $this->totalNaoConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite);
        
        $resultado = ($total_nao_convertido/$valor_total);

       return $resultado;
    }

    public function totalComissaoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $comissao = $this->totalNaoConvertidoPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite)*0.02;

        return $comissao ? $comissao : 0;
    }

    public function totalConvertidoPosFechPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $sql = $this->pdo->prepare("SELECT sum(valor_pago) as total FROM financeiro_boletos WHERE id_campanha = :id_campanha AND id_usuario = :id_vendedor AND id_empresa = :id_empresa AND data_pago > :data_limite AND deleted_at IS NULL AND data_pago IS NOT NULL");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':data_limite',$data_limite);
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->bindParam(':id_vendedor',$id_vendedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalComissaoConvertidoPosFechPorVendedor($id_campanha,$id_vendedor,$id_empresa,$data_limite){
        $sql = $this->pdo->prepare("SELECT sum(valor_pago) as total FROM financeiro_boletos WHERE id_campanha = :id_campanha AND id_usuario = :id_vendedor AND id_empresa = :id_empresa AND data_pago > :data_limite AND deleted_at IS NULL AND data_pago IS NOT NULL");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':data_limite',$data_limite);
        $sql->bindParam(':id_campanha',$id_campanha);
        $sql->bindParam(':id_vendedor',$id_vendedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        $porcentagem = $resultado->total*0.02;

        return $resultado ? $porcentagem : 0;
    }

}

?>
