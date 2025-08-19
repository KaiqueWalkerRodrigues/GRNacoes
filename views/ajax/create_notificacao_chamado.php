<?php
// created_notificacao_chamado.php
require_once '../../const.php';
session_start();

try {
    // Sanitização/normalização básica
    $tipo        = isset($_GET['tipo']) ? (int)$_GET['tipo'] : null;
    $id_chamado  = isset($_GET['id_chamado']) ? (int)$_GET['id_chamado'] : null;
    $id_setor    = isset($_GET['id_setor']) ? (int)$_GET['id_setor'] : null;
    $id_usuario  = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;
    $status      = isset($_GET['status']) ? trim($_GET['status']) : null;

    if($status){
        $status = Helper::statusChamado($status);
    }

    if ($tipo === null || $tipo < 0 || $tipo > 2) {
        http_response_code(400);
        echo "Parâmetro 'tipo' é obrigatório e deve ser 0, 1 ou 2.";
        exit;
    }

    if (empty($id_chamado)) {
        http_response_code(400);
        echo "Parâmetro 'id_chamado' é obrigatório.";
        exit;
    }

    $mensagem = "";
    // Regras por tipo
    switch ($tipo) {
        case 0:
            // Novo chamado criado para o setor
            if (empty($id_setor)) {
                http_response_code(400);
                echo "Para tipo 0, o parâmetro 'id_setor' é obrigatório.";
                exit;
            }
            $mensagem = "(#{$id_chamado}) Novo chamado aberto";
            // id_usuario pode ser nulo nesse caso
            break;

        case 1:
            // Nova mensagem no chamado
            if (empty($id_usuario) && empty($id_setor)) {
                http_response_code(400);
                echo "Para tipo 1, informe 'id_usuario' ou 'id_setor' (pode informar ambos).";
                exit;
            }
            $mensagem = "(#{$id_chamado}) Nova mensagem no chamado ";
            break;

        case 2:
            // Status alterado
            if (empty($id_usuario)) {
                http_response_code(400);
                echo "Para tipo 2, o parâmetro 'id_usuario' é obrigatório.";
                exit;
            }
            if ($status === null || $status === '') {
                http_response_code(400);
                echo "Para tipo 2, o parâmetro 'status' é obrigatório (vem via GET e é usado apenas na mensagem).";
                exit;
            }
            $mensagem = "(#{$id_chamado}) Alterado para {$status}";
            break;
    }

    // Definir usuário logado para log
    $usuario_logado = isset($_SESSION['id_usuario']) && (int)$_SESSION['id_usuario'] > 0
        ? (int)$_SESSION['id_usuario']
        : (!empty($id_usuario) ? (int)$id_usuario : 0);

    // Monta payload para o cadastrar()
    // IMPORTANTE: sua classe NotificacaoChamado::cadastrar precisa aceitar 'texto'
    // e inserir esse campo na tabela.
    $payload = [
        'id_chamado'     => $id_chamado,
        'id_usuario'     => !empty($id_usuario) ? (int)$id_usuario : null,
        'id_setor'       => !empty($id_setor) ? (int)$id_setor : null,
        'tipo'           => $tipo,            // 0, 1, 2
        'texto'          => $mensagem,        // mensagem padrão definida acima
        'usuario_logado' => $usuario_logado
    ];

    // Cria notificação
    $notificacao = new Notificacao_Chamado();
    $notificacao->cadastrar($payload);

    // Se sua função cadastrar já faz redirect/echo e exit, o código abaixo pode nem ser executado.
    // Caso não faça, podemos retornar um simples OK:
    echo "Notificação criada com sucesso.";

} catch (Throwable $e) {
    http_response_code(500);
    echo "Erro ao criar notificação: " . $e->getMessage();
    exit;
}
