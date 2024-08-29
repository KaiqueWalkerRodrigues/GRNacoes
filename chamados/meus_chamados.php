<?php 
    include_once('../const.php');

    $Chamado = new Chamados();
    $Setor = new Setor();

    if(isset($_POST['AbrirChamado'])){
        $Chamado->cadastrar($_POST);
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

    <title>GRNacoes - Meus Chamados</title>

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

                    <br><br><br><br>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Meus Chamados</h1>
                    </div>

                    <br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Meus Chamados | <button class="btn btn-primary" data-toggle="modal" data-target="#modalAbrirChamado">Abrir Chamado</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Urgência</th>
                                            <th>Título</th>
                                            <th>Status</th>
                                            <th>Setor</th>
                                            <th>Aberto Há</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Chamado->listar() as $chamado){
                                            $created_at = new DateTime($chamado->created_at);
                                            $now = new DateTime();
                                            $interval = $created_at->diff($now);
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo Helper::Urgencia($chamado->urgencia) ?></td>
                                            <td><?php echo $chamado->titulo ?></td>
                                            <td><?php echo Helper::statusChamado($chamado->status) ?></td>
                                            <td><?php echo $Setor->mostrar($chamado->id_setor)->setor ?></td>
                                            <td>
                                            <?php
                                                if ($interval->y > 0) {
                                                    echo $interval->y . ' ano' . ($interval->y > 1 ? 's' : '');
                                                } elseif ($interval->m > 0) {
                                                    echo $interval->m . ' mês' . ($interval->m > 1 ? 'es' : '');
                                                } elseif ($interval->d > 0) {
                                                    echo $interval->d . ' dia' . ($interval->d > 1 ? 's' : '');
                                                } elseif ($interval->h > 0) {
                                                    echo $interval->h . ' hora' . ($interval->h > 1 ? 's' : '');
                                                } else {
                                                    echo $interval->i . ' minuto' . ($interval->i > 1 ? 's' : '');
                                                }
                                            ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-secondary"><i class="fa-solid fa-newspaper"></i></button>
                                                <button class="btn btn-primary"><i class="fa-solid fa-comment"></i></button>
                                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
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
                                            <?php foreach($Setor->listar() as $setor){ 
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="AbrirChamado">Abrir Chamado</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal encaminhar -->
            <div class="modal fade" id="modalEncaminhar" tabindex="1" role="dialog" aria-labelledby="modalencaminharLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Encaminhar Chamado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <label for="encaminhar" class="form-label">Encaminhar para:</label>
                                <select name="encaminhar" id="encaminhar" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="ti">TI</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-info">Encaminhar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Concluir -->
            <div class="modal fade" id="modalConcluir" tabindex="1" role="dialog" aria-labelledby="modalConcluirLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Concluir o chamado: ?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Deseja concluir o chamado: ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success">Concluir</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#cham').addClass('active');
            $('#meus_chamados').addClass('active');
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesChamados.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>