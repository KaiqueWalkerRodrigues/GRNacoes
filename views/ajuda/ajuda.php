<?php 

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
    <?php include_once('resources/topbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('sidebar_ajuda.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <br>
                    <div id="main">
                        <?php include_once('ajuda.html'); ?>
                    </div>
                </div>  
            </div>  
                <div id="conteudo-ajuda"></div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#ajuda').addClass('active');

            $('#ajuda').on('click', function () {
                $('#main').html('<div>Carregando...</div>');
                $('#main').load('views/ajuda/ajuda.html');
            });

            $('#chat').on('click', function () {
                $('#main').html('<div>Carregando...</div>');
                $('#main').load('views/ajuda/chat.html');

                $('.active').removeClass('active');
                $('#chat').addClass('active');
            });

            $('#como-abrir-link').on('click', function () {
                $('#main').html('<div>Carregando...</div>');
                $('#main').load('views/ajuda/chamados/como_abrir.html');
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>