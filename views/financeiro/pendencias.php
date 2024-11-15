<?php 
    $Financeiro_Campanha = new Financeiro_Campanha();
    $Financeiro_Boleto = new Financeiro_Boleto();
    $Usuario = new Usuario();
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
        <!-- Tela de Carregamento -->
    <!-- <div id="preloader">
        <div class="spinner"></div>
    </div> -->
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                <span>Pendencias</span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-3">
                                    <label for="filtroEmpresa" class="form-label text-light">Filtrar por Empresa:</label>
                                    <select id="filtroEmpresa" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="2">Ótica Matriz</option>
                                        <option value="4">Ótica Prestigio</option>
                                        <option value="6">Ótica Daily</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="filtroVendedor" class="form-label text-light">Filtrar por Vendedor:</label>
                                    <select id="filtroVendedor" class="form-control">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Boletos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarBoleto">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Campanha</th>
                                            <th>N° Boleto</th>
                                            <th>Empresa</th>
                                            <th>Vendedor</th>
                                            <th class="d-none">ID Vendedor</th>
                                            <th>Cliente</th>
                                            <th>Data Venda</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $total = 0;
                                            foreach($Financeiro_Boleto->listarAtrasados() as $boleto){
                                            $campanha = $Financeiro_Campanha->mostrar($boleto->id_campanha);
                                            $total+= $boleto->valor
                                        ?>
                                        <tr>
                                        <td class="text-center"><?php echo $campanha->nome ?></td>
                                            <td class="text-center"><?php echo $boleto->n_boleto ?></td>
                                            <td><?php echo Helper::mostrar_empresa($boleto->id_empresa) ?></td>
                                            <td><?php echo $Usuario->mostrar($boleto->id_usuario)->nome ?></td>
                                            <td class="d-none"><?php echo $boleto->id_usuario ?></td>
                                            <td><?php echo $boleto->cliente ?></td>
                                            <td><?php echo Helper::converterData($boleto->data_venda) ?></td>
                                            <td class="text-center">R$ <?php echo number_format($boleto->valor, 2, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                    echo '<b class="badge badge-danger badge-pill">Atrasado</b>';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <td class="text-center"><b>R$ <span id="total_valor">0,00</span></b></td>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
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
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#fina').addClass('active')
            $('#financeiro_boletos').addClass('active')

            // Função para carregar vendedores via AJAX
            function carregarVendedores(idEmpresa, vendedorSelect, incluirTodos = true) {
                $.ajax({
                    url: '/GRNacoes/views/ajax/get_lista_vendedores.php',
                    type: 'GET',
                    data: { id_empresa: idEmpresa },
                    success: function(response) {
                        vendedorSelect.empty();
                        if (incluirTodos) {
                            vendedorSelect.append('<option value="">Todos</option>');
                        } else {
                            vendedorSelect.append('<option value="">Selecione...</option>');
                        }
                        vendedorSelect.append(response);
                    }
                });
            }

            // Atualiza a lista de vendedores no filtro
            $('#filtroEmpresa').change(function() {
                let idEmpresa = $(this).val();
                let vendedorSelect = $('#filtroVendedor');
                carregarVendedores(idEmpresa, vendedorSelect, true);
            });

            // Filtro de vendedores, empresas e datas na tabela
            $('#filtroVendedor, #filtroEmpresa').change(function() {
                var filtroVendedor = $('#filtroVendedor').val();
                var filtroEmpresa = $('#filtroEmpresa').val();

                // Verifica a empresa selecionada
                if(filtroEmpresa == 2){
                    filtroEmpresa = "Ótica Matriz";
                }else if(filtroEmpresa == 4){
                    filtroEmpresa = "Ótica Prestigio";
                }else if(filtroEmpresa == 6){
                    filtroEmpresa = "Ótica Daily";
                }else{
                    filtroEmpresa = "Erro;";
                }

                // Mostrar todas as linhas inicialmente
                $('#dataTable tbody tr').show();
                
                // Iterar sobre as linhas da tabela para aplicar os filtros
                $('#dataTable tbody tr').each(function() {
                    var vendedor = $(this).find('td:eq(3)').text(); // ID do vendedor
                    var empresa = $(this).find('td:eq(2)').text();

                    // Verificar se a linha atende aos critérios de filtragem
                    if ((filtroVendedor && vendedor !== filtroVendedor) || 
                        (filtroEmpresa && empresa !== filtroEmpresa)) {
                        $(this).hide(); // Ocultar a linha se não atender aos critérios
                    }
                });
                recalcularTotal();
            });

            function recalcularTotal() {
                var total = 0;

                // Iterar sobre todas as linhas visíveis da tabela
                $('#dataTable tbody tr:visible').each(function() {
                    var valor = parseFloat($(this).find('td:eq(7)').text().replace('R$', '').replace('.', '').replace(',', '.'));
                    if (!isNaN(valor)) {
                        total += valor;
                    }
                });

                // Atualizar o valor total no rodapé da tabela
                $('#total_valor').text(total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }

            $('#modalRelatorioPorVendedor').on('submit', 'form', function(event) {
                $('#modalRelatorioPorVendedor').modal('hide'); // Fecha o modal
            });


            recalcularTotal();
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-pendencias.js"></script>
</body>
</html>