<?php

class Catarata_Agendamento {

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

    /**
     * Listar todos os agendamentos
     * @return array
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_agendamentos WHERE deleted_at IS NULL AND externo = 0 ORDER BY nome');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }         

    /**
     * Cadastrar um novo Paciente
     * @param Array $dados    
     * @return void
     */
    public function cadastrar(Array $dados){
        // Sanitização e formatação dos dados
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $cpf = preg_replace('/\D/', '', $dados['cpf']); // Remove caracteres não numéricos
        $contato = preg_replace('/\D/', '', $dados['contato']); // Remove caracteres não numéricos
        $id_solicitante = (int)$dados['id_solicitante'];
        $id_convenio = (int)$dados['id_convenio'];
        $id_lente = (int)$dados['id_lente'];
        $id_agenda = (int)$dados['id_agenda'];
        $id_turma = (int)$dados['id_turma'];
        $id_orientador = (int)$dados['id_orientador']; // Novo campo adicionado
        $olhos = trim($dados['olhos']);
        $dioptria_esquerda = trim($dados['dioptria_esquerda']);
        $dioptria_direita = trim($dados['dioptria_direita']);
        $valor = trim($dados['valor']);
        $forma_pgto = trim($dados['forma_pgto']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        // Preparar a consulta de inserção com o novo campo
        $sql = $this->pdo->prepare('INSERT INTO catarata_agendamentos 
                                    (id_solicitante, id_convenio, id_lente, id_agenda, id_turma, id_orientador, nome, cpf, contato, olhos, dioptria_esquerda, dioptria_direita, valor, forma_pgto, created_at, updated_at)
                                    VALUES
                                    (:id_solicitante, :id_convenio, :id_lente, :id_agenda, :id_turma, :id_orientador, :nome, :cpf, :contato, :olhos, :dioptria_esquerda, :dioptria_direita, :valor, :forma_pgto, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        // Bind dos parâmetros incluindo o novo campo
        $sql->bindParam(':id_solicitante', $id_solicitante, PDO::PARAM_INT);
        $sql->bindParam(':id_convenio', $id_convenio, PDO::PARAM_INT);
        $sql->bindParam(':id_lente', $id_lente, PDO::PARAM_INT);
        $sql->bindParam(':id_agenda', $id_agenda, PDO::PARAM_INT);
        $sql->bindParam(':id_turma', $id_turma, PDO::PARAM_INT);
        $sql->bindParam(':id_orientador', $id_orientador, PDO::PARAM_INT);
        $sql->bindParam(':nome', $nome, PDO::PARAM_STR);
        $sql->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $sql->bindParam(':contato', $contato, PDO::PARAM_STR);
        $sql->bindParam(':olhos', $olhos, PDO::PARAM_STR);
        $sql->bindParam(':dioptria_esquerda', $dioptria_esquerda, PDO::PARAM_STR);
        $sql->bindParam(':dioptria_direita', $dioptria_direita, PDO::PARAM_STR);
        $sql->bindParam(':valor', $valor, PDO::PARAM_STR);
        $sql->bindParam(':forma_pgto', $forma_pgto, PDO::PARAM_STR);
        $sql->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $sql->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);          

        if ($sql->execute()) {
            $id_agendamento = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o agendamento: $nome ($id_agendamento)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Agendamento cadastrado com sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cadastrar o agendamento!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um Paciente
     * @param int $id_agendamento
     * @return object
     */
    public function mostrar(int $id_agendamento){
        $sql = $this->pdo->prepare('SELECT * FROM catarata_agendamentos WHERE id_catarata_agendamento = :id_agendamento LIMIT 1');
        $sql->bindParam(':id_agendamento', $id_agendamento, PDO::PARAM_INT);
        $sql->execute();
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado Paciente
     * @param array $dados   
     * @return void
     */
    public function editar(array $dados){
        // Sanitização e formatação dos dados
        $id_agendamento = (int)$dados['id_agendamento'];
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $cpf = preg_replace('/\D/', '', $dados['cpf']); // Remove caracteres não numéricos
        $contato = preg_replace('/\D/', '', $dados['contato']); // Remove caracteres não numéricos
        $olhos = trim($dados['olhos']);
        $dioptria_esquerda= trim($dados['dioptria_esquerda']);
        $dioptria_direita = trim($dados['dioptria_direita']);
        $valor = trim($dados['valor']);
        $forma_pgto = trim($dados['forma_pgto']);
        $id_convenio = $dados['id_convenio'];
        $id_orientador = (int)$dados['id_orientador'];
        $id_turma = (int)$dados['id_turma'];
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        // Preparar a consulta de atualização incluindo o novo campo
        $sql = $this->pdo->prepare("UPDATE catarata_agendamentos SET
            nome = :nome,
            cpf = :cpf,
            contato = :contato,
            olhos = :olhos,
            dioptria_esquerda = :dioptria_esquerda,
            dioptria_direita = :dioptria_direita,
            valor = :valor,
            forma_pgto = :forma_pgto,
            id_orientador = :id_orientador,
            id_convenio = :id_convenio,
            id_turma = :id_turma,
            updated_at = :updated_at
        WHERE id_catarata_agendamento = :id_agendamento
        ");

        // Bind dos parâmetros incluindo o novo campo
        $sql->bindParam(':nome', $nome, PDO::PARAM_STR);
        $sql->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $sql->bindParam(':contato', $contato, PDO::PARAM_STR);
        $sql->bindParam(':olhos', $olhos, PDO::PARAM_STR);
        $sql->bindParam(':dioptria_esquerda', $dioptria_esquerda, PDO::PARAM_STR);
        $sql->bindParam(':dioptria_direita', $dioptria_direita, PDO::PARAM_STR);
        $sql->bindParam(':valor', $valor, PDO::PARAM_STR);
        $sql->bindParam(':forma_pgto', $forma_pgto, PDO::PARAM_STR);
        $sql->bindParam(':id_convenio', $id_convenio, PDO::PARAM_INT);
        $sql->bindParam(':id_orientador', $id_orientador, PDO::PARAM_INT);
        $sql->bindParam(':id_turma', $id_turma, PDO::PARAM_INT);
        $sql->bindParam(':updated_at', $agora, PDO::PARAM_STR);
        $sql->bindParam(':id_agendamento', $id_agendamento, PDO::PARAM_INT);

        if ($sql->execute()) {
            $descricao = "Editou o agendamento: $nome ($id_agendamento)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Agendamento editado com sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar o agendamento!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        }
    }


    /**
     * Deleta um Paciente
     * @param integer $id_agendamento
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_agendamento, $usuario_logado){
        // Consulta para obter o nome do Paciente
        $consulta = $this->pdo->prepare('SELECT nome FROM catarata_agendamentos WHERE id_catarata_agendamento = :id_agendamento');
        $consulta->bindParam(':id_agendamento', $id_agendamento, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $nome = $resultado ? $resultado['nome'] : "Agendamento Não encontrado";

        // Atualiza o registro de Paciente com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE catarata_agendamentos SET deleted_at = :deleted_at WHERE id_catarata_agendamento = :id_agendamento');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora, PDO::PARAM_STR);
        $sql->bindParam(':id_agendamento', $id_agendamento, PDO::PARAM_INT);

        if ($sql->execute()) {
            $descricao = "Deletou o agendamento $nome ($id_agendamento)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Agendamento deletado com sucesso!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar o agendamento!');
                window.location.href = '" . URL . "/cirurgias/catarata/agendamento';
            </script>";
            exit;
        }
    }

}

?>
