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
                                    <span>Dashboard Captação</span>
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
                        <div class="row">
                            <div class="col-8 card mb-4">
                                <div class="card-header">Receita Semanal
                                    <?php 
                                         // Obtém a data atual
                                         $dataAtual = new DateTime();

                                         // Clona a data atual para manter a original intacta
                                         $inicioSemana = clone $dataAtual;
                                         $fimSemana = clone $dataAtual;
 
                                         // Define o início da semana (segunda-feira)
                                         $inicioSemana->modify('monday this week');
 
                                         // Define o fim da semana (sábado)
                                         $fimSemana->modify('sunday this week');
 
                                         // Exibe a receita semanal com a data de início e fim da semana (segunda-feira a sábado)
                                         echo '<h6 class="m-0 font-weight-bold text-primary ml-2"> (' . $inicioSemana->format('d/m/Y') . ' á ' . $fimSemana->format('d/m/Y') . ')</h6>';
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas id="area_receita_semanal"></canvas></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card mb-4">
                                    <div class="card-header">Receita por Empresa (Semanal)</div>
                                    <div class="card-body">
                                        <div class="chart-pie"><canvas id="pie_receita_semanal" width="100%" height="50"></canvas></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header">Porcentagem de Captação Por Captador (Semanal)</div>
                                    <div class="card-body">
                                        <div class="chart-bar"><canvas id="bar-porcentagem-captacao" width="100%" height="50"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card mb-4">
                                    <div class="card-header">Motivos de Não Captação Mais Utilizados (Indeterminado)</div>
                                    <div class="card-body">
                                        <div class="chart-bar"><canvas id="bar-motivos" width="100%" height="50"></canvas></div>
                                    </div>

                                </div>
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
                $('#dashboards_captacao').addClass('active')

                $('#empresaSelect').change(function (e) { 
                    if($('#empresaSelect').val() == 0){
                        $('#tituloPizza').text('Receita Por Empresa (Semanal)')
                    }else{
                        $('#tituloPizza').text('Receita Por Profissional (Semanal)')
                    }
                });

                $('#semanaInput').change(function (e) { 
                    let semana = $('#semanaInput').val()
                    console.log(semana)
                });
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/areas/area-receita_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/pies/pie-receita_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/bars/bar-motivos.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/bars/bar-porcentagem-captacao.js"></script>
    </body>
</html>
