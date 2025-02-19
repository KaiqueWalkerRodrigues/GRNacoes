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
                    <div class="page-header pb-10 page-header-dark bg-primary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <!-- Corrigido o ícone para classes compatíveis com o Font Awesome 5 -->
                                    <div class="page-header-icon"><i class="fas fa-chart-line"></i></div>
                                    <span>Dashboard Lente de Contato</span>
                                </h1>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <!-- Corrigido o atributo 'for' para corresponder ao id do select -->
                                        <label for="empresaSelect" class="form-label text-light">Empresa</label>
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
                                                } 
                                            ?>
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
                            <!-- Card Lucro Semanal -->
                            <div class="col-3 mb-4">
                                <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-green h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="small font-weight-bold text-green mb-1">Lucro</div>
                                                <!-- Lucro formatado -->
                                                <div class="h5" id="card_lucro"></div>
                                                <!-- Trending atualizado dinamicamente -->
                                                <div class="text-xs font-weight-bold d-inline-flex align-items-center" id="card_lucro_trending">
                                                    <i class="mr-1" data-feather="trending-up"></i>0%
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-200"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Faturamento Semanal -->
                            <div class="col-3 mb-4">
                                <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-cyan h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="small font-weight-bold text-cyan mb-1">Faturamento</div>
                                                <!-- Faturamento formatado -->
                                                <div class="h5" id="card_faturamento"></div>
                                                <!-- Trending atualizado dinamicamente -->
                                                <div class="text-xs font-weight-bold d-inline-flex align-items-center" id="card_trending">
                                                    <i class="mr-1" data-feather="trending-up"></i>0%
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <i class="fas fa-coins fa-2x text-gray-200"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Número de Orçamentos -->
                            <div class="col-3 mb-4">
                                <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-pink h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="small font-weight-bold text-pink mb-1">N° de Orçamentos</div>
                                                <!-- Valor preenchido via AJAX -->
                                                <div class="h5" id="card_n_orcamento"></div>
                                                <!-- Trending atualizado dinamicamente -->
                                                <div class="text-xs font-weight-bold d-inline-flex align-items-center" id="card_n_orcamento_trending">
                                                    <i class="mr-1" data-feather="trending-up"></i>0%
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <i class="fas fa-clipboard fa-2x text-gray-200"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Preço de Venda Médio -->
                            <div class="col-3 mb-4">
                                <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-purple h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="small font-weight-bold text-purple mb-1">Preço de Venda Médio</div>
                                                <!-- Valor preenchido via AJAX -->
                                                <div class="h5" id="card_preco_medio"></div>
                                                <!-- Trending atualizado dinamicamente -->
                                                <div class="text-xs font-weight-bold d-inline-flex align-items-center" id="card_preco_trending">
                                                    <i class="mr-1" data-feather="trending-up"></i>0%
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <i class="fas fa-tag fa-2x text-gray-200"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Demais elementos gráficos da dashboard -->
                            <div class="col-8 card mb-4">
                                <div class="card-header">Lente de Contato Semanal
                                    <?php 
                                         // Obtém a data atual
                                         $dataAtual = new DateTime();
                                         $inicioSemana = clone $dataAtual;
                                         $fimSemana = clone $dataAtual;
                                         $inicioSemana->modify('monday this week');
                                         $fimSemana->modify('saturday this week');
                                         echo '<h6 class="m-0 font-weight-bold text-primary ml-2"> (' . $inicioSemana->format('d/m/Y') . ' á ' . $fimSemana->format('d/m/Y') . ')</h6>';
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="area_lente_contato_semanal"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card mb-4">
                                    <div class="card-header">Lente de Contato por Empresa (Semanal)</div>
                                    <div class="card-body">
                                        <div class="chart-pie">
                                            <canvas id="pie_lente_contato_semanal" width="100%" height="50"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 card mb-4">
                                <div class="card-header">Faturamento Lente de Contato
                                    <?php 
                                         $dataAtual = new DateTime();
                                         $inicioSemana = clone $dataAtual;
                                         $fimSemana = clone $dataAtual;
                                         $inicioSemana->modify('monday this week');
                                         $fimSemana->modify('saturday this week');
                                         echo '<h6 class="m-0 font-weight-bold text-primary ml-2"> (' . $inicioSemana->format('d/m/Y') . ' á ' . $fimSemana->format('d/m/Y') . ')</h6>';
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="area_faturamento_lente_contato_semanal"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
        // Aguarda o carregamento completo do DOM
        $(document).ready(function () {

            // Função auxiliar para obter o número da semana ISO de uma data
            function getWeekNumber(d) {
                d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
                d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
                var yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
                return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            }

            // Função para atualizar o faturamento e calcular o trending comparativo com a semana anterior
            function atualizarFaturamento() {
                var semana = $('#semanaInput').val(); // Exemplo: "2025-W06"
                var empresa = $('#empresaSelect').val();

                // Cálculo da semana anterior a partir do valor selecionado
                var parts = semana.split('-W');
                var anoAtual = parseInt(parts[0], 10);
                var semanaAtual = parseInt(parts[1], 10);
                var anoAnterior = anoAtual;
                var semanaAnterior = semanaAtual - 1;

                if (semanaAnterior < 1) {
                    anoAnterior = anoAtual - 1;
                    var data31DeDezembro = new Date(anoAnterior, 11, 31);
                    semanaAnterior = getWeekNumber(data31DeDezembro);
                }

                // Formata a semana anterior no mesmo padrão: "YYYY-Www"
                var semanaAnteriorStr = anoAnterior + '-W' + (semanaAnterior < 10 ? '0' + semanaAnterior : semanaAnterior);

                // Requisição AJAX para obter o faturamento da semana atual
                var requestAtual = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_faturamento.php',
                    method: 'GET',
                    data: { semana: semana, empresa: empresa },
                    dataType: 'json'
                });

                // Requisição AJAX para obter o faturamento da semana anterior
                var requestAnterior = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_faturamento.php',
                    method: 'GET',
                    data: { semana: semanaAnteriorStr, empresa: empresa },
                    dataType: 'json'
                });

                // Aguarda que ambas as requisições sejam concluídas
                $.when(requestAtual, requestAnterior).done(function (respostaAtual, respostaAnterior) {
                    var dadosAtual = respostaAtual[0];
                    var dadosAnterior = respostaAnterior[0];

                    if (dadosAtual.total_faturamento !== undefined && dadosAnterior.total_faturamento !== undefined) {
                        var faturamentoAtual = parseFloat(dadosAtual.total_faturamento);
                        var faturamentoAnterior = parseFloat(dadosAnterior.total_faturamento);

                        // Formata o faturamento atual para o padrão R$ 1.000,00
                        var faturamentoFormatado = faturamentoAtual.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        $('#card_faturamento').html(faturamentoFormatado);

                        // Calcula a variação percentual entre as semanas
                        var percentual;
                        if (faturamentoAnterior === 0) {
                            percentual = faturamentoAtual > 0 ? 100 : 0;
                        } else {
                            percentual = ((faturamentoAtual - faturamentoAnterior) / faturamentoAnterior) * 100;
                        }
                        var percentualFormatado = Math.abs(percentual).toFixed(2) + '%';

                        // Define o ícone e a cor do trending conforme a variação
                        var iconeTrending, classeTrending;
                        if (percentual >= 0) {
                            iconeTrending = 'trending-up';
                            classeTrending = 'text-success';
                        } else {
                            iconeTrending = 'trending-down';
                            classeTrending = 'text-danger';
                        }

                        // Atualiza o elemento com o trending
                        $('#card_trending').html('<i class="mr-1 ' + classeTrending + '" data-feather="' + iconeTrending + '"></i>' + percentualFormatado);
                        feather.replace();
                    } else {
                        console.error("Erro: Dados de faturamento inválidos.");
                    }
                }).fail(function (xhr, status, error) {
                    console.error("Erro nas requisições AJAX (faturamento): " + error);
                });
            }

            // Função para atualizar o preço de venda médio com trending
            function atualizarPrecoVendaMedio() {
                var semana = $('#semanaInput').val();
                var empresa = $('#empresaSelect').val();

                // Cálculo da semana anterior (igual ao usado no faturamento)
                var parts = semana.split('-W');
                var anoAtual = parseInt(parts[0], 10);
                var semanaAtual = parseInt(parts[1], 10);
                var anoAnterior = anoAtual;
                var semanaAnterior = semanaAtual - 1;

                if (semanaAnterior < 1) {
                    anoAnterior = anoAtual - 1;
                    var data31DeDezembro = new Date(anoAnterior, 11, 31);
                    semanaAnterior = getWeekNumber(data31DeDezembro);
                }

                var semanaAnteriorStr = anoAnterior + '-W' + (semanaAnterior < 10 ? '0' + semanaAnterior : semanaAnterior);

                // Requisição AJAX para obter o preço de venda médio da semana atual
                var requestPrecoAtual = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_preco_venda_medio.php',
                    method: 'GET',
                    data: { semana: semana, empresa: empresa },
                    dataType: 'json'
                });

                // Requisição AJAX para obter o preço de venda médio da semana anterior
                var requestPrecoAnterior = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_preco_venda_medio.php',
                    method: 'GET',
                    data: { semana: semanaAnteriorStr, empresa: empresa },
                    dataType: 'json'
                });

                // Aguarda as duas requisições
                $.when(requestPrecoAtual, requestPrecoAnterior).done(function (respostaPrecoAtual, respostaPrecoAnterior) {
                    var dadosPrecoAtual = respostaPrecoAtual[0];
                    var dadosPrecoAnterior = respostaPrecoAnterior[0];

                    if (dadosPrecoAtual.preco_medio !== undefined && dadosPrecoAnterior.preco_medio !== undefined) {
                        var precoAtual = parseFloat(dadosPrecoAtual.preco_medio);
                        var precoAnterior = parseFloat(dadosPrecoAnterior.preco_medio);

                        // Formata o preço de venda médio atual para o padrão brasileiro
                        var precoFormatado = precoAtual.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        $('#card_preco_medio').html(precoFormatado);

                        // Calcula a variação percentual entre as semanas
                        var percentual;
                        if (precoAnterior === 0) {
                            percentual = precoAtual > 0 ? 100 : 0;
                        } else {
                            percentual = ((precoAtual - precoAnterior) / precoAnterior) * 100;
                        }
                        var percentualFormatado = Math.abs(percentual).toFixed(2) + '%';

                        // Define o ícone e a cor do trending conforme a variação
                        var iconeTrending, classeTrending;
                        if (percentual >= 0) {
                            iconeTrending = 'trending-up';
                            classeTrending = 'text-success';
                        } else {
                            iconeTrending = 'trending-down';
                            classeTrending = 'text-danger';
                        }

                        // Atualiza o elemento com o trending para o preço de venda médio
                        $('#card_preco_trending').html('<i class="mr-1 ' + classeTrending + '" data-feather="' + iconeTrending + '"></i>' + percentualFormatado);
                        feather.replace();
                    } else {
                        console.error("Erro: Dados de preço médio inválidos.");
                    }
                }).fail(function (xhr, status, error) {
                    console.error("Erro nas requisições AJAX (preço médio): " + error);
                });
            }

            // Função para atualizar o número de orçamentos (contando apenas os com valor > 0 e deleted_at IS NULL)
            // e calcular o trending comparativo com a semana anterior
            function atualizarNumeroOrcamentos() {
                var semana = $('#semanaInput').val(); // Exemplo: "2025-W06"
                var empresa = $('#empresaSelect').val();

                // Calcula a semana anterior a partir do valor selecionado
                var parts = semana.split('-W');
                var anoAtual = parseInt(parts[0], 10);
                var semanaAtual = parseInt(parts[1], 10);
                var anoAnterior = anoAtual;
                var semanaAnterior = semanaAtual - 1;

                if (semanaAnterior < 1) {
                    anoAnterior = anoAtual - 1;
                    var data31DeDezembro = new Date(anoAnterior, 11, 31);
                    semanaAnterior = getWeekNumber(data31DeDezembro);
                }
                // Formata a semana anterior no mesmo padrão: "YYYY-Www"
                var semanaAnteriorStr = anoAnterior + '-W' + (semanaAnterior < 10 ? '0' + semanaAnterior : semanaAnterior);

                // Requisição AJAX para obter o número de orçamentos da semana atual
                var requestAtual = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_n_orcamento.php',
                    method: 'GET',
                    data: { semana: semana, empresa: empresa },
                    dataType: 'json'
                });

                // Requisição AJAX para obter o número de orçamentos da semana anterior
                var requestAnterior = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_n_orcamento.php',
                    method: 'GET',
                    data: { semana: semanaAnteriorStr, empresa: empresa },
                    dataType: 'json'
                });

                // Aguarda que ambas as requisições sejam concluídas
                $.when(requestAtual, requestAnterior).done(function (respostaAtual, respostaAnterior) {
                    var dadosAtual = respostaAtual[0];
                    var dadosAnterior = respostaAnterior[0];

                    if (dadosAtual.total_orcamentos !== undefined && dadosAnterior.total_orcamentos !== undefined) {
                        var orcamentosAtual = parseInt(dadosAtual.total_orcamentos, 10);
                        var orcamentosAnterior = parseInt(dadosAnterior.total_orcamentos, 10);

                        // Atualiza o número de orçamentos no card
                        $('#card_n_orcamento').html(orcamentosAtual);

                        // Calcula a variação percentual entre as semanas
                        var percentual;
                        if (orcamentosAnterior === 0) {
                            percentual = orcamentosAtual > 0 ? 100 : 0;
                        } else {
                            percentual = ((orcamentosAtual - orcamentosAnterior) / orcamentosAnterior) * 100;
                        }
                        var percentualFormatado = Math.abs(percentual).toFixed(2) + '%';

                        // Define o ícone e a cor do trending conforme a variação
                        var iconeTrending, classeTrending;
                        if (percentual >= 0) {
                            iconeTrending = 'trending-up';
                            classeTrending = 'text-success';
                        } else {
                            iconeTrending = 'trending-down';
                            classeTrending = 'text-danger';
                        }
                        // Atualiza o elemento com o trending
                        $('#card_n_orcamento_trending').html('<i class="mr-1 ' + classeTrending + '" data-feather="' + iconeTrending + '"></i>' + percentualFormatado);
                        feather.replace();
                    } else {
                        console.error("Erro: Dados de número de orçamentos inválidos.");
                    }
                }).fail(function (xhr, status, error) {
                    console.error("Erro na requisição AJAX (número de orçamentos): " + error);
                });
            }

            // Função para atualizar o lucro e calcular o trending comparativo com a semana anterior
            function atualizarLucro() {
                var semana = $('#semanaInput').val(); // Exemplo: "2025-W06"
                var empresa = $('#empresaSelect').val();

                // Cálculo da semana anterior
                var parts = semana.split('-W');
                var anoAtual = parseInt(parts[0], 10);
                var semanaAtual = parseInt(parts[1], 10);
                var anoAnterior = anoAtual;
                var semanaAnterior = semanaAtual - 1;

                if (semanaAnterior < 1) {
                    anoAnterior = anoAtual - 1;
                    var data31DeDezembro = new Date(anoAnterior, 11, 31);
                    semanaAnterior = getWeekNumber(data31DeDezembro);
                }
                var semanaAnteriorStr = anoAnterior + '-W' + (semanaAnterior < 10 ? '0' + semanaAnterior : semanaAnterior);

                // Requisição AJAX para obter o lucro da semana atual
                var requestAtual = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_lucro.php',
                    method: 'GET',
                    data: { semana: semana, empresa: empresa },
                    dataType: 'json'
                });

                // Requisição AJAX para obter o lucro da semana anterior
                var requestAnterior = $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_lucro.php',
                    method: 'GET',
                    data: { semana: semanaAnteriorStr, empresa: empresa },
                    dataType: 'json'
                });

                // Aguarda que ambas as requisições sejam concluídas
                $.when(requestAtual, requestAnterior).done(function (respostaAtual, respostaAnterior) {
                    var dadosAtual = respostaAtual[0];
                    var dadosAnterior = respostaAnterior[0];

                    if (dadosAtual.lucro !== undefined && dadosAnterior.lucro !== undefined) {
                        var lucroAtual = parseFloat(dadosAtual.lucro);
                        var lucroAnterior = parseFloat(dadosAnterior.lucro);

                        // Formata o lucro atual para o padrão R$ 1.000,00
                        var lucroFormatado = lucroAtual.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        $('#card_lucro').html(lucroFormatado);

                        // Calcula a variação percentual entre as semanas
                        var percentual;
                        if (lucroAnterior === 0) {
                            percentual = lucroAtual > 0 ? 100 : 0;
                        } else {
                            percentual = ((lucroAtual - lucroAnterior) / lucroAnterior) * 100;
                        }
                        var percentualFormatado = Math.abs(percentual).toFixed(2) + '%';

                        // Define o ícone e a cor do trending conforme a variação
                        var iconeTrending, classeTrending;
                        if (percentual >= 0) {
                            iconeTrending = 'trending-up';
                            classeTrending = 'text-success';
                        } else {
                            iconeTrending = 'trending-down';
                            classeTrending = 'text-danger';
                        }
                        $('#card_lucro_trending').html('<i class="mr-1 ' + classeTrending + '" data-feather="' + iconeTrending + '"></i>' + percentualFormatado);
                        feather.replace();
                    } else {
                        console.error("Erro: Dados de lucro inválidos.");
                    }
                }).fail(function (xhr, status, error) {
                    console.error("Erro na requisição AJAX (lucro): " + error);
                });
            }

            // Vincula os eventos de alteração dos campos "semana" e "empresa"
            $('#semanaInput, #empresaSelect').on('change', function () {
                atualizarFaturamento();
                atualizarPrecoVendaMedio();
                atualizarNumeroOrcamentos();
                atualizarLucro();
            });

            // Ativa as classes de navegação e realiza as atualizações ao carregar a página
            $('#dash').addClass('active');
            $('#dashboards_lente_contato').addClass('active');

            atualizarFaturamento();
            atualizarPrecoVendaMedio();
            atualizarNumeroOrcamentos();
            atualizarLucro();
        });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/areas/area-lente_contato_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/areas/area-faturamento_lente_contato_semanal.js"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/pies/pie-lente_contato_semanal.js"></script>
    </body>
</html>
