<?php 
    include_once('../const.php'); // Ajuste no caminho do arquivo

    $Usuario = new Usuario();
    $Cargo = new Cargo();

    if (isset($_POST['btnCadastrar'])) {
        $Cargo->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Cargo->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Cargo->desativar($_POST['id_cargo'],$_POST['usuario_logado']);
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Gerenciar Cargos</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('../sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('../topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cargos Ativos | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarCargo" class="collapse-item" data-usuario_logado="<?php echo $_SESSION['id_usuario'] ?>">Cadastrar Novo Cargo</button> | <button class="btn btn-secondary">Ver Cargos Desativados</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" aria id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Cargo</th>
                                            <th>Qntd Usuários</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Cargo->listar() as $cargo){ ?>
                                        <tr>
                                            <td><?php echo $cargo->cargo ?></td>
                                            <td><?php echo $Usuario->contarUsuariosPorCargo($cargo->id_cargo) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary collapse-item" data-toggle="modal" data-target="#modalEditarCargo" data-idcargo="<?php echo $cargo->id_cargo ?>" data-cargo="<?php echo $cargo->cargo ?>" data-usuario_logado="<?php echo $_SESSION['id_usuario']; ?>"><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-danger collapse-item" data-toggle="modal" data-target="#modalDesativarCargo" data-idcargo="<?php echo $cargo->id_cargo ?>" data-cargo="<?php echo $cargo->cargo ?>" data-usuario_logado="<?php echo $_SESSION['id_usuario']; ?>"><i class="fa-solid fa-power-off"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Modal Cadastrar Cargo -->
    <div class="modal fade" id="modalCadastrarCargo" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCargoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="?" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar Novo Cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8 offset-2">
                            <input type="hidden" id="cadastrar_usuario_logado" name="usuario_logado" value="">
                            <label for="cargo" class="form-label">Nome do Cargo *</label>
                            <input type="text" name="cargo" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Cargo -->
    <div class="modal fade" id="modalEditarCargo" tabindex="1" role="dialog" aria-labelledby="modalEditarCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Cargo: <span class="modalEditarCargoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="editar_id_cargo" name="id_cargo" value="">
                            <input type="hidden" id="editar_usuario_logado" name="usuario_logado" value="">
                            <div class="col-8 offset-2">
                                <label for="cargo" class="form-label">Nome do Cargo *</label>
                                <input type="text" name="cargo" id="editar_cargo" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Desativar Cargo-->
    <div class="modal fade" id="modalDesativarCargo" tabindex="-1" role="dialog" aria-labelledby="modalDesativarCargoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar Cargo: <span class="modalDesativarCargoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="desativar_id_cargo" name="id_cargo" value="">
                        <input type="hidden" id="desativar_usuario_logado" name="usuario_logado" value="">
                        <div class="row">
                            <p>Deseja desativar o cargo: <span class="modalDesativarCargoLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDesativar" class="btn btn-danger">Desativar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    

<!-- Bootstrap core JavaScript-->
<!-- Bootstrap core JavaScript-->
<script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesCargos.min.js"></script>
<script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function() {
            $('#config').addClass('active');
            $('#gerenciar_cargos').addClass('active');

            //Sistema Online
            {
                function manterOnline() {
                    $.ajax({
                        type: "get",
                        url: "manter_online.php",
                        data: { id_usuario: id_usuario },
                    });
                }
                setInterval(manterOnline, 1000);
            }
        });
        $('#modalCadastrarCargo').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let usuario_logado = button.data('usuario_logado')
            $('#cadastrar_usuario_logado').val(usuario_logado)
        })
        $('#modalEditarCargo').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idcargo = button.data('idcargo')
            let cargo = button.data('cargo')
            let usuario_logado = button.data('usuario_logado')
            $('.modalEditarCargoLabel').empty()
            $('.modalEditarCargoLabel').append(cargo)
            $('#editar_id_cargo').empty()
            $('#editar_id_cargo').val(idcargo)
            $('#editar_cargo').val(cargo)
            $('#editar_usuario_logado').val(usuario_logado)
        })
        $('#modalDesativarCargo').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idcargo = button.data('idcargo')
            let cargo = button.data('cargo')
            let usuario_logado = button.data('usuario_logado')
            $('.modalDesativarCargoLabel').empty()
            $('.modalDesativarCargoLabel').append(cargo)
            $('#desativar_id_cargo').empty()
            $('#desativar_id_cargo').val(idcargo)
            $('#desativar_usuario_logado').val(usuario_logado)
        })
    </script>


</body>

</html>
