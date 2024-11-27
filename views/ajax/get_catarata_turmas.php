<?php
require_once '../../const.php'; // Inclua o arquivo de conexão

try {
    // Define a conexão usando a classe Conexao
    $pdo = Conexao::conexao();

    // Verifica se o id_agenda foi passado via GET ou POST
    $id_agenda = $_GET['id_agenda'] ?? $_POST['id_agenda'] ?? null;

    if ($id_agenda) {
        // Consulta para buscar as turmas e verificar a disponibilidade
        $sql = $pdo->prepare(
            'SELECT 
                t.id_catarata_turma AS id_turma, 
                t.horario, 
                t.qntd AS limite,
                (t.qntd - COUNT(a.id_catarata_agendamento)) AS vagas_disponiveis
             FROM catarata_turmas t
             LEFT JOIN catarata_agendamentos a ON t.id_catarata_turma = a.id_turma
             WHERE t.id_agenda = :id_agenda AND t.deleted_at IS NULL
             GROUP BY t.id_catarata_turma, t.horario, t.qntd
             ORDER BY t.horario'
        );

        $sql->bindParam(':id_agenda', $id_agenda, PDO::PARAM_INT);
        $sql->execute();

        $turmas = $sql->fetchAll(PDO::FETCH_OBJ);

        foreach($turmas as $turma){
            $turma->horario = Helper::formatarHorario($turma->horario);
        }

        // Verifica se existem turmas
        if (!empty($turmas)) {
            // Retorna os dados no formato JSON
            echo json_encode($turmas);
        } else {
            // Caso não haja turmas
            echo json_encode(['error' => 'Nenhuma turma encontrada para o id_agenda fornecido']);
        }
    } else {
        // Retorna um erro se o id_agenda não for fornecido
        echo json_encode(['error' => 'id_agenda não fornecido']);
    }
} catch (Exception $e) {
    // Retorna um erro genérico em caso de exceção
    echo json_encode(['error' => 'Ocorreu um erro: ' . $e->getMessage()]);
}
