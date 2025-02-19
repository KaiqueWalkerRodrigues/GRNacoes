<?php

class Catarata_Agenda {

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
     * Verifica se já existe uma agenda para a data especificada
     * @param string $data
     * @return bool
     */
    private function verificarDataExistente($data) {
        $sql = $this->pdo->prepare('SELECT id_catarata_agenda FROM catarata_agendas WHERE data = :data AND deleted_at IS NULL LIMIT 1');
        $sql->bindParam(':data', $data);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_OBJ) ? true : false;
    }

    /**
     * Listar todas as agendas de catarata
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_agendas WHERE deleted_at IS NULL ORDER BY data');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Listar todas as agendas de catarata
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listarProximas(){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_agendas WHERE deleted_at IS NULL AND data > CURDATE() ORDER BY data');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastra uma nova agenda de catarata
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados){
        $data  = trim($dados['data']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        try {
            // Verificar se já existe uma agenda para a data
            if ($this->verificarDataExistente($data)) {
                echo "
                <script>
                    alert('Já existe uma agenda cadastrada para esta data!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            }

            $sql = $this->pdo->prepare('INSERT INTO catarata_agendas 
                                        (data, created_at, updated_at)
                                        VALUES
                                        (:data, :created_at, :updated_at)
                                    ');

            $created_at  = $agora;
            $updated_at  = $agora;

            $sql->bindParam(':data', $data);          
            $sql->bindParam(':created_at', $created_at);          
            $sql->bindParam(':updated_at', $updated_at);          

            if ($sql->execute()) {
                $catarata_agenda_id = $this->pdo->lastInsertId();
                
                $descricao = "Cadastrou a Agenda de Catarata para o dia: $data ($catarata_agenda_id)";
                $this->addLog("Cadastrar",$descricao,$usuario_logado);

                echo "
                <script>
                    alert('Agenda de Catarata Cadastrada com Sucesso!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            } else {
                echo "
                <script>
                    alert('Não foi possível Cadastrar a Agenda de Catarata!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            }
        } catch (Exception $e) {
            echo "
            <script>
                alert('Ocorreu um erro ao cadastrar a agenda. Por favor, tente novamente.');
                window.location.href = '" . URL . "/cirurgias/catarata/agendas';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma Agenda de Catarata
     * @param int $id_catarata_agenda
     * @return object
     * @example $variavel = $Obj->mostrar($id_catarata_agenda);
     */
    public function mostrar(int $id_catarata_agenda){
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('SELECT * FROM catarata_agendas WHERE id_catarata_agenda = :id_catarata_agenda LIMIT 1');
        $sql->bindParam(':id_catarata_agenda', $id_catarata_agenda);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza uma agenda de catarata
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE catarata_agendas SET
            data = :data,
            updated_at = :updated_at 
        WHERE id_catarata_agenda = :id_catarata_agenda
        ");

        $agora = date("Y-m-d H:i:s");

        $id_catarata_agenda = $dados['id_catarata_agenda'];
        $data = trim($dados['data']);
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        try {
            // Verificar se já existe uma agenda para a data
            if ($this->verificarDataExistente($data)) {
                echo "
                <script>
                    alert('Já existe uma agenda cadastrada para esta data!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            }

            $sql->bindParam(':id_catarata_agenda',$id_catarata_agenda);
            $sql->bindParam(':data',$data);
            $sql->bindParam(':updated_at', $updated_at);       

            if ($sql->execute()) {
                $descricao = "Editou a Agenda de Catarata para o dia: $data ($id_catarata_agenda)";
                $this->addLog("Editar",$descricao,$usuario_logado);

                echo "
                <script>
                    alert('Agenda de Catarata Editada com Sucesso!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            } else {
                echo "
                <script>
                    alert('Não foi possível Editar a Agenda de Catarata!');
                    window.location.href = '" . URL . "/cirurgias/catarata/agendas';
                </script>";
                exit;
            }
        } catch (Exception $e) {
            echo "
            <script>
                alert('Ocorreu um erro ao editar a agenda. Por favor, tente novamente.');
                window.location.href = '" . URL . "/cirurgias/catarata/agendas';
            </script>";
            exit;
        }
    }

    /**
     * Deleta uma Agenda de Catarata
     *
     * @param integer $id_catarata_agenda
     * @return void (esse metodo não retorna nada)
     */
    public function deletar(int $id_catarata_agenda, $usuario_logado){
        // Consulta para obter a data da agenda
        $consulta_agenda = $this->pdo->prepare('SELECT data FROM catarata_agendas WHERE id_catarata_agenda = :id_catarata_agenda');
        $consulta_agenda->bindParam(':id_catarata_agenda', $id_catarata_agenda);
        $consulta_agenda->execute();
        $resultado_agenda = $consulta_agenda->fetch(PDO::FETCH_ASSOC);

        // Verifica se a agenda foi encontrada
        if ($resultado_agenda) {
            $data_agenda = $resultado_agenda['data'];
        } else {
            // Se a agenda não for encontrada, use uma data genérica
            $data_agenda = "Data Desconhecida";
        }

        // Atualiza o registro de agenda com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE catarata_agendas SET deleted_at = :deleted_at WHERE id_catarata_agenda = :id_catarata_agenda');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_catarata_agenda', $id_catarata_agenda);

        if ($sql->execute()) {
            $descricao = "Deletou a Agenda de Catarata para o dia: $data_agenda ($id_catarata_agenda)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Agenda de Catarata Deletada com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendas';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Agenda de Catarata!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendas';
            </script>";
            exit;
        }
    }

    //Conta quantos agendamentos tem naquela agenda
    public function contarAgendamentos($id_agenda){
        $sql = $this->pdo->prepare("SELECT count(id_catarata_agendamento) AS qntd FROM catarata_agendamentos WHERE id_agenda = :id_agenda AND deleted_at IS NULL");
        $sql->bindParam(':id_agenda',$id_agenda);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_ASSOC);

        return $result['qntd'];
    }

    //Conta a quantidade de Vagas totais na agenda
    public function somarQntdVagas($id_agenda){
        $sql = $this->pdo->prepare("SELECT sum(qntd) as soma FROM catarata_turmas WHERE id_agenda = :id_agenda AND deleted_at IS NULL");
        $sql->bindParam(':id_agenda',$id_agenda);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_ASSOC);

        $result['soma'] ?? $result['soma'] = 0;

        return $result['soma'];
    }

}
?>
