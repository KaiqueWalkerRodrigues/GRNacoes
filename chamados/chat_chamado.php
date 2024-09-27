<?php
    include_once('../const.php');

    $Chamado = new Chamados();
    $Mensagem = new Mensagem();
    $Usuario = new Usuario();

    $id_chamado = $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    $chamado = $Chamado->mostrar($id_chamado);
    $id_destinatario = $Usuario->mostrar($chamado->id_usuario)->id_usuario;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Chat Chamado</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

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

    <input type="hidden" id="id_usuario" value="<?php echo $id_usuario ?>">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('../sidebar.php'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include_once('../topbar.php'); ?>

            <?php $destinatario = $Usuario->mostrar($chamado->id_usuario); ?>

            <!-- Begin Page Content -->
            <div class="container-fluid chat">

                <div id="mensagensContainer">
                    
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <div id="msg" style="width: 98%">
            <form action="<?php echo URL ?>/class/Mensagens.php" method="post" id="chatForm">
                <div class="mb">
                    <div class="align-items-center">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
                        <input type="hidden" name="id_destinatario" id="id_destinatario" value="<?php echo $id_destinatario ?>">
                        <input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $_GET['id'] ?>">
                        <input type="hidden" name="id_avatar" id="id_avatar" value="<?php echo $Usuario->mostrar($id_usuario)->id_avatar; ?>">
                        <input type="hidden" name="id_destinatario_avatar" id="id_destinatario_avatar" value="<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>">
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
            var id = $('#id_chamado').val();
            var id_usuario = $('#id_usuario').val();
            var id_destinatario = $('#id_destinatario').val();
            var id_avatar = $('#id_avatar').val();
            var id_destinatario_avatar = $('#id_destinatario_avatar').val();

            function mostrarMensagens() {
                $.ajax({
                    type: "get",
                    url: "get_messages.php",
                    data: { id_chamado: id, id_usuario: id_usuario, id_avatar: id_avatar, id_destinatario_avatar: id_destinatario_avatar },
                    success: function (result) {
                        $('#mensagensContainer').html(result);
                    },
                    error: function(){
                        $('#mensagensContainer').html("Error");
                    }
                });
            }

            //Sistema Online
            {
                function manterOnline() {
                    $.ajax({
                        type: "get",
                        url: "../manter_online.php",
                        data: { id_usuario: id_usuario },
                    });
                }
                setInterval(manterOnline, 1000);
            }

            setInterval(mostrarMensagens, 100);
            
            setTimeout(function() {
                var container = document.body;
                container.scrollTop = container.scrollHeight;
            }, 175); 

            $('#cham').addClass('active');

            $('#mensagem').keydown(function (e) {
                if (e.keyCode == 13 && !e.shiftKey) { 
                    e.preventDefault(); 
                    $('#chatForm').submit(); 
                }
            });
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

</body>

</html>
