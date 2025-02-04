<?php

class Lente_contato_Orcamento {

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
     * Listar todos os orçamentos
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('
            SELECT * FROM lente_contato_orcamentos WHERE deleted_at IS NULL
        ');        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }      

    /**
     * Cadastrar um novo orçamento
     * @param Array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        // Extrair e sanitizar os dados do formulário
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $cpf = preg_replace('/\D/', '', $dados['cpf']);
        $contato = preg_replace('/\D/', '', $dados['contato']);
        $id_medico = intval($dados['id_medico']);
        $olhos = intval($dados['olhos']);
        $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
        $olho_direito = isset($dados['olho_direito']) ? trim($dados['olho_direito']) : null;
        $id_modelo = intval($dados['id_lente']);
        $valor = floatval($dados['valor']);
        $forma_pgto1 = isset($dados['forma_pgto1']) ? intval($dados['forma_pgto1']) : null;
        $forma_pgto2 = isset($dados['forma_pgto2']) ? intval($dados['forma_pgto2']) : null;
        $pagamento = isset($dados['pagamento']) ? intval($dados['pagamento']) : 0;
        $usuario_logado = intval($dados['usuario_logado']);
        $id_empresa = intval($dados['id_empresa']);
        $agora = date("Y-m-d H:i:s");

        // Preparar a consulta SQL para inserir o novo orçamento
        $sql = $this->pdo->prepare('
            INSERT INTO lente_contato_orcamentos 
            (nome, cpf, contato, id_medico, olhos, olho_esquerdo, olho_direito, id_modelo, valor, id_forma_pagamento1, id_forma_pagamento2, pagamento, id_empresa, created_at, updated_at)
            VALUES
            (:nome, :cpf, :contato, :id_medico, :olhos, :olho_esquerdo, :olho_direito, :id_modelo, :valor, :forma_pgto1, :forma_pgto2, :pagamento, :id_empresa, :created_at, :updated_at)
        ');

        $sql->bindParam(':nome', $nome);          
        $sql->bindParam(':cpf', $cpf);          
        $sql->bindParam(':contato', $contato);          
        $sql->bindParam(':id_medico', $id_medico);          
        $sql->bindParam(':olhos', $olhos);          
        $sql->bindParam(':olho_esquerdo', $olho_esquerdo);          
        $sql->bindParam(':olho_direito', $olho_direito);          
        $sql->bindParam(':id_modelo', $id_modelo);          
        $sql->bindParam(':valor', $valor);          
        $sql->bindParam(':forma_pgto1', $forma_pgto1);          
        $sql->bindParam(':forma_pgto2', $forma_pgto2);          
        $sql->bindParam(':pagamento', $pagamento);          
        $sql->bindParam(':id_empresa', $id_empresa);          
        $sql->bindParam(':created_at', $agora);          
        $sql->bindParam(':updated_at', $agora);          

        if ($sql->execute()) {
            $orcamento_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Orçamento: $nome ($orcamento_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Orçamento Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Orçamento!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um orçamento específico
     * @param int $id_orcamento
     * @return object
     * @example $variavel = $Obj->mostrar($id_orcamento);
     */
    public function mostrar(int $id_orcamento){
        $sql = $this->pdo->prepare('
            SELECT 
                o.*, 
                m.nome AS nome_medico, 
                l.modelo AS modelo_lente 
            FROM 
                lente_contato_orcamentos o
            LEFT JOIN 
                medicos m ON o.id_medico = m.id_medico
            LEFT JOIN 
                lente_contato_lentes l ON o.id_modelo = l.id_lente
            WHERE 
                o.id_lente_contato_orcamento = :id_orcamento AND o.deleted_at IS NULL
            LIMIT 1
        ');
        $sql->bindParam(':id_orcamento', $id_orcamento);
        $sql->execute();
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    public function editar(array $dados){
        // Extrair e sanitizar os dados do formulário
    
        // Para os campos numéricos, removemos quaisquer caracteres que não sejam dígitos
        $id_orcamento = intval(preg_replace('/\D/', '', $dados['id_orcamento']));
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $cpf = preg_replace('/\D/', '', $dados['cpf']);
        $contato = preg_replace('/\D/', '', $dados['contato']);
        $id_medico = intval(preg_replace('/\D/', '', $dados['id_medico']));
        $olhos = intval(preg_replace('/\D/', '', $dados['olhos']));
        $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
        $olho_direito = isset($dados['olho_direito']) ? trim($dados['olho_direito']) : null;
        $id_modelo = intval(preg_replace('/\D/', '', $dados['id_lente']));
        
        // Para valores monetários, podemos remover tudo exceto dígitos e ponto (assumindo o ponto como separador decimal)
        $valorLimpo = preg_replace('/[^\d\.]/', '', $dados['valor']);
        $valor = floatval($valorLimpo);
        
        $forma_pgto1 = isset($dados['forma_pgto1']) ? intval(preg_replace('/\D/', '', $dados['forma_pgto1'])) : null;
        $forma_pgto2 = isset($dados['forma_pgto2']) ? intval(preg_replace('/\D/', '', $dados['forma_pgto2'])) : null;
        $pagamento = isset($dados['pagamento']) ? intval(preg_replace('/\D/', '', $dados['pagamento'])) : 0;
        $usuario_logado = intval(preg_replace('/\D/', '', $dados['usuario_logado']));
        $agora = date("Y-m-d H:i:s");
    
        // Preparar a consulta SQL para atualizar o orçamento
        $sql = $this->pdo->prepare('
            UPDATE lente_contato_orcamentos SET
                nome = :nome,
                cpf = :cpf,
                contato = :contato,
                id_medico = :id_medico,
                olhos = :olhos,
                olho_esquerdo = :olho_esquerdo,
                olho_direito = :olho_direito,
                id_modelo = :id_modelo,
                valor = :valor,
                id_forma_pagamento1 = :forma_pgto1,
                id_forma_pagamento2 = :forma_pgto2,
                pagamento = :pagamento,
                updated_at = :updated_at
            WHERE 
                id_lente_contato_orcamento = :id_orcamento
        ');
    
        $sql->bindParam(':nome', $nome);          
        $sql->bindParam(':cpf', $cpf);          
        $sql->bindParam(':contato', $contato);          
        $sql->bindParam(':id_medico', $id_medico);          
        $sql->bindParam(':olhos', $olhos);          
        $sql->bindParam(':olho_esquerdo', $olho_esquerdo);          
        $sql->bindParam(':olho_direito', $olho_direito);          
        $sql->bindParam(':id_modelo', $id_modelo);          
        $sql->bindParam(':valor', $valor);          
        $sql->bindParam(':forma_pgto1', $forma_pgto1);          
        $sql->bindParam(':forma_pgto2', $forma_pgto2);          
        $sql->bindParam(':pagamento', $pagamento);          
        $sql->bindParam(':updated_at', $agora);          
        $sql->bindParam(':id_orcamento', $id_orcamento);          
    
        if ($sql->execute()) {
            $descricao = "Editou o Orçamento: $nome ($id_orcamento)";
            $this->addLog("Editar", $descricao, $usuario_logado);
    
            echo "
            <script>
                alert('Orçamento Editado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Orçamento!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        }
    }
    

    /**
     * Deleta um orçamento (soft delete)
     *
     * @param integer $id_orcamento
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_orcamento, $usuario_logado){
        // Consulta para obter o nome do orçamento
        $consulta_orcamento = $this->pdo->prepare('SELECT nome FROM lente_contato_orcamentos WHERE id_lente_contato_orcamento = :id_orcamento');
        $consulta_orcamento->bindParam(':id_orcamento', $id_orcamento);
        $consulta_orcamento->execute();
        $resultado_orcamento = $consulta_orcamento->fetch(PDO::FETCH_ASSOC);

        // Verifica se o orçamento foi encontrado
        if ($resultado_orcamento) {
            $nome_orcamento = $resultado_orcamento['nome'];
        } else {
            // Se o orçamento não for encontrado, use um nome genérico
            $nome_orcamento = "Orçamento Desconhecido";
        }

        // Atualiza o registro com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE lente_contato_orcamentos SET deleted_at = :deleted_at WHERE id_lente_contato_orcamento = :id_orcamento');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_orcamento', $id_orcamento);

        if ($sql->execute()) {
            $descricao = "Deletou o Orçamento $nome_orcamento ($id_orcamento)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Orçamento Deletado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Orçamento!');
                window.location.href = '" . URL . "/lente_contato/orcamentos';
            </script>";
            exit;
        }
    }

}