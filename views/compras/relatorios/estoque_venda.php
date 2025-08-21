<?php 
    $Compra_Fornecedor = new Compra_Fornecedor();

    $meses = [
    "Janeiro", 
    "Fevereiro", 
    "Março", 
    "Abril", 
    "Maio", 
    "Junho", 
    "Julho", 
    "Agosto", 
    "Setembro", 
    "Outubro", 
    "Novembro", 
    "Dezembro"
];

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
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="nav-fixed">
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include_once('resources/sidebar.php') ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-primary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title mb-4">
                                    <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                    <span>Estoque e Venda</span>
                                </h1>
                                <div class="row">
                                    <div class="col-2">
                                        <label for="ano" class="form-label text-light">Ano</label>
                                        <select name="ano" id="ano" class="form-control">
                                            <option value="2025">2025</option>
                                            <option value="2024">2024</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="mes" class="form-label text-light">Mês</label>
                                        <select name="mes" id="mes" class="form-control">
                                            <option value="">Selecione...</option>
                                            <?php $x = 1; foreach($meses as $mes){ ?>
                                                <option value="<?php echo $x; ?>"><?php echo $mes; ?></option>
                                            <?php $x++; } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="row">
                            <?php foreach($Compra_Fornecedor->listarFornecedoresDeArmacoes() as $fornecedor){ ?>
                                <div class="col-4">
                                    <div class="card p-2 mb-3">
                                        <b class="text-center"><?php echo $fornecedor->fornecedor ?></b>
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="estoque" class="form-label">Estoque</label>
                                                <input type="text" name="estoque" id="estoque" class="form-control">
                                            </div>
                                            <div class="col-6">
                                                <label for="venda" class="form-label">Venda</label>
                                                <input type="text" name="venda" id="venda" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#comp').addClass('active')
                $('#compras_relatorios').addClass('active')
                $('#compras_relatorios_estoque_venda').addClass('active')
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>
</html>
