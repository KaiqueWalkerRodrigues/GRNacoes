<?php

class Captacao {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
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
     * cadastra um novo captado
     * @param Array $dados    
     * @return int
     */
    public function cadastrar(Array $dados)
    {
        $id_captador = $dados['id_captador'];
        $id_medico = $dados['id_medico'];
        $nome_paciente = ucwords(strtolower(trim($dados['nome_paciente'])));
        $captado = $dados['captado'];
        $observacao = trim($dados['observacao']);
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO captados 
                                    (id_captador, id_medico, nome_paciente, captado, observacao, created_at, updated_at)
                                    VALUES
                                    (:id_captador, :id_medico, :nome_paciente, :captado, :observacao, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':id_captador', $id_captador);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':nome_paciente', $nome_paciente);
        $sql->bindParam(':captado', $captado);
        $sql->bindParam(':observacao', $observacao);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
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
            id_captador = :id_captador,
            id_medico = :id_medico,
            nome_paciente = :nome_paciente,
            captado = :captado,
            observacao = :observacao,
            updated_at = :updated_at 
        WHERE id_captado = :id_captado
        ");

        $agora = date("Y-m-d H:i:s");

        $id_captado = $dados['id_captado'];
        $id_captador = $dados['id_captador'];
        $id_medico = $dados['id_medico'];
        $nome_paciente = ucwords(strtolower(trim($dados['nome_paciente'])));
        $captado = $dados['captado'];
        $observacao = trim($dados['observacao']);
        $updated_at = $agora;

        $sql->bindParam(':id_captado', $id_captado);
        $sql->bindParam(':id_captador', $id_captador);
        $sql->bindParam(':id_medico', $id_medico);
        $sql->bindParam(':nome_paciente', $nome_paciente);
        $sql->bindParam(':captado', $captado);
        $sql->bindParam(':observacao', $observacao);
        $sql->bindParam(':updated_at', $updated_at);

        return $sql->execute();
    }

    /**
     * Desativa um captado
     *
     * @param int $id_captado
     * @return bool
     */
    public function desativar(int $id_captado)
    {
        $sql = $this->pdo->prepare('UPDATE captados SET deleted_at = :deleted_at WHERE id_captado = :id_captado');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_captado', $id_captado);

        return $sql->execute();
    }

    public function listarHoje() {
        $sql = $this->pdo->prepare("SELECT captados.*, medicos.nome AS nome_medico 
                                    FROM captados 
                                    JOIN medicos ON captados.id_medico = medicos.id_medico
                                    WHERE DATE(captados.created_at) = CURDATE() 
                                    AND captados.deleted_at IS NULL
                                    ORDER BY captados.created_at DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }    

    public function contarPessoas() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    public function contarCaptacoes() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND captado = 1 OR captado = 3 AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    public function contarNaoCaptacoes() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND captado = 0 AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    public function contarLentes() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND captado = 2 OR captado = 3 AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    public function contarGarantias() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND captado = 4 AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    public function contarCaptaveis() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM captados WHERE DATE(captados.created_at) = CURDATE() AND captado = 0 OR captado = 1 OR captado = 2 AND deleted_at IS NULL");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

}
include_once('Conexao.php');

$Captacao = new Captacao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'id_captador' => $_POST['id_usuario'], // Ajuste conforme seu sistema de sessão
        'id_medico' => $_POST['id_medico'],
        'nome_paciente' => $_POST['nome_paciente'],
        'captado' => $_POST['captado'],
        'observacao' => $_POST['observacao']
    ];
    $Captacao->cadastrar($dados);
    header('Location: /GRNacoes/captacao/'); // Redireciona para evitar re-submissão de formulário
}    

?>
