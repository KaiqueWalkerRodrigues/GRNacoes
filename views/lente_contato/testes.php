<?php 
    // Instâncias dos objetos necessários
    $Lc_orcamento  = new Lente_contato_Orcamento();
    $Lc_modelo    = new Lente_contato_Modelo();
    $Medico       = new Medico();
    $Lc_fornecedor= new Lente_contato_fornecedor();

    if (isset($_POST['btnCadastrar'])) {
        $Lc_orcamento->cadastrarTeste($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_orcamento->editarTeste($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_orcamento->deletarTeste($_POST['id_teste'], $_POST['usuario_logado']);
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
                <div class="page-header pb-10 page-header-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Testes Lente de Contato</span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="filtroMes" class="form-label text-light">Filtrar por Mês:</label>
                                    <input class="form-control" type="month" name="filtroMes" id="filtroMes">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroFornecedor" class="form-label text-light">Filtrar por Lente:</label>
                                    <select id="filtroFornecedor" class="form-control">
                                        <option value="">Todas</option>
                                        <?php foreach($Lc_modelo->listar() as $modelo){ ?>
                                            <option value="<?php echo $modelo->modelo ?>"><?php echo $modelo->modelo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabela de Testes -->
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Testes
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarTeste">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="tabelaTestes" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="d-none">Mês</th>
                                            <th>Empresa</th>
                                            <th>Paciente</th>
                                            <!-- <th>Status</th> -->
                                            <th>Lentes</th>
                                            <th>Data/Hora</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_orcamento->listarTeste($_SESSION['id_empresa']) as $teste){ ?>
                                        <tr>
                                            <td><?php echo $teste->id_lente_contato_orcamento ?></td>
                                            <td class="d-none"><?php echo Helper::formatarDataSemHorario($teste->created_at) ?></td>
                                            <td><?php echo Helper::mostrar_empresa($teste->id_empresa) ?></td>
                                            <td><?php echo $teste->nome ?></td>
                                            <!-- <td class="text-center">
                                                <?php //if($teste->status == 2) { ?>
                                                    <b class="badge badge-success badge-pill">Entregue</b>
                                                <?php //} elseif($teste->status == 1){ ?>
                                                    <b class="badge badge-secondary badge-pill">Pago</b>
                                                <?php //} else { ?>
                                                    <b class="badge badge-warning badge-pill">Pendente</b>
                                                <?php //} ?>
                                            </td> -->
                                            <td><b>Direita:</b> <?php if($teste->id_modelo_direito != Null){ echo $Lc_modelo->mostrar($teste->id_modelo_direito)->modelo; } ?> <br> <b>Esquerda:</b> <?php if($teste->id_modelo_esquerdo != Null){echo $Lc_modelo->mostrar($teste->id_modelo_esquerdo)->modelo; } ?></td>
                                            <td><?php echo Helper::formatarData($teste->created_at) ?></td>
                                            <td class="text-center">
                                                <!-- Botão de Editar -->
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarTeste"
                                                    data-id_teste="<?php echo $teste->id_lente_contato_orcamento ?>"
                                                    data-nome="<?php echo $teste->nome ?>"
                                                    data-cpf="<?php echo $teste->cpf ?>"
                                                    data-contato="<?php echo $teste->contato ?>"
                                                    data-id_medico="<?php echo $teste->id_medico ?>"
                                                    data-olhos="<?php echo $teste->olhos ?>"
                                                    data-fornecedor_direito="<?php echo ($teste->id_modelo_direito ? $Lc_modelo->mostrar($teste->id_modelo_direito)->id_fornecedor : ''); ?>"
                                                    data-id_lente_direita="<?php echo ($teste->id_modelo_direito ? $teste->id_modelo_direito : ''); ?>"
                                                    data-olho_direito="<?php echo $teste->olho_direito ?>"
                                                    data-qnt_direita="<?php echo $teste->qnt_direita ?>"
                                                    data-observacao="<?php echo $teste->observacao ?>"
                                                    data-fornecedor_esquerdo="<?php echo ($teste->id_modelo_esquerdo ? $Lc_modelo->mostrar($teste->id_modelo_esquerdo)->id_fornecedor : ''); ?>"
                                                    data-id_lente_esquerda="<?php echo ($teste->id_modelo_esquerdo ? $teste->id_modelo_esquerdo : ''); ?>"
                                                    data-olho_esquerdo="<?php echo $teste->olho_esquerdo ?>"
                                                    data-qnt_esquerda="<?php echo $teste->qnt_esquerda ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>

                                                <!-- Botão de Deletar -->
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarTeste"
                                                        data-id_teste="<?php echo $teste->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $teste->nome ?>">
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

    <!-- MODAIS -->

    <!-- Modal Cadastrar Teste -->
    <div class="modal fade" id="modalCadastrarTeste" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarTesteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Teste</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                           <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Dados do Usuário e Empresa -->
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        <!-- Ficha Paciente -->
                        <div class="row">
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
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- Ficha Lente -->
                        <div class="row mt-4">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_olhos" class="form-label">Olhos *</label>
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
                        <div class="row d-none" id="cadastrar_grupo_olho_direito">
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_fornecedor_direito" class="form-label">Fornecedor Direito</label>
                                <select name="fornecedor" id="cadastrar_fornecedor_direito" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="cadastrar_lente_direita" class="form-label">Lente Direita</label>
                                <select name="id_lente_direita" id="cadastrar_lente_direita" class="form-control">
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
                        <div class="row d-none" id="cadastrar_grupo_olho_esquerdo">
                            <div class="col-3 offset-1 mb-2">
                                <label for="cadastrar_fornecedor_esquerdo" class="form-label">Fornecedor Esquerdo</label>
                                <select name="fornecedor" id="cadastrar_fornecedor_esquerdo" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="cadastrar_lente_esquerda" class="form-label">Lente Esquerda</label>
                                <select name="id_lente_esquerda" id="cadastrar_lente_esquerda" class="form-control">
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
                        <div class="col-10 mt-2 offset-1">
                            <label for="cadastrar_observacao" class="form-label">Observacao</label>
                            <textarea class="form-control" name="observacao" id="cadastrar_observacao"></textarea>
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

    <!-- Modal Editar Teste -->
    <div class="modal fade" id="modalEditarTeste" tabindex="-1" role="dialog" aria-labelledby="modalEditarTesteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <!-- Cabeçalho -->
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Teste</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Corpo -->
                    <div class="modal-body">
                        <!-- Dados do Usuário e Identificação do Teste -->
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_teste" id="editar_id_teste">
                        
                        <!-- Ficha Paciente -->
                        <div class="row">
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
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Ficha Lente -->
                        <div class="row mt-4">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                        </div>
                        <!-- Seleção do Tipo de Olhos -->
                        <div class="row">
                            <div class="col-3 offset-1 mb-2">
                                <label for="editar_olhos" class="form-label">Olhos *</label>
                                <select name="olhos" id="editar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="2">Somente Olho Direito</option>
                                    <option value="1">Somente Olho Esquerdo</option>
                                </select>
                            </div>
                            <div class="col-1 mt-1 d-none" id="editar_copiar">
                                <br>
                                <button id="editar_copiar_botao" class="btn btn-icon btn-secondary" type="button">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Grupo para Olho Direito -->
                        <div class="row d-none" id="editar_grupo_olho_direito">
                            <div class="col-3 offset-1 mb-2">
                                <label for="editar_fornecedor_direito" class="form-label">Fornecedor Direito</label>
                                <select name="fornecedor_direito" id="editar_fornecedor_direito" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="editar_lente_direita" class="form-label">Lente Direita</label>
                                <select name="id_lente_direita" id="editar_lente_direita" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX conforme o fornecedor selecionado -->
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
                                <select name="id_lente_esquerda" id="editar_lente_esquerda" class="form-control">
                                    <option value="">Selecione...</option>
                                    <!-- Opções carregadas via AJAX conforme o fornecedor selecionado -->
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
                        <div class="col-10 mt-2 offset-1">
                            <label for="editar_observacao" class="form-label">Observacao</label>
                            <textarea class="form-control" name="observacao" id="editar_observacao"></textarea>
                        </div>
                    </div><!-- Fim do modal-body -->
                    
                    <!-- Rodapé -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success">Editar</button>
                    </div>
                </div><!-- Fim do modal-content -->
            </div><!-- Fim do modal-dialog -->
        </form>
    </div>

    <!-- Modal Deletar Teste -->
    <div class="modal fade" id="modalDeletarTeste" tabindex="-1" role="dialog" aria-labelledby="modalDeletarTesteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Teste: <span class="modalDeletarTesteLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                           <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_teste" id="deletar_id_teste">
                        <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>" required>
                        <div class="container row">
                            <p>Deseja deletar o teste: <strong><span class="modalDeletarTesteLabel"></span></strong>?</p>
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

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#lent').addClass('active');
            $('#lente_contato_testes').addClass('active');

            $('#cadastrar_olhos').on('change', function(){
                var valor = $(this).val();
                if (valor === '0') { // Ambos os Olhos
                    $('#cadastrar_grupo_olho_esquerdo').removeClass('d-none');
                    $('#cadastrar_grupo_olho_direito').removeClass('d-none');
                    $('#cadastrar_copiar').removeClass('d-none');
                } else if (valor === '1') { // Somente Olho Esquerdo
                    $('#cadastrar_grupo_olho_esquerdo').removeClass('d-none');
                    $('#cadastrar_grupo_olho_direito').addClass('d-none');
                    $('#cadastrar_copiar').addClass('d-none');
                } else if (valor === '2') { // Somente Olho Direito
                    $('#cadastrar_grupo_olho_esquerdo').addClass('d-none');
                    $('#cadastrar_grupo_olho_direito').removeClass('d-none');
                    $('#cadastrar_copiar').addClass('d-none');
                } else {
                    $('#cadastrar_grupo_olho_esquerdo, #cadastrar_grupo_olho_direito, #cadastrar_copiar').addClass('d-none');
                }
            });

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

            $('#modalEditarTeste').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                
                // Dados do paciente
                var id_teste  = button.data('id_teste');
                var nome      = button.data('nome');
                var cpf       = button.data('cpf');
                var contato   = button.data('contato');
                var id_medico = button.data('id_medico');
                
                // Dados da ficha de lente
                var olhos = button.data('olhos'); // 0 = Ambos, 1 = Somente Esquerdo, 2 = Somente Direito
                var observacao = button.data('observacao');
                
                // Dados para olho direito
                var fornecedor_direito = button.data('fornecedor_direito');
                var id_lente_direita   = button.data('id_lente_direita');
                var olho_direito       = button.data('olho_direito');
                var qnt_direita        = button.data('qnt_direita');
                
                // Dados para olho esquerdo
                var fornecedor_esquerdo = button.data('fornecedor_esquerdo');
                var id_lente_esquerda   = button.data('id_lente_esquerda');
                var olho_esquerdo       = button.data('olho_esquerdo');
                var qnt_esquerda        = button.data('qnt_esquerda');
            
                // Preenche os campos do paciente
                $('#editar_id_teste').val(id_teste);
                $('#editar_nome').val(nome);
                $('#editar_cpf').val(cpf);
                $('#editar_contato').val(contato);
                $('#editar_id_medico').val(id_medico);
                $('#editar_observacao').val(observacao);
                $('#editar_fornecedor_direito').val(fornecedor_direito)
                $('#editar_fornecedor_esquerdo').val(fornecedor_esquerdo)

                if (fornecedor_direito) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: fornecedor_direito },
                        success: function (response) {
                            // Insere as novas opções
                            $('#editar_lente_direita').html(response);
                            // Agora, define o valor desejado para selecionar a opção correta
                            $('#editar_lente_direita').val(id_lente_direita);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor direita.');
                        }
                    });
                } else {
                    $('#editar_lente_direita').html('<option value="">Selecione...</option>');
                }

                if (fornecedor_esquerdo) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: fornecedor_esquerdo },
                        success: function (response) {
                            // Insere as novas opções
                            $('#editar_lente_esquerda').html(response);
                            // Define o valor desejado para selecionar a opção correta
                            $('#editar_lente_esquerda').val(id_lente_esquerda);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor esquerda.');
                        }
                    });
                } else {
                    $('#editar_lente_esquerda').html('<option value="">Selecione...</option>');
                }

                // Preenche o select de olhos e dispara o evento change para exibir o grupo correto
                $('#editar_olhos').val(olhos).trigger('change');
                console.log(olhos)
                // Se for para exibir o grupo do olho direito (Ambos ou Somente Direito)
                if(olhos === 0 || olhos === 2){
                    $('#editar_fornecedor_direito').val(fornecedor_direito).trigger('change');
                    // Aguarda a resposta do AJAX para definir a lente
                    setTimeout(function(){
                        $('#editar_lente_direita').val(id_lente_direita);
                    }, 500);
                    $('#editar_olho_direito').val(olho_direito);
                    $('#editar_qnt_direita').val(qnt_direita ? qnt_direita : 1);
                }
                
                // Se for para exibir o grupo do olho esquerdo (Ambos ou Somente Esquerdo)
                if(olhos === 0 || olhos === 1){
                    $('#editar_fornecedor_esquerdo').val(fornecedor_esquerdo).trigger('change');
                    setTimeout(function(){
                        $('#editar_lente_esquerda').val(id_lente_esquerda);
                    }, 500);
                    $('#editar_olho_esquerdo').val(olho_esquerdo);
                    $('#editar_qnt_esquerda').val(qnt_esquerda ? qnt_esquerda : 1);
                }
                
                // Formata CPF e Contato (supondo que as funções formatarCPF e formatarCelular já estejam definidas)
                formatarCPF($('#editar_cpf'));
                formatarCelular($('#editar_contato'));

                $('#editar_fornecedor_direito').change(function (e) { 
                    let fornecedor = $(this).val()
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: fornecedor },
                        success: function (response) {
                            // Insere as novas opções
                            $('#editar_lente_direita').html(response);
                            // Agora, define o valor desejado para selecionar a opção correta
                            $('#editar_lente_direita').val(lente_direita);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor direita.');
                        }
                    });
                });
                $('#editar_fornecedor_esquerdo').change(function (e) { 
                    let fornecedor = $(this).val()
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: fornecedor },
                        success: function (response) {
                            // Insere as novas opções
                            $('#editar_lente_esquerda').html(response);
                            // Agora, define o valor desejado para selecionar a opção correta
                            $('#editar_lente_esquerda').val(lente_esquerda);
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor esquerdo.');
                        }
                    });
                });
            });


            $('#editar_olhos').on('change', function(){
                var valor = $(this).val();
                if (valor === '0') { // Ambos os Olhos
                    $('#editar_grupo_olho_esquerdo, #editar_grupo_olho_direito').removeClass('d-none');
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

            // Modal de Exclusão do Teste
            $('#modalDeletarTeste').on('show.bs.modal', function (event) {
                var button   = $(event.relatedTarget);
                var id_teste = button.data('id_teste');
                var nome     = button.data('nome');
                $('#deletar_id_teste').val(id_teste);
                $('.modalDeletarTesteLabel').text(nome);
            });
            
            // AJAX para carregar os modelos no cadastro do Teste
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
                            alert('Erro na requisição AJAX para o fornecedor (cadastro).');
                        }
                    });
                } else {
                    $('#cadastrar_lente_teste').html('<option value="">Selecione...</option>');
                }
            });
            // AJAX para carregar os modelos no cadastro do Teste
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
                            alert('Erro na requisição AJAX para o fornecedor (cadastro).');
                        }
                    });
                } else {
                    $('#cadastrar_lente_teste').html('<option value="">Selecione...</option>');
                }
            });
            
            // AJAX para carregar os modelos no modal de edição do Teste
            $('#editar_fornecedor_teste').on('change', function () {
                var idFornecedor = $(this).val();
                var selectedModel = $(this).data('selectedModel') || null;
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: { id_fornecedor: idFornecedor },
                        success: function (response) {
                            $('#editar_lente_teste').html(response);
                            if (selectedModel) {
                                $('#editar_lente_teste').val(selectedModel);
                            }
                        },
                        error: function () {
                            alert('Erro na requisição AJAX para o fornecedor (edição).');
                        }
                    });
                } else {
                    $('#editar_lente_teste').html('<option value="">Selecione...</option>');
                }
            });
        });
        
        // Funções de formatação
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
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_testes.js"></script>
</body>
</html>
