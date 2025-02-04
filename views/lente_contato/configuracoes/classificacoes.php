<?php 
    $Lc_classificacao = new Lente_contato_classificacao();

    if (isset($_POST['btnCadastrar'])) {
       $Lc_classificacao->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_classificacao->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_classificacao->deletar($_POST['id_lente_contato_classificacao'], $_SESSION['id_usuario']);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Página de Classificações de Lente de Contato" />
    <meta name="author" content="Seu Nome ou Empresa" />
    <title><?php echo htmlspecialchars($pageTitle) ?></title>
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
        <?php include_once('resources/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-tags"></i></div>
                                <span>Classificações de Lente de Contato</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Classificações
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarClassificacao">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Classificação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_classificacao->listar() as $classificacao){ ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($classificacao->id_lente_contato_classificacao) ?></td>
                                                <td><?php echo htmlspecialchars($classificacao->classificacao) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarClassificacao"
                                                        data-classificacao="<?php echo htmlspecialchars($classificacao->classificacao) ?>"
                                                        data-id_lente_contato_classificacao="<?php echo htmlspecialchars($classificacao->id_lente_contato_classificacao) ?>"
                                                        >
                                                        <i class="fa-solid fa-gear"></i>
                                                    </button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarClassificacao"
                                                        data-classificacao="<?php echo htmlspecialchars($classificacao->classificacao) ?>"
                                                        data-id_lente_contato_classificacao="<?php echo htmlspecialchars($classificacao->id_lente_contato_classificacao) ?>"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

    <!-- Modal Cadastrar Classificação -->
    <div class="modal fade" id="modalCadastrarClassificacao" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarClassificacaoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Classificação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>">
                                <div class="form-group">
                                    <label for="cadastrar_classificacao">Nome da Classificação *</label>
                                    <input type="text" name="classificacao" id="cadastrar_classificacao" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal Editar Classificação -->
    <div class="modal fade" id="modalEditarClassificacao" tabindex="-1" role="dialog" aria-labelledby="modalEditarClassificacaoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Classificação: <span id="editar_classificacao_nome"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>">
                                <input type="hidden" name="id_lente_contato_classificacao" id="editar_id_lente_contato_classificacao">
                                <div class="form-group">
                                    <label for="editar_classificacao">Nome da Classificação *</label>
                                    <input type="text" name="classificacao" id="editar_classificacao" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal Deletar Classificação -->
    <div class="modal fade" id="modalDeletarClassificacao" tabindex="-1" role="dialog" aria-labelledby="modalDeletarClassificacaoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Classificação: <span class="modalDeletarClassificacaoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_lente_contato_classificacao" id="Deletar_id_lente_contato_classificacao">
                        <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>" required>
                        <div class="container row">
                            <p>Deseja deletar a classificação: <strong><span class="modalDeletarClassificacaoLabel"></span></strong>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDeletar" class="btn btn-danger">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            // Ativar os itens do menu
            $('#lent').addClass('active');
            $('#lente_contato_configuracoes').addClass('active');
            $('#lente_contato_configuracoes_classificacoes').addClass('active');

            // Evento para abrir a modal de edição com os dados corretos
            $('#modalEditarClassificacao').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var classificacao = button.data('classificacao');
                var id_lente_contato_classificacao = button.data('id_lente_contato_classificacao');
                var modal = $(this);
                modal.find('#editar_classificacao_nome').text(classificacao);
                modal.find('#editar_id_lente_contato_classificacao').val(id_lente_contato_classificacao);
                modal.find('#editar_classificacao').val(classificacao);
            });

            // Evento para abrir a modal de deleção com os dados corretos
            $('#modalDeletarClassificacao').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var classificacao = button.data('classificacao');
                var id_lente_contato_classificacao = button.data('id_lente_contato_classificacao');
                var modal = $(this);
                modal.find('.modalDeletarClassificacaoLabel').text(classificacao);
                modal.find('#Deletar_id_lente_contato_classificacao').val(id_lente_contato_classificacao);
            });

            // Remover o preloader caso ainda esteja visível
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_classificacoes.js"></script>
</body>

</html>
