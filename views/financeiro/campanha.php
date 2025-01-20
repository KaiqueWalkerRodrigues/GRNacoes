<?php 
    $Financeiro_Campanha = new Financeiro_Campanha();
    $Financeiro_Boleto = new Financeiro_Boleto();
    $Usuario = new Usuario();

    if (isset($_POST['btnCadastrar'])) {
        $Financeiro_Boleto->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Financeiro_Boleto->editar($_POST);
    }
    if (isset($_POST['btnEditarCampanha'])) {
        $Financeiro_Campanha->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Financeiro_Boleto->deletar($_POST['id_financeiro_boleto'],$_POST['usuario_logado'],$_POST['id_campanha']);
    }
    if (isset($_POST['btnCadastrarValorPago'])) {
        $Financeiro_Boleto->CadastrarValorPago($_POST);
    }

    if (isset($_GET['btnGerarPorVendedor'])) {
        $id_campanha = isset($_GET['id_campanha']) ? $_GET['id_campanha'] : 0;
        $id_vendedor = isset($_GET['id_vendedor']) ? $_GET['id_vendedor'] : 0;
    
        $url = "relatorios/vendedor?id_campanha=" . $id_campanha . "&id_vendedor=" . $id_vendedor;

        header("Location: $url");
        exit(); 
    }

    $id = $_GET['id'];

    $campanha = $Financeiro_Campanha->mostrar($id);

    $pageTitle .= $campanha->nome;
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
                                <span>Boletos - <?php echo $campanha->nome ?></span>
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
                                <div class="col-2 offset-4 text-end text-light">
                                    <b>Início: <?php echo Helper::formatarData($campanha->periodo_inicio) ?></b>
                                    <br>
                                    <b>Fim: <?php echo Helper::formatarData($campanha->periodo_fim) ?></b>
                                    <br>
                                    <b>Pagamento: <?php echo Helper::formatarData($campanha->data_pagamento) ?></b>
                                    <br>
                                    <b>Pagamento Pós: <?php echo Helper::formatarData($campanha->data_pagamento_pos) ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header ml-1 row">Boletos
                            <div class="align-items-start">
                                <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarBoleto">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <div class="align-items-end ml-auto">
                                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalRelatorioPorVendedor">Relatório por Vendedor</button>
                                <button class="btn btn-success" onclick="document.getElementById('gerar_campanha').submit();">Gerar Relatório</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>N° Boleto</th>
                                            <th>Empresa</th>
                                            <th>Vendedor</th>
                                            <th class="d-none">ID Vendedor</th>
                                            <th>Cliente</th>
                                            <th>Data Venda</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0; foreach($Financeiro_Boleto->listar($campanha->id_financeiro_campanha) as $boleto){ $total+= $boleto->valor?>
                                        <tr>
                                        <td class="text-center"><?php echo $boleto->n_boleto ?></td>
                                            <td><?php echo Helper::mostrar_empresa($boleto->id_empresa) ?></td>
                                            <td><?php echo $Usuario->mostrar($boleto->id_usuario)->nome ?></td>
                                            <td class="d-none"><?php echo $boleto->id_usuario ?></td>
                                            <td><?php echo $boleto->cliente ?></td>
                                            <td><?php echo Helper::converterData($boleto->data_venda) ?></td>
                                            <td class="text-center">R$ <?php echo number_format($boleto->valor, 2, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <?php 
                                                    $hoje = date("Y-m-d");

                                                    if($boleto->valor_pago === $boleto->valor AND $boleto->data_pago<=$campanha->periodo_fim){
                                                        echo '<b class="badge badge-success badge-pill">Convertido</b>';
                                                    }elseif($boleto->valor_pago == 0 AND $hoje<=$campanha->periodo_fim){
                                                        echo '<b class="badge badge-dark badge-pill">Pendente</b>';
                                                    }elseif($boleto->valor_pago == 0 AND $hoje>=$campanha->periodo_fim){
                                                        echo '<b class="badge badge-danger badge-pill">Atrasado</b>';
                                                    }elseif($boleto->valor_pago === $boleto->valor AND $boleto->data_pago>$campanha->periodo_fim){
                                                        echo '<b class="badge badge-primary badge-pill">PÓS</b>';
                                                    }elseif($boleto->valor_pago > 0 AND $boleto->valor_pago != $boleto->valor AND $boleto->data_pago<=$campanha->periodo_fim){
                                                        echo '<b class="badge badge-warning badge-pill">Parcial</b>';
                                                    }elseif($boleto->valor_pago > 0 AND $boleto->valor_pago != $boleto->valor AND $boleto->data_pago>$campanha->periodo_fim){
                                                        echo '<b class="badge badge-warning badge-pill">PÓS Parcial</b>';
                                                    }else{
                                                        echo '<b class="badge badge-danger badge-pill">ERRO</b>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalCadastrarValorPago" class="collapse-item"
                                                    data-id_financeiro_boleto="<?php echo $boleto->id_financeiro_boleto ?>"
                                                    data-n_boleto="<?php echo $boleto->n_boleto ?>"
                                                    data-valor_pago="<?php echo $boleto->valor_pago ?>"
                                                    data-data_pago="<?php echo $boleto->data_pago ?>"
                                                    data-valor="<?php echo $boleto->valor ?>"
                                                >
                                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVisualizarBoleto" class="collapse-item"
                                                data-n_boleto="<?php echo $boleto->n_boleto ?>"
                                                data-empresa="<?php echo Helper::mostrar_empresa($boleto->id_empresa) ?>"
                                                data-usuario="<?php echo $Usuario->mostrar($boleto->id_usuario)->nome ?>"
                                                data-data_venda="<?php echo Helper::converterData($boleto->data_venda) ?>"
                                                data-periodo_fim="<?php echo Helper::converterData($campanha->periodo_fim) ?>"
                                                data-valor="<?php echo $boleto->valor ?>"
                                                data-cliente="<?php echo $boleto->cliente ?>"
                                                data-valor_pago="<?php echo $boleto->valor_pago ?>"
                                                data-data_pago="<?php if($boleto->data_pago){echo Helper::converterData($boleto->data_pago);} ?>"
                                                >
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarBoleto" class="collapse-item"
                                                data-id_financeiro_boleto="<?php echo $boleto->id_financeiro_boleto ?>"
                                                data-n_boleto="<?php echo $boleto->n_boleto ?>"
                                                data-empresa="<?php echo $boleto->id_empresa ?>"
                                                data-usuario="<?php echo $boleto->id_usuario ?>"
                                                data-cliente="<?php echo $boleto->cliente ?>"
                                                data-data_venda="<?php echo $boleto->data_venda ?>"
                                                data-valor="<?php echo $boleto->valor ?>"
                                                >
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>

                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarBoleto" class="collapse-item"
                                                data-id_financeiro_boleto="<?php echo $boleto->id_financeiro_boleto ?>"
                                                data-n_boleto="<?php echo $boleto->n_boleto ?>">
                                                    <i class="fa-solid fa-power-off"></i>
                                                </button>
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
                                            <td><b>R$ <span id="total_valor">0,00</span></b></td>
                                            <th></th>
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
    
    <form id="gerar_campanha" action="relatorios/campanha?id=<?php echo $campanha->id_financeiro_campanha ?>" method="post" style="display:none;"></form>

    <!-- Modal Relatório por Vendedor -->
    <div class="modal fade" id="modalRelatorioPorVendedor" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorVendedorLabel" aria-hidden="true">
        <form action="?" method="get">
            <div class="modal-dialog modal-lg" role="document">
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
                                    <?php foreach($Usuario->listarVendedoresAtivos() as $vendedor){ ?>
                                        <option value="<?php echo $vendedor->id_usuario ?>"><?php echo $vendedor->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorVendedor">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
     <!-- Modal Visualizar Boleto -->
     <div class="modal fade" id="modalVisualizarBoleto" tabindex="1" role="dialog" aria-labelledby="modalVisualizarBoletosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
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
                                Numero Boleto: <b class="text-primary" id="visualizar_n_boleto"></b>
                                <br>
                                Empresa: <b class="text-primary" id="visualizar_id_empresa"></b>
                                <br>
                                Vendedor: <b class="text-primary" id="visualizar_id_usuario"></b>
                                <br>
                                Cliente: <b class="text-primary" id="visualizar_cliente"></b>
                                <br>
                                Data de Venda: <b class="text-primary" id="visualizar_data_venda"></b>
                                <br>
                                Data Limite: <b class="text-primary" id="visualizar_periodo_fim"></b>
                                <br>
                                Valor: <b class="text-primary" id="visualizar_valor"></b>
                                <br>
                                Valor Pago: <b class="text-primary" id="visualizar_valor_pago"></b>
                                <br>
                                Data do Pagamento: <b class="text-primary" id="visualizar_data_pago"></b>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
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
                        <h5 class="text-dark">Valor a Ser Pago:<b class="text-primary" id="pagar_valor"></b></h5>
                        <br>
                        <input type="hidden" name="id_financeiro_boleto" id="pagar_id_financeiro_boleto">
                        <input type="hidden" name="id_campanha" value="<?php echo $campanha->id_financeiro_campanha ?>">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <input type="hidden" name="id_campanha" value="<?php echo $id ?>">
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
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Boleto-->
    <div class="modal fade" id="modalDeletarBoleto" tabindex="-1" role="dialog" aria-labelledby="modalDeletarBoletoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarBoletoLabel">Deletar Boleto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja Deletar o boleto: <b id="deletar_titulo"></b>?</p>
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_financeiro_boleto" id="deletar_id_financeiro_boleto">
                        <input type="hidden" name="id_campanha" value="<?php echo $id ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnDeletar">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#fina').addClass('active')
            $('#financeiro_campanhas').addClass('active')

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
                    var vendedor = $(this).find('td:eq(3)').text(); // ID do vendedor
                    var empresa = $(this).find('td:eq(1)').text();

                    // Verificar se a linha atende aos critérios de filtragem
                    if ((filtroVendedor && vendedor !== filtroVendedor) || 
                        (filtroEmpresa && empresa !== filtroEmpresa)) {
                        $(this).hide(); // Ocultar a linha se não atender aos critérios
                    }
                });
                recalcularTotal();
            });

            $('#modalVisualizarBoleto').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let n_boleto = button.data('n_boleto');
                let empresa = button.data('empresa');
                let usuario = button.data('usuario');
                let cliente = button.data('cliente');
                let data_venda = button.data('data_venda');
                let periodo_fim = button.data('periodo_fim');
                let valor = button.data('valor');
                let valor_pago = button.data('valor_pago');
                let data_pago = button.data('data_pago');

                // Caso data_pago esteja vazia, definir como "--"
                if (!data_pago) {
                    data_pago = "--";
                }

                $('#visualizar_n_boleto').text(n_boleto);
                $('#visualizar_titulo').text(n_boleto);
                $('#visualizar_id_empresa').text(empresa);
                $('#visualizar_id_usuario').text(usuario);
                $('#visualizar_cliente').text(cliente);
                $('#visualizar_data_venda').text(data_venda);
                $('#visualizar_periodo_fim').text(periodo_fim);
                $('#visualizar_valor').text("R$ " + valor);
                $('#visualizar_valor_pago').text("R$ " + valor_pago);
                $('#visualizar_data_pago').text(data_pago);
            });

            $('#modalEditarBoleto').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_financeiro_boleto = button.data('id_financeiro_boleto');
                let n_boleto = button.data('n_boleto');
                let empresa = button.data('empresa');
                let usuario = button.data('usuario');
                let cliente = button.data('cliente');
                let data_venda = button.data('data_venda');
                let valor = button.data('valor');
                let valor_pago = button.data('valor_pago');
                let data_pago = button.data('data_pago');

                $('#editar_id_financeiro_boleto').val(id_financeiro_boleto);
                $('#editar_n_boleto').val(n_boleto);
                $('#editar_Empresa').val(empresa);
                $('#editar_Vendedor').val(usuario);
                $('#editar_cliente').val(cliente);
                $('#editar_data_venda').val(data_venda);
                $('#editar_valor').val(valor);
                $('#editar_valor_pago').val(valor_pago);
                $('#editar_data_pago').val(data_pago);
                $('#editar_titulo').text(n_boleto);
            });

            $('#modalEditarCampanha').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let nome = button.data('nome');
                let periodo_inicio = button.data('periodo_inicio');
                let periodo_fim = button.data('periodo_fim');
                let data_pagamento = button.data('data_pagamento');
                let data_pagamento_pos = button.data('data_pagamento_pos');

                $('#editar_nome').val(nome);
                $('#editar_periodo_inicio').val(periodo_inicio);
                $('#editar_periodo_fim').val(periodo_fim);
                $('#editar_data_pagamento').val(data_pagamento);
                $('#editar_data_pagamento_pos').val(data_pagamento_pos);
            });

            $('#modalDeletarBoleto').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_financeiro_boleto = button.data('id_financeiro_boleto');
                let n_boleto = button.data('n_boleto');

                $('#deletar_id_financeiro_boleto').val(id_financeiro_boleto);
                $('#deletar_titulo').text(n_boleto);
            });

            $('#modalCadastrarValorPago').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_financeiro_boleto = button.data('id_financeiro_boleto');
                let n_boleto = button.data('n_boleto');
                let valor_pago = button.data('valor_pago');
                let data_pago = button.data('data_pago');
                let valor = button.data('valor')

                $('#pagar_id_financeiro_boleto').val(id_financeiro_boleto);
                $('#pagar_valor_pago').val(valor_pago)
                $('#pagar_data_pago').val(data_pago)
                $('#pagar_titulo').text(n_boleto);
                $('#pagar_valor').text(' R$ '+valor);
            });


            function recalcularTotal() {
                var total = 0;

                // Iterar sobre todas as linhas visíveis da tabela
                $('#dataTable tbody tr:visible').each(function() {
                    var valor = parseFloat($(this).find('td:eq(6)').text().replace('R$', '').replace('.', '').replace(',', '.'));
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
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-boletos.js"></script>
</body>

</html>