<?php 
    include_once('../const.php'); 

    $Financeiro_Campanha = new Financeiro_Campanhas();
    $Financeiro_Boleto = new Financeiro_Boletos();
    $Usuario = new Usuario();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - <?php echo $campanha->nome ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <br>

                    <div class="row mb-4">
                        <div class="col-3">
                            <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
                            <select id="filtroEmpresa" class="form-control">
                                <option value="">Todos</option>
                                <option value="2">Ótica Matriz</option>
                                <option value="4">Ótica Prestigio</option>
                                <option value="6">Ótica Daily</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="filtroVendedor" class="form-label">Filtrar por Vendedor:</label>
                            <select id="filtroVendedor" class="form-control">
                                <option value="">Todos</option>
                            </select>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary text-center">
                                    Boletos em Atraso
                                </h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                                    echo '<b class="text-danger">Atrasado</b>';
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
                                            <th><b>R$ <span id="total_valor">0,00</span></b></th>
                                            <td></td>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações 2024</span>
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

     <!-- Modal Relatório por Vendedor -->
     <div class="modal fade" id="modalRelatorioPorVendedor" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorVendedorLabel" aria-hidden="true">
        <form action="?" method="get">
            <div class="modal-dialog modal-lg
            ." role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimir o Relatório do Vendedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8 offset-2">
                                <input type="hidden" name="id_campanha" value="<?php echo $campanha->id_financeiro_campanha  ?>">
                                <select name="id_vendedor" id="id_vendedor" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach($Usuario->listarVendedores() as $vendedor){ ?>
                                        <option value="<?php echo $vendedor->id_usuario ?>"><?php echo $vendedor->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorVendedor">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
     <!-- Modal Visualizar Boleto -->
     <div class="modal fade" id="modalVisualizarBoleto" tabindex="1" role="dialog" aria-labelledby="modalVisualizarBoletosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg
            ." role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Boleto: <span id="visualizar_titulo"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10 offset-1">
                                Numero Boleto: <b class="text-info" id="visualizar_n_boleto"></b>
                                <br>
                                Empresa: <b class="text-info" id="visualizar_id_empresa"></b>
                                <br>
                                Vendedor: <b class="text-info" id="visualizar_id_usuario"></b>
                                <br>
                                Cliente: <b class="text-info" id="visualizar_cliente"></b>
                                <br>
                                Data de Venda: <b class="text-info" id="visualizar_data_venda"></b>
                                <br>
                                Data Limite: <b class="text-info" id="visualizar_periodo_fim"></b>
                                <br>
                                Valor: <b class="text-info" id="visualizar_valor"></b>
                                <br>
                                Valor Pago: <b class="text-info" id="visualizar_valor_pago"></b>
                                <br>
                                Data do Pagamento: <b class="text-info" id="visualizar_data_pago"></b>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Cadastrar Valor Pago -->
    <div class="modal fade" id="modalCadastrarValorPago" tabindex="1" role="dialog" aria-labelledby="modalCadastrarValorPagoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pagamento: <span id="pagar_titulo"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-dark">Valor a Ser Pago:<b class="text-info" id="pagar_valor"></b></h5>
                        <br>
                        <input type="hidden" name="id_financeiro_boleto" id="pagar_id_financeiro_boleto">
                        <input type="hidden" name="id_campanha" value="<?php echo $campanha->id_financeiro_campanha ?>">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <input type="hidden" name="id_campanha" value="<?php echo $c ?>">
                            <div class="col-4 offset-2">
                                <label for="pagar_valor_pago" class="form-label">Valor Pago *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor_pago" id="pagar_valor_pago" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="pagar_data_pago" class="form-label">Data do Pagamento *</label>
                                <input type="date" name="data_pago" id="pagar_data_pago" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnCadastrarValorPago">Pagar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Cadastrar Boleto -->
    <div class="modal fade" id="modalCadastrarBoleto" tabindex="1" role="dialog" aria-labelledby="modalCadastrarBoletoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Boleto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_campanha" value="<?php echo $campanha->id_financeiro_campanha ?>">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-3 offset-1">
                                <label for="n_boleto" class="form-label">Numero da Boleto *</label>
                                <input type="text" name="n_boleto" id="cadastrar_n_boleto" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_Empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="cadastrar_Empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="Vendedor" class="form-label">Vendedor *</label>
                                <select name="id_usuario" id="cadastrar_Vendedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="data_venda" class="form-label">Data do Boleto *</label>
                                <input type="date" name="data_venda" id="data_venda" class="form-control" value="<?php $today = date("Y-m-d"); echo $today ?>" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="valor" class="form-label">Cliente</label>
                                <input type="text" class="form-control" name="cliente" id="cadastrar_cliente">
                            </div>
                            <div class="col-3">
                                <label for="valor" class="form-label">Valor *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnCadastrar">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Boleto -->
    <div class="modal fade" id="modalEditarBoleto" tabindex="1" role="dialog" aria-labelledby="modalEditarBoletoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Boleto: <span id="editar_titulo"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_financeiro_boleto" id="editar_id_financeiro_boleto">
                        <input type="hidden" name="id_campanha" value="<?php echo $campanha->id_financeiro_campanha ?>">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-3 offset-1">
                                <label for="editar_n_boleto" class="form-label">Numero da Boleto *</label>
                                <input type="text" name="n_boleto" id="editar_n_boleto" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="editar_Empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="editar_Empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="editar_Vendedor" class="form-label">Vendedor *</label>
                                <select name="id_usuario" id="editar_Vendedor" class="form-control" required>
                                    <?php foreach($Usuario->listarVendedores() as $vendedores){ ?>
                                        <option value="<?php echo $vendedores->id_usuario ?>"><?php echo $vendedores->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="editar_data_venda" class="form-label">Data do Boleto *</label>
                                <input type="date" name="data_venda" id="editar_data_venda" class="form-control" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="editar_cliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" name="cliente" id="editar_cliente">
                            </div>
                            <div class="col-3">
                                <label for="editar_valor" class="form-label">Valor *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="editar_valor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Editar Campanha -->
    <div class="modal fade" id="modalEditarCampanha" tabindex="1" role="dialog" aria-labelledby="modalEditarCampanhaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Campanha: <span id="editar_campanha_titulo"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_financeiro_campanha" value="<?php echo $campanha->id_financeiro_campanha ?>">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-3 offset-1">
                                <label for="editar_nome" class="form-label">Nome Campanha *</label>
                                <input type="text" name="nome" id="editar_nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="editar_periodo_inicio" class="form-label">Do dia *</label>
                                <input type="date" name="periodo_inicio" id="editar_periodo_inicio" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="editar_periodo_fim" class="form-label">Até *</label>
                                <input type="date" name="periodo_fim" id="editar_periodo_fim" class="form-control" required>
                            </div>
                            <div class="col-3 offset-1 mt-2">
                                <label for="editar_data_pagamento" class="form-label">Dia Pagamento *</label>
                                <input type="date" name="data_pagamento" id="editar_data_pagamento" class="form-control" required>
                            </div>
                            <div class="col-3 mt-2">
                                <label for="editar_data_pagamento_pos" class="form-label">Dia Pagamento PÓS *</label>
                                <input type="date" name="data_pagamento_pos" id="editar_data_pagamento_pos" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditarCampanha">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Excluir Boleto-->
    <div class="modal fade" id="modalExcluirBoleto" tabindex="-1" role="dialog" aria-labelledby="modalExcluirBoletoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExcluirBoletoLabel">Excluir Boleto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja excluir o boleto: <b id="excluir_titulo"></b>?</p>
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_financeiro_boleto" id="excluir_id_financeiro_boleto">
                        <input type="hidden" name="id_campanha" value="<?php echo $c ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnExcluir">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#finan').addClass('active');
            $('#financeiro_atrasos').addClass('active');
            
            // Função para carregar vendedores via AJAX
            function carregarVendedores(idEmpresa, vendedorSelect, incluirTodos = true) {
                $.ajax({
                    url: 'listar_vendedores.php', // Arquivo PHP que vai retornar os vendedores
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
            
            // Atualiza a lista de vendedores no modal de cadastro
            $('#cadastrar_Empresa').change(function() {
                let idEmpresa = $(this).val();
                let vendedorSelect = $('#cadastrar_Vendedor');
                carregarVendedores(idEmpresa, vendedorSelect, false);
            });

            // Atualiza a lista de vendedores no modal de edição
            $('#editar_Empresa').change(function() {
                let idEmpresa = $(this).val();
                let vendedorSelect = $('#editar_Vendedor');
                carregarVendedores(idEmpresa, vendedorSelect, false);
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
                    var vendedor = $(this).find('td:eq(4)').text(); // ID do vendedor
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

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesCampanhas.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>