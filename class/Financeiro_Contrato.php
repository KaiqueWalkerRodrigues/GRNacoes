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

    public function cadastrarNovo(array $dados)
    {
        try {
            $this->pdo->beginTransaction(); // Inicia uma transação
            $agora = date("Y-m-d H:i:s");

            // Inserção da nota de compra
            $sql = $this->pdo->prepare('INSERT INTO compras_notas 
                                        (id_fornecedor, valor, n_nota, data, id_empresa, created_at, updated_at)
                                        VALUES
                                        (:id_fornecedor, :valor, :n_nota, :data, :id_empresa, :created_at, :updated_at)');
            $sql->execute([
                ':id_fornecedor' => $dados['id_fornecedor'],
                ':valor'         => $dados['valor'],
                ':n_nota'        => $dados['n_nota'],
                ':data'          => $dados['data'],
                ':id_empresa'    => $dados['id_empresa'],
                ':created_at'    => $agora,
                ':updated_at'    => $agora,
            ]);

            $id_nota = $this->pdo->lastInsertId();

            // Inserção dos itens da nota fiscal na tabela compras_notas_itens
            // Espera-se que os itens venham em $dados['itens'] como um array de itens,
            // onde cada item é um array associativo com as chaves: item, valor_uni, quantidade, descricao.
            if (isset($dados['itens']) && is_array($dados['itens'])) {
                foreach ($dados['itens'] as $item) {
                    $sqlItem = $this->pdo->prepare("INSERT INTO compras_notas_itens 
                        (id_nota, item, valor_uni, quantidade, descricao, created_at, updated_at, deleted_at)
                        VALUES (:id_nota, :item, :valor_uni, :quantidade, :descricao, :created_at, :updated_at, :deleted_at)");
                    $sqlItem->execute([
                        ':id_nota'    => $id_nota,
                        ':item'       => $item['item'],
                        ':valor_uni'  => $item['valor_uni'],
                        ':quantidade' => $item['quantidade'],
                        ':descricao'  => $item['descricao'],
                        ':created_at' => $agora,
                        ':updated_at' => $agora,
                        ':deleted_at' => null,
                    ]);
                }
            }

            // Adiciona o log da operação
            $descricao_log = "Cadastrou a nota de compra: {$dados['n_nota']} ($id_nota)";
            $this->addLog('Cadastrar', $descricao_log, $dados['usuario_logado']);

            $this->pdo->commit();

            echo "<script>
                    alert('Nota de compra cadastrada com sucesso!');
                    window.location.href = '" . URL . "/compras/notas';
                </script>";
            exit;

        } catch (PDOException $e) {
            $this->pdo->rollBack();

            // Verifica se o erro é de entrada duplicada
            if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
                echo "<script>
                        alert('A nota de compra já foi cadastrada!');
                        window.location.href = '" . URL . "/compras/notas';
                    </script>";
                exit;
            }

            echo "<script>
                    alert('Não foi possível cadastrar a nota de compra! Erro: " . addslashes($e->getMessage()) . "');
                    window.location.href = '" . URL . "/compras/notas';
                </script>";
            exit;
        }
    }

    
    public function editar($dados) {
        try {
            $this->pdo->beginTransaction(); // Inicia uma transação
    
            $agora = date('Y-m-d H:i:s');
    
            // Remove caracteres especiais do CPF, CEP, telefones e celulares
            $dados['cpf'] = preg_replace('/\D/', '', $dados['cpf']);
            $dados['cep'] = preg_replace('/\D/', '', $dados['cep']);
            $dados['tel_res'] = preg_replace('/\D/', '', $dados['tel_res']);
            $dados['tel_com'] = preg_replace('/\D/', '', $dados['tel_com']);
            $dados['celular1'] = preg_replace('/\D/', '', $dados['celular1']);
            $dados['celular2'] = preg_replace('/\D/', '', $dados['celular2']);
    
            // Atualiza os dados do contrato principal
            $sql = $this->pdo->prepare('UPDATE financeiro_contratos
                                        SET id_empresa = :id_empresa,
                                            n_contrato = :n_contrato,
                                            data = :data,
                                            id_testemunha1 = :id_testemunha1,
                                            nome = :nome,
                                            data_nascimento = :data_nascimento,
                                            cpf = :cpf,
                                            cep = :cep,
                                            numero = :numero,
                                            endereco = :endereco,
                                            complemento = :complemento,
                                            bairro = :bairro,
                                            cidade = :cidade,
                                            uf = :uf,
                                            telefone_residencial = :tel_res,
                                            telefone_comercial = :tel_com,
                                            celular1 = :celular1,
                                            celular2 = :celular2,
                                            updated_at = :updated_at
                                        WHERE id_financeiro_contrato = :id_financeiro_contrato');
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
                ':tel_res' => $dados['tel_res'],
                ':tel_com' => $dados['tel_com'],
                ':celular1' => $dados['celular1'],
                ':celular2' => $dados['celular2'],
                ':updated_at' => $agora,
                ':id_financeiro_contrato' => $dados['id_financeiro_contrato']
            ]);
            
            // Remova ou atualize as parcelas, se necessário
    
            // Adiciona o log
            $descricao = "Editou o contrato: {$dados['n_contrato']}";
            $this->addLog('Editar', $descricao, $dados['usuario_logado']);
    
            $this->pdo->commit();
    
            echo "<script>
                    alert('Contrato editado com sucesso!');
                    window.location.href = '" . URL . "/financeiro/contratos';
                  </script>";
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "<script>
                    alert('Erro ao editar contrato: " . $e->getMessage() . "');
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
            WHERE 
                fc.deleted_at IS NULL
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
            $agora = date('Y-m-d H:i:s');
    
            // Recupera o id_contrato e o valor da parcela
            $sqlParcela = $this->pdo->prepare('SELECT id_contrato, valor FROM financeiro_contratos_parcelas WHERE id_financeiro_contrato_parcela = :id_parcela');
            $sqlParcela->execute([':id_parcela' => $id_parcela]);
            $parcela = $sqlParcela->fetch(PDO::FETCH_ASSOC);
    
            if (!$parcela) {
                throw new Exception('Parcela não encontrada.');
            }
    
            $id_contrato = $parcela['id_contrato'];
            $valor_parcela = (float)$parcela['valor'];
    
            // Atualiza a parcela
            $sql = $this->pdo->prepare('UPDATE financeiro_contratos_parcelas SET valor_pago = :valor_pago, status = :status WHERE id_financeiro_contrato_parcela = :id_parcela');
            $sql->execute([
                ':valor_pago' => $valor_pago,
                ':pago_em' => $agora,
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