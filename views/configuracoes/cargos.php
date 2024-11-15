<?php 
    $Usuario = new Usuario();
    $Cargo = new Cargo();

    if (isset($_POST['btnCadastrar'])) {
        $Cargo->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Cargo->editar($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Cargo->deletar($_POST['id_cargo'],$_POST['usuario_logado']);
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
                                <span>Cargos</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Cargos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarCargo">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Cargo</th>
                                            <th>Quantidade Usuários</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Cargo->listar() as $cargo){ ?>
                                        <tr>
                                            <td><?php echo $cargo->cargo ?></td>
                                            <td class="text-center"><a data-toggle="modal" style="text-decoration: underline; color: blue;" data-target="#modalUsuariosDoCargo"><?php echo $Usuario->contarUsuariosPorCargo($cargo->id_cargo);?></a></td>
                                            <td>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarCargo"
                                                    data-cargo="<?php echo $cargo->cargo ?>" 
                                                    data-idcargo="<?php echo $cargo->id_cargo ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarCargo"
                                                    data-cargo="<?php echo $cargo->cargo ?>" 
                                                    data-idcargo="<?php echo $cargo->id_cargo ?>"
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

    <!-- Modal Usuarios do Cargo -->
    <div class="modal fade" id="modalUsuariosDoCargo" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosDoCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUsuariosDoCargoLabel">Usuários do Cargo:</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Cadastrar Cargo -->
    <div class="modal fade" id="modalCadastrarCargo" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarCargoLabel">Cadastrar Novo Cargo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="cargo" class="form-label">Cargo *</label>
                                <input type="text" class="form-control" name="cargo" required>
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

    <!-- Modal Editar Cargo -->
    <div class="modal fade" id="modalEditarCargo" tabindex="-1" role="dialog" aria-labelledby="modalEditarCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCargoLabel">Editar o Cargo: <span id="editar_cargo_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_cargo" id="editar_id_cargo">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="editar_cargo" class="form-label">Cargo *</label>
                                <input type="text" class="form-control" name="cargo" id="editar_cargo" required>
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

    <!-- Modal Deletar Cargo -->
    <div class="modal fade" id="modalDeletarCargo" tabindex="-1" role="dialog" aria-labelledby="modalDeletarCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarCargoLabel">Deletar o Cargo: <span class="deletar_cargo_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_cargo" id="deletar_id_cargo">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar o Cargo <br> <b class="deletar_Cargo_nome"></b>? <br> Essa ação é Irreversível.</p>
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
            $('#configuracoes_cargos').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarCargo').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let cargo = button.data('cargo');
                let id_cargo = button.data('idcargo');

                $('#editar_cargo_nome').text(cargo);
                $('#editar_id_cargo').val(id_cargo);
                $('#editar_cargo').val(cargo);
            });

            $('#modalDeletarCargo').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let cargo = button.data('cargo');
                let id_cargo = button.data('idcargo');

                $('.deletar_cargo_nome').text(cargo);
                $('#deletar_id_cargo').val(id_cargo);
                $('#deletar_cargo').val(cargo);
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-cargos.js"></script>
</body>

</html>
