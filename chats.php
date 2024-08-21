<?php 
    include_once("const.php");

    $Usuarios = new Usuario();
    $Setores = new Setor();
    $Chats = new Conversa();

    if(isset($_POST['btnAbrir'])){
        $Chats->cadastrarPrivado($_POST);
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

    <title>GRNacoes - Chats</title>

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

                    <button class="btn btn-success btn-circle" data-toggle="modal" data-target="#modalAbrirChat"><i class="fa-solid fa-circle-plus"></i></button>

                    <div class="row">

                    <div class="col-12">
                        <?php foreach($Chats->listar($_SESSION['id_usuario']) as $chat){ ?>
                        <?php $destinatario = $Usuarios->mostrar($Chats->destinatario($chat->id_conversa,$_SESSION['id_usuario'])->id_usuario); ?>
                        <a href="<?php echo URL ?>/chat?id=<?php echo $chat->id_conversa ?>&id_destinatario=<?php echo $destinatario->id_usuario ?>" class="text-decoration-none grow-on-hover">
                            <div class="card my-3 border-1">
                                <div class="row align-items-center">
                                    <div class="col-1">
                                        <img class="img-profile rounded-circle p-2 ml-3" width="75%" src="<?php echo URL ?>/img/avatar/<?php echo $Usuarios->mostrar($Chats->destinatario($chat->id_conversa,$_SESSION['id_usuario'])->id_usuario)->id_avatar ?>.png">
                                    </div>
                                    <div class="col-4 text-start">
                                        <p class="text-dark mt-3"><?php echo ($Chats->mostrar($chat->id_conversa)->nome ?? $destinatario->nome); ?></p> 
                                    </div>
                                    <div class="col-4 text-start">
                                        <p class="text-dark mt-3"><?php echo $Setores->mostrar($destinatario->id_setor)->setor ?></p> 
                                    </div>
                                    <div class="col-2 text-start">
                                        <p class="text-dark mt-3"><?php echo Helper::mostrar_empresa($destinatario->empresa) ?></p>
                                    </div>
                                    <div class="col-1 text-start">
                                        <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center" style="width: 30px; height: 30px;">
                                            <span>0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php } ?>
                    </div>

                    </div>

                   <br>

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

    <!-- Modal Abrir Novo Chat -->
    <div class="modal fade" id="modalAbrirChat" tabindex="1" role="dialog" aria-labelledby="modalAbrirChatLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Abrir novo chat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="categoria" class="form-label">Usuário *</label>
                                <select class="form-control" name="id_destinatario" id="id_destinatario" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Usuarios->listar() as $u){ ?>
                                        <option value="<?php echo $u->id_usuario ?>"><?php echo $u->nome ?> | <?php echo $Setores->mostrar($u->id_setor)->setor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnAbrir" class="btn btn-primary">Abrir Chat</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#chat').addClass('active');
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