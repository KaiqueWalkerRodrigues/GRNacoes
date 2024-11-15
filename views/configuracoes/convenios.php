<?php 
    $Convenio = new Convenio();

    if (isset($_POST['btnCadastrar'])) {
        $Convenio->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Convenio->editar($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Convenio->deletar($_POST['id_convenio'],$_POST['usuario_logado']);
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
                                <span>Convênios</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Convênios
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarConvenio">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Convênio</th>
                                            <th>Razão Social</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Convenio->listar() as $Convenio){ ?>
                                        <tr>
                                            <td><?php echo $Convenio->convenio ?></td>
                                            <td><?php echo $Convenio->razao_social ?></td>
                                            <td>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarConvenio"
                                                    data-convenio="<?php echo $Convenio->convenio ?>" 
                                                    data-razao="<?php echo $Convenio->razao_social ?>" 
                                                    data-idconvenio="<?php echo $Convenio->id_convenio ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarconvenio"
                                                    data-convenio="<?php echo $Convenio->convenio ?>" 
                                                    data-idConvenio="<?php echo $Convenio->id_convenio ?>"
                                                    ><i class="fa-solid fa-trash"></i></button>
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

    <!-- Modal Cadastrar Convenio -->
    <div class="modal fade" id="modalCadastrarConvenio" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarConvenioLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarConvenioLabel">Cadastrar Novo Convenio</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-5 offset-1">
                                <label for="convenio" class="form-label">Convenio *</label>
                                <input type="text" class="form-control" name="convenio" required>
                            </div>
                            <div class="col-5">
                                <label for="razao_social" class="form-label">Razão Social *</label>
                                <input type="text" class="form-control" name="razao_social" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrar">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Convenio -->
    <div class="modal fade" id="modalEditarConvenio" tabindex="-1" role="dialog" aria-labelledby="modalEditarConvenioLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarConvenioLabel">Editar o Convenio: <span id="editar_Convenio_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_convenio" id="editar_id_convenio">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <label for="editar_convenio" class="form-label">Convenio *</label>
                                <input type="text" class="form-control" name="convenio" id="editar_convenio" required>
                            </div>
                            <div class="col-6">
                                <label for="razao_social" class="form-label">Razão Social *</label>
                                <input type="text" class="form-control" name="razao_social" id="editar_razao_social" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary" type="submit" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Convenio -->
    <div class="modal fade" id="modalDeletarConvenio" tabindex="-1" role="dialog" aria-labelledby="modalDeletarConvenioLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarConvenioLabel">Deletar o Convenio: <span class="deletar_Convenio_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_convenio" id="deletar_id_convenio">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar o Convenio <br> <b class="deletar_Convenio_nome"></b>? <br> Essa ação é Irreversível.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletar">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#conf').addClass('active')
            $('#configuracoes_convenios').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarConvenio').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_convenio = button.data('idconvenio')
                let convenio = button.data('convenio')
                let razao = button.data('razao')

                $('#editar_id_convenio').val(id_convenio)
                $('#editar_convenio').val(convenio)
                $('#editar_razao_social').val(razao)
            });

            $('#modalDeletarConvenio').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);

                let id_convenio = button.data('idconvenio')
                let convenio = button.data('convenio')

                $('#deletar_id_convenio').val(id_convenio)
                $('.deletar_convenio_nome').text(convenio)
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-convenios.js"></script>
</body>

</html>
