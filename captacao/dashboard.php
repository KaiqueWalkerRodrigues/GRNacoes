<?php 
    include_once('../const.php');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Captação Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .porcentagem-p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('../sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('../topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <br><br><br><br>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->                                 
                    <div class="row">

                        <div class="col-6 mb-3 row">
                            <div class="col-6">
                                <label for="empresa" class="form-label">Empresa</label>
                                <select id="empresaSelect" class="form-control">
                                <?php 
                                    if ($_SESSION['id_setor'] == 1 || $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14) { ?>
                                        <option value="0">Todas</option>
                                        <option value="1">Clínica Parque</option>
                                        <option value="3">Clínica Mauá</option>
                                        <option value="5">Clínica Jardim</option>
                                    <?php 
                                    } elseif ($_SESSION['id_setor'] == 13) {
                                        // Se o id_setor for 13, mostramos apenas a unidade específica que o usuário pode ver
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
                        </div>
                        <div class="col-4 offset-2 mb-3 text-center">
                        </div>
                        <!-- Area Chart -->
                        <div class="col-8">

                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Receita Semanal
                                    <?php
                                        // Obtém a data atual
                                        $dataAtual = new DateTime();

                                        // Clona a data atual para manter a original intacta
                                        $inicioSemana = clone $dataAtual;
                                        $fimSemana = clone $dataAtual;

                                        // Define o início da semana (segunda-feira)
                                        $inicioSemana->modify('monday this week');

                                        // Define o fim da semana (sábado)
                                        $fimSemana->modify('saturday this week');

                                        // Exibe a receita semanal com a data de início e fim da semana (segunda-feira a sábado)
                                        echo '<h6 class="m-0 font-weight-bold text-primary"> (' . $inicioSemana->format('d/m/Y') . ' a ' . $fimSemana->format('d/m/Y') . ')</h6>';
                                    ?>
                                    </h6>

                                    <div class="dropdown no-arrow">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary" id="tituloPizza">Receita por Empresa (Semanal)</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Motivos de Não Captação Mais Utilizados (Indeterminado)</h6>
                                </div>
                                <div class="card-body" id="motivosMaisUtilizados">
                                    <!-- O conteúdo será carregado dinamicamente por AJAX -->
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Porcentagem de Captação Por Captador (Semanal)</h6>
                                </div>
                                <div class="card-body" id="porcentagemCaptacao">
                                    <!-- O conteúdo será carregado dinamicamente por AJAX -->
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="col-xl-4 col-lg-5">
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GRNacoes <?php echo date('Y') ?></span>
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
        $(document).ready(function () {
            $('#cap').addClass('active')
            $('#captacao_dashboard').addClass('active')

            $('#empresaSelect').change(function (e) { 
                if($('#empresaSelect').val() == 0){
                    $('#tituloPizza').text('Receita Por Empresa (Semanal)')
                }else{
                    $('#tituloPizza').text('Receita Por Profissional (Semanal)')
                }
            });

            function carregarMotivos(id_empresa) {
                $.ajax({
                    url: '/GRNacoes/captacao/filtrarMotivos.php', // Certifique-se de que o caminho está correto
                    type: 'GET',
                    data: { id_empresa: id_empresa },
                    success: function(data) {
                        $('#motivosMaisUtilizados').html(data); // Atualiza o conteúdo dos motivos
                    },
                    error: function() {
                        $('#motivosMaisUtilizados').html('<p>Erro ao carregar os motivos.</p>');
                    }
                });
            }

            // Função para carregar as porcentagens de captação
            function carregarPorcentagemCaptacao(id_empresa) {
                $.ajax({
                    url: '/GRNacoes/captacao/get_porcentagem_captacao.php', // Caminho do arquivo que retorna o JSON
                    type: 'GET',
                    data: { empresaId: id_empresa },
                    success: function(data) {
                        var captadores = data; // Data já é um objeto JS, não precisa de JSON.parse()
                        var html = '';

                        captadores.forEach(function(captador) {
                            html += `
                                <h4 class="small font-weight-bold">
                                    ${captador.nome}
                                    <span class="float-right text-danger">${captador.nao_captado}% Não Captados</span>
                                    <span class="float-right text-success" style="margin-right: 10px;">${captador.captado}% Captados</span>
                                </h4>
                                <div class="progress mb-4">
                                    <!-- Barra de progresso verde para captados -->
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: ${captador.captado}%"
                                        aria-valuenow="${captador.captado}" aria-valuemin="0" aria-valuemax="100">
                                    </div>

                                    <!-- Barra de progresso vermelha para não captados -->
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: ${captador.nao_captado}%"
                                        aria-valuenow="${captador.nao_captado}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>`;
                        });

                        $('#porcentagemCaptacao').html(html); // Preenche a div com as porcentagens
                    },
                    error: function() {
                        $('#porcentagemCaptacao').html('<p>Erro ao carregar os dados de captação.</p>');
                    }
                });
            }

             // Carregar os motivos ao carregar a página
                carregarMotivos($('#empresaSelect').val());
                carregarPorcentagemCaptacao($('#empresaSelect').val());

            // Carregar os motivos quando o select de empresa mudar
            $('#empresaSelect').change(function () {
                var id_empresa = $(this).val();
                carregarMotivos(id_empresa);
                carregarPorcentagemCaptacao(id_empresa);
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

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/receitas_semanal_bar.js"></script>
    <script src="js/receitas_semanal_pie.js"></script>

</body>

</html>