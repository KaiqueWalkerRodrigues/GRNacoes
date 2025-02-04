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
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="nav-fixed">
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <?php include_once('resources/sidebar.php') ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-sharp fa-thin fa-chart-line"></i></div>
                                    <span>Dashboard Lente de Contato</span>
                                </h1>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <label for="empresa" class="form-label text-light">Empresa</label>
                                        <select id="empresaSelect" class="form-control">
                                        <?php 
                                            if (verificarSetor([1,12,14])) { ?>
                                                <option value="0">Todas</option>
                                                <option value="1">Clínica Parque</option>
                                                <option value="3">Clínica Mauá</option>
                                                <option value="5">Clínica Jardim</option>
                                            <?php 
                                            } elseif (verificarSetor([13])) {
                                                if ($_SESSION['id_empresa'] == 1) { ?>
                                                    <option value="1">Clínica Parque</option>
                                                <?php 
                                                } elseif ($_SESSION['id_empresa'] == 3) { ?>
                                                    <option value="3">Clínica Mauá</option>
                                                <?php 
                                                } elseif ($_SESSION['id_empresa'] == 5) { ?>
                                                    <option value="5">Clínica Jardim</option>
                                                <?php 
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="semanaInput" class="form-label text-light">Semana</label>
                                        <input type="week" class="form-control" name="semanaInput" id="semanaInput" value="<?php echo date('Y'),'-W',date('W') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-n10">

                            <div class="col-3">
                                <div class="card bg-success text-white mb-4 text-center">
                                    <div class="card-header" style="font-weight: bolder"><span style="margin-left: 15%;">Cirurgias Vendidas</span></div>
                                    <div class="card-body" style="font-weight: bolder; font-size: 1.5rem;text-decoration: underline;"><span id="n_cirurgias_vendidas"></span></div>
                                </div>
                            </div>

                    </div>

                    </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#dash').addClass('active')
                $('#dashboards_catarata').addClass('active')
                
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/areas/area-receita_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/pies/pie-receita_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/bars/bar-motivos.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/bars/bar-porcentagem-catarata.js"></script>
    </body>
</html>