<?php
    $Faturamento_Competencia = new Faturamento_Competencia();
    $Faturamento_Nota_Servico = new Faturamento_Nota_Servico();
    $Convenio = new Convenio();
    $Usuario = new Usuario();

    if (isset($_POST['btnCadastrarNota'])) {
        $Faturamento_Nota_Servico->cadastrar($_POST);
    }
    if (isset($_POST['btnEditarNota'])) {
        $Faturamento_Nota_Servico->editar($_POST);
    }
    if (isset($_POST['btnEditarPagamento'])) {
        $Faturamento_Nota_Servico->editarPagamento($_POST);
    }
    if (isset($_POST['btnDeletarNota'])) {
        $Faturamento_Nota_Servico->deletar($_POST['id_faturamento_nota_servico'], $_POST['usuario_logado'], $_POST['id_competencia']);
    }

    $id = $_GET['id'];
    $competencia = $Faturamento_Competencia->mostrar($id);
    $pageTitle .= $competencia->nome;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $pageTitle ?></title>
    <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
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
                                <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                <span><?php echo $competencia->nome ?></span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-2 offset-8 text-end text-light">
                                    <b>Início: <?php echo Helper::formatarData($competencia->periodo_inicio) ?></b>
                                    <br>
                                    <b>Fim: <?php echo Helper::formatarData($competencia->periodo_fim) ?></b>
                                    <br>
                                    <b>Pagamento: <?php echo Helper::formatarData($competencia->mes_pagamento) ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header ml-1 row">Notas de Serviço
                            <div class="align-items-start">
                                <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarNotaServico">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable-competencia" width="100%" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Convênio</th>
                                            <th>Pagamento Previsto</th>
                                            <th>BF/NF</th>
                                            <th>Valor Faturado</th>
                                            <th>Valor Imposto</th>
                                            <th>Valor a Receber</th>
                                            <th>Valor Pago</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total = 0; 
                                        $total_imposto = 0;
                                        $total_pago = 0;
                                        $total_a_receber = 0;
                                        foreach($Faturamento_Nota_Servico->listar($competencia->id_faturamento_competencia) as $nota){
                                            $valor_a_receber = $nota->valor_faturado-$nota->valor_imposto;
                                            $total += $nota->valor_faturado;
                                            $total_imposto += $nota->valor_imposto;
                                            $total_pago += $nota->valor_pago;
                                            $total_a_receber += $valor_a_receber;
                                            if($nota->valor_pago == $nota->valor_faturado){
                                                $status = 1;
                                            }elseif(($nota->valor_pago != $nota->valor_faturado) && (new \DateTime($nota->data_pagamento_previsto) < new \DateTime('today'))){
                                                if($nota->feedback != '0000-00-00'){
                                                    $status = 3;
                                                    $valor_glosa = $valor_a_receber - $nota->valor_pago;
                                                }else{
                                                    $status = 2;
                                                    $valor_glosa = $valor_a_receber - $nota->valor_pago;
                                                }
                                            }else{
                                                $status = 0;
                                            } 
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $Convenio->mostrar($nota->id_convenio)->convenio; switch($nota->tipo){ case 0: echo ""; break; case 1: echo " (Consultas)"; break; case 3: echo " (Exames)"; break; }?></td>
                                            <td><?php echo Helper::formatarData($nota->data_pagamento_previsto) ?></td>
                                            <td><?php echo $nota->bf_nf ?></td>
                                            <td>R$ <?php echo number_format($nota->valor_faturado, 2, ',', '.'); ?></td>
                                            <td>R$ <?php echo number_format($nota->valor_imposto, 2, ',', '.'); ?></td>
                                            <td>R$ <?php echo number_format($valor_a_receber, 2, ',', '.'); ?></td>
                                            <td><?php if($nota->valor_pago > 0){ ?>R$ <?php echo number_format($nota->valor_pago, 2, ',', '.'); ?><?php }else{ echo "R$ 0,00"; } ?></td>
                                            <td>
                                                <?php 
                                                    switch($status){
                                                        case 0:
                                                            echo "<b class='badge badge-dark badge-pill'>Pendente</b>";
                                                        break;
                                                        case 1:
                                                            echo "<b class='badge badge-success badge-pill'>OK</b>";
                                                        break;
                                                        case 2:
                                                            echo "<b class='badge badge-danger badge-pill'>R$ ".$valor_glosa."</b>";
                                                        break;
                                                        case 3:
                                                            echo "<b class='badge badge-warning badge-pill'>R$ ".$valor_glosa."</b>";
                                                        break;
                                                    }
                                                ?>
                                             </td>
                                            <td>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVisualizarNotaServico"
                                                    data-id_faturamento_nota_servico="<?php echo $nota->id_faturamento_nota_servico ?>"
                                                    data-id_convenio="<?php echo $Convenio->mostrar($nota->id_convenio)->convenio ?>"
                                                    data-tipo="<?php switch($nota->tipo){ case 0: echo "Tudo"; break; case 1: echo "Consultas"; break; case 3: echo "Exames"; break; } ?>"
                                                    data-bf_nf="<?php echo $nota->bf_nf ?>"
                                                    data-valor_faturado="<?php echo $nota->valor_faturado ?>"
                                                    data-valor_imposto="<?php echo $nota->valor_imposto ?>"
                                                    data-valor_pago="<?php if($nota->valor_pago > 0){ echo $nota->valor_pago; }else{ echo '0'; } ?>"
                                                    data-data_pagamento_previsto="<?php echo Helper::formatarData($nota->data_pagamento_previsto) ?>"
                                                    data-data_pago="<?php echo $nota->data_pago ?>"
                                                    data-feedback="<?php echo $nota->feedback ?>"
                                                    data-status="<?php 
                                                        switch($status){
                                                            case 0:
                                                                echo "<b class='badge badge-dark badge-pill'>Pendente</b>";
                                                            break;
                                                            case 1:
                                                                echo "<b class='badge badge-success badge-pill'>OK</b>";
                                                            break;
                                                            case 2:
                                                                echo "<b class='badge badge-danger badge-pill'>R$ ".$valor_glosa."</b>";
                                                            break;
                                                        }
                                                    ?>"
                                                >
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <?php if(verificarSetor([1,12,14])){ ?>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarPagamento"
                                                    data-id_faturamento_nota_servico="<?php echo $nota->id_faturamento_nota_servico ?>"
                                                    data-id_competencia="<?php echo $competencia->id_faturamento_competencia ?>"
                                                    data-valor_pago="<?php echo $nota->valor_pago ?>"
                                                    data-data_pago="<?php echo $nota->data_pago ?>"
                                                >
                                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                                </button>
                                                <?php } ?>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarNotaServico"
                                                    data-id_faturamento_nota_servico="<?php echo $nota->id_faturamento_nota_servico ?>"
                                                    data-id_convenio="<?php echo $nota->id_convenio ?>"
                                                    data-tipo="<?php echo $nota->tipo ?>"
                                                    data-bf_nf="<?php echo $nota->bf_nf ?>"
                                                    data-valor_faturado="<?php echo $nota->valor_faturado ?>"
                                                    data-valor_imposto="<?php echo $nota->valor_imposto ?>"
                                                    data-valor_pago="<?php echo $nota->valor_pago ?>"
                                                    data-data_pagamento_previsto="<?php echo $nota->data_pagamento_previsto ?>"
                                                    data-data_pago="<?php echo $nota->data_pago ?>"
                                                    data-feedback="<?php echo $nota->feedback ?>"
                                                >
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarNotaServico"
                                                    data-id_faturamento_nota_servico="<?php echo $nota->id_faturamento_nota_servico ?>"
                                                    data-bf_nf="<?php echo $nota->bf_nf ?>"
                                                    data-id_competencia="<?php echo $nota->id_competencia ?>">
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
                                            <th class="text-center">R$ <span id="total_valor"><?php echo number_format($total, 2, ',', '.'); ?></span></th>
                                            <th class="text-center">R$ <span id="total_imposto"><?php echo number_format($total_imposto, 2, ',', '.'); ?></span></th>
                                            <th class="text-center">R$ <span id="total_a_receber"><?php echo number_format($total_a_receber, 2, ',', '.'); ?></span></th>
                                            <th class="text-center">R$ <span id="total_pago"><?php echo number_format($total_pago, 2, ',', '.'); ?></span></th>
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

    <!-- Modal Cadastrar Nota de Serviço -->
    <div class="modal fade" id="modalCadastrarNotaServico" tabindex="1" role="dialog" aria-labelledby="modalCadastrarNotaServicoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nota de Serviço</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <input type="hidden" name="id_competencia" value="<?php echo $competencia->id_faturamento_competencia ?>">
                            <div class="col-4 offset-2">
                                <label for="id_convenio" class="form-label">Convênio *</label>
                                <select name="id_convenio" id="cadastrar_id_convenio" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Convenio->listarMenosParticular() as $convenio){ ?>
                                        <option value="<?php echo $convenio->id_convenio ?>"><?php echo $convenio->convenio ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select name="tipo" id="cadastrar_tipo" class="form-control">
                                    <option value="0">Tudo</option>
                                    <option value="1">Consultas</option>
                                    <option value="2">Exames</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="bf_nf" class="form-label">NF *</label>
                                <input type="text" name="bf_nf" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2 offset-1">
                                <label for="valor_faturado" class="form-label">Valor Faturado *</label>
                                <input type="number" step="0.01" id="cadastrar_valor_faturado" name="valor_faturado" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="valor_imposto" class="form-label">Valor Imposto</label>
                                <input type="number" step="0.01" id="cadastrar_valor_imposto" name="valor_imposto" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="data_pagamento_previsto" class="form-label">Data Pagamento Previsto</label>
                                <input type="date" name="data_pagamento_previsto" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="feedback" class="form-label">Data Feedback Feedback</label>
                                <input type="date" name="feedback" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnCadastrarNota">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Nota de Serviço -->
    <div class="modal fade" id="modalEditarNotaServico" tabindex="1" role="dialog" aria-labelledby="modalEditarNotaServicoLabel" aria-hidden="true">
        <form action="?id=<?php echo $id; ?>" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Nota de Serviço</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_faturamento_nota_servico" id="editar_id_faturamento_nota_servico">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <input type="hidden" name="id_competencia" id="editar_id_competencia" value="<?php echo $competencia->id_faturamento_competencia ?>">
                            <div class="col-4 offset-2">
                                <label for="id_convenio" class="form-label">Convênio *</label>
                                <select name="id_convenio" id="editar_id_convenio" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Convenio->listarMenosParticular() as $convenio){ ?>
                                        <option value="<?php echo $convenio->id_convenio ?>"><?php echo $convenio->convenio ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select name="tipo" id="editar_tipo" class="form-control">
                                    <option value="0">Tudo</option>
                                    <option value="1">Consultas</option>
                                    <option value="2">Exames</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="bf_nf" class="form-label">NF *</label>
                                <input type="text" name="bf_nf" id="editar_bf_nf" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2 offset-1">
                                <label for="valor_faturado" class="form-label">Valor Faturado *</label>
                                <input type="number" step="0.01" id="editar_valor_faturado" name="valor_faturado" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="valor_imposto" class="form-label">Valor Imposto</label>
                                <input type="number" step="0.01" id="editar_valor_imposto" name="valor_imposto" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="data_pagamento_previsto" class="form-label">Data Pagamento Previsto</label>
                                <input type="date" name="data_pagamento_previsto" id="editar_data_pagamento_previsto" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="feedback" class="form-label">Data Feedback Feedback</label>
                                <input type="date" name="feedback" id="editar_feedback" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditarNota">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Pagamento Nota de Serviço -->
    <div class="modal fade" id="modalEditarPagamento" tabindex="1" role="dialog" aria-labelledby="modalEditarPagamentoLabel" aria-hidden="true">
        <form action="?id=<?php echo $id; ?>" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Pagamento da  Nota de Serviço</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_faturamento_nota_servico" id="editar_pagamento_id_faturamento_nota_servico">
                        <input type="hidden" name="id_competencia" id="editar_pagamento_id_competencia" value="<?php echo $competencia->id_faturamento_competencia ?>">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="offset-1 col-5">
                                <label for="editar_pagamento_valor_pago" class="form-label">Valor Pago *</label>
                                <input type="text" name="valor_pago" id="editar_pagamento_valor_pago" class="form-control" required>
                            </div>
                            <div class="col-5">
                                <label for="editar_pagamento_data_pago" class="form-label">Data Pagamento *</label>
                                <input type="date" name="data_pago" id="editar_pagamento_data_pago" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnEditarPagamento">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Visualizar Nota de Serviço -->
    <div class="modal fade" id="modalVisualizarNotaServico" tabindex="1" role="dialog" aria-labelledby="modalVisualizarNotaServicoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Visualizar Nota de Serviço</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10 offset-1">
                            Convênio: <b class="text-primary" id="visualizar_id_convenio"></b>
                            <br>
                            Tipo: <b class="text-primary" id="visualizar_tipo"></b>
                            <br>
                            NF: <b class="text-primary" id="visualizar_bf_nf"></b>
                            <br>
                            Valor Faturado: <b class="text-primary" id="visualizar_valor_faturado"></b>
                            <br>
                            Valor Imposto: <b class="text-primary" id="visualizar_valor_imposto"></b>
                            <br>
                            Valor Pago: <b class="text-primary" id="visualizar_valor_pago"></b>
                            <br>
                            Data Pagamento Previsto: <b class="text-primary" id="visualizar_data_pagamento_previsto"></b>
                            <br>
                            Data Pago: <b class="text-primary" id="visualizar_data_pago"></b>
                            <br>
                            Status: <b class="text-primary" id="visualizar_status"></b>
                            <br>
                            Feedback: <b class="text-primary" id="visualizar_feedback"></b>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deletar Nota de Serviço -->
    <div class="modal fade" id="modalDeletarNotaServico" tabindex="-1" role="dialog" aria-labelledby="modalDeletarNotaServicoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarNotaServicoLabel">Deletar Nota de Serviço</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja deletar a nota: <b id="deletar_bf_nf"></b>?</p>
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_faturamento_nota_servico" id="deletar_id_faturamento_nota_servico">
                        <input type="hidden" name="id_competencia" id="deletar_id_competencia">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnDeletarNota">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#fatu').addClass('active');
            $('#faturamento_competencias').addClass('active');

            $('#modalVisualizarNotaServico').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                $('#visualizar_id_convenio').text(button.data('id_convenio'));
                $('#visualizar_tipo').text(button.data('tipo'));
                $('#visualizar_bf_nf').text(button.data('bf_nf'));
                $('#visualizar_valor_faturado').text("R$ " + parseFloat(button.data('valor_faturado')).toLocaleString('pt-BR', {minimumFractionDigits:2}));
                $('#visualizar_valor_imposto').text("R$ " + parseFloat(button.data('valor_imposto')).toLocaleString('pt-BR', {minimumFractionDigits:2}));
                $('#visualizar_valor_pago').text("R$ " + parseFloat(button.data('valor_pago')).toLocaleString('pt-BR', {minimumFractionDigits:2}));
                $('#visualizar_data_pagamento_previsto').text(button.data('data_pagamento_previsto'));
                $('#visualizar_data_pago').text(button.data('data_pago'));
                $('#visualizar_status').html(button.data('status'));
                var feedback = button.data('feedback');

                if (feedback && feedback !== '0000-00-00') {
                    $('#visualizar_feedback').text(feedback);
                } else {
                    $('#visualizar_feedback').text('');
                }
            });

            $('#modalEditarNotaServico').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                $('#editar_id_faturamento_nota_servico').val(button.data('id_faturamento_nota_servico'));
                $('#editar_id_convenio').val(button.data('id_convenio'));
                $('#editar_tipo').val(button.data('tipo'));
                $('#editar_bf_nf').val(button.data('bf_nf'));
                $('#editar_valor_faturado').val(button.data('valor_faturado'));
                $('#editar_valor_imposto').val(button.data('valor_imposto'));
                $('#editar_valor_pago').val(button.data('valor_pago'));
                $('#editar_data_pagamento_previsto').val(button.data('data_pagamento_previsto'));
                $('#editar_data_pago').val(button.data('data_pago'));
                $('#editar_status').val(button.data('status'));
                $('#editar_feedback').val(button.data('feedback'));
            });

            $('#modalEditarPagamento').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                $('#editar_pagamento_id_faturamento_nota_servico').val(button.data('id_faturamento_nota_servico'));
                $('#editar_pagamento_id_competencia').val(button.data('id_competencia'));
                $('#editar_pagamento_valor_pago').val(button.data('valor_pago'));
                $('#editar_pagamento_data_pago').val(button.data('data_pago'));
            });

            $('#modalDeletarNotaServico').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                $('#deletar_id_faturamento_nota_servico').val(button.data('id_faturamento_nota_servico'));
                $('#deletar_bf_nf').text(button.data('bf_nf'));
                $('#deletar_id_competencia').val(button.data('id_competencia'));
            });

            $('#cadastrar_valor_faturado').on('keyup change', function() {
                const valorFaturado = parseFloat($(this).val());

                if (!isNaN(valorFaturado) && valorFaturado > 0) {
                    const imposto = valorFaturado * 0.0615;

                    const valorImposto = imposto.toFixed(2);
                    $('#cadastrar_valor_imposto').val(valorImposto);
                } else {
                    $('#cadastrar_valor_imposto').val('');
                }
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-notas_servicos.js"></script>
</body>
</html>