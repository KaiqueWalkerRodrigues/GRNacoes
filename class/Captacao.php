<?php

class Captacao {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * Adiciona log de atividade
     */
    private function addLog($acao, $descricao, $id_usuario)
    {
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
     * listar todos os captados
     * @return array
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM captados WHERE deleted_at IS NULL ORDER BY created_at DESC');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * listar todos os captadores da empresa
     * @return array
     */
    public function listarCaptadoresPorEmpresa($id_empresa){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND ativo = 1 AND empresa = :empresa ORDER BY nome DESC');        
        $sql->bindParam(':empresa',$id_empresa);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Contar captações no intervalo de datas
     * @param int $id_captador
     * @param string $inicio
     * @param string $fim
     * @return int
     */
    public function contarCaptacoesNoIntervalo($id_captador, $inicio, $fim) {
        // Prepara a consulta SQL
        $sql = $this->pdo->prepare('
            SELECT count(*) as total
            FROM captados 
            WHERE id_captador = :id_captador 
            AND created_at BETWEEN :inicio AND :fim
            AND deleted_at IS NULL
        ');

        // Vincula os parâmetros à consulta
        $sql->bindParam(':id_captador', $id_captador);
        $sql->bindParam(':inicio', $inicio);
        $sql->bindParam(':fim', $fim);

        // Executa a consulta
        $sql->execute();

        // Obtém o resultado da contagem
        $dados = $sql->fetch(PDO::FETCH_OBJ);

        // Retorna apenas o número de captações
        return $dados->total;
    }

    /**
     * cadastra um novo captado
     * @param Array $dados    
     * @return int
     */
    public function cadastrar(Array $dados)
    {
        $id_captador = $dados['id_captador'];
        $id_medico = $dados['id_medico'];
        $id_empresa = $dados['id_empresa'];
        $nome_paciente = ucwords(strtolower(trim($dados['nome_paciente'])));
        $captado = $dados['captado'];
        $observacao = trim($dados['observacao']);
        $id_motivo = isset($dados['id_motivo']) ? $dados['id_motivo'] : null; // Pega o id_motivo, se estiver presente
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO captados 
                                    (id_captador, id_medico, id_empresa, nome_paciente, captado, observacao, id_motivo, created_at, updated_at)
                                    VALUES
                                    (:id_captador, :id_medico, :id_empresa, :nome_paciente, :captado, :observacao, :id_motivo, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':id_captador', $id_captador);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':nome_paciente', $nome_paciente);
        $sql->bindParam(':captado', $captado);
        $sql->bindParam(':observacao', $observacao);
        $sql->bindParam(':id_motivo', $id_motivo); // Adiciona o id_motivo
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            return $this->pdo->lastInsertId(); // Retorna o ID do captado recém-cadastrado
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function cadastrarAlteracao(Array $dados)
    {
        $id_captador = $dados['id_captador'];
        $id_medico = $dados['id_medico'];
        $id_empresa = $dados['id_empresa'];
        $nome_paciente = ucwords(strtolower(trim($dados['nome_paciente'])));
        $captado = $dados['captado'];
        $id_motivo = isset($dados['id_motivo']) ? $dados['id_motivo'] : null; // Pega o id_motivo, se estiver presente
        $observacao = trim($dados['observacao']);

        // Combinar $_POST['dia'] e $_POST['horario'] para formar o TIMESTAMP
        $dia = $dados['dia']; // Ex: '2024-10-03'
        $horario = $dados['horario']; // Ex: '14:30:00'
        $date_time = "$dia $horario"; // Ex: '2024-10-03 14:30:00'

        $sql = $this->pdo->prepare('INSERT INTO captados 
                                    (id_captador, id_medico, id_empresa, nome_paciente, id_motivo,captado, observacao, created_at, updated_at)
                                    VALUES
                                    (:id_captador, :id_medico, :id_empresa, :nome_paciente, :id_motivo,:captado, :observacao, :created_at, :updated_at)
                                ');

        $agora = date("Y-m-d H:i:s");

        $created_at  = $date_time;
        $updated_at  = $agora;

        $sql->bindParam(':id_captador', $id_captador);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':nome_paciente', $nome_paciente);
        $sql->bindParam(':captado', $captado);
        $sql->bindParam(':id_motivo', $id_motivo);
        $sql->bindParam(':observacao', $observacao);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {         
            $id_captado = $this->pdo->lastInsertId();    
            // Adiciona o log da edição
            $descricao = "Cadastrou a captação: $nome_paciente ($id_captado) no dia ($date_time)";
            $this->addLog('Cadastrou', $descricao, $dados['id_captador']);

            return $this->pdo->lastInsertId(); // Retorna o ID do captado recém-cadastrado
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }


    /**
     * Retorna os dados de um captado
     * @param int $id_captado
     * @return object
     */
    public function mostrar(int $id_captado)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM captados WHERE id_captado = :id_captado LIMIT 1');
        $sql->bindParam(':id_captado', $id_captado);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza os dados de um captado
     *
     * @param array $dados   
     * @return bool
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE captados SET
            id_medico = :id_medico,
            nome_paciente = :nome_paciente,
            captado = :captado,
            observacao = :observacao,
            id_motivo = :id_motivo,  -- Adiciona o campo id_motivo
            updated_at = :updated_at 
        WHERE id_captado = :id_captado
        ");

        $agora = date("Y-m-d H:i:s");

        $id_captado = $dados['id_captado'];
        $id_medico = $dados['id_medico'];
        $nome_paciente = ucwords(strtolower(trim($dados['nome_paciente'])));
        $captado = $dados['captado'];
        $observacao = trim($dados['observacao']);
        $id_motivo = isset($dados['id_motivo']) ? $dados['id_motivo'] : null; // Pega o id_motivo, se estiver presente
        $updated_at = $agora;

        $sql->bindParam(':id_captado', $id_captado);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':nome_paciente', $nome_paciente);
        $sql->bindParam(':captado', $captado);
        $sql->bindParam(':observacao', $observacao);
        $sql->bindParam(':id_motivo', $id_motivo); // Adiciona o id_motivo
        $sql->bindParam(':updated_at', $updated_at);

        if ($sql->execute()) {
            // Adiciona o log da edição
            $descricao = "Editou a captação: $nome_paciente ($id_captado)";
            $this->addLog('Editar', $descricao, $dados['id_usuario']);
        }

        return $sql->execute();
    }


    /**
     * Deleta um captado
     *
     * @param int $id_captado
     * @return bool
     */
    public function deletar($id_captado, $id_usuario)
    {
        $sql = $this->pdo->prepare('UPDATE captados SET deleted_at = :deleted_at WHERE id_captado = :id_captado');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_captado', $id_captado);

        if ($sql->execute()) {
            // Adiciona o log da desativação
            $descricao = "Deletou a captação ID: $id_captado";
            $this->addLog('Deletar', $descricao, $id_usuario);
        }

        return $sql->execute();
    }

    public function listarHoje($id_empresa) {
        $sql = $this->pdo->prepare("SELECT captados.*, medicos.nome AS nome_medico 
                                    FROM captados 
                                    JOIN medicos ON captados.id_medico = medicos.id_medico
                                    WHERE id_empresa = :id_empresa AND DATE(captados.created_at) = CURDATE() 
                                    AND captados.deleted_at IS NULL
                                    ORDER BY captados.created_at DESC");
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }  

    public function listarDoDia($id_empresa, $data) {
        // Prepara a consulta SQL, usando a data passada como parâmetro
        $sql = $this->pdo->prepare("SELECT captados.*, medicos.nome AS nome_medico 
                                     FROM captados 
                                     JOIN medicos ON captados.id_medico = medicos.id_medico
                                     WHERE id_empresa = :id_empresa AND DATE(captados.created_at) = :data 
                                     AND captados.deleted_at IS NULL
                                     ORDER BY captados.created_at DESC");
    
        // Vincula os parâmetros
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':data', $data); // Bind da nova data
        $sql->execute();
    
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }    

    public function listarHojeAdmin() {
        $sql = $this->pdo->prepare("SELECT captados.*, medicos.nome AS nome_medico 
                                    FROM captados 
                                    JOIN medicos ON captados.id_medico = medicos.id_medico
                                    WHERE DATE(captados.created_at) = CURDATE() 
                                    AND captados.deleted_at IS NULL
                                    ORDER BY captados.created_at DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }    

    public function listarDoDiaAdmin($data) {
        // Prepara a consulta SQL, usando a data passada como parâmetro
        $sql = $this->pdo->prepare("SELECT captados.*, medicos.nome AS nome_medico 
                                     FROM captados 
                                     JOIN medicos ON captados.id_medico = medicos.id_medico
                                     WHERE DATE(captados.created_at) = :data 
                                     AND captados.deleted_at IS NULL
                                     ORDER BY captados.created_at DESC");
    
        // Vincula os parâmetros
        $sql->bindParam(':data', $data); // Bind da nova data
        $sql->execute();
    
        return $sql->fetchAll(PDO::FETCH_OBJ);
    } 

    //Relatórios
        public function contarPacientes($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCaptacoes($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador
                    AND (captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarNaoCaptacoes($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND captado = 0 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarLentes($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND (captado = 3 OR captado = 4) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarGarantias($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND captado = 5 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCataratas($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND captado = 2 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCaptaveis($data, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captados.id_captador = :id_captador 
                    AND (captado = 0 OR captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

    //Total Relatórios
        public function contarTotalPacientes($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCaptacoes($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND (captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalNaoCaptacoes($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captado = 0 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalLentes($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND (captado = 3 OR captado = 4) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalGarantias($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captado = 5 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCataratas($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND captado = 2
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCaptaveis($data, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) = :data 
                    AND (captado = 0 OR captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':data', $data);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

    // Relatórios do Periodo
        public function contarPacientesDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCaptacoesDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador
                    AND (captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarNaoCaptacoesDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND captado = 0 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarLentesDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND (captado = 3 OR captado = 4) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarGarantiasDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND captado = 5 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCataratasDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND captado = 2
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarCaptaveisDoPeriodo($inicio, $fim, $id_captador, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captados.id_captador = :id_captador 
                    AND (captado = 0 OR captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            $sql->bindValue(':id_captador', $id_captador);
            
            if ($id_empresa) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

    // Total Relatórios
        public function contarTotalPacientesDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCaptacoesDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND (captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalNaoCaptacoesDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captado = 0
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalLentesDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND (captado = 3 OR captado = 4) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalGarantiasDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captado = 5 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCataratasDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND captado = 2 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function contarTotalCaptaveisDoPeriodo($inicio, $fim, $id_empresa = null) {
            $query = "SELECT COUNT(*) as total FROM captados 
                    WHERE DATE(captados.created_at) BETWEEN :inicio AND :fim 
                    AND (captado = 0 OR captado = 1 OR captado = 2) 
                    AND deleted_at IS NULL";
            
            if (!is_null($id_empresa)) {
                $query .= " AND captados.id_empresa = :id_empresa";
            }

            $sql = $this->pdo->prepare($query);
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim);
            
            if (!is_null($id_empresa)) {
                $sql->bindValue(':id_empresa', $id_empresa);
            }

            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_OBJ);
            return $result->total;
        }

        public function listarMotivosMaisUtilizados($id_empresa = null) {
            $query = "SELECT id_motivo, COUNT(*) as total 
                      FROM captados 
                      WHERE id_motivo IS NOT NULL AND id_motivo != 0
                      AND deleted_at IS NULL";
            
            if ($id_empresa) {
                $query .= " AND id_empresa = :id_empresa";
            }
        
            $query .= " GROUP BY id_motivo 
                        ORDER BY total DESC 
                        LIMIT 5"; // Limite de 5 motivos mais usados
        
            $sql = $this->pdo->prepare($query);
        
            if ($id_empresa) {
                $sql->bindParam(':id_empresa', $id_empresa);
            }
        
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
        
    
}
include_once('Conexao.php');

$Captacao = new Captacao();

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['captacao_cadastrar'])) {
    if(!isset($_POST['observacao']))
        $_POST['observacao'] = '';
    $dados = [
        'id_captador' => $_POST['id_usuario'], // Ajuste conforme seu sistema de sessão
        'id_medico' => $_POST['id_medico'],
        'id_motivo' => $_POST['id_motivo'],
        'id_empresa' => $_POST['id_empresa'],
        'nome_paciente' => $_POST['nome_paciente'],
        'captado' => $_POST['captado'],
        'observacao' => $_POST['observacao']
    ];
    $Captacao->cadastrar($dados);
    header('Location: '.URL.'/captacao/captar'); // Redireciona para evitar re-submissão de formulário
}    

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['captacao_cadastrarAlteracao'])) {
    $dados = [
        'id_captador' => $_POST['id_usuario'], // Ajuste conforme seu sistema de sessão
        'id_medico' => $_POST['id_medico'],
        'id_motivo' => $_POST['id_motivo'],
        'id_empresa' => $_POST['id_empresa'],
        'nome_paciente' => $_POST['nome_paciente'],
        'captado' => $_POST['captado'],
        'observacao' => $_POST['observacao'],
        'dia' => $_POST['dia'],
        'horario' => $_POST['horario']
    ];
    $Captacao->cadastrarAlteracao($dados);
    $url = 'Location: '.URL.'/captacao/alterar?data_atendimento='.$_POST['dia'];
    header($url);
}    
?>
