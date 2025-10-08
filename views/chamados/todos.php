<?php
$Chamado = new Chamado();
$Usuario = new Usuario();
$Cargo = new Cargo();
$Setor = new Setor();

$usuario = $Usuario->mostrar($_SESSION['id_usuario']);
$setor = $Setor->mostrar($_SESSION['id_setor']);

if (isset($_POST['btnEncaminhar'])) {
    $Chamado->encaminhar($_POST['id_chamado'], $_POST['id_setor_novo'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
if (isset($_POST['btnConcluir'])) {
    $Chamado->concluir($_POST['id_chamado'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
if (isset($_POST['btnRecusar'])) {
    $Chamado->recusar($_POST['id_chamado'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
if (isset($_POST['btnReabrir'])) {
    $Chamado->reabrir($_POST['id_chamado'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
if (isset($_POST['btnIniciar'])) {
    $Chamado->iniciar($_POST['id_chamado'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
if (isset($_POST['btnExcluir'])) {
    $Chamado->deletar($_POST['id_chamado'], $_POST['id_usuario']);
    header('location: ' . URL . '/chamados/todos');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $pageTitle ?></title>
    <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="nav-fixed">
    <!-- Tela de Carregamento -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Chamados para <?php echo $Setor->mostrar($_SESSION['id_setor'])->setor ?></span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="filtroStatus" class="form-label text-light">Filtrar por Status:</label>
                                    <select id="filtroStatus" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Pendentes">Pendentes</option>
                                        <option value="Em Análise">Em Análise</option>
                                        <option value="Em Andamento">Em Andamento</option>
                                        <option value="Concluído">Concluído</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Recusado">Recusado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n15">
                    <div class="card mb-4">
                        <div class="card-header">Chamados
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">created_at</th>
                                            <th>Nº</th>
                                            <th>Urgência</th>
                                            <th>Título</th>
                                            <th>Usuário</th>
                                            <th>Status</th>
                                            <th>Aberto Em</th>
                                            <th>Setor Destinado</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyChamados">
                                        <!-- Chamados serão carregados via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php') ?>
        </div>
    </div>

    <!-- Modal Abrir Chamado -->
    <form action="?" method="post">
        <div class="modal fade" id="modalAbrirChamado" tabindex="-1" role="dialog" aria-labelledby="abrirchamadoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Abrir Chamado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-6 mb-2">
                                <label for="cadastrar_titulo" class="form-label">Título do Chamado *</label>
                                <input type="text" name="titulo" id="cadastrar_titulo" class="form-control" required>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="cadastrar_id_setor" class="form-label">Destinatário *</label>
                                <select name="id_setor" id="cadastrar_id_setor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($Setor->listar() as $setor) {
                                        echo "<option value='$setor->id_setor'>$setor->setor</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_urgencia" class="form-label">Urgência *</label>
                                <select name="urgencia" id="cadastrar_urgencia" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <option value="4">Urgente</option>
                                </select>
                            </div>
                            <!-- <div class="col-4">
                                <label for="cadastrar_print" class="form-label">Prints</label>
                                <input type="file" name="cadastrar-print" multiple accept="image/" id="cadastrar-print" class="form-control">
                            </div> -->
                            <div class="col-12 mt-1">
                                <label for="cadastrar-descricao" class="form-label">Descreva o Problema *</label>
                                <textarea name="descricao" id="cadastrar-descricao" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="AbrirChamado">Abrir Chamado</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal encaminhar -->
    <div class="modal fade" id="modalEncaminhar" tabindex="1" role="dialog" aria-labelledby="modalencaminharLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Encaminhar Chamado para o Setor...</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <input type="hidden" id="encaminhar_id_chamado" name="id_chamado">
                            <label for="encaminhar" class="form-label">Encaminhar para:</label>
                            <select name="id_setor_novo" id="encaminhar" class="form-control" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($Setor->listar() as $setor) { ?>
                                    <option value="<?php echo $setor->id_setor ?>"><?php echo $setor->setor ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info" name="btnEncaminhar">Encaminhar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Visualizar Chamado -->
    <div class="modal fade" id="modalVisualizarChamado" tabindex="-1" role="dialog" aria-labelledby="visualizarchamadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Visualizar Chamado: <span id="titulo_modal"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id_chamado" id="visualizar_id_chamado">
                        <div class="col-5 mb-2">
                            <label for="visualizar_titulo" class="form-label">Título do Chamado *</label>
                            <input type="text" name="titulo" id="visualizar_titulo" class="form-control" disabled>
                        </div>
                        <div class="col-2 mb-2">
                            <label for="visualizar_status" class="form-label">Status *</label>
                            <input type="text" name="status" id="visualizar_status" class="form-control" disabled>
                        </div>
                        <div class="col-3 mb-2">
                            <label for="visualizar_id_setor" class="form-label">Setor *</label>
                            <input type="text" name="setor" id="visualizar_id_setor" class="form-control" disabled>
                        </div>
                        <div class="col-2">
                            <label for="visualizar_urgencia" class="form-label">Urgência *</label>
                            <input type="text" name="urgencia" id="visualizar_urgencia" class="form-control" disabled>
                        </div>
                        <div class="col-7 offset-1 mb-2">
                            <label for="visualizar_usuario" class="form-label">Usuário *</label>
                            <input type="text" name="usuario" id="visualizar_usuario" class="form-control" disabled>
                        </div>
                        <div class="col-3">
                            <label for="visualizar_sla" class="form-label">SLA</label>
                            <input type="text" name="sla" id="visualizar_sla" class="form-control" disabled>
                        </div>
                        <div class="col-12 mt-1">
                            <label for="visualizar_descricao" class="form-label">Descrição *</label>
                            <textarea name="descricao" id="visualizar_descricao" cols="30" rows="10" class="form-control" disabled></textarea>
                        </div>
                        <div class="col-3 mt-2">
                            <label for="visualizar_created_at" class="form-label">Criado em</label>
                            <input type="text" name="created_at" id="visualizar_created_at" class="form-control" disabled>
                        </div>
                        <div class="col-3 mt-2">
                            <label for="visualizar_deleted_at" class="form-label">Cancelado em</label>
                            <input type="text" name="deleted_at" id="visualizar_deleted_at" class="form-control" disabled>
                        </div>
                        <div class="col-3 mt-2">
                            <label for="visualizar_started_at" class="form-label">Iniciado em</label>
                            <input type="text" name="started_at" id="visualizar_started_at" class="form-control" disabled>
                        </div>
                        <div class="col-3 mt-2">
                            <label for="visualizar_finished_at" class="form-label">Finalizado em</label>
                            <input type="text" name="finished_at" id="visualizar_finished_at" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Recusar -->
    <div class="modal fade" id="modalRecusar" tabindex="1" role="dialog" aria-labelledby="modalRecusarLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Recusar o chamado: <span class="recusar_titulo"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="recusar_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja recusar o chamado: <span class="recusar_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark" name="btnRecusar">Recusar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Reabrir -->
    <div class="modal fade" id="modalReabrir" tabindex="1" role="dialog" aria-labelledby="modalReabrirLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reabrir o chamado: <span class="reabrir_titulo"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="reabrir_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja reabrir o chamado: <span class="reabrir_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning" name="btnReabrir">Reabrir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Iniciar -->
    <div class="modal fade" id="modalIniciar" tabindex="1" role="dialog" aria-labelledby="modalIniciarLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar o chamado: <span class="iniciar_titulo"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="iniciar_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja iniciar o chamado: <span class="iniciar_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnIniciar">Iniciar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Concluir -->
    <div class="modal fade" id="modalConcluir" tabindex="1" role="dialog" aria-labelledby="modalConcluirLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Concluir o chamado: <span class="concluir_titulo"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="concluir_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja concluir o chamado: <span class="concluir_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnConcluir">Concluir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Excluir -->
    <div class="modal fade" id="modalExcluir" tabindex="1" role="dialog" aria-labelledby="modalExcluirLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir o chamado: "<span class="excluir_titulo"></span>" ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="excluir_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja Excluir o chamado: <span class="excluir_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnExcluir">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function() {
            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });
        });

        $(document).ready(function() {
            $('#cham').addClass('active')
            $('#chamados_todos').addClass('active')

            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });

            // endpoint AJAX
            const ENDPOINT_TODOS_CHAMADOS = "<?php echo URL ?>/views/ajax/get_chamados.php";
            const ENDPOINT_COUNT_NAO_LIDAS = "<?php echo URL ?>/views/ajax/get_count_chamados_mensagens_nao_lidas.php";

            function atualizarBadgesChatVisiveis() {
                $('#dataTable tbody tr:visible').each(function() {
                    const $link = $(this).find('a.chat-link');
                    if ($link.length === 0) return;

                    const idChamado = $link.data('id_chamado');
                    if (!idChamado) return;

                    $.getJSON(ENDPOINT_COUNT_NAO_LIDAS, {
                            id_chamado: idChamado
                        })
                        .done(function(res) {
                            const $badge = $link.find('.chat-badge');
                            if (res && res.ok && Number(res.nao_lidas) > 0) {
                                const qtd = Number(res.nao_lidas);
                                $badge.text(qtd > 99 ? '99+' : qtd).show();
                            } else {
                                $badge.text('0').hide();
                            }
                        })
                        .fail(function() {});
                });
            }

            function renderChamados(chamados) {
                let table = $('#dataTable').DataTable();
                table.clear();

                chamados.forEach(function(chamado) {
                    let btns = `
                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVisualizarChamado"
                    data-id_chamado="${chamado.id_chamado}"
                    data-titulo="${chamado.titulo}"
                    data-status="${chamado.status_texto_input}"
                    data-usuario="${chamado.usuario_nome}"
                    data-sla="${chamado.sla || ''}"
                    data-setor="${chamado.setor_nome}"
                    data-urgencia="${chamado.urgencia_texto_input}"
                    data-descricao="${(chamado.descricao || '').replace(/"/g, '&quot;')}"
                    data-created_at="${chamado.created_at_formatado || ''}"
                    data-deleted_at="${chamado.deleted_at_formatado || ''}"
                    data-started_at="${chamado.started_at_formatado || ''}"
                    data-finished_at="${chamado.finished_at_formatado || ''}">
                    <i class="fa-solid fa-newspaper"></i>
                </button>
                <a href="<?php echo URL ?>/chamados/chat_chamado?id=${chamado.id_chamado}"
                    class="btn btn-datatable btn-icon btn-transparent-dark position-relative chat-link"
                    data-id_chamado="${chamado.id_chamado}">
                    <i class="fa-solid fa-comment"></i>
                    <span class="chat-badge badge badge-pill"
                        style="position:absolute; top:0; right:0; display:none; background:#25D366; color:#fff; border:2px solid #fff;">
                        0
                    </span>
                </a>
            `;
                    if (chamado.status == 1) {
                        btns += `
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalIniciar"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-clock"></i>
                    </button>
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEncaminhar"
                        data-id_chamado="${chamado.id_chamado}">
                        <i class="fa-solid fa-share"></i>
                    </button>
                `;
                    }
                    if (chamado.status <= 2) {
                        btns += `
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalConcluir"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalRecusar"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-times"></i>
                    </button>
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalExcluir"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                    } else {
                        btns += `
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalReabrir"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                `;
                    }

                    table.row.add([
                        chamado.created_at,
                        chamado.id_chamado,
                        chamado.urgencia_texto,
                        chamado.titulo,
                        chamado.usuario_nome,
                        chamado.status_texto,
                        chamado.created_at_formatado,
                        chamado.setor_nome,
                        btns
                    ]);
                });

                table.draw();
                setTimeout(atualizarBadgesChatVisiveis, 150);
            }

            function carregarChamados(status) {
                $.ajax({
                    url: ENDPOINT_TODOS_CHAMADOS,
                    method: "GET",
                    dataType: "json",
                    data: {
                        status: status // filtro do select
                    },
                    success: function(response) {
                        if (response.ok) {
                            renderChamados(response.chamados);
                        } else {
                            $('#tbodyChamados').html('<tr><td colspan="9">Nenhum chamado encontrado.</td></tr>');
                        }
                    },
                    error: function() {
                        $('#tbodyChamados').html('<tr><td colspan="9">Erro ao carregar chamados.</td></tr>');
                    }
                });
            }

            $('#filtroStatus').change(function() {
                var filtroStatus = $(this).val();
                carregarChamados(filtroStatus);
            });

            // Inicialização
            let status = 'Pendentes';
            $('#filtroStatus').val(status).change();

            // Atualiza badges após busca/paginação
            $('#dataTable').on('draw.dt search.dt page.dt', function() {
                setTimeout(atualizarBadgesChatVisiveis, 150);
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-chamados.js"></script>
</body>

</html>