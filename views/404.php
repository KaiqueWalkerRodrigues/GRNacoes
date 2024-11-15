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
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="error-page">
    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <br><br><br><br><br>
                            <div class="text-center">
                                <img class="mb-4 img-error" src="<?php echo URL_RESOURCES ?>/assets/img/drawkit/color/drawkit-error-404.svg" />
                                <p class="lead">A URL solicitada não foi encontrada neste servidor.</p>
                                <a class="text-arrow-icon" href="<?php echo URL ?>"><i class="ml-0 mr-1" data-feather="arrow-left"></i>Voltar para a Pagina Inícial</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include_once('resources/footer.php') ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>
