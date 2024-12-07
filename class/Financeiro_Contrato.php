<?php

class Financeiro_Contrato {

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

    public function cadastrar($dados) {
        try {
            $this->pdo->beginTransaction(); // Inicia uma transação
    
            $agora = date('Y-m-d H:i:s');
    
            // Cadastro do contrato principal
            $sql = $this->pdo->prepare('INSERT INTO financeiro_contratos 
                                        (id_empresa, n_contrato, data, id_testemunha1, nome, data_nascimento, cpf, cep, numero, endereco, complemento, bairro, cidade, uf,
                                        telefone_residencial, telefone_comercial, celular1, celular2, sinal_entrada, valor, created_at, updated_at)
                                        VALUES
                                        (:id_empresa, :n_contrato, :data, :id_testemunha1, :nome, :data_nascimento, :cpf, :cep, :numero, :endereco, :complemento, :bairro, :cidade, :uf,
                                        :telefone_residencial, :telefone_comercial, :celular1, :celular2, :sinal_entrada, :valor, :created_at, :updated_at)');
            $sql->execute([
                ':id_empresa' => $dados['id_empresa'],
                ':n_contrato' => $dados['n_contrato'],
                ':data' => $dados['data'],
                ':id_testemunha1' => $dados['id_testemunha1'],
                ':nome' => $dados['nome'],
                ':data_nascimento' => $dados['data_nascimento'],
                ':cpf' => $dados['cpf'],
                ':cep' => $dados['cep'],
                ':numero' => $dados['numero'],
                ':endereco' => $dados['endereco'],
                ':complemento' => $dados['complemento'],
                ':bairro' => $dados['bairro'],
                ':cidade' => $dados['cidade'],
                ':uf' => $dados['uf'],
                ':telefone_residencial' => $dados['tel_res'],
                ':telefone_comercial' => $dados['tel_com'],
                ':celular1' => $dados['celular1'],
                ':celular2' => $dados['celular2'],
                ':sinal_entrada' => $dados['sinal_entrada'],
                ':valor' => $dados['valor'],
                ':created_at' => $agora,
                ':updated_at' => $agora,
            ]);
    
            $id_contrato = $this->pdo->lastInsertId();
    
            // Cadastro das parcelas
            for ($x = 1; $x <= $dados['parcelas']; $x++) {
                $data_key = "data_parcela$x";
                $valor_key = "valor_parcela$x";
    
                // Verifica se os valores existem
                if (!isset($dados[$data_key]) || !isset($dados[$valor_key])) {
                    throw new Exception("Dados da parcela $x estão ausentes.");
                }
    
                $sql = $this->pdo->prepare('INSERT INTO financeiro_contratos_parcelas
                                            (id_contrato, parcela, data, valor, created_at, updated_at)
                                            VALUES
                                            (:id_contrato, :parcela, :data, :valor, :created_at, :updated_at)');
                $sql->execute([
                    ':id_contrato' => $id_contrato,
                    ':parcela' => $x,
                    ':data' => $dados[$data_key],
                    ':valor' => $dados[$valor_key],
                    ':created_at' => $agora,
                    ':updated_at' => $agora,
                ]);
            }
    
            // Adiciona o log e confirma a transação
            $descricao = "Cadastrou o contrato: {$dados['n_contrato']}";
            $this->addLog('Cadastrar', $descricao, $dados['usuario_logado']);
            $this->pdo->commit();
    
            echo "<script>
                    alert('Contrato cadastrado com sucesso!');
                    window.location.href = '" . URL . "/financeiro/contratos';
                  </script>";
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "<script>
                    alert('Erro ao cadastrar contrato: " . $e->getMessage() . "');
                    window.location.href = '" . URL . "/financeiro/contratos';
                  </script>";
        }
    }

    public function listar() {
        $sql = $this->pdo->prepare("
            SELECT 
                fc.*, 
                COALESCE(SUM(fp.valor), 0) AS valor_total_original, -- Soma das parcelas originais
                COALESCE(SUM(fp.valor_pago), 0) AS valor_pago_total, -- Soma das parcelas pagas (com juros, se aplicável)
                COALESCE(SUM(fp.valor_pago) + SUM(fp.valor) - SUM(fp.valor_pago), 0) AS valor_total_atualizado -- Total atualizado (pagos + pendentes)
            FROM 
                financeiro_contratos AS fc
            LEFT JOIN 
                financeiro_contratos_parcelas AS fp 
            ON 
                fc.id_financeiro_contrato = fp.id_contrato
            GROUP BY 
                fc.id_financeiro_contrato
            ORDER BY 
                fc.n_contrato DESC
        ");
        $sql->execute();
        
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }    

    public function mostrar($id_financeiro_contrato){
        $sql = $this->pdo->prepare('SELECT * FROM financeiro_contratos WHERE id_financeiro_contrato = :id_financeiro_contrato ORDER BY n_contrato DESC');
        $sql->bindParam(':id_financeiro_contrato',$id_financeiro_contrato);
        $sql->execute();
        
        $dados = $sql->fetch(PDO::FETCH_OBJ);

        return $dados;
    }

    public function contarParcelas($id_financeiro_contrato){
        $sql = $this->pdo->prepare('SELECT count(id_financeiro_contrato_parcela) as qntd FROM financeiro_contratos_parcelas WHERE id_contrato = :id_contrato');
        $sql->execute([
            ':id_contrato' => $id_financeiro_contrato,
        ]);

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['qntd'];
    }    

    public function contarParcelasPagas($id_financeiro_contrato){
        $sql = $this->pdo->prepare('SELECT count(id_financeiro_contrato_parcela) as qntd FROM financeiro_contratos_parcelas WHERE id_contrato = :id_contrato AND valor_pago > 0');
        $sql->execute([
            ':id_contrato' => $id_financeiro_contrato,
        ]);

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['qntd'];
    }    

    public function listarParcelas($id_financeiro_contrato){
        $sql = $this->pdo->prepare('SELECT * FROM financeiro_contratos_parcelas WHERE id_contrato = :id_contrato');
        $sql->execute([
            ':id_contrato' => $id_financeiro_contrato,
        ]);

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        
        return $dados;
    }

    public function confirmarPagamento($id_parcela, $usuario_logado, $valor_pago) {
        try {
            $this->pdo->beginTransaction();
    
            // Recupera o id_contrato e o valor da parcela
            $sqlParcela = $this->pdo->prepare('SELECT id_contrato, valor FROM financeiro_contratos_parcelas WHERE id_financeiro_contrato_parcela = :id_parcela');
            $sqlParcela->execute([':id_parcela' => $id_parcela]);
            $parcela = $sqlParcela->fetch(PDO::FETCH_ASSOC);
    
            if (!$parcela) {
                throw new Exception('Parcela não encontrada.');
            }
    
            $id_contrato = $parcela['id_contrato'];
            $valor_parcela = (float)$parcela['valor'];
    
            // Define o status com base no valor pago
            $status = 2; // Pendente (valor pago < valor parcela)
            if ($valor_pago == $valor_parcela) {
                $status = 1; // Pago
            } elseif ($valor_pago > $valor_parcela) {
                $status = 3; // Pago com excesso
            }
    
            // Atualiza a parcela
            $sql = $this->pdo->prepare('UPDATE financeiro_contratos_parcelas SET valor_pago = :valor_pago, status = :status WHERE id_financeiro_contrato_parcela = :id_parcela');
            $sql->execute([
                ':valor_pago' => $valor_pago,
                ':status' => $status,
                ':id_parcela' => $id_parcela
            ]);
    
            // Registra o log
            $descricao = "Confirmou o pagamento da Parcela: {$id_parcela} com valor pago: R$ {$valor_pago}";
            $this->addLog('Confirmar', $descricao, $usuario_logado);
    
            $this->pdo->commit();
    
            return $id_contrato;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception('Erro ao confirmar pagamento: ' . $e->getMessage());
        }
    }
    
}