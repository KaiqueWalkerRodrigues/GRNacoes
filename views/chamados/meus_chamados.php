<?php
$Chamado = new Chamado();
$Setor = new Setor();
$Usuario = new Usuario();

$usuario = $Usuario->mostrar($_SESSION['id_usuario']);
$setor = $Setor->mostrar($_SESSION['id_setor']);

if (isset($_POST['AbrirChamado'])) {
    $Chamado->cadastrar($_POST);
}
if (isset($_POST['btnExcluir'])) {
    $Chamado->deletar($_POST['id_chamado'], $_POST['id_usuario']);
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
                                <span>Meus Chamados</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Meus Chamados | <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalAbrirChamado">Abrir Chamado</button>
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
                    <div>
                    <div>
                        <div><b class="text-dark">Em Análise -</b> Chamado está sendo análisado (Se é possivel concluir o chamado)</div>
                        <div><b class="text-secondary">Em Andamento -</b> Chamado foi iniciado (Foi iniciado as medidas necessarias para concluir)</div>
                        <div><b class="text-success">Concluído -</b> Chamado foi concluído </div>
                        <div><b class="text-danger">Cancelado -</b> Chamado foi cancelado pelo Remetente </div>
                        <div><b class="text-dark">Recusado -</b> Chamado foi recusado pelo Destinatário (Não é possivel/Não é necessário/Não deve concluir)</div>
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
                                    <option value="1">Tecnologia da Informação</option>
                                    <?php foreach ($Setor->listar($_SESSION['id_usuario']) as $setor) {
                                        echo "<option value='$setor->id_setor'>$setor->setor</option>";
                                    } ?>
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
                            <div class="col-12 mt-1 text-center">
                                <span>(Para adicionar prints ou anexos use o chat do chamado)</span>
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

    <!-- Modal Excluir Chamado -->
    <div class="modal fade" id="modalExcluir" tabindex="1" role="dialog" aria-labelledby="modalExcluirLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir o chamado: <span class="excluir_titulo"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_chamado" id="excluir_id_chamado">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Deseja excluir o chamado: <span class="excluir_titulo"></span> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnExcluir" class="btn btn-danger">Excluir</button>
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
            $('#chamados_meus_chamados').addClass('active')

            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });

            $('#modalExcluir').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')

                $('#excluir_id_chamado').val(id_chamado)
                $('.excluir_titulo').text(titulo)
            })

            $('#modalVisualizarChamado').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_chamado = button.data('id_chamado');
                let titulo = button.data('titulo');
                let sla = button.data('sla');
                let status = button.data('status');
                let usuario = button.data('usuario');
                let setor = button.data('setor');
                let urgencia = button.data('urgencia');
                let descricao = button.data('descricao');
                let created_at = button.data('created_at');
                let deleted_at = button.data('deleted_at');
                let finished_at = button.data('finished_at');
                let started_at = button.data('started_at');

                $('#visualizar_id_chamado').val(id_chamado);
                $('#visualizar_titulo').val(titulo);
                $('#titulo_modal').text(titulo);
                if (sla > 0) {
                    $('#visualizar_sla').val(sla + " hora(s)");
                } else {
                    $('#visualizar_sla').val("");
                }
                $('#visualizar_status').val(status);
                $('#visualizar_usuario').val(usuario);
                $('#visualizar_id_setor').val(setor);
                $('#visualizar_urgencia').val(urgencia);
                $('#visualizar_descricao').val(descricao);
                $('#visualizar_created_at').val(created_at);
                $('#visualizar_deleted_at').val(deleted_at);
                $('#visualizar_started_at').val(started_at);
                $('#visualizar_finished_at').val(finished_at);
            });

            // endpoint AJAX (ajuste caso use outro caminho base)
            const ENDPOINT_MEUS_CHAMADOS = "<?php echo URL ?>/views/ajax/get_chamados.php";
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
                    data-sla="${chamado.sla || ''}"
                    data-status="${chamado.status_texto}"
                    data-usuario="${chamado.usuario_nome}"
                    data-setor="${chamado.setor_nome}"
                    data-urgencia="${chamado.urgencia_texto}"
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
                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalExcluir"
                        data-id_chamado="${chamado.id_chamado}"
                        data-titulo="${chamado.titulo}">
                        <i class="fa-solid fa-trash"></i>
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

            function carregarChamados() {
                $.ajax({
                    url: ENDPOINT_MEUS_CHAMADOS,
                    method: "GET",
                    dataType: "json",
                    data: {
                        id_usuario: <?php echo $_SESSION["id_usuario"]; ?>
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

            // Inicialização
            carregarChamados();

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