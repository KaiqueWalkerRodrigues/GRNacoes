<?php 
    $Lc_fornecedor = new Lente_contato_fornecedor();
    $Lc_orcamento   = new Lente_contato_Orcamento();
    $Lc_modelo      = new Lente_contato_Modelo();
    $Medico         = new Medico();

    if (isset($_POST['btnCadastrar'])) {
        $Lc_orcamento->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_orcamento->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_orcamento->deletar($_POST['id_orcamento'], $_POST['usuario_logado']);
    }

    function forma_pgto($id){
        switch ($id) {
            case 1:
                return "Crédito";
            break;
            case 2:
                return "Débito";
            break;
            case 3:
                return "Boleto";
            break;
            case 4:
                return "Pix";
            break;
            case 5:
                return "Dinheiro";
            break;
        }
    }
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
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
</head>
<body class="nav-fixed">
    <!-- Tela de Carregamento -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <!-- Cabeçalho da Página -->
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Orçamento Lente de Contato</span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="filtroMes" class="form-label text-light">Filtrar por Mês:</label>
                                    <input class="form-control" type="month" name="filtroMes" id="filtroMes">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroLente" class="form-label text-light">Filtrar por Lente:</label>
                                    <select id="filtroLente" class="form-control">
                                        <option value="">Todas</option>
                                        <?php foreach($Lc_modelo->listar() as $modelo){ ?>
                                            <option value="<?php echo $modelo->modelo ?>"><?php echo $modelo->modelo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroStatus" class="form-label text-light">Filtrar por Status:</label>
                                    <select id="filtroStatus" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Pendente">Pendente</option>
                                        <option value="Pago">Pago</option>
                                        <option value="Entregue">Entregue</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabela de Orçamentos -->
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Orçamentos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarOrcamento">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="tabelaOrcamentos" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="d-none">Mes</th>
                                            <th>Empresa</th>
                                            <th>Paciente</th>
                                            <th>Status</th>
                                            <th>Lentes</th>
                                            <th>Data/Hora</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(verificarSetor([1,3,5,12])){
                                                $orcamentos = $Lc_orcamento->listarAdmin($_SESSION['id_empresa']);
                                            } else {
                                                $orcamentos = $Lc_orcamento->listar($_SESSION['id_empresa']);
                                            }
                                            foreach($orcamentos as $orcamento){
                                        ?>
                                        <tr>
                                            <td><?php echo $orcamento->id_lente_contato_orcamento ?></td>
                                            <td class="d-none"><?php echo Helper::formatarDataSemHorario($orcamento->created_at) ?></td>
                                            <td><?php echo Helper::mostrar_empresa($orcamento->id_empresa) ?></td>
                                            <td><?php echo $orcamento->nome ?></td>
                                            <td class="text-center">
                                                <?php if($orcamento->status == 2) { ?>
                                                    <b class="badge badge-success badge-pill">Entregue</b>
                                                <?php } elseif($orcamento->status == 1){ ?>
                                                    <b class="badge badge-secondary badge-pill">Pago</b>
                                                <?php } else { ?>
                                                    <b class="badge badge-warning badge-pill">Pendente</b>
                                                <?php } ?>
                                            </td>
                                            <td><b>Direita:</b> <?php if($orcamento->id_modelo_direito != Null){ echo $Lc_modelo->mostrar($orcamento->id_modelo_direito)->modelo; } ?> <br> <b>Esquerda:</b> <?php if($orcamento->id_modelo_esquerdo != Null){echo $Lc_modelo->mostrar($orcamento->id_modelo_esquerdo)->modelo; } ?></td>
                                            <td><?php echo Helper::formatarData($orcamento->created_at) ?></td>
                                            <td class="text-center">
                                                <?php 
                                                    if(isset($orcamento->cv_pgto1) || isset($orcamento->cv_pgto2)){
                                                ?>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalCvOrcamento"
                                                            data-id_orcamento="<?php echo $orcamento->id_lente_contato_orcamento ?>"
                                                            data-nome="<?php echo $orcamento->nome ?>"
                                                            data-valor="<?php echo $orcamento->valor ?>"
                                                            data-cv_pgto1="<?php echo $orcamento->cv_pgto1 ?>"
                                                            data-cv_pgto2="<?php echo $orcamento->cv_pgto2 ?>"
                                                            data-forma_pgto1="<?php echo forma_pgto($orcamento->id_forma_pagamento1) ?>"
                                                            data-forma_pgto2="<?php echo forma_pgto($orcamento->id_forma_pagamento2) ?>">
                                                        <i class="fa-solid fa-credit-card"></i>
                                                    </button>
                                                <?php
                                                    }
                                                ?>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarOrcamento"
                                                        data-id_orcamento="<?php echo $orcamento->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $orcamento->nome ?>"
                                                        data-cpf="<?php echo $orcamento->cpf ?>"
                                                        data-contato="<?php echo $orcamento->contato ?>"
                                                        data-id_medico="<?php echo $orcamento->id_medico ?>"
                                                        data-olhos="<?php echo $orcamento->olhos ?>"
                                                        data-olho_esquerdo="<?php echo $orcamento->olho_esquerdo ?>"
                                                        data-olho_direito="<?php echo $orcamento->olho_direito ?>"
                                                        data-lente_esquerda="<?php echo $orcamento->id_modelo_esquerdo ?>"
                                                        data-lente_direita="<?php echo $orcamento->id_modelo_direito ?>"
                                                        data-qnt_esquerda="<?php echo $orcamento->qnt_esquerda ?>"
                                                        data-qnt_direita="<?php echo $orcamento->qnt_direita ?>"
                                                        data-valor="<?php echo $orcamento->valor ?>"
                                                        data-forma_pgto1="<?php echo $orcamento->id_forma_pagamento1 ?>"
                                                        data-forma_pgto2="<?php echo $orcamento->id_forma_pagamento2 ?>"
                                                        data-cv_pgto1="<?php echo $orcamento->cv_pgto1 ?>"
                                                        data-cv_pgto2="<?php echo $orcamento->cv_pgto2 ?>"
                                                        data-observacao="<?php echo $orcamento->observacao ?>"
                                                        data-status="<?php echo $orcamento->status ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarOrcamento"
                                                        data-id_orcamento="<?php echo $orcamento->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $orcamento->nome ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Cadastrar Orçamento -->
    <div class="modal fade" id="modalCadastrarOrcamento" tabindex="1" role="dialog" aria-labelledby="modalCadastrarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Orçamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Dados do Usuário e Empresa -->
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        
                        <div class="row">
                            <!-- Ficha Paciente -->
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-4 offset-1 mb-2">
                                <label for="cadastrar_nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="cadastrar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_cpf" class="form-label">CPF *</label>
                                <input type="text" id="cadastrar_cpf" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_contato" class="form-label">Contato *</label>
                                <input type="text" id="cadastrar_contato" name="contato" class="form-control" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="cadastrar_id_medico" class="form-label">Médico Solicitante</label>
                                <select id="cadastrar_id_medico" name="id_medico" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <!-- Ficha Lente -->
                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <!-- Seleção de Olho(s) -->
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_olhos" class="form-label">Olho(s) *</label>
                                <select name="olhos" id="cadastrar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="2">Somente Olho Direito</option>
                                    <option value="1">Somente Olho Esquerdo</option>
                                </select>
                            </div>
                            <div class="col-1 mt-1 d-none" id="cadastrar_copiar">
                                <br>
                                <button id="cadastrar_copiar_botao" class="btn btn-icon btn-secondary" type="button">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Grupo para Olho Direito -->
                        <div class="row d-none" id="grupo_olho_direito">
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_fornecedor_direito" class="form-label">Fornecedor Direito *</label>
                                <select name="fornecedor_direito" id="cadastrar_fornecedor_direito" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="cadastrar_lente_direita" class="form-label">Lente Direita</label>
                                <select name="lente_direita" id="cadastrar_lente_direita" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX conforme o fornecedor selecionado -->
                                </select>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="cadastrar_olho_direito" class="form-label">Olho Direito</label>
                                <input type="text" name="olho_direito" id="cadastrar_olho_direito" class="form-control">
                            </div>
                            <div class="col-1 mb-2">
                                <label for="cadastrar_qnt_direita" class="form-label">Quantidade</label>
                                <input type="number" name="qnt_direita" id="cadastrar_qnt_direita" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        <!-- Grupo para Olho Esquerdo -->
                        <div class="row d-none" id="grupo_olho_esquerdo">
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_fornecedor_esquerdo" class="form-label">Fornecedor Esquerdo</label>
                                <select name="fornecedor_esquerdo" id="cadastrar_fornecedor_esquerdo" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="cadastrar_lente_esquerda" class="form-label">Lente Esquerda</label>
                                <select name="lente_esquerda" id="cadastrar_lente_esquerda" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX conforme o fornecedor selecionado -->
                                </select>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="cadastrar_olho_esquerdo" class="form-label">Olho Esquerdo</label>
                                <input type="text" name="olho_esquerdo" id="cadastrar_olho_esquerdo" class="form-control">
                            </div>
                            <div class="col-1 mb-2">
                                <label for="cadastrar_qnt_esquerda" class="form-label">Quantidade</label>
                                <input type="number" name="qnt_esquerda" id="cadastrar_qnt_esquerda" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Campo para o Valor Total (calculado com base nos valores das lentes e quantidades) -->
                            <div class="col-4 offset-1 text-center mt-1">
                                <label for="cadastrar_valor" class="form-label">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor" required>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="cadastrar_forma_pgto1" class="form-label">Forma Pagamento 1</label>
                                <select name="forma_pgto1" id="cadastrar_forma_pgto1" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                    <option value="5">Dinheiro</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="cadastrar_forma_pgto2" class="form-label">Forma Pagamento 2</label>
                                <select name="forma_pgto2" id="cadastrar_forma_pgto2" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                    <option value="5">Dinheiro</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="cadastrar_status" class="form-label">Status *</label>
                                <select name="status" id="cadastrar_status" class="form-control" required>
                                    <option value="0">Pendente</option>
                                    <option value="1">Pago</option>
                                    <option value="2">Entregue</option>
                                </select>
                            </div>
                            <div class="col-10 mt-2 offset-1">
                                <label for="cadastrar_observacao" class="form-label">Observacao</label>
                                <textarea class="form-control" name="observacao" id="cadastrar_observacao"></textarea>
                            </div>
                        </div>
                        <!-- Row para CV da Forma de Pagamento 1 -->
                        <div class="row d-none" id="cv_forma_pgto">
                            <div class="d-none col-3 offset-1" id="cv_forma_pgto1">
                                <label for="cv_input_pgto1">CV - Forma de Pagamento 1 *</label>
                                <input type="text" id="cv_input_pgto1" name="cv_pgto1" class="form-control">
                            </div>

                            <div class="d-none col-3 offset-1" id="cv_forma_pgto2">
                                <label for="cv_input_pgto2">CV - Forma de Pagamento 2 *</label>
                                <input type="text" id="cv_input_pgto2" name="cv_pgto2" class="form-control">
                            </div>
                        </div>
                    </div><!-- Fim do modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
                    </div>
                </div><!-- Fim do modal-content -->
            </div><!-- Fim do modal-dialog -->
        </form>
    </div>

    <!-- Modal Editar Orçamento -->
    <div class="modal fade" id="modalEditarOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalEditarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <!-- Cabeçalho do Modal -->
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Orçamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Corpo do Modal -->
                    <div class="modal-body">
                        <!-- Dados Ocultos -->
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        <input type="hidden" name="id_orcamento" id="editar_id_orcamento">
                        
                        <div class="row">
                            <!-- Ficha Paciente -->
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-4 offset-1 mb-2">
                                <label for="editar_nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="editar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="editar_cpf" class="form-label">CPF *</label>
                                <input type="text" id="editar_cpf" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="editar_contato" class="form-label">Contato *</label>
                                <input type="text" id="editar_contato" name="contato" class="form-control" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="editar_id_medico" class="form-label">Médico Solicitante</label>
                                <select id="editar_id_medico" name="id_medico" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <!-- Ficha Lente -->
                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <!-- Seleção de Olho(s) -->
                            <div class="col-3 offset-1 mb-2">
                                <label for="editar_olhos" class="form-label">Olho(s) *</label>
                                <select name="olhos" id="editar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="2">Somente Olho Direito</option>
                                    <option value="1">Somente Olho Esquerdo</option>
                                </select>
                            </div>
                        </div>
                        <!-- Grupo para Olho Direito -->
                        <div class="row d-none" id="editar_grupo_olho_direito">
                            <div class="col-3 offset-1 mb-2">
                                <label for="editar_fornecedor_direito" class="form-label">Fornecedor Direito *</label>
                                <select name="fornecedor_direito" id="editar_fornecedor_direito" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="editar_lente_direita" class="form-label">Lente Direita</label>
                                <select name="lente_direita" id="editar_lente_direita" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX -->
                                </select>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="editar_olho_direito" class="form-label">Olho Direito</label>
                                <input type="text" name="olho_direito" id="editar_olho_direito" class="form-control">
                            </div>
                            <div class="col-1 mb-2">
                                <label for="editar_qnt_direita" class="form-label">Quantidade</label>
                                <input type="number" name="qnt_direita" id="editar_qnt_direita" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        <!-- Grupo para Olho Esquerdo -->
                        <div class="row d-none" id="editar_grupo_olho_esquerdo">
                            <div class="col-3 offset-1 mb-2">
                                <label for="editar_fornecedor_esquerdo" class="form-label">Fornecedor Esquerdo</label>
                                <select name="fornecedor_esquerdo" id="editar_fornecedor_esquerdo" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="editar_lente_esquerda" class="form-label">Lente Esquerda</label>
                                <select name="lente_esquerda" id="editar_lente_esquerda" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX -->
                                </select>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="editar_olho_esquerdo" class="form-label">Olho Esquerdo</label>
                                <input type="text" name="olho_esquerdo" id="editar_olho_esquerdo" class="form-control">
                            </div>
                            <div class="col-1 mb-2">
                                <label for="editar_qnt_esquerda" class="form-label">Quantidade</label>
                                <input type="number" name="qnt_esquerda" id="editar_qnt_esquerda" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Campo para o Valor Total -->
                            <div class="col-4 offset-1 text-center mt-1">
                                <label for="editar_valor" class="form-label">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="editar_valor" required>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="editar_forma_pgto1" class="form-label">Forma Pagamento 1</label>
                                <select name="forma_pgto1" id="editar_forma_pgto1" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                    <option value="5">Dinheiro</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="editar_forma_pgto2" class="form-label">Forma Pagamento 2</label>
                                <select name="forma_pgto2" id="editar_forma_pgto2" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="editar_status" class="form-label">Status *</label>
                                <select name="status" id="editar_status" class="form-control" required>
                                    <option value="0">Pendente</option>
                                    <option value="1">Pago</option>
                                    <option value="2">Entregue</option>
                                </select>
                            </div>
                            <div class="col-10 mt-2 offset-1">
                                <label for="editar_observacao" class="form-label">Observacao</label>
                                <textarea class="form-control" name="observacao" id="editar_observacao"></textarea>
                            </div>
                        </div>
                        <!-- Row para CV no Modal Editar -->
                        <div class="row d-none" id="cv_forma_pgto_edt">
                            <div class="d-none col-3 offset-1" id="cv_forma_pgto1_edt">
                                <label for="editar_cv_pgto1">CV - Forma de Pagamento 1 *</label>
                                <input type="text" id="editar_cv_pgto1" name="cv_pgto1" class="form-control">
                            </div>
                            <div class="d-none col-3 offset-1" id="cv_forma_pgto2_edt">
                                <label for="editar_cv_pgto2">CV - Forma de Pagamento 2 *</label>
                                <input type="text" id="editar_cv_pgto2" name="cv_pgto2" class="form-control">
                            </div>
                        </div>

                    </div><!-- Fim do modal-body -->
                    <!-- Rodapé do Modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success">Editar</button>
                    </div>
                </div><!-- Fim do modal-content -->
            </div><!-- Fim do modal-dialog -->
        </form>
    </div>

    <!-- Modal Deletar Orçamento -->
    <div class="modal fade" id="modalDeletarOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalDeletarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Orçamento: <span class="modalDeletarOrcamentoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_orcamento" id="deletar_id_orcamento">
                        <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>" required>
                        <div class="container row">
                            <p>Deseja deletar o orçamento: <strong><span class="modalDeletarOrcamentoLabel"></span></strong>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDeletar" class="btn btn-danger">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal CV Orçamento -->
    <div class="modal fade" id="modalCvOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalCvOrcamentoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cv de Pagamento de <span class="modalCvOrcamentoLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="col-10 offset-1">
                            <b>Valor: </b><span id="cv_valor"></span><br>
                            <span><b>CV Forma de Pagamento 1 </b><span id="forma_pgto1_cv"></span>: <span id="cv_cv_pgto1"></span></span><br>
                            <span><b>CV Forma de Pagamento 2 </b><span id="forma_pgto2_cv"></span>: <span id="cv_cv_pgto2"></span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#lent').addClass('active');
            $('#lente_contato_orcamentos').addClass('active');

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            // Formatação de CPF e Celular (Cadastro)
            $('#cadastrar_cpf').on('input', function() {
                formatarCPF($(this));
            });
            $('#cadastrar_contato').on('input', function() {
                formatarCelular($(this));
            });

            // Ao alterar o select de fornecedor para o olho esquerdo (cadastro)
            $('#cadastrar_fornecedor_esquerdo').on('change', function () {
                var idFornecedor = $(this).val();
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            $('#cadastrar_lente_esquerda').html(response);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor esquerdo.');
                        }
                    });
                } else {
                    $('#cadastrar_lente_esquerda').html('<option value="">Selecione...</option>');
                }
            });

            // Ao alterar o select de fornecedor para o olho direito (cadastro)
            $('#cadastrar_fornecedor_direito').on('change', function () {
                var idFornecedor = $(this).val();
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            $('#cadastrar_lente_direita').html(response);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor direito.');
                        }
                    });
                } else {
                    $('#cadastrar_lente_direita').html('<option value="">Selecione...</option>');
                }
            });

           // Função para buscar o valor da lente no banco de dados via AJAX
            function getPrice(id_modelo, olhos) {
                return $.ajax({
                    url: '/GRNacoes/views/ajax/get_lente_contato_modelo_valor.php', // Ajuste o caminho se necessário
                    type: 'GET',
                    data: { id_modelo: id_modelo, olhos: olhos },
                    dataType: 'json'
                });
            }

            // Função para atualizar o valor total automaticamente
            function atualizarValorTotal() {
                var promises = [];

                // Se o grupo do olho ESQUERDO estiver visível (ou seja, o select está ativo)
                if (!$('#grupo_olho_esquerdo').hasClass('d-none')) {
                    var idModeloEsq = $('#cadastrar_lente_esquerda').val();
                    var qntEsq = parseFloat($('#cadastrar_qnt_esquerda').val()) || 0;
                    if (idModeloEsq && qntEsq > 0) {
                        // Usamos olhos=1 para obter o valor unitário para o olho esquerdo
                        var promessaEsq = getPrice(idModeloEsq, 1).then(function(response) {
                            console.log('a')
                            if (response.valor_total !== undefined) {
                                return parseFloat(response.valor_total) * qntEsq;
                            }
                            return 0;
                        });
                        promises.push(promessaEsq);
                    } else {
                        // Se não houver lente ou quantidade, resolve com zero
                        promises.push($.Deferred().resolve(0));
                    }
                } else {
                    promises.push($.Deferred().resolve(0));
                }

                // Se o grupo do olho DIREITO estiver visível
                if (!$('#grupo_olho_direito').hasClass('d-none')) {
                    var idModeloDir = $('#cadastrar_lente_direita').val();
                    var qntDir = parseFloat($('#cadastrar_qnt_direita').val()) || 0;
                    if (idModeloDir && qntDir > 0) {
                        // Usamos olhos=1 para o olho direito
                        var promessaDir = getPrice(idModeloDir, 1).then(function(response) {
                            if (response.valor_total !== undefined) {
                                return parseFloat(response.valor_total) * qntDir;
                            }
                            return 0;
                        });
                        promises.push(promessaDir);
                    } else {
                        promises.push($.Deferred().resolve(0));
                    }
                } else {
                    promises.push($.Deferred().resolve(0));
                }

                // Quando todas as chamadas AJAX forem concluídas, soma os valores e atualiza o campo total
                $.when.apply($, promises).done(function() {
                    var total = 0;
                    // Se houver apenas uma promessa, arguments não é um array
                    if (promises.length === 1) {
                        total = arguments[0];
                    } else {
                        for (var i = 0; i < arguments.length; i++) {
                            total += arguments[i];
                        }
                    }
                    $('#cadastrar_valor').val(total.toFixed(2));
                });
            }

            // Vincula a função aos eventos de mudança dos selects e inputs de quantidade
            $('#cadastrar_lente_esquerda, #cadastrar_lente_direita, #cadastrar_qnt_esquerda, #cadastrar_qnt_direita').on('change', atualizarValorTotal);


            // Exibir/ocultar grupos conforme o campo "olhos" (Cadastro)
            $('#cadastrar_olhos').on('change', function() {
                let valor = $(this).val();
                if (valor === '0') { // Ambos os olhos
                    $('#grupo_olho_esquerdo').removeClass('d-none');
                    $('#grupo_olho_direito').removeClass('d-none');
                    $('#cadastrar_copiar').removeClass('d-none')
                } else if (valor === '1') { // Somente olho esquerdo
                    $('#grupo_olho_esquerdo').removeClass('d-none');
                    $('#grupo_olho_direito').addClass('d-none');
                    $('#cadastrar_fornecedor_direito, #cadastrar_lente_direita').val('');
                    $('#cadastrar_qnt_direita').val('1');
                } else if (valor === '2') { // Somente olho direito
                    $('#grupo_olho_direito').removeClass('d-none');
                    $('#grupo_olho_esquerdo').addClass('d-none');
                    $('#cadastrar_fornecedor_esquerdo, #cadastrar_lente_esquerda').val('');
                    $('#cadastrar_qnt_esquerda').val('1');
                } else {
                    $('#grupo_olho_esquerdo, #grupo_olho_direito').addClass('d-none');
                }
            });
            $('#cadastrar_olhos').trigger('change');

            $('#cadastrar_copiar_botao').click(function (e) { 
                e.preventDefault(); // previne ação padrão, se necessário
                let fornecedor_direito = $('#cadastrar_fornecedor_direito').val();
                let lente_direita = $('#cadastrar_lente_direita').val();

                // Define o fornecedor esquerdo igual ao direito
                $('#cadastrar_fornecedor_esquerdo').val(fornecedor_direito);
                var idFornecedor = $('#cadastrar_fornecedor_esquerdo').val();

                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            // Insere as novas opções
                            $('#cadastrar_lente_esquerda').html(response);
                            // Agora, define o valor desejado para selecionar a opção correta
                            $('#cadastrar_lente_esquerda').val(lente_direita);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor esquerdo.');
                        }
                    });
                } else {
                    $('#cadastrar_lente_esquerda').html('<option value="">Selecione...</option>');
                }
            });


            // Funções de formatação e validação
            function formatarCelular(campo) {
                let telefone = campo.val().replace(/\D/g, '');
                if (telefone.length === 10) {
                    telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
                    telefone = telefone.replace(/(\d{4})(\d{4})$/, '$1-$2');
                    campo.val(telefone);
                }
                if (telefone.length === 11) {
                    telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
                    telefone = telefone.replace(/(\d{5})(\d{4})$/, '$1-$2');
                    campo.val(telefone);
                }
            }
            function formatarCPF(campo) {
                let cpf = campo.val().replace(/\D/g, '');
                if (cpf.length === 11) {
                    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    campo.val(cpf);
                }
                if (!validarCPF(cpf)) {
                    campo.addClass('is-invalid');
                    if (campo.next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">CPF Inválido. Tente novamente.</div>').insertAfter(campo);
                    }
                } else {
                    campo.removeClass('is-invalid').addClass('is-valid');
                    campo.next('.invalid-feedback').remove();
                }
            }
            function validarCPF(cpf) {
                cpf = cpf.replace(/[^\d]+/g,'');
                if (cpf == '') return false;
                if (cpf.length != 11 || 
                    cpf == "00000000000" || 
                    cpf == "11111111111" || 
                    cpf == "22222222222" || 
                    cpf == "33333333333" || 
                    cpf == "44444444444" || 
                    cpf == "55555555555" || 
                    cpf == "66666666666" || 
                    cpf == "77777777777" || 
                    cpf == "88888888888" || 
                    cpf == "99999999999")
                    return false;
                let add = 0;
                for (let i = 0; i < 9; i++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                let rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) rev = 0;
                if (rev != parseInt(cpf.charAt(9))) return false;
                add = 0;
                for (let i = 0; i < 10; i++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) rev = 0;
                if (rev != parseInt(cpf.charAt(10))) return false;
                return true;
            }

            // Funções para o Modal de Edição

            // Ao alterar o select de fornecedor para o olho esquerdo (edição)
            $('#editar_fornecedor_esquerdo').on('change', function () {
                var idFornecedor = $(this).val();
                // Recupera o modelo previamente armazenado (caso exista)
                var selectedModel = $(this).data('selectedModel') || null;
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            $('#editar_lente_esquerda').html(response);
                            // Se houver um modelo pré-selecionado, define-o
                            if (selectedModel) {
                                $('#editar_lente_esquerda').val(selectedModel);
                            }
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor esquerdo (edição).');
                        }
                    });
                } else {
                    $('#editar_lente_esquerda').html('<option value="">Selecione...</option>');
                }
            });

            // Ao alterar o select de fornecedor para o olho direito (edição)
            $('#editar_fornecedor_direito').on('change', function () {
                var idFornecedor = $(this).val();
                var selectedModel = $(this).data('selectedModel') || null;
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            $('#editar_lente_direita').html(response);
                            if (selectedModel) {
                                $('#editar_lente_direita').val(selectedModel);
                            }
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor direito (edição).');
                        }
                    });
                } else {
                    $('#editar_lente_direita').html('<option value="">Selecione...</option>');
                }
            });


            $('#editar_olhos').on('change', function(){
                var valor = $(this).val();
                if (valor === '0') { // Ambos os Olhos
                    $('#editar_grupo_olho_esquerdo').removeClass('d-none');
                    $('#editar_grupo_olho_direito').removeClass('d-none');
                    $('#editar_copiar').removeClass('d-none');
                } else if (valor === '1') { // Somente Olho Esquerdo
                    $('#editar_grupo_olho_esquerdo').removeClass('d-none');
                    $('#editar_grupo_olho_direito').addClass('d-none');
                    $('#editar_copiar').addClass('d-none');
                } else if (valor === '2') { // Somente Olho Direito
                    $('#editar_grupo_olho_esquerdo').addClass('d-none');
                    $('#editar_grupo_olho_direito').removeClass('d-none');
                    $('#editar_copiar').addClass('d-none');
                } else {
                    $('#editar_grupo_olho_esquerdo, #editar_grupo_olho_direito, #editar_copiar').addClass('d-none');
                }
            });

            $('#modalEditarOrcamento').on('show.bs.modal', function (event) {
                var button                = $(event.relatedTarget);
                var id_orcamento          = button.data('id_orcamento');
                var nome                  = button.data('nome');
                var cpf                   = button.data('cpf');
                var contato               = button.data('contato');
                var id_medico             = button.data('id_medico');
                var olhos                 = button.data('olhos');
                var olho_esquerdo         = button.data('olho_esquerdo');
                var olho_direito          = button.data('olho_direito');
                var fornecedor_esquerdo   = button.data('fornecedor_esquerdo');
                var fornecedor_direito    = button.data('fornecedor_direito');
                var lente_esquerda        = button.data('lente_esquerda');  // id_modelo da lente do olho esquerdo
                var lente_direita         = button.data('lente_direita');   // id_modelo da lente do olho direito
                var qnt_esquerda          = button.data('qnt_esquerda');
                var qnt_direita           = button.data('qnt_direita');
                var valor                 = button.data('valor');
                var forma_pgto1           = button.data('forma_pgto1');
                var forma_pgto2           = button.data('forma_pgto2');
                var cv_pgto1              = button.data('cv_pgto1');
                var cv_pgto2              = button.data('cv_pgto2');
                var status                = button.data('status');
                var observacao                = button.data('observacao');

                $('#editar_id_orcamento').val(id_orcamento);
                $('#editar_nome').val(nome);
                $('#editar_cpf').val(cpf);
                $('#editar_contato').val(contato);
                $('#editar_id_medico').val(id_medico);
                $('#editar_olhos').val(olhos);
                $('#editar_olho_esquerdo').val(olho_esquerdo);
                $('#editar_olho_direito').val(olho_direito);
                $('#editar_fornecedor_esquerdo').val(fornecedor_esquerdo);
                $('#editar_fornecedor_direito').val(fornecedor_direito);
                $('#editar_lente_esquerda').val(lente_esquerda);
                $('#editar_lente_direita').val(lente_direita);
                $('#editar_qnt_esquerda').val(qnt_esquerda ? qnt_esquerda : '1');
                $('#editar_qnt_direita').val(qnt_direita ? qnt_direita : '1');
                $('#editar_valor').val(valor);
                $('#editar_forma_pgto1').val(forma_pgto1);
                $('#editar_forma_pgto2').val(forma_pgto2);
                $('#editar_cv_pgto1').val(cv_pgto1);
                $('#editar_cv_pgto2').val(cv_pgto2);
                $('#editar_status').val(status);
                $('#editar_observacao').val(observacao);

                $('#editar_forma_pgto1').trigger('change');
                $('#editar_forma_pgto2').trigger('change');

                // Dispara o evento change ao carregar o modal para garantir que os campos sejam exibidos conforme o valor pré-selecionado
                $('#editar_olhos').trigger('change');

                // Formatação de CPF e Celular no modal de edição
                formatarCPF($('#editar_cpf'));
                formatarCelular($('#editar_contato'));
                if (!validarCPF($('#editar_cpf').val().replace(/\D/g, ''))) {
                    $('#editar_cpf').addClass('is-invalid');
                    if ($('#editar_cpf').next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">CPF Inválido. Tente novamente.</div>').insertAfter($('#editar_cpf'));
                    }
                } else {
                    $('#editar_cpf').removeClass('is-invalid').addClass('is-valid');
                    $('#editar_cpf').next('.invalid-feedback').remove();
                }

                // Requisição AJAX para o olho esquerdo
                if (lente_esquerda) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_lente_contato_fornecedor_modelo.php',
                        type: 'GET',
                        data: { id_modelo: lente_esquerda },
                        dataType: 'json',
                        success: function(response) {
                            if (response.id_fornecedor) {
                                // Define o fornecedor, armazena o modelo e dispara o evento change
                                $('#editar_fornecedor_esquerdo')
                                    .val(response.id_fornecedor)
                                    .data('selectedModel', lente_esquerda)
                                    .trigger('change');
                            } else if (response.error) {
                                console.error('Erro: ' + response.error);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Erro na requisição AJAX para o olho esquerdo: ' + textStatus);
                        }
                    });
                }

                // Requisição AJAX para o olho direito
                if (lente_direita) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_lente_contato_fornecedor_modelo.php',
                        type: 'GET',
                        data: { id_modelo: lente_direita },
                        dataType: 'json',
                        success: function(response) {
                            if (response.id_fornecedor) {
                                $('#editar_fornecedor_direito')
                                    .val(response.id_fornecedor)
                                    .data('selectedModel', lente_direita)
                                    .trigger('change');
                            } else if (response.error) {
                                console.error('Erro: ' + response.error);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Erro na requisição AJAX para o olho direito: ' + textStatus);
                        }
                    });
                }
            });

            // Validação no modal de edição
            $('#editar_cpf').on('input', function() {
                formatarCPF($(this));
            });
            $('#editar_contato').on('input', function() {
                formatarCelular($(this));
            });

            // Preencher modal de exclusão
            $('#modalDeletarOrcamento').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id_orcamento = button.data('id_orcamento');
                var nome = button.data('nome');

                $('#deletar_id_orcamento').val(id_orcamento);
                $('.modalDeletarOrcamentoLabel').text(nome);
            });

            $('#modalCvOrcamento').on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var nome = button.data('nome');
                // Converte o valor para número e formata no padrão de moeda brasileiro
                var valor = parseFloat(button.data('valor')) || 0;
                var valorFormatado = valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                
                var forma_pgto1 = button.data('forma_pgto1');
                var forma_pgto2 = button.data('forma_pgto2');
                var cv_pgto1 = button.data('cv_pgto1');
                var cv_pgto2 = button.data('cv_pgto2');

                $('.modalCvOrcamentoLabel').text(nome);
                $('#cv_valor').text(valorFormatado);
                $('#cv_cv_pgto1').text(cv_pgto1);
                $('#cv_cv_pgto2').text(cv_pgto2);
                $('#forma_pgto1_cv').text('(' + forma_pgto1 + ')');
                $('#forma_pgto2_cv').text('(' + forma_pgto2 + ')');
            });

            function filtrarTabela() {
                var filtroMes    = $('#filtroMes').val();
                var filtroLente  = $('#filtroLente').val();
                var filtroStatus = $('#filtroStatus').val();

                $('#tabelaOrcamentos tbody tr').each(function () {
                    // Obtem a data do orçamento (coluna oculta)
                    var dataOrcamento = $(this).find('td:eq(1)').text().trim();
                    var partesData    = dataOrcamento.split('/');
                    var mesOrcamento  = (partesData.length === 3) ? partesData[2] + '-' + partesData[1] : '';

                    // Obtem o status a partir do elemento <b> dentro da célula (coluna 4)
                    var statusOrcamento = $(this).find('td:eq(4) b').text().trim();
                    // Obtem o nome da lente da coluna 5
                    var nomeLente = $(this).find('td:eq(5)').text().trim();

                    var exibirMes = true;
                    if (filtroMes) {
                        exibirMes = (mesOrcamento === filtroMes.substring(0, 7));
                    }
                    var exibirLente = true;
                    if (filtroLente) {
                        exibirLente = (nomeLente === filtroLente);
                    }
                    var exibirStatus = true;
                    if (filtroStatus) {
                        exibirStatus = (statusOrcamento === filtroStatus);
                    }

                    if (exibirMes && exibirLente && exibirStatus) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            $('#filtroMes, #filtroLente, #filtroStatus').on('change', function () {
                filtrarTabela();
            });

            $('#cadastrar_forma_pgto1').on('change', function(){
                var valor = $(this).val();
                if(valor === '1' || valor === '2'){
                    // Exibe a row, adiciona o atributo required no input
                    $('#cv_forma_pgto').removeClass('d-none');
                    $('#cv_forma_pgto1').removeClass('d-none');
                    $('#cv_input_pgto1').attr('required', true);
                } else {
                    // Oculta a row, limpa o valor e remove o atributo required
                    $('#cv_forma_pgto').addClass('d-none');
                    $('#cv_forma_pgto1').addClass('d-none');
                    $('#cv_input_pgto1').val('');
                    $('#cv_input_pgto1').removeAttr('required');
                }
            });

            $('#cadastrar_forma_pgto2').on('change', function(){
                var valor = $(this).val();
                if(valor === '1' || valor === '2'){
                    $('#cv_forma_pgto').removeClass('d-none');
                    $('#cv_forma_pgto2').removeClass('d-none');
                    $('#cv_input_pgto').attr('required', true);
                } else {
                    $('#cv_forma_pgto').addClass('d-none');
                    $('#cv_forma_pgto2').addClass('d-none');
                    $('#cv_input_pgto2').val('');
                    $('#cv_input_pgto2').removeAttr('required');
                }
            });

            $('#editar_forma_pgto1').on('change', function(){
                var valor = $(this).val();
                if(valor === '1' || valor === '2'){
                    // Exibe a row geral e a coluna do CV
                    $('#cv_forma_pgto_edt').removeClass('d-none');
                    $('#cv_forma_pgto1_edt').removeClass('d-none');
                    // $('#cv_input_pgto1_edt').attr('required', true);
                } else {
                    // Oculta a coluna do CV e limpa seu valor e atributo required
                    $('#cv_forma_pgto1_edt').addClass('d-none');
                    $('#cv_input_pgto1_edt').val('');
                    $('#cv_input_pgto1_edt').removeAttr('required');
                    // Se nenhum dos dois campos estiver visível, oculta a row geral
                    if($('#cv_forma_pgto2_edt').hasClass('d-none')){
                        $('#cv_forma_pgto_edt').addClass('d-none');
                    }
                }
            });

            $('#editar_forma_pgto2').on('change', function(){
                var valor = $(this).val();
                if(valor === '1' || valor === '2'){
                    $('#cv_forma_pgto_edt').removeClass('d-none');
                    $('#cv_forma_pgto2_edt').removeClass('d-none');
                    // $('#cv_input_pgto2_edt').attr('required', true);
                } else {
                    $('#cv_forma_pgto2_edt').addClass('d-none');
                    $('#cv_input_pgto2_edt').val('');
                    $('#cv_input_pgto2_edt').removeAttr('required');
                    if($('#cv_forma_pgto1_edt').hasClass('d-none')){
                        $('#cv_forma_pgto_edt').addClass('d-none');
                    }
                }
            });

        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_orcamentos.js"></script>
</body>
</html>
