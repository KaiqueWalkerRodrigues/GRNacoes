<?php 
    $Lc_orcamento = new Lente_contato_Orcamento();
    $Lc_modelo = new Lente_contato_Modelo();
    $Medico = new Medico();

    if (isset($_POST['btnCadastrar'])) {
        $Lc_orcamento->cadastrarTeste($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_orcaemtno->editarTeste($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_orcamento->deletar($_POST['id_teste'],$_POST['usuario_logado']);
    }
    if (isset($_POST['btnTransferir'])) {
        $Lc_orcamento->transferirTeste($_POST);
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
</head>

<body class="nav-fixed">
        <!-- Tela de Carregamento -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
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
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Testes
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarOrcamento">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="tabelaTestes" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="d-none">Mes</th>
                                            <th>Paciente</th>
                                            <th>Lente</th>
                                            <th>Data/Hora</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_orcamento->listarTeste() as $teste){ ?>
                                        <tr>
                                            <td><?php echo $teste->id_lente_contato_orcamento ?></td>
                                            <td class="d-none"><?php echo Helper::formatarDataSemHorario($teste->created_at) ?></td>
                                            <td><?php echo $teste->nome ?></td>
                                            <td><?php echo $Lc_modelo->mostrar($teste->id_modelo)->modelo ?></td>
                                            <td><?php echo Helper::formatarData($teste->created_at) ?></td>
                                            <td class="text-center">
                                                <!-- Botão para Transferir Teste para Orçamento -->
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalTransferirTeste"
                                                        data-id_teste="<?php echo $teste->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $teste->nome ?>">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                </button>
                                                <!-- Botão de Editar -->
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarOrcamento"
                                                        data-id_teste="<?php echo $teste->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $teste->nome ?>"
                                                        data-cpf="<?php echo $teste->cpf ?>"
                                                        data-contato="<?php echo $teste->contato ?>"
                                                        data-id_medico="<?php echo $teste->id_medico ?>"
                                                        data-olhos="<?php echo $teste->olhos ?>"
                                                        data-olho_esquerdo="<?php echo $teste->olho_esquerdo ?>"
                                                        data-olho_direito="<?php echo $teste->olho_direito ?>"
                                                        data-id_lente="<?php echo $teste->id_modelo ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <!-- Botão de Deletar -->
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarOrcamento"
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
            <?php include_once('resources/footer.php') ?>
        </div>
    </div>

    <!-- Modal Cadastrar Testes -->
    <div class="modal fade" id="modalCadastrarOrcamento" tabindex="1" role="dialog" aria-labelledby="modalCadastrarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Teste</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        <div class="row">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <br>
                            <div class="col-4 offset-1 mb-2">
                                <label for="nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="cadastrar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" id="cadastrar_cpf" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="contato" class="form-label">Contato *</label>
                                <input type="text" id="cadastrar_contato" name="contato" class="form-control" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="id_solicitante" class="form-label">Médico Solicitante *</label>
                                <select id="cadastrar_id_solicitante" name="id_solicitante" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-2 offset-1">
                                <label for="olhos" class="form-label">Olho(s) *</label>
                                <select name="olhos" id="cadastrar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="1">Olho Esquerdo</option>
                                    <option value="2">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-4 d-none" id="campo_olho_esquerdo">
                                <label for="olho_esquerdo" class="form-label">Olho Esquerdo</label>
                                <input type="text" name="olho_esquerdo" id="cadastrar_olho_esquerdo" class="form-control">
                            </div>
                            <div class="col-4 d-none" id="campo_olho_direito">
                                <label for="olho_direito" class="form-label">Olho Direito</label>
                                <input type="text" name="olho_direito" id="cadastrar_olho_direito" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 offset-1">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="cadastrar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_modelo->listar() as $modelo){ ?>
                                        <option value="<?php echo $modelo->id_lente_contato_modelo ?>"><?php echo $modelo->modelo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Teste -->
    <div class="modal fade" id="modalEditarOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalEditarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Teste</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_teste" id="editar_id_teste">
                        <div class="row">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <br>
                            <div class="col-4 offset-1 mb-2">
                                <label for="nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="editar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" id="editar_cpf" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="contato" class="form-label">Contato *</label>
                                <input type="text" id="editar_contato" name="contato" class="form-control" required>
                            </div>
                            <div class="col-4 offset-1">
                                <label for="id_medico" class="form-label">Médico Solicitante *</label>
                                <select id="editar_id_medico" name="id_medico" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Lente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-2 offset-1">
                                <label for="olhos" class="form-label">Olho(s) *</label>
                                <select name="olhos" id="editar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="1">Olho Esquerdo</option>
                                    <option value="2">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-4 d-none" id="editar_campo_olho_esquerdo">
                                <label for="olho_esquerdo" class="form-label">Olho Esquerdo</label>
                                <input type="text" name="olho_esquerdo" id="editar_olho_esquerdo" class="form-control">
                            </div>
                            <div class="col-4 d-none" id="editar_campo_olho_direito">
                                <label for="olho_direito" class="form-label">Olho Direito</label>
                                <input type="text" name="olho_direito" id="editar_olho_direito" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 offset-1">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="editar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_modelo->listar() as $modelo){ ?>
                                        <option value="<?php echo $modelo->id_lente_contato_modelo ?>"><?php echo $modelo->modelo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Teste -->
    <div class="modal fade" id="modalDeletarOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalDeletarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Teste: <span class="modalDeletarOrcamentoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_teste" id="deletar_id_teste">
                        <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>" required>
                        <div class="container row">
                            <p>Deseja deletar o teste: <strong><span class="modalDeletarOrcamentoLabel"></span></strong>?</p>
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

    <!-- Modal Transferir Teste para Orçamento -->
    <div class="modal fade" id="modalTransferirTeste" tabindex="-1" role="dialog" aria-labelledby="modalTransferirTesteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transferir Teste para Orçamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Campo oculto para identificar o teste -->
                            <input type="hidden" name="id_teste" id="transferir_id_teste">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-10 offset-1 form-group">
                                <label for="transferir_valor">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="transferir_valor" required>
                                </div>
                            </div>
                            <div class="col-5 offset-1 form-group">
                                <label for="transferir_forma_pgto1">Forma de Pagamento 1 *</label>
                                <select name="forma_pgto1" id="transferir_forma_pgto1" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-5 form-group">
                                <label for="transferir_forma_pgto2">Forma de Pagamento 2</label>
                                <select name="forma_pgto2" id="transferir_forma_pgto2" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">Débito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-10 offset-1 form-group">
                                <label for="transferir_pagamento">Pagamento *</label>
                                <select name="pagamento" id="transferir_pagamento" class="form-control" required>
                                    <option value="0">Pendente</option>
                                    <option value="1">Concluído</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnTransferir" class="btn btn-success">Transferir</button>
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
            $('#lent').addClass('active')
            $('#lente_contato_testes').addClass('active') 

            $('#cadastrar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            $('#cadastrar_contato').on('input', function() {
                formatarCelular($(this));
            });

            // Vincula os eventos de formatação para os campos do modal de edição
            $('#editar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            $('#editar_contato').on('input', function() {
                formatarCelular($(this));
            });

            // Ao alterar a seleção de olhos:
            $('#cadastrar_olhos').on('change', function() {
                let valor = $(this).val();

                if(valor === '0' || valor === '1') {
                    $('#campo_olho_esquerdo').removeClass('d-none');
                } else {
                    $('#campo_olho_esquerdo').addClass('d-none');
                    $('#cadastrar_olho_esquerdo').val('');
                }

                if(valor === '0' || valor === '2') {
                    $('#campo_olho_direito').removeClass('d-none');
                } else {
                    $('#campo_olho_direito').addClass('d-none');
                    $('#cadastrar_olho_direito').val('');
                }
            });

            // Função para formatar o celular
            function formatarCelular(campo) {
                let telefone = campo.val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos

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

            // Função para formatar o CPF
            function formatarCPF(campo) {
                let cpf = campo.val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos

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
                    campo.next('.invalid-feedback').remove(); // Remove a mensagem de erro, se existir
                }
            }

            function validarCPF(cpf) {
                cpf = cpf.replace(/[^\d]+/g,''); // Remove caracteres não numéricos
                if (cpf == '') return false;
                // Elimina CPFs conhecidos que são inválidos
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
                // Validação do primeiro dígito
                let add = 0;
                for (let i = 0; i < 9; i++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                let rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(9)))
                    return false;
                // Validação do segundo dígito
                add = 0;
                for (let i = 0; i < 10; i++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(10)))
                    return false;
                return true;
            }

            // Ao abrir o modal de edição, preenche os campos e dispara a formatação
            $('#modalEditarOrcamento').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id_teste = button.data('id_teste');
                var nome = button.data('nome');
                var cpf = button.data('cpf');
                var contato = button.data('contato');
                var id_medico = button.data('id_medico');
                var olhos = button.data('olhos');
                var olho_esquerdo = button.data('olho_esquerdo');
                var olho_direito = button.data('olho_direito');
                var id_lente = button.data('id_lente');

                $('#editar_id_teste').val(id_teste);
                $('#editar_nome').val(nome);
                $('#editar_cpf').val(cpf);
                $('#editar_contato').val(contato);
                $('#editar_id_medico').val(id_medico);
                $('#editar_olhos').val(olhos);
                $('#editar_olho_esquerdo').val(olho_esquerdo);
                $('#editar_olho_direito').val(olho_direito);
                $('#editar_id_lente').val(id_lente);

                // Chama as funções de formatação para CPF e contato logo após preencher os valores
                formatarCPF($('#editar_cpf'));
                formatarCelular($('#editar_contato'));

                // Mostrar/ocultar campos de olho esquerdo e direito de acordo com a seleção
                if (olhos === 0 || olhos === 1) {
                    $('#editar_campo_olho_esquerdo').removeClass('d-none');
                } else {
                    $('#editar_campo_olho_esquerdo').addClass('d-none');
                }
                if (olhos === 0 || olhos === 2) {
                    $('#editar_campo_olho_direito').removeClass('d-none');
                } else {
                    $('#editar_campo_olho_direito').addClass('d-none');
                }
            });

            // Preencher modal de exclusão
            $('#modalDeletarOrcamento').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou a modal
                var id_teste = button.data('id_teste');
                var nome = button.data('nome');

                $('#deletar_id_teste').val(id_teste);
                $('.modalDeletarOrcamentoLabel').text(nome);
            });
            
            $('#modalTransferirTeste').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id_teste = button.data('id_teste');
                var nome = button.data('nome');
                
                // Preenche o campo oculto com o id do teste
                $('#transferir_id_teste').val(id_teste);
                
                // Opcional: você pode atualizar algum elemento no modal com o nome do teste, por exemplo:
                $(this).find('.modal-title').text("Transferir " + nome + " para Orçamento?");
            });

            // Função para filtrar a tabela de testes
            function filtrarTabelaTeste() {
                var filtroMes   = $('#filtroMes').val();    // Valor do filtro de mês (formato YYYY-MM)
                var filtroLente = $('#filtroLente').val();  // Valor do filtro de lente

                $('#tabelaTestes tbody tr').each(function () {
                    // Pega o valor da data (na coluna oculta – índice 1) no formato DD/MM/YYYY
                    var dataTeste = $(this).find('td:eq(1)').text().trim();
                    var mesTeste  = "";
                    
                    if(dataTeste !== "") {
                        // Converte para o formato YYYY-MM
                        var partesData = dataTeste.split('/');
                        if(partesData.length === 3) {
                            mesTeste = partesData[2] + '-' + partesData[1];
                        }
                    }
                    
                    // Pega o nome da lente na coluna 3 (índice 3)
                    var nomeLente = $(this).find('td:eq(3)').text().trim();

                    // Verifica se a linha deve ser exibida
                    var exibirMes   = true;
                    var exibirLente = true;

                    if (filtroMes) {
                        exibirMes = (mesTeste === filtroMes.substring(0, 7)); // Compara YYYY-MM
                    }
                    if (filtroLente) {
                        exibirLente = (nomeLente === filtroLente);
                    }

                    if (exibirMes && exibirLente) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Chama a função de filtro quando os filtros são alterados
            $('#filtroMes, #filtroLente').on('change', function () {
                filtrarTabelaTeste();
            });
        });

    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_testes.js"></script>
</body>

</html>
