<?php 
    include_once('const.php');

    $Usuario = new Usuario();

    if(isset($_POST['btnAvatar'])){
        $Usuario->editarAvatar($_POST['id_usuario'],$_POST['id_avatar']);
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

    <title>GRNacoes - Perfil</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include_once('topbar.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <div class="row">
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Nome Completo</label>
                            <input type="text" disabled class="form-control" value="Kaique Rodrigues de Souza">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">N° Folha</label>
                            <input type="text" disabled class="form-control" value="0200000000000000000017">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Email cadastrado</label>
                            <input type="text" disabled class="form-control" value="ykaiqz17@gmail.com">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Cargo</label>
                            <input type="text" disabled class="form-control" value="Auxiliar de Analista de Suporte Junior">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Data de Admissão</label>
                            <input type="text" disabled class="form-control" value="01/11/2024">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Celular</label>
                            <input type="text" disabled class="form-control" value="(11) 9 8346-9084">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Setor</label>
                            <input type="text" disabled class="form-control" value="Tecnologia da Informação">
                        </div>
                    </div>

                    <hr>

                        <div class="col-4 mb-2">
                            <label for="" class="form-label">Usuário</label>
                            <input type="text" disabled class="form-control" value="Kaique.Souza">
                            <br>
                            <button class="btn btn-primary">Alterar Senha</button>
                            <button class="btn btn-dark" data-toggle="modal" data-target="#modalAvatar" class="collapse-item">Alterar Foto</button>
                        </div>

                <br><br>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="modalAvatarLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <h5 class="modal-title">Escolha um avatar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio0" value="0">
                                    <label class="form-check-label" for="inlineRadio0">
                                        <img src="img/avatar/0.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio1" value="1">
                                    <label class="form-check-label" for="inlineRadio1">
                                        <img src="img/avatar/1.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio2" value="2">
                                    <label class="form-check-label" for="inlineRadio2">
                                        <img src="img/avatar/2.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio3" value="3">
                                    <label class="form-check-label" for="inlineRadio3">
                                        <img src="img/avatar/3.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio4" value="4">
                                    <label class="form-check-label" for="inlineRadio4">
                                        <img src="img/avatar/4.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio5" value="5">
                                    <label class="form-check-label" for="inlineRadio5">
                                        <img src="img/avatar/5.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio6" value="6">
                                    <label class="form-check-label" for="inlineRadio6">
                                        <img src="img/avatar/6.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio7" value="7">
                                    <label class="form-check-label" for="inlineRadio7">
                                        <img src="img/avatar/7.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio8" value="8">
                                    <label class="form-check-label" for="inlineRadio8">
                                        <img src="img/avatar/8.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio9" value="9">
                                    <label class="form-check-label" for="inlineRadio9">
                                        <img src="img/avatar/9.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio10" value="10">
                                    <label class="form-check-label" for="inlineRadio10">
                                        <img src="img/avatar/10.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio11" value="11">
                                    <label class="form-check-label" for="inlineRadio11">
                                        <img src="img/avatar/11.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio12" value="12">
                                    <label class="form-check-label" for="inlineRadio12">
                                        <img src="img/avatar/12.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio13" value="13">
                                    <label class="form-check-label" for="inlineRadio13">
                                        <img src="img/avatar/13.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio14" value="14">
                                    <label class="form-check-label" for="inlineRadio14">
                                        <img src="img/avatar/14.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio15" value="15">
                                    <label class="form-check-label" for="inlineRadio15">
                                        <img src="img/avatar/15.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio16" value="16">
                                    <label class="form-check-label" for="inlineRadio16">
                                        <img src="img/avatar/16.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio17" value="17">
                                    <label class="form-check-label" for="inlineRadio17">
                                        <img src="img/avatar/17.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio18" value="18">
                                    <label class="form-check-label" for="inlineRadio18">
                                        <img src="img/avatar/18.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary" name="btnAvatar">Alterar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

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
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>