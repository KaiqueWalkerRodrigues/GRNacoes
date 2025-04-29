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
    <!-- Tela de Carregamento -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-sharp fa-thin fa-chart-line"></i></div>
                                <span>Dashboard Cobranças</span>
                            </h1>
                            <br>
                            <div class="row">
                                <div class="col-3">
                                    <label for="filtro_id_empresa" class="form-label text-light">Empresa</label>
                                    <select id="filtro_id_empresa" class="form-control">
                                    <?php 
                                        if (verificarSetor([1,5,12,14])) { ?>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Cobranças</div>
                        <div class="card-body">
                            <div class="col-10 offset-1">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="tabelaCobranca" style="width:800px;" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="d-none">N_Mês</th>
                                                <th>Mês</th>
                                                <th>Vendas</th>
                                                <th>Valor Pago</th>
                                                <th>A Vencer</th>
                                                <th>Vencido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Conteúdo carregado via AJAX -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
            $('#dash').addClass('active');

            // Inicializa o DataTable com o parâmetro id_empresa enviado via AJAX
            var tabelaCobranca = $('#tabelaCobranca').DataTable({
                "ajax": {
                    "url": "/GRNacoes/views/ajax/get_financeiro_cobrancas.php",
                    "data": function (d) {
                        // Adiciona o valor do filtro na requisição
                        d.id_empresa = $('#filtro_id_empresa').val();
                    },
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "n_mes", "visible": false },
                    { 
                        "data": "mes",
                        "render": function(data, type, row) {
                            return '<span style="font-weight: bolder;">' + data + '</span>';
                        }
                    },
                    { 
                        "data": "vendas",
                        "render": function(data, type, row) {
                            data = parseFloat(data) || 0;
                            var valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data);
                            return '<span style="font-weight: bold; text-align: left;">' + valorFormatado + '</span>';
                        }
                    },
                    { 
                        "data": "valor_pago",
                        "render": function(data, type, row) {
                            data = parseFloat(data) || 0;
                            var valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data);
                            return '<span style="color: green; font-weight: bold; text-align: left;">' + valorFormatado + '</span>';
                        }
                    },
                    { 
                        "data": "a_vencer",
                        "render": function(data, type, row) {
                            data = parseFloat(data) || 0;
                            var valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data);
                            return '<span style="color: orange; font-weight: bold; text-align: left;">' + valorFormatado + '</span>';
                        }
                    },
                    { 
                        "data": "vencido",
                        "render": function(data, type, row) {
                            data = parseFloat(data) || 0;
                            var valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data);
                            return '<span style="color: red; font-weight: bold; text-align: left;">' + valorFormatado + '</span>';
                        }
                    }
                ],
                "columnDefs": [
                    { "visible": false, "targets": 0 },
                    { "width": "20%", "targets": 1 },
                    { "width": "22%", "targets": 2 },
                    { "width": "22%", "targets": 3 },
                    { "width": "22%", "targets": 4 },
                    { "width": "25%", "targets": 5 }
                ],
                "order": [[ 0, 'asc' ]],
                "info": false,
                "lengthChange": false,
                "searching": false,
                "pageLength": 12,
                "paginate": false,
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api();

                    // Função auxiliar para converter valor formatado para número
                    var intVal = function ( i ) {
                        if (typeof i === 'string') {
                            // Remove os caracteres de moeda e formatação
                            return parseFloat(i.replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
                        } else if (typeof i === 'number') {
                            return i;
                        } else {
                            return 0;
                        }
                    };

                    // Calcula os totais para cada coluna (para as páginas exibidas atualmente)
                    var totalVendas = api.column(2, { page: 'current'} ).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var totalValorPago = api.column(3, { page: 'current'} ).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var totalAVencer = api.column(4, { page: 'current'} ).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var totalVencido = api.column(5, { page: 'current'} ).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Função para formatar os valores no padrão R$ 1.000,00
                    var formatCurrency = function(value){
                        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
                    };

                    // Atualiza as células do rodapé
                    // Na coluna "Mês" (índice 1) podemos colocar um rótulo, como "Totais"
                    $(api.column(1).footer()).html('Totais');
                    $(api.column(2).footer()).html(formatCurrency(totalVendas));
                    $(api.column(3).footer()).html(formatCurrency(totalValorPago));
                    $(api.column(4).footer()).html(formatCurrency(totalAVencer));
                    $(api.column(5).footer()).html(formatCurrency(totalVencido));
                }
            });

            // Evento de change no select do filtro para recarregar a tabela
            $('#filtro_id_empresa').on('change', function(){
                tabelaCobranca.ajax.reload();
            });
        });     
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
</body>
</html>
