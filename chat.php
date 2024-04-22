<!DOCTYPE html>
<html lang="en">

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

                        <div class="icon-container mb-2">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="card p-2 mb-2">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie mauris. Aenean in pretium mauris. In tempus ullamcorper gravida. Sed mi lectus, facilisis id euismod vel, suscipit vel lacus. Praesent sit amet dolor ultrices ante luctus elementum vel id dolor. Morbi in nulla nulla. Sed condimentum odio eu dolor porttitor ultrices. Fusce quis pellentesque sem, non porta ligula. Maecenas vehicula tempus lorem et elementum. Nulla tincidunt, ligula vel pulvinar pretium, mauris sapien luctus mi, non egestas orci eros non lacus. Quisque volutpat quis felis volutpat finibus. Sed tellus augue, fringilla nec aliquam ac, tincidunt et ipsum. Donec ut lectus eget lectus malesuada maximus congue non mauris. Suspendisse nec vestibulum justo. Pellentesque ut posuere nunc. Integer eu justo faucibus, ornare arcu a, sodales odio.
                            </p>
                        </div>

                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="card p-2 mb-2">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie mauris. Aenean in pretium mauris. In tempus ullamcorper gravida. Sed mi lectus, facilisis id euismod vel, suscipit vel lacus. Praesent sit amet dolor ultrices ante luctus elementum vel id dolor. Morbi in nulla nulla. Sed condimentum odio eu dolor porttitor ultrices. Fusce quis pellentesque sem, non porta ligula. Maecenas vehicula tempus lorem et elementum. Nulla tincidunt, ligula vel pulvinar pretium, mauris sapien luctus mi, non egestas orci eros non lacus. Quisque volutpat quis felis volutpat finibus. Sed tellus augue, fringilla nec aliquam ac, tincidunt et ipsum. Donec ut lectus eget lectus malesuada maximus congue non mauris. Suspendisse nec vestibulum justo. Pellentesque ut posuere nunc. Integer eu justo faucibus, ornare arcu a, sodales odio.
                            </p>
                        </div>
                        <div class="card p-2 mb-2 eu">
                            <p>
                                E o pix?
                            </p>
                        </div>
                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="card p-2 mb-2 eu">
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat quasi nulla qui est iste quisquam impedit repellendus ea nemo voluptate distinctio ab iure vero cupiditate, ipsa asperiores aperiam sit? Blanditiis!
                            </p>
                        </div>
                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="card p-2 mb-2">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie mauris. Aenean in pretium mauris. In tempus ullamcorper gravida. Sed mi lectus, facilisis id euismod vel, suscipit vel lacus. Praesent sit amet dolor ultrices ante luctus elementum vel id dolor. Morbi in nulla nulla. Sed condimentum odio eu dolor porttitor ultrices. Fusce quis pellentesque sem, non porta ligula. Maecenas vehicula tempus lorem et elementum. Nulla tincidunt, ligula vel pulvinar pretium, mauris sapien luctus mi, non egestas orci eros non lacus. Quisque volutpat quis felis volutpat finibus. Sed tellus augue, fringilla nec aliquam ac, tincidunt et ipsum. Donec ut lectus eget lectus malesuada maximus congue non mauris. Suspendisse nec vestibulum justo. Pellentesque ut posuere nunc. Integer eu justo faucibus, ornare arcu a, sodales odio.
                            </p>
                        </div>
                        <div class="card p-2 mb-2 eu">
                            <p>
                                E o pix?
                            </p>
                        </div>
                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="icon-container">
                            <img class="perfil-icon mb-2" src="img/logo.jpg" alt="">
                        </div>
                        <div class="card p-2 mb-2">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel molestie mauris. Aenean in pretium mauris. In tempus ullamcorper gravida. Sed mi lectus, facilisis id euismod vel, suscipit vel lacus. Praesent sit amet dolor ultrices ante luctus elementum vel id dolor. Morbi in nulla nulla. Sed condimentum odio eu dolor porttitor ultrices. Fusce quis pellentesque sem, non porta ligula. Maecenas vehicula tempus lorem et elementum. Nulla tincidunt, ligula vel pulvinar pretium, mauris sapien luctus mi, non egestas orci eros non lacus. Quisque volutpat quis felis volutpat finibus. Sed tellus augue, fringilla nec aliquam ac, tincidunt et ipsum. Donec ut lectus eget lectus malesuada maximus congue non mauris. Suspendisse nec vestibulum justo. Pellentesque ut posuere nunc. Integer eu justo faucibus, ornare arcu a, sodales odio.
                            </p>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <div id="msg">
                <div class="mb-3" style="margin-left: 6%;">
                    <div class="row">
                        <div class="col-8">
                            <textarea class="form-control" name="" id="" cols="30" rows="3"></textarea>
                        </div>
                        <div class="col-1">
                            <label for="file-upload" class="btn btn-lg btn-primary"><i class="fa-solid fa-paperclip"></i></label>
                            <input id="file-upload" type="file" accept="image/jpeg, image/jpg, image/png, image/webp, image/gif" style="display: none;">
                        </div>
                        <div class="col-1">
                            <button class="btn btn-lg btn-success"><i class="fa-solid fa-paper-plane"></i></button>
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