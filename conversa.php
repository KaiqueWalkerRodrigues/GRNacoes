<?php
    include_once('const.php');
    include_once('class/Chat.php');
    include_once('class/Mensagens.php');

    $Mensagem = new Mensagem();
    $id_chat = $_GET['id'];
    $lista_mensagens = $Mensagem->listar($id_chat);

    if (isset($_POST['enviarMensagem'])) {
        $Mensagem->cadastrar($_POST);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Chat</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body{
            color: black;
        }
        .row{
            display: flex;
            align-items: center;
        }
        .card{
            width: 93%;
            text-align: justify;
        }
        .perfil-icon {
            border-radius: 50%;
            height: 50px;
            width: 50px;
        }
        .icon-container {
            margin-right: 10px; /* Adapte conforme necessário */
            margin-left: 10px; /* Adapte conforme necessário */
        }
        .eu{
            background-color: #98ace9;
        }
        #msg {
            position: fixed;
            bottom: 0;
            width: 100%; /* Para ocupar toda a largura */
            background-color: #fff; /* Cor de fundo da div, se necessário */
            padding: 10px; /* Adicione o preenchimento conforme necessário */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1); /* Adicione uma sombra, se desejar */
            z-index: 900;
        }
        .chat{
            margin-top: 8%; /* Altura da topbar */
            margin-bottom: 12%;
            margin-left: 5px; /* Largura da sidebar */
            z-index: 100; /* Garante que a seção de chat esteja abaixo das barras laterais */
            position: relative;
        }

    </style>

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
                <div class="container-fluid chat">

                    <div class="row">
                        <?php foreach ($lista_mensagens as $msg): ?>
                            <div class="icon-container mb-2">
                                <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                            </div>
                            <div class="card p-2 mb-2 <?php echo ($msg->id_usuario == $_SESSION['id_usuario']) ? 'eu' : ''; ?>">
                                <p><?php echo htmlspecialchars($msg->mensagem); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <div id="msg">
                <form action="?" method="post">
                    <div class="mb-3" style="margin-left: 6%;">
                        <div class="row">
                            <div class="col-9">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_chat" value="<?php echo $_GET['id'] ?>">
                                <textarea class="form-control" id="mensagem" name="mensagem" cols="30" rows="3"></textarea>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-lg btn-success" name="enviarMensagem"><i class="fa-solid fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#chat').addClass('active');
        });
        window.onload = function() {
            window.scrollTo(0, document.body.scrollHeight);
        }
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
