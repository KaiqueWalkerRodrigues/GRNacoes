<?php 
    include_once('../const.php'); 

    $Setor = new Setor();
    $Usuario = new Usuario();

    if (isset($_POST['btnCadastrar'])) {
        $Setor->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Setor->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Setor->desativar($_POST['id_setor'],$_POST['usuario_logado']);
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

    <title>GRNacoes - Gerenciar Setores</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Setores Ativos | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarSetor" class="collapse-item">Cadastrar Novo Setor</button> | <button class="btn btn-secondary">Ver Setores Desativados</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Setor</th>
                                            <th>Qntd Usuários</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Setor->listar() as $setor){ ?>
                                        <tr>
                                            <td><?php echo $setor->setor ?></td>
                                            <td><?php echo $Usuario->contarUsuariosPorSetor($setor->id_setor); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarSetor" class="collapse-item" data-setor="<?php echo $setor->setor ?>" data-idsetor="<?php echo $setor->id_setor ?>"><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarSetor" class="collapse-item" data-setor="<?php echo $setor->setor ?>" data-idsetor="<?php echo $setor->id_setor ?>"><i class="fa-solid fa-power-off"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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


    <!-- Modal Cadastrar Setor -->
    <div class="modal fade" id="modalCadastrarSetor" tabindex="1" role="dialog" aria-labelledby="modalCadastrarSetorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Setor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8 offset-2">
                                <input type="hidden" id="cadastrar_usuario_logado" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                                <label for="setor" class="form-label">Nome do Setor *</label>
                                <input type="text" name="setor" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button name="btnCadastrar" type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Setor -->
    <div class="modal fade" id="modalEditarSetor" tabindex="1" role="dialog" aria-labelledby="modalEditarSetorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Setor: <span class="modalEditarSetorLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8 offset-2">
                                <input type="hidden" id="editar_id_setor" name="id_setor">
                                <input type="hidden" id="cadastrar_usuario_logado" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                                <label for="setor" class="form-label">Nome do Setor *</label>
                                <input type="text" id="editar_setor" name="setor" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Desativar Usuário-->
    <div class="modal fade" id="modalDesativarSetor" tabindex="-1" role="dialog" aria-labelledby="modalDesativarSetorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar o setor: <span class="modalDesativarSetorLabel"></span> ?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="desativar_id_setor" name="id_setor">
                        <input type="hidden" id="desativar_usuario_logado" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                        <div class="row">
                            <p>Deseja desativar o setor: <span class="modalDesativarSetorLabel"></span>?</p>
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
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesSetores.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function() {
            $('#config').addClass('active');
            $('#gerenciar_setores').addClass('active');
        });
        $('#modalEditarSetor').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idsetor = button.data('idsetor')
            let setor = button.data('setor')
            $('.modalEditarSetorLabel').empty()
            $('.modalEditarSetorLabel').append(setor)
            $('#editar_id_setor').empty()
            $('#editar_id_setor').val(idsetor)
            $('#editar_setor').val(setor)
        })
        $('#modalDesativarSetor').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idsetor = button.data('idsetor')
            let setor = button.data('setor')
            $('.modalDesativarSetorLabel').empty()
            $('.modalDesativarSetorLabel').append(setor)
            $('#desativar_id_setor').empty()
            $('#desativar_id_setor').val(idsetor)
        })
    </script>

</body>

</html>