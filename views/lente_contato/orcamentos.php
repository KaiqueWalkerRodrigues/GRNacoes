<?php 
    $Lc_fornecedor = new Lente_contato_fornecedor();
    $Lc_orcamento = new Lente_contato_Orcamento();
    $Lc_modelo = new Lente_contato_Modelo();
    $Medico = new Medico();

    if (isset($_POST['btnCadastrar'])) {
        $Lc_orcamento->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_orcamento->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_orcamento->deletar($_POST['id_orcamento'], $_POST['usuario_logado']);
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
    <!-- Inclua o jQuery aqui -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
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
                            </div>
                        </div>
                    </div>
                </div>
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
                                            <th>Paciente</th>
                                            <th>Lente</th>
                                            <th>Data/Hora</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_orcamento->listar() as $orcamento){ ?>
                                        <tr>
                                            <td><?php echo $orcamento->id_lente_contato_orcamento ?></td>
                                            <td class="d-none"><?php echo Helper::formatarDataSemHorario($orcamento->created_at) ?></td>
                                            <td><?php echo $orcamento->nome ?></td>
                                            <td><?php echo $Lc_modelo->mostrar($orcamento->id_modelo)->modelo ?></td>
                                            <td><?php echo Helper::formatarData($orcamento->created_at) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarOrcamento"
                                                        data-id_orcamento="<?php echo $orcamento->id_lente_contato_orcamento ?>"
                                                        data-nome="<?php echo $orcamento->nome ?>"
                                                        data-cpf="<?php echo $orcamento->cpf ?>"
                                                        data-contato="<?php echo $orcamento->contato ?>"
                                                        data-id_medico="<?php echo $orcamento->id_medico ?>"
                                                        data-olhos="<?php echo $orcamento->olhos ?>"
                                                        data-olho_esquerdo="<?php echo $orcamento->olho_esquerdo ?>"
                                                        data-olho_direito="<?php echo $orcamento->olho_direito ?>"
                                                        data-id_lente="<?php echo $orcamento->id_modelo ?>"
                                                        data-valor="<?php echo $orcamento->valor ?>"
                                                        data-forma_pgto1="<?php echo $orcamento->id_forma_pagamento1 ?>"
                                                        data-forma_pgto2="<?php echo $orcamento->id_forma_pagamento2 ?>"
                                                        data-pagamento="<?php echo $orcamento->pagamento ?>"
                                                        data-id_fornecedor="<?php echo $orcamento->id_fornecedor ?>">
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
            <?php include_once('resources/footer.php') ?>
        </div>
    </div>

    <!-- Modal Cadastrar Orçamento -->
    <div class="modal fade" id="modalCadastrarOrcamento" tabindex="1" role="dialog" aria-labelledby="modalCadastrarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Orçamento</h5>
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
                                <label for="id_medico" class="form-label">Médico Solicitante</label>
                                <select id="cadastrar_id_medico" name="id_medico" class="form-control">
                                    <option value="0">Selecione...</option>
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
                                <label for="id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="cadastrar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3 d-none" id="campo_id_lente">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="cadastrar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 offset-1 text-center mt-1">
                                <label for="valor" class="form-label">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor" required>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="forma_pgto1" class="form-label">Forma Pagamento 1 *</label>
                                <select name="forma_pgto1" id="cadastrar_forma_pgto1" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Credito</option>
                                    <option value="2">Debito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="forma_pgto2" class="form-label">Forma Pagamento 2</label>
                                <select name="forma_pgto2" id="cadastrar_forma_pgto2" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Credito</option>
                                    <option value="2">Debito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="pagamento" class="form-label">Pagamento *</label>
                                <select name="pagamento" id="cadastrar_pagamento" class="form-control" required>
                                    <option value="0">Pendente</option>
                                    <option value="1">Concluído</option>
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

    <!-- Modal Editar Orçamento -->
    <div class="modal fade" id="modalEditarOrcamento" tabindex="-1" role="dialog" aria-labelledby="modalEditarOrcamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Orçamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_orcamento" id="editar_id_orcamento">
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
                                <label for="id_medico" class="form-label">Médico Solicitante</label>
                                <select id="editar_id_medico" name="id_medico" class="form-control">
                                    <option value="0">Selecione...</option>
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
                                <label for="id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="editar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3 d-none" id="editar_campo_id_lente">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="editar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 offset-1 text-center mt-1">
                                <label for="valor" class="form-label">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="editar_valor" required>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="forma_pgto1" class="form-label">Forma Pagamento 1 *</label>
                                <select name="forma_pgto1" id="editar_forma_pgto1" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Credito</option>
                                    <option value="2">Debito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="forma_pgto2" class="form-label">Forma Pagamento 2</label>
                                <select name="forma_pgto2" id="editar_forma_pgto2" class="form-control">
                                    <option value="0">Selecione...</option>
                                    <option value="1">Credito</option>
                                    <option value="2">Debito</option>
                                    <option value="3">Boleto</option>
                                    <option value="4">Pix</option>
                                </select>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="pagamento" class="form-label">Pagamento *</label>
                                <select name="pagamento" id="editar_pagamento" class="form-control" required>
                                    <option value="0">Pendente</option>
                                    <option value="1">Concluído</option>
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

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#lent').addClass('active')
            $('#lente_contato_orcamentos').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#cadastrar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            $('#cadastrar_contato').on('input', function() {
                formatarCelular($(this));
            });

            // Evento para o formulário de cadastro
            $('#cadastrar_id_lente, #cadastrar_olhos').on('change', function () {
                var id_modelo = $('#cadastrar_id_lente').val();
                var olhos = $('#cadastrar_olhos').val();
                if (id_modelo && olhos) {
                    buscarValorLente(id_modelo, olhos, '#cadastrar_valor');
                }
            });

            // Evento para o formulário de edição
            $('#editar_id_lente, #editar_olhos').on('change', function () {
                var id_modelo = $('#editar_id_lente').val();
                var olhos = $('#editar_olhos').val();
                if (id_modelo && olhos) {
                    buscarValorLente(id_modelo, olhos, '#editar_valor');
                }
            });

            // Disparar o evento de change ao abrir o modal de edição
            $('#modalEditarOrcamento').on('show.bs.modal', function (event) {
                var id_modelo = $('#editar_id_lente').val();
                var olhos = $('#editar_olhos').val();
                if (id_modelo && olhos) {
                    buscarValorLente(id_modelo, olhos, '#editar_valor');
                }
            });

            function buscarValorLente(id_modelo, olhos, campoValor) {
            $.ajax({
                    url: '/GRNacoes/views/ajax/get_valor_lente_contato_modelo.php',
                    type: 'GET',
                    data: {
                        id_modelo: id_modelo,
                        olhos: olhos
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.valor_total) {
                            $(campoValor).val(data.valor_total);
                        } else {
                            alert(data.error || 'Erro ao buscar o valor da lente.');
                        }
                    },
                    error: function () {
                        alert('Erro na requisição AJAX.');
                    }
                });
            }

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
                for (let i=0; i < 9; i ++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                let rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) 
                    rev = 0;
                if (rev != parseInt(cpf.charAt(9))) 
                    return false;
                // Validação do segundo dígito
                add = 0;
                for (let i = 0; i < 10; i ++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11) 
                    rev = 0;
                if (rev != parseInt(cpf.charAt(10)))
                    return false;
                return true;
            }

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

            // Opcional: dispara o change para configurar o estado inicial
            $('#cadastrar_olhos').trigger('change');

            $('#modalEditarOrcamento').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou a modal
                var id_orcamento = button.data('id_orcamento');
                var nome = button.data('nome');
                var cpf = button.data('cpf');
                var contato = button.data('contato');
                var id_medico = button.data('id_medico');
                var olhos = button.data('olhos');
                var olho_esquerdo = button.data('olho_esquerdo');
                var olho_direito = button.data('olho_direito');
                var id_lente = button.data('id_lente');
                var valor = button.data('valor');
                var forma_pgto1 = button.data('forma_pgto1');
                var forma_pgto2 = button.data('forma_pgto2');
                var pagamento = button.data('pagamento');
                var id_fornecedor = button.data('id_fornecedor');

                $('#editar_id_orcamento').val(id_orcamento);
                $('#editar_nome').val(nome);
                $('#editar_cpf').val(cpf);
                $('#editar_contato').val(contato);
                $('#editar_id_medico').val(id_medico);
                $('#editar_olhos').val(olhos);
                $('#editar_olho_esquerdo').val(olho_esquerdo);
                $('#editar_olho_direito').val(olho_direito);
                $('#editar_id_lente').val(id_lente);
                $('#editar_valor').val(valor);
                $('#editar_forma_pgto1').val(forma_pgto1);
                $('#editar_forma_pgto2').val(forma_pgto2);
                $('#editar_pagamento').val(pagamento);
                $('#editar_id_fornecedor').val(id_fornecedor);

                // Disparar o evento de change para carregar os modelos de lente
                $('#editar_id_fornecedor').trigger('change');

                // Formatar CPF e Celular ao abrir o modal
                formatarCPF($('#editar_cpf'));
                formatarCelular($('#editar_contato'));

                // Validar CPF ao abrir o modal
                if (!validarCPF($('#editar_cpf').val().replace(/\D/g, ''))) {
                    $('#editar_cpf').addClass('is-invalid');
                    if ($('#editar_cpf').next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">CPF Inválido. Tente novamente.</div>').insertAfter($('#editar_cpf'));
                    }
                } else {
                    $('#editar_cpf').removeClass('is-invalid').addClass('is-valid');
                    $('#editar_cpf').next('.invalid-feedback').remove(); // Remove a mensagem de erro, se existir
                }

                // Mostrar/ocultar campos de olho esquerdo e direito
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
                var id_orcamento = button.data('id_orcamento');
                var nome = button.data('nome');

                $('#deletar_id_orcamento').val(id_orcamento);
                $('.modalDeletarOrcamentoLabel').text(nome);
            });

            function filtrarTabela() {
                var filtroMes = $('#filtroMes').val(); // Valor do filtro de mês (formato YYYY-MM)
                var filtroLente = $('#filtroLente').val(); // Valor do filtro de lente (nome da lente)

                $('#tabelaOrcamentos tbody tr').each(function () {
                    var dataOrcamento = $(this).find('td:eq(1)').text().trim(); // Coluna da data (formato DD/MM/YYYY)
                    var nomeLente = $(this).find('td:eq(3)').text().trim(); // Nome da lente na coluna 2

                    // Converter a data do orçamento para o formato YYYY-MM
                    var partesData = dataOrcamento.split('/');
                    var mesOrcamento = partesData[2] + '-' + partesData[1]; // Formato YYYY-MM

                    // Aplicar filtro de mês
                    var exibirMes = true;
                    if (filtroMes) {
                        exibirMes = (mesOrcamento === filtroMes.substring(0, 7)); // Comparar YYYY-MM
                    }

                    // Aplicar filtro de lente
                    var exibirLente = true;
                    if (filtroLente) {
                        exibirLente = (nomeLente === filtroLente); // Comparar nomes das lentes
                    }

                    // Mostrar ou ocultar a linha da tabela
                    if (exibirMes && exibirLente) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Aplicar filtros ao alterar os valores
            $('#filtroMes, #filtroLente').on('change', function () {
                filtrarTabela();
            });

            // Ao abrir o modal de edição
            $('#modalEditarOrcamento').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou a modal
                var id_orcamento = button.data('id_orcamento');
                var nome = button.data('nome');
                var cpf = button.data('cpf');
                var contato = button.data('contato');
                var id_medico = button.data('id_medico');
                var olhos = button.data('olhos');
                var olho_esquerdo = button.data('olho_esquerdo');
                var olho_direito = button.data('olho_direito');
                var id_lente = button.data('id_lente');
                var valor = button.data('valor');
                var forma_pgto1 = button.data('forma_pgto1');
                var forma_pgto2 = button.data('forma_pgto2');
                var pagamento = button.data('pagamento');

                $('#editar_id_orcamento').val(id_orcamento);
                $('#editar_nome').val(nome);
                $('#editar_cpf').val(cpf);
                $('#editar_contato').val(contato);
                $('#editar_id_medico').val(id_medico);
                $('#editar_olhos').val(olhos);
                $('#editar_olho_esquerdo').val(olho_esquerdo);
                $('#editar_olho_direito').val(olho_direito);
                $('#editar_id_lente').val(id_lente);
                $('#editar_valor').val(valor);
                $('#editar_forma_pgto1').val(forma_pgto1);
                $('#editar_forma_pgto2').val(forma_pgto2);
                $('#editar_pagamento').val(pagamento);

                // Formatar CPF e Celular ao abrir o modal
                formatarCPF($('#editar_cpf'));
                formatarCelular($('#editar_contato'));

                // Validar CPF ao abrir o modal
                if (!validarCPF($('#editar_cpf').val().replace(/\D/g, ''))) {
                    $('#editar_cpf').addClass('is-invalid');
                    if ($('#editar_cpf').next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">CPF Inválido. Tente novamente.</div>').insertAfter($('#editar_cpf'));
                    }
                } else {
                    $('#editar_cpf').removeClass('is-invalid').addClass('is-valid');
                    $('#editar_cpf').next('.invalid-feedback').remove(); // Remove a mensagem de erro, se existir
                }

                // Mostrar/ocultar campos de olho esquerdo e direito
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

            // Adicionar validação ao CPF no formulário de edição
            $('#editar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            // Adicionar formatação ao celular no formulário de edição
            $('#editar_contato').on('input', function() {
                formatarCelular($(this));
            });

            $('#cadastrar_id_fornecedor').on('change', function () {
                var idFornecedor = $(this).val();
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: {
                            id_fornecedor: idFornecedor
                        },
                        success: function (response) {
                            $('#cadastrar_id_lente').html(response);
                            $('#cadastrar_id_lente').parent().removeClass('d-none');
                        },
                        error: function () {
                            alert('Erro na requisição AJAX.');
                        }
                    });
                } else {
                    $('#cadastrar_id_lente').html('<option value="">Selecione...</option>');
                    $('#cadastrar_id_lente').parent().addClass('d-none');
                }
            });

            // Evento para o formulário de edição
            $('#editar_id_fornecedor').on('change', function () {
                var idFornecedor = $(this).val();
                if (idFornecedor) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_modelos_por_fornecedor.php',
                        type: 'GET',
                        data: {
                            id_fornecedor: idFornecedor
                        },
                        success: function (response) {
                            $('#editar_id_lente').html(response);
                            $('#editar_id_lente').parent().removeClass('d-none');
                        },
                        error: function () {
                            alert('Erro na requisição AJAX.');
                        }
                    });
                } else {
                    $('#editar_id_lente').html('<option value="">Selecione...</option>');
                    $('#editar_id_lente').parent().addClass('d-none');
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
