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
     * Listar todos os orçamentos da unidade
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(int $id_empresa){
        $sql = $this->pdo->prepare('
            SELECT * FROM lente_contato_orcamentos WHERE id_empresa = :id_empresa AND deleted_at IS NULL AND valor IS NOT NULL
        ');        
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }   

    /**
     * Listar todos os orçamentos
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listarAdmin(){
        $sql = $this->pdo->prepare('
            SELECT * FROM lente_contato_orcamentos WHERE deleted_at IS NULL AND valor IS NOT NULL
        ');        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }   

    /**
     * Listar todos os testes
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listarTeste(){
        $sql = $this->pdo->prepare('
            SELECT * FROM lente_contato_orcamentos WHERE deleted_at IS NULL AND valor IS NULL
        ');        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }      

    /**
     * Cadastrar um novo orçamento
     * @param array $dados
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(array $dados) {
        // Extrair e sanitizar os dados do formulário
        $nome    = ucwords(strtolower(trim($dados['nome'])));
        $cpf     = preg_replace('/\D/', '', $dados['cpf']);
        $contato = preg_replace('/\D/', '', $dados['contato']);
        
        // Se o médico não for selecionado, atribui null
        $id_medico = (isset($dados['id_medico']) && intval($dados['id_medico']) > 0)
                        ? intval($dados['id_medico'])
                        : null;
                        
        $olhos = intval($dados['olhos']);
        
        // Dados adicionais do paciente
        $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
        $olho_direito  = isset($dados['olho_direito'])  ? trim($dados['olho_direito'])  : null;
        
        // Dados referentes aos modelos das lentes (em vez de fornecedores)
        $id_modelo_direito = (isset($dados['lente_direita']) && $dados['lente_direita'] !== '')
                                ? intval($dados['lente_direita'])
                                : null;
        $id_modelo_esquerdo = (isset($dados['lente_esquerda']) && $dados['lente_esquerda'] !== '')
                                ? intval($dados['lente_esquerda'])
                                : null;
        
        // Quantidades para cada olho
        $qnt_direita  = isset($dados['qnt_direita']) ? intval($dados['qnt_direita']) : 1;
        $qnt_esquerda = isset($dados['qnt_esquerda']) ? intval($dados['qnt_esquerda']) : 1;
        
        $valor = floatval($dados['valor']);
        
        // Formas de pagamento: se o valor enviado for 0 ou não estiver definido, armazenamos null
        $forma_pgto1 = (isset($dados['forma_pgto1']) && intval($dados['forma_pgto1']) > 0)
                            ? intval($dados['forma_pgto1'])
                            : null;
        $forma_pgto2 = (isset($dados['forma_pgto2']) && intval($dados['forma_pgto2']) > 0)
                            ? intval($dados['forma_pgto2'])
                            : null;
                            
        $status = isset($dados['status']) ? intval($dados['status']) : 0;
        
        // Usuário logado (contatóloga) e empresa
        $id_contatologa = intval($dados['usuario_logado']);
        $id_empresa     = intval($dados['id_empresa']);
        
        // Datas de criação e atualização
        $agora = date("Y-m-d H:i:s");
        $deleted_at = null; // Valor nulo para deleted_at
        
        // Preparar a consulta SQL para inserir o novo orçamento
        $sql = $this->pdo->prepare('
            INSERT INTO lente_contato_orcamentos 
            (
                nome, cpf, contato, id_medico, olhos, valor,
                id_modelo_esquerdo, id_modelo_direito, qnt_esquerda, qnt_direita,
                id_forma_pagamento1, id_forma_pagamento2, status,
                olho_esquerdo, olho_direito, id_contatologa, id_empresa,
                created_at, updated_at, deleted_at
            )
            VALUES
            (
                :nome, :cpf, :contato, :id_medico, :olhos, :valor,
                :id_modelo_esquerdo, :id_modelo_direito, :qnt_esquerda, :qnt_direita,
                :forma_pgto1, :forma_pgto2, :status,
                :olho_esquerdo, :olho_direito, :id_contatologa, :id_empresa,
                :created_at, :updated_at, :deleted_at
            )
        ');
        
        // Bind dos parâmetros
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':cpf', $cpf);
        $sql->bindParam(':contato', $contato);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':olhos', $olhos);
        $sql->bindParam(':valor', $valor);
        
        $sql->bindParam(':id_modelo_esquerdo', $id_modelo_esquerdo);
        $sql->bindParam(':id_modelo_direito', $id_modelo_direito);
        $sql->bindParam(':qnt_esquerda', $qnt_esquerda);
        $sql->bindParam(':qnt_direita', $qnt_direita);
        
        $sql->bindParam(':forma_pgto1', $forma_pgto1);
        $sql->bindParam(':forma_pgto2', $forma_pgto2);
        $sql->bindParam(':status', $status);
        
        $sql->bindParam(':olho_esquerdo', $olho_esquerdo);
        $sql->bindParam(':olho_direito', $olho_direito);
        
        $sql->bindParam(':id_contatologa', $id_contatologa);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':deleted_at', $deleted_at);

        if ($sql->execute()) {
            $orcamento_id = $this->pdo->lastInsertId();
            $descricao = "Cadastrou o Orçamento: $nome ($orcamento_id)";
            $this->addLog("Cadastrar", $descricao, $id_contatologa);

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
     * Cadastrar um novo Teste de Lente
     * @param array $dados
     * @return void
     * @example $Obj->cadastrarTeste($_POST);
     */
    public function cadastrarTeste(array $dados) {
        // Extrair e sanitizar os dados do formulário
        $nome    = ucwords(strtolower(trim($dados['nome'])));
        $cpf     = preg_replace('/\D/', '', $dados['cpf']);
        $contato = preg_replace('/\D/', '', $dados['contato']);
        
        // Se o médico não for selecionado, atribui null
        $id_medico = (isset($dados['id_medico']) && intval($dados['id_medico']) > 0)
                        ? intval($dados['id_medico'])
                        : null;
        
        $olhos = intval($dados['olhos']);
        
        // Dados adicionais do paciente
        $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
        $olho_direito  = isset($dados['olho_direito'])  ? trim($dados['olho_direito'])  : null;
        
        // Dados referentes aos modelos das lentes (em vez de fornecedores)
        $id_modelo_direito = (isset($dados['id_lente_direita']) && $dados['id_lente_direita'] !== '')
                                ? intval($dados['id_lente_direita'])
                                : null;
        $id_modelo_esquerdo = (isset($dados['id_lente_esquerda']) && $dados['id_lente_esquerda'] !== '')
                                ? intval($dados['id_lente_esquerda'])
                                : null;
        
        $id_empresa     = intval($dados['id_empresa']);
        $id_contatologa = intval($dados['usuario_logado']);
        $agora = date("Y-m-d H:i:s");

        // Verificar o valor de $olhos para ignorar um dos lados, se necessário
        if ($olhos === 1) {
            // Ignora o cadastro do lado direito
            $olho_direito = null;
            $id_modelo_direito = null;
            $qnt_direita = null;
        } elseif ($olhos === 2) {
            // Ignora o cadastro do lado esquerdo
            $olho_esquerdo = null;
            $id_modelo_esquerdo = null;
            $qnt_esquerda = null;
        }
        
        
        // Preparar a consulta SQL para inserir o novo Teste de Lente
        $sql = $this->pdo->prepare('
            INSERT INTO lente_contato_orcamentos 
            (nome, cpf, contato, id_medico, olhos, olho_esquerdo, olho_direito, id_modelo_direito, id_modelo_esquerdo, qnt_esquerda, qnt_direita, id_empresa, id_contatologa, created_at, updated_at)
            VALUES
            (:nome, :cpf, :contato, :id_medico, :olhos, :olho_esquerdo, :olho_direito, :id_modelo_direito, :id_modelo_esquerdo, :qnt_esquerda, :qnt_direita, :id_empresa, :id_contatologa, :created_at, :updated_at)
        ');
        
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':cpf', $cpf);
        $sql->bindParam(':contato', $contato);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':olhos', $olhos);
        $sql->bindParam(':olho_esquerdo', $olho_esquerdo);
        $sql->bindParam(':olho_direito', $olho_direito);
        $sql->bindParam(':id_modelo_direito', $id_modelo_direito);
        $sql->bindParam(':id_modelo_esquerdo', $id_modelo_esquerdo);
        $sql->bindParam(':qnt_esquerda', $qnt_esquerda);
        $sql->bindParam(':qnt_direita', $qnt_direita);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':id_contatologa', $id_contatologa);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);
        
        if ($sql->execute()) {
            $teste_id = $this->pdo->lastInsertId();
            $descricao = "Cadastrou o Teste: $nome ($teste_id)";
            $this->addLog("Cadastrar", $descricao, $id_contatologa);
            
            echo "
            <script>
                alert('Teste Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/testes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Teste de Lente!');
                window.location.href = '" . URL . "/lente_contato/testes';
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

    /**
     * Editar um orçamento existente
     * @param array $dados
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados) {
        // Extrair e sanitizar os dados do formulário
        $nome    = ucwords(strtolower(trim($dados['nome'])));
        $cpf     = preg_replace('/\D/', '', $dados['cpf']);
        $contato = preg_replace('/\D/', '', $dados['contato']);
        
        // Se o médico não for selecionado, atribui null
        $id_medico = (isset($dados['id_medico']) && intval($dados['id_medico']) > 0)
                        ? intval($dados['id_medico'])
                        : null;
                        
        $olhos = intval($dados['olhos']);
        $valor = floatval($dados['valor']);
        
        // Dados adicionais do paciente
        $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
        $olho_direito  = isset($dados['olho_direito'])  ? trim($dados['olho_direito'])  : null;
        
        // Dados referentes aos modelos das lentes
        $id_modelo_direito = (isset($dados['lente_direita']) && $dados['lente_direita'] !== '')
                                ? intval($dados['lente_direita'])
                                : null;
        $id_modelo_esquerdo = (isset($dados['lente_esquerda']) && $dados['lente_esquerda'] !== '')
                                ? intval($dados['lente_esquerda'])
                                : null;
        
        // Quantidades para cada olho
        $qnt_direita  = isset($dados['qnt_direita']) ? intval($dados['qnt_direita']) : 1;
        $qnt_esquerda = isset($dados['qnt_esquerda']) ? intval($dados['qnt_esquerda']) : 1;
        
        // Formas de pagamento
        $forma_pgto1 = (isset($dados['forma_pgto1']) && intval($dados['forma_pgto1']) > 0)
                            ? intval($dados['forma_pgto1'])
                            : null;
        $forma_pgto2 = (isset($dados['forma_pgto2']) && intval($dados['forma_pgto2']) > 0)
                            ? intval($dados['forma_pgto2'])
                            : null;
                            
        $status = isset($dados['status']) ? intval($dados['status']) : 0;
        
        // Usuário logado (contatóloga) e empresa
        $id_contatologa = intval($dados['usuario_logado']);
        $id_empresa     = intval($dados['id_empresa']);
        
        // Data de atualização
        $agora = date("Y-m-d H:i:s");
        
        // Identificador do orçamento a ser editado
        $id_orcamento = intval($dados['id_orcamento']);
        
        // Preparar a consulta SQL para atualizar o orçamento
        $sql = $this->pdo->prepare('
            UPDATE lente_contato_orcamentos SET
                nome = :nome,
                cpf = :cpf,
                contato = :contato,
                id_medico = :id_medico,
                olhos = :olhos,
                valor = :valor,
                id_modelo_esquerdo = :id_modelo_esquerdo,
                id_modelo_direito = :id_modelo_direito,
                qnt_esquerda = :qnt_esquerda,
                qnt_direita = :qnt_direita,
                id_forma_pagamento1 = :forma_pgto1,
                id_forma_pagamento2 = :forma_pgto2,
                status = :status,
                olho_esquerdo = :olho_esquerdo,
                olho_direito = :olho_direito,
                id_contatologa = :id_contatologa,
                id_empresa = :id_empresa,
                updated_at = :updated_at
            WHERE id_lente_contato_orcamento = :id_lente_contato_orcamento
        ');
        
        // Bind dos parâmetros
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':cpf', $cpf);
        $sql->bindParam(':contato', $contato);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':olhos', $olhos);
        $sql->bindParam(':valor', $valor);
        $sql->bindParam(':id_modelo_esquerdo', $id_modelo_esquerdo);
        $sql->bindParam(':id_modelo_direito', $id_modelo_direito);
        $sql->bindParam(':qnt_esquerda', $qnt_esquerda);
        $sql->bindParam(':qnt_direita', $qnt_direita);
        $sql->bindParam(':forma_pgto1', $forma_pgto1);
        $sql->bindParam(':forma_pgto2', $forma_pgto2);
        $sql->bindParam(':status', $status);
        $sql->bindParam(':olho_esquerdo', $olho_esquerdo);
        $sql->bindParam(':olho_direito', $olho_direito);
        $sql->bindParam(':id_contatologa', $id_contatologa);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_lente_contato_orcamento', $id_orcamento);
        
        if ($sql->execute()) {
            $descricao = "Editou o Orçamento: $nome ($id_orcamento)";
            $this->addLog("Editar", $descricao, $id_contatologa);

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

    public function editarTeste(array $dados) {
        try {
            // Validação básica dos dados
            if (empty($dados['id_teste']) || empty($dados['nome']) || empty($dados['cpf'])) {
                throw new Exception("Dados obrigatórios não fornecidos.");
            }
    
            // Sanitização dos dados
            $id_orcamento = $this->sanitizeNumber($dados['id_teste']);
            $nome = $this->formatName($dados['nome']);
            $cpf = $this->sanitizeCPF($dados['cpf']);
            $contato = $this->sanitizeNumber($dados['contato']);
            $id_medico = $this->sanitizeNumber($dados['id_medico']);
            $olhos = $this->sanitizeNumber($dados['olhos']);
            $olho_esquerdo = isset($dados['olho_esquerdo']) ? trim($dados['olho_esquerdo']) : null;
            $olho_direito = isset($dados['olho_direito']) ? trim($dados['olho_direito']) : null;
            $id_modelo_direito = $this->sanitizeNumber($dados['id_lente_direita']);
            $id_modelo_esquerdo = $this->sanitizeNumber($dados['id_lente_esquerda']);
            $qnt_direita = $this->sanitizeNumber($dados['qnt_direita']);
            $qnt_esquerda = $this->sanitizeNumber($dados['qnt_esquerda']);
            $usuario_logado = $this->sanitizeNumber($dados['usuario_logado']);
            $agora = date("Y-m-d H:i:s");
    
            // Verificar o valor de $olhos para ignorar um dos lados, se necessário
            if ($olhos === 1) {
                // Ignora o cadastro do lado direito
                $olho_direito = null;
                $id_modelo_direito = null;
                $qnt_direita = null;
            } elseif ($olhos === 2) {
                // Ignora o cadastro do lado esquerdo
                $olho_esquerdo = null;
                $id_modelo_esquerdo = null;
                $qnt_esquerda = null;
            }
    
            // Preparar a consulta SQL para atualizar o teste
            $sql = $this->pdo->prepare('
                UPDATE lente_contato_orcamentos SET
                    nome = :nome,
                    cpf = :cpf,
                    contato = :contato,
                    id_medico = :id_medico,
                    olhos = :olhos,
                    olho_esquerdo = :olho_esquerdo,
                    olho_direito = :olho_direito,
                    id_modelo_direito = :id_modelo_direito,
                    id_modelo_esquerdo = :id_modelo_esquerdo,
                    qnt_direita = :qnt_direita,
                    qnt_esquerda = :qnt_esquerda,
                    updated_at = :updated_at
                WHERE 
                    id_lente_contato_orcamento = :id_orcamento
            ');
    
            // Vincular os parâmetros
            $sql->bindValue(':nome', $nome);
            $sql->bindValue(':cpf', $cpf);
            $sql->bindValue(':contato', $contato);
            $sql->bindValue(':id_medico', $id_medico, PDO::PARAM_INT);
            $sql->bindValue(':olhos', $olhos, PDO::PARAM_INT);
            $sql->bindValue(':olho_esquerdo', $olho_esquerdo);
            $sql->bindValue(':olho_direito', $olho_direito);
            $sql->bindValue(':id_modelo_direito', $id_modelo_direito, PDO::PARAM_INT);
            $sql->bindValue(':id_modelo_esquerdo', $id_modelo_esquerdo, PDO::PARAM_INT);
            $sql->bindValue(':qnt_direita', $qnt_direita, PDO::PARAM_INT);
            $sql->bindValue(':qnt_esquerda', $qnt_esquerda, PDO::PARAM_INT);
            $sql->bindValue(':updated_at', $agora);
            $sql->bindValue(':id_orcamento', $id_orcamento, PDO::PARAM_INT);
    
            // Executar a consulta
            if ($sql->execute()) {
                $descricao = "Editou o Teste: $nome ($id_orcamento)";
                $this->addLog("Editar", $descricao, $usuario_logado);
    
                echo "
                <script>
                    alert('Teste Editado com Sucesso!');
                    window.location.href = '" . URL . "/lente_contato/testes';
                </script>";
                exit;
            } else {
                throw new Exception("Erro ao executar a consulta SQL.");
            }
        } catch (Exception $e) {
            echo "
            <script>
                alert('Erro: " . addslashes($e->getMessage()) . "');
                window.location.href = '" . URL . "/lente_contato/testes';
            </script>";
            exit;
        }
    }
    
    // Funções auxiliares para sanitização
    private function sanitizeNumber($value) {
        return intval(preg_replace('/\D/', '', $value));
    }
    
    private function formatName($name) {
        return ucwords(strtolower(trim($name)));
    }
    
    private function sanitizeCPF($cpf) {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11) {
            throw new Exception("CPF inválido.");
        }
        return $cpf;
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

    public function transferirTeste(Array $dados){
        // Sanitização dos dados recebidos
        $id_teste      = intval($dados['id_teste']);
        $valor         = floatval($dados['valor']);
        $forma_pgto1   = intval($dados['forma_pgto1']);
        $forma_pgto2   = intval($dados['forma_pgto2']);
        $status        = intval($dados['status']);
        $usuario_logado= intval($dados['usuario_logado']);
        $agora         = date("Y-m-d H:i:s");
    
        // Atualiza o registro do teste, inserindo os dados financeiros
        $sql = $this->pdo->prepare('
            UPDATE lente_contato_orcamentos 
            SET 
                valor = :valor, 
                id_forma_pagamento1 = :forma_pgto1, 
                id_forma_pagamento2 = :forma_pgto2, 
                status = :status, 
                updated_at = :updated_at 
            WHERE 
                id_lente_contato_orcamento = :id_teste AND valor IS NULL
        ');
    
        $sql->bindParam(':valor', $valor);
        $sql->bindParam(':forma_pgto1', $forma_pgto1);
        $sql->bindParam(':forma_pgto2', $forma_pgto2);
        $sql->bindParam(':status', $status);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_teste', $id_teste);
    
        if ($sql->execute()){
            $descricao = "Transferiu o Teste para Orçamento: Teste ID $id_teste";
            $this->addLog("Transferir", $descricao, $usuario_logado);
            echo "
            <script>
                alert('Teste Transferido para Orçamento com Sucesso!');
                window.location.href = '" . URL . "/lente_contato/testes';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível transferir o Teste!');
                window.location.href = '" . URL . "/lente_contato/testes';
            </script>";
            exit;
        }
    }
    
}
