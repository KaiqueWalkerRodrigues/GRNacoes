<?php
    include_once('const.php');

    $Conversa = new Conversa();
    $Mensagem = new Mensagem();
    $Usuario = new Usuario();

    $id_chat = $_GET['id'];
    $id_destinatario = $_GET['id_destinatario'];
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
        body {
            color: black;
        }

        .row {
            display: flex;
            flex-direction: column;
        }

        .card {
            width: 93%;
            text-align: justify;
            border-radius: 10px;
        }

        .perfil-icon {
            border-radius: 50%;
            height: 50px;
            width: 50px;
        }

        .icon-container {
            display: flex;
            align-items: center;
            margin: 10px 0; /* Adapte conforme necessário */
        }

        .icon-container.eu {
            justify-content: flex-start; /* Ícone e mensagem à esquerda */
        }

        .icon-container.outro {
            justify-content: flex-end; /* Ícone e mensagem à direita */
        }

        .icon-container.eu .card {
            background-color: #98ace9;
            margin-left: 10px;
        }

        .icon-container.outro .card {
            background-color: #f1f1f1;
            margin-right: 10px;
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

        .chat {
            margin-top: 8%; /* Altura da topbar */
            margin-bottom: 10%;
            margin-left: 5px; /* Largura da sidebar */
            z-index: 100; /* Garante que a seção de chat esteja abaixo das barras laterais */
            position: relative;
        }
        .btn-enviar-mensagem {
            background-color: #25D366; /* Cor verde do WhatsApp */
            border: none;
            border-radius: 50%; /* Botão redondo */
            width: 50px; /* Tamanho do botão */
            height: 50px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); /* Sombra suave */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-enviar-mensagem:hover {
            background-color: #20b358; /* Cor mais escura ao passar o mouse */
        }

        .btn-enviar-mensagem i {
            font-size: 24px; /* Tamanho do ícone */
        }

        .input-mensagem {
            border-radius: 25px; /* Deixa o input com bordas arredondadas */
            border: 1px solid #ced4da; /* Cor da borda */
            padding: 10px 20px; /* Espaçamento interno do input */
            width: 80%;
            resize: none; /* Evita que o textarea seja redimensionado */
        }

        .align-items-center {
            display: flex;
            align-items: center;
        }

        .justify-content-center {
            display: flex;
            justify-content: center;
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

            <?php $destinatario = $Usuario->mostrar($id_destinatario); ?>

            <!-- Begin Page Content -->
            <div class="container-fluid chat">

            <div class="card p-2 d-flex align-items-center" style="margin-top: -15px; position: fixed; z-index: 1000;width: 80%;">
                <div class="d-flex align-items-center">
                    <img class="perfil img-profile rounded-circle" style="width: 50px; margin-right: 10px;" src="<?php echo URL ?>/img/avatar/<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>.png">
                    <div>
                        <b><?php echo $destinatario->nome; ?></b>
                        <span style="font-size: 11px; display: block;">Última vez online há 10 min</span>
                    </div>
                </div>
            </div>

            <br><br><br>

                <div id="mensagensContainer">
                    
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <div id="msg" style="width: 98%">
            <form action="class/Mensagens.php" method="post" id="chatForm">
                <div class="mb">
                    <div class="align-items-center">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_destinatario" id="id_usuario" value="<?php echo $id_destinatario ?>">
                        <input type="hidden" name="id_conversa" id="id_conversa" value="<?php echo $_GET['id'] ?>">
                        <input type="hidden" name="id_avatar" id="id_avatar" value="<?php echo $Usuario->mostrar($_SESSION['id_usuario'])->id_avatar; ?>">
                        <input type="hidden" name="id_avatar_destinatario" id="id_avatar_destinatario" value="<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>">
                        <textarea class="form-control input-mensagem mr-4" id="mensagem" name="mensagem" rows="2" style="max-width: calc(100% - 60px);"></textarea>
                        <button type="submit" class="btn-enviar-mensagem ms-3" name="enviarMensagem">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
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
        $(document).ready(function () {
            var id = $('#id_conversa').val();
            var id_usuario = $('#id_usuario').val();
            var id_avatar = $('#id_avatar').val();
            var id_avatar_destinatario = $('#id_avatar_destinatario').val();

            function mostrarMensagens() {
                $.ajax({
                    type: "get",
                    url: "get_messages.php",
                    data: { id_conversa: id, id_usuario: id_usuario, id_avatar: id_avatar, id_avatar_destinatario: id_avatar_destinatario },
                    success: function (result) {
                        $('#mensagensContainer').html(result);
                    },
                    error: function(){
                        $('#mensagensContainer').html("Error");
                    }
                });
            }

            setInterval(mostrarMensagens, 100);

            setTimeout(function() {
                var container = document.body;
                container.scrollTop = container.scrollHeight;
            }, 175); 

            $('#chat').addClass('active');

            $('#mensagem').keydown(function (e) {
                if (e.keyCode == 13 && !e.shiftKey) { 
                    e.preventDefault(); 
                    $('#chatForm').submit(); 
                }
            });
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
