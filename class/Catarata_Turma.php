<?php

class Catarata_turma {

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
     * Listar todas as turmas catarata
     * @return array
     * @example $variavel = $Obj->listar();
     */
    public function listar($id_agenda){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_turmas WHERE id_agenda = :id_agenda AND deleted_at IS NULL ORDER BY horario');  
        $sql->bindParam(':id_agenda',$id_agenda);      
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastra uma nova turma catarata
     * @param Array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        $id_agenda = trim($dados['id_agenda']);
        $horario  = trim($dados['horario']);
        $qntd     = intval($dados['qntd']); // Conversão para inteiro
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO catarata_turmas 
                                    (id_agenda, horario, qntd, created_at, updated_at)
                                    VALUES
                                    (:id_agenda, :horario, :qntd, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':id_agenda', $id_agenda);          
        $sql->bindParam(':horario', $horario);
        $sql->bindParam(':qntd', $qntd);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $catarata_turma_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou a Turma Catarata: ID $catarata_turma_id, Agenda ID $id_agenda, Horário $horario, Quantidade $qntd";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Turma Catarata Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar a Turma Catarata!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma turma catarata específica
     * @param int $id_catarata_turma
     * @return object
     * @example $variavel = $Obj->mostrar($id_catarata_turma);
     */
    public function mostrar(int $id_catarata_turma){
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('SELECT * FROM catarata_turmas WHERE id_catarata_turma = :id_catarata_turma LIMIT 1');
        $sql->bindParam(':id_catarata_turma', $id_catarata_turma);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza uma turma catarata específica
     *
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE catarata_turmas SET
            id_agenda = :id_agenda,
            horario = :horario,
            qntd = :qntd,
            updated_at = :updated_at 
        WHERE id_catarata_turma = :id_catarata_turma
        ");

        $agora = date("Y-m-d H:i:s");

        $id_catarata_turma = $dados['id_catarata_turma'];
        $id_agenda = trim($dados['id_agenda']);
        $horario = trim($dados['horario']);
        $qntd = intval($dados['qntd']); // Conversão para inteiro
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_catarata_turma', $id_catarata_turma);
        $sql->bindParam(':id_agenda', $id_agenda);
        $sql->bindParam(':horario', $horario);
        $sql->bindParam(':qntd', $qntd);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou a Turma Catarata: ID $id_catarata_turma, Agenda ID $id_agenda, Horário $horario, Quantidade $qntd";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Turma Catarata Editada com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar a Turma Catarata!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        }
    }

    /**
     * Deleta uma turma catarata específica (soft delete)
     *
     * @param integer $id_catarata_turma
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_catarata_turma, $usuario_logado){
        // Consulta para obter os detalhes da turma catarata
        $consulta_turma = $this->pdo->prepare('SELECT * FROM catarata_turmas WHERE id_catarata_turma = :id_catarata_turma');
        $consulta_turma->bindParam(':id_catarata_turma', $id_catarata_turma);
        $consulta_turma->execute();
        $resultado_turma = $consulta_turma->fetch(PDO::FETCH_ASSOC);

        // Verifica se a turma foi encontrada
        if ($resultado_turma) {
            $id_agenda = $resultado_turma['id_agenda'];
            $horario = $resultado_turma['horario'];
            $qntd = $resultado_turma['qntd'];
            $nome_turma = "Turma Catarata ID $id_catarata_turma, Agenda ID $id_agenda, Horário $horario, Quantidade $qntd";
        } else {
            // Se a turma não for encontrada, use um nome genérico
            $nome_turma = "Turma Catarata Desconhecida";
        }

        // Atualiza o registro de turma catarata com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE catarata_turmas SET deleted_at = :deleted_at WHERE id_catarata_turma = :id_catarata_turma');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_catarata_turma', $id_catarata_turma);

        if ($sql->execute()) {
            $descricao = "Deletou a $nome_turma";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Turma Catarata Deletada com Sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Turma Catarata!');
                window.location.href = '" . URL . "/cirurgias/catarata/agenda?id=".$id_agenda."';
            </script>";
            exit;
        }
    }

    public function contarAgendamentos($id_turma){
        $sql = $this->pdo->prepare("SELECT count(id_catarata_agendamento) AS qntd FROM catarata_agendamentos WHERE id_turma = :id_turma");
        $sql->bindParam(':id_turma',$id_turma);
        $sql->execute();

        $result = $sql->fetch(PDO::FETCH_ASSOC);

        return $result['qntd'];
    }

    public function listarAgendamentos($id_turma){
        $sql = $this->pdo->prepare("SELECT * FROM catarata_agendamentos WHERE id_turma = :id_turma");
        $sql->bindParam(':id_turma',$id_turma);
        $sql->execute();

        $agendamentos = $sql->fetchAll(PDO::FETCH_OBJ);

        return $agendamentos;
    }
}

?>
