<?php 
    $Usuario = new Usuario();
    $Financeiro_Contrato = new Financeiro_Contrato();

    if (isset($_POST['btnCadastrar'])) {
        $Financeiro_Contrato->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Financeiro_Contrato->editar($_POST);
    }
    // if (isset($_POST['btnDeletar'])) {
    //     $Financeiro_Contrato->deletar($_POST['id_financeiro_contrato'],$_POST['usuario_logado']);
    // }
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
    <style>
        .modal-backdrop.modal-stack {
            z-index: 1039 !important; /* Sempre um nível abaixo do modal */
        }
    </style>
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
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Contratos</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Contratos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarContrato">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>N° Contrato</th>
                                            <th>Unidade</th>
                                            <th>Nome Paciente</th>
                                            <th>Data Contrato</th>
                                            <th>Parcelas</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($Financeiro_Contrato->listar() as $contrato) { ?>
                                        <tr>
                                            <td><?php echo $contrato->n_contrato; ?></td>
                                            <td><?php echo Helper::mostrar_empresa($contrato->id_empresa); ?></td>
                                            <td><?php echo $contrato->nome; ?></td>
                                            <td><?php echo Helper::formatarData($contrato->data); ?></td>
                                            <td class="text-center">
                                                <button 
                                                    data-toggle="modal" 
                                                    data-target="#modalParcelasContrato" 
                                                    class="badge badge-dark badge-pill"
                                                    data-id_financeiro_contrato="<?php echo $contrato->id_financeiro_contrato; ?>"
                                                    data-nome-contrato="<?php echo $contrato->n_contrato; ?>"
                                                >
                                                    <?php echo $Financeiro_Contrato->contarParcelasPagas($contrato->id_financeiro_contrato); ?>/<?php echo $Financeiro_Contrato->contarParcelas($contrato->id_financeiro_contrato); ?>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                R$ <?php echo number_format($contrato->valor, 2, ',', '.'); ?>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="documentos/gerar_contrato_pdf?id=<?php echo $contrato->id_financeiro_contrato ?>"><i class="fa-solid fa-file-pdf"></i></a>
                                                <?php if (date('Y-m-d', strtotime($contrato->created_at)) == date('Y-m-d')) { ?>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarContrato"
                                                        data-id_empresa="<?php echo $contrato->id_empresa; ?>"
                                                        data-n_contrato="<?php echo $contrato->n_contrato; ?>"
                                                        data-data="<?php echo $contrato->data; ?>"
                                                        data-id_testemunha1="<?php echo $contrato->id_testemunha1; ?>"
                                                        data-nome="<?php echo $contrato->nome; ?>"
                                                        data-data_nascimento="<?php echo $contrato->data_nascimento; ?>"
                                                        data-cpf="<?php echo $contrato->cpf; ?>"
                                                        data-cep="<?php echo $contrato->cep; ?>"
                                                        data-numero="<?php echo $contrato->numero; ?>"
                                                        data-endereco="<?php echo $contrato->endereco; ?>"
                                                        data-complemento="<?php echo $contrato->complemento; ?>"
                                                        data-bairro="<?php echo $contrato->bairro; ?>"
                                                        data-cidade="<?php echo $contrato->cidade; ?>"
                                                        data-uf="<?php echo $contrato->uf; ?>"
                                                        data-telefone_residencial="<?php echo $contrato->telefone_residencial; ?>"
                                                        data-telefone_comercial="<?php echo $contrato->telefone_comercial; ?>"
                                                        data-celular1="<?php echo $contrato->celular1; ?>"
                                                        data-celular2="<?php echo $contrato->celular2; ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                <?php } ?>
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

    <!-- Modal Cadastrar Contrato -->
    <div class="modal fade" id="modalCadastrarContrato" tabindex="1" role="dialog" aria-labelledby="modalCadastrarContratosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Contrato</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']?> ">

                            <div class="offset-1 col-10">
                                <b class="text-dark">Dados Contrato:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-3 offset-1 mb-1">
                                <label for="cadastrar_id_empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="cadastrar_id_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                </select>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_n_contrato" class="form-label">N° Contrato *</label>
                                <input type="number" name="n_contrato" id="cadastrar_n_contrato" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_data" class="form-label">Data *</label>
                                <input type="date" name="data" id="cadastrar_data" class="form-control" value="<?php echo date('Y-m-d') ?>" required>
                            </div>

                            <div class="offset-1 col-10">
                                <b class="text-dark">Testamunhas:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-3 offset-1 mb-1">
                                <label for="cadastrar_id_testemunha1" class="form-label">Testemunha 1 *</label>
                                <select name="id_testemunha1" id="cadastrar_id_testemunha1" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>

                            <!-- <div class="col-3 mb-1">
                                <label for="cadastrar_id_testemunha2" class="form-label">Testemunha 2 *</label>
                                <select name="id_testemunha2" id="cadastrar_id_testemunha2" class="form-control">
                                    <option value="">Selecione...</option>
                                </select>
                            </div> -->

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Dados Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-4 offset-1 mb-1">
                                <label for="cadastrar_nome" class="form-label">Nome Paciente *</label>
                                <input type="text" name="nome" id="cadastrar_nome" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_data_nascimento" class="form-label">Data Nascimento*</label>
                                <input type="date" name="data_nascimento" id="cadastrar_data_nascimento" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="cadastrar_cpf" class="form-control" required>
                            </div>
                            <div class="col-2 offset-1 mb-1">
                                <label for="cadastrar_cep" class="form-label">CEP *</label>
                                <input type="text" name="cep" id="cadastrar_cep" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_numero" class="form-label">Numero *</label>
                                <input type="number" name="numero" id="cadastrar_numero" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_endereco" class="form-label">Endereço *</label>
                                <input type="text" name="endereco" id="cadastrar_endereco" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_complemento" class="form-label">Complemento</label>
                                <input type="text" name="complemento" id="cadastrar_complemento" class="form-control">
                            </div>
                            <div class="col-3 offset-1 mb-1">
                                <label for="cadastrar_bairro" class="form-label">Bairro *</label>
                                <input type="text" name="bairro" id="cadastrar_bairro" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_cidade" class="form-label">Cidade *</label>
                                <input type="text" name="cidade" id="cadastrar_cidade" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_uf" class="form-label">UF *</label>
                                <select name="uf" id="cadastrar_uf" class="form-control">
                                    <option value="SP">SP</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AM">AM</option>
                                    <option value="AP">AP</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MG">MG</option>
                                    <option value="MS">MS</option>
                                    <option value="MT">MT</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="PR">PR</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="RS">RS</option>
                                    <option value="SC">SC</option>
                                    <option value="SE">SE</option>
                                    <option value="TO">TO</option>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Contato Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-2 offset-1 mb-1">
                                <label for="cadastrar_tel_residencial" class="form-label">Telefone Residencial</label>
                                <input type="text" name="tel_res" id="cadastrar_tel_residencial" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_tel_comercial" class="form-label">Telefone Comercial</label>
                                <input type="text" name="tel_com" id="cadastrar_tel_comercial" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_celular1" class="form-label">Celular 1</label>
                                <input type="text" name="celular1" id="cadastrar_celular1" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="cadastrar_celular2" class="form-label">Celular 2</label>
                                <input type="text" name="celular2" id="cadastrar_celular2" class="form-control">
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Dados Pagamento:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-2 offset-1 mb-1">
                                <label for="cadastrar_sinal_entrada" class="form-label">Sinal / Entrada</label>
                                <input type="number" name="sinal_entrada" id="cadastrar_sinal_entrada" class="form-control" max="9999999">
                            </div>
                            <div class="col-3 mb-1">
                                <label for="cadastrar_valor" class="form-label">Valor do Financiamento *</label>
                                <input type="number" name="valor" id="cadastrar_valor" class="form-control" required max="9999999">
                            </div>
                            <div class="col-3 mb-1">
                                <div class="mt-3">
                                    <br>
                                    <b><span id="valor_extenso"></span></b>
                                </div>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Dados Parcelas:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-3 offset-1">
                                <label for="cadastrar_parcelas" class="form-label">Parcelas *</label>
                                <select name="parcelas" id="cadastrar_parcelas" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>

                            </div>

                            <!-- Contêiner para as Parcelas -->
                            <div class="col-12 mt-3" id="parcelas_container">
                                <!-- Os campos de parcelas serão adicionados aqui dinamicamente -->
                            </div>

                            <!-- Mensagem de Validação do Saldo -->
                            <div class="col-10 offset-1 mt-1">
                                <b id="saldo_mensagem" class="text-danger"></b>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success" disabled>Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Contrato -->
    <div class="modal fade" id="modalEditarContrato" tabindex="1" role="dialog" aria-labelledby="modalEditarContratosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Contrato: <span id="editarcontrato_titulo"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']?> ">

                            <div class="offset-1 col-10">
                                <b class="text-dark">Dados Contrato:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-3 offset-1 mb-1">
                                <label for="editar_id_empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="editar_id_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                </select>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="editar_n_contrato" class="form-label">N° Contrato *</label>
                                <input type="number" name="n_contrato" id="editar_n_contrato" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_data" class="form-label">Data *</label>
                                <input type="date" name="data" id="editar_data" class="form-control" required>
                            </div>

                            <div class="offset-1 col-10">
                                <b class="text-dark">Testamunhas:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-3 offset-1 mb-1">
                                <label for="editar_id_testemunha1" class="form-label">Testemunha 1 *</label>
                                <select name="id_testemunha1" id="editar_id_testemunha1" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>

                            <!-- <div class="col-3 mb-1">
                                <label for="editar_id_testemunha2" class="form-label">Testemunha 2 *</label>
                                <select name="id_testemunha2" id="editar_id_testemunha2" class="form-control">
                                    <option value="">Selecione...</option>
                                </select>
                            </div> -->

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Dados Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-4 offset-1 mb-1">
                                <label for="editar_nome" class="form-label">Nome Paciente *</label>
                                <input type="text" name="nome" id="editar_nome" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_data_nascimento" class="form-label">Data Nascimento*</label>
                                <input type="date" name="data_nascimento" id="editar_data_nascimento" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="editar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="editar_cpf" class="form-control" required>
                            </div>
                            <div class="col-2 offset-1 mb-1">
                                <label for="editar_cep" class="form-label">CEP *</label>
                                <input type="text" name="cep" id="editar_cep" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_numero" class="form-label">Numero *</label>
                                <input type="number" name="numero" id="editar_numero" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="editar_endereco" class="form-label">Endereço *</label>
                                <input type="text" name="endereco" id="editar_endereco" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="editar_complemento" class="form-label">Complemento</label>
                                <input type="text" name="complemento" id="editar_complemento" class="form-control">
                            </div>
                            <div class="col-3 offset-1 mb-1">
                                <label for="editar_bairro" class="form-label">Bairro *</label>
                                <input type="text" name="bairro" id="editar_bairro" class="form-control" required>
                            </div>
                            <div class="col-3 mb-1">
                                <label for="editar_cidade" class="form-label">Cidade *</label>
                                <input type="text" name="cidade" id="editar_cidade" class="form-control" required>
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_uf" class="form-label">UF *</label>
                                <select name="uf" id="editar_uf" class="form-control">
                                    <option value="SP">SP</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AM">AM</option>
                                    <option value="AP">AP</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MG">MG</option>
                                    <option value="MS">MS</option>
                                    <option value="MT">MT</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="PR">PR</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="RS">RS</option>
                                    <option value="SC">SC</option>
                                    <option value="SE">SE</option>
                                    <option value="TO">TO</option>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Contato Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>

                            <div class="col-2 offset-1 mb-1">
                                <label for="editar_tel_residencial" class="form-label">Telefone Residencial</label>
                                <input type="text" name="tel_res" id="editar_tel_residencial" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_tel_comercial" class="form-label">Telefone Comercial</label>
                                <input type="text" name="tel_com" id="editar_tel_comercial" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_celular1" class="form-label">Celular 1</label>
                                <input type="text" name="celular1" id="editar_celular1" class="form-control">
                            </div>
                            <div class="col-2 mb-1">
                                <label for="editar_celular2" class="form-label">Celular 2</label>
                                <input type="text" name="celular2" id="editar_celular2" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success" disabled>Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Contrato-->
    <div class="modal fade" id="modalDeletarContrato" tabindex="1" role="dialog" aria-labelledby="modalDeletarContratoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar o Categoria: <span class="modalDeletarCategoriaLabel"></span>?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_categoria" id="Deletar_id_compra_categoria">
                        <div class="row">
                            <p>Deseja Deletar a Categoria: <span class="modalDeletarCategoriaLabel"></span>?</p>
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

    <!-- Modal Ver Parcelas -->
    <div class="modal fade" id="modalParcelasContrato" tabindex="1" role="dialog" aria-labelledby="modalParcelasContratoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Parcelas do Contrato: <span id="parcelas_titulo"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tabela para exibir as parcelas -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>N° Parcela</th>
                                    <th>Data de Vencimento</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="parcelas_table_body">
                                <input type="hidden" name="usuario_logado" id="parcelas_usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <!-- As linhas da tabela serão preenchidas dinamicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Pagamento -->
    <div class="modal fade" id="modalConfirmarPagamento" tabindex="2" role="dialog" aria-labelledby="modalConfirmarPagamentoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formConfirmarPagamento">
                        <input type="hidden" id="confirmarPagamentoIdParcela" name="id_parcela">
                        <input type="hidden" id="confirmarPagamentoUsuarioLogado" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                        <div class="form-group">
                            <label for="valor_pago">Valor Pago</label>
                            <input type="number" step="0.01" class="form-control" id="valor_pago" name="valor_pago" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarPagamento">Confirmar Pagamento</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#fina').addClass('active')
            $('#financeiro_contratos').addClass('active')

            var usuario_logado = $('#parcelas_usuario_logado').val();

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $(document).on('show.bs.modal', '.modal', function () {
                const zIndex = 1040 + 10 * $('.modal:visible').length;
                $(this).css('z-index', zIndex); // Ajusta o z-index do modal
                setTimeout(function () {
                    $('.modal-backdrop')
                        .not('.modal-stack')
                        .css('z-index', zIndex - 1)
                        .addClass('modal-stack'); // Ajusta o z-index do backdrop
                }, 0);
            });

            $(document).on('hidden.bs.modal', '.modal', function () {
                // Se ainda houver modais visíveis
                if ($('.modal:visible').length > 0) {
                    // Reaplica a classe `modal-open` no body
                    $('body').addClass('modal-open');
                } else {
                    // Remove qualquer backdrop restante, se for o último modal
                    $('.modal-backdrop').remove();
                }
            });

            let isCPFValid = false;
            let isCEPValid = false;
            let isParcelasValid = false;

            function atualizarbtnCadastrar() {
                if (isCPFValid && isCEPValid && isParcelasValid) {
                    $('button[name="btnCadastrar"]').prop('disabled', false);
                } else {
                    $('button[name="btnCadastrar"]').prop('disabled', true);
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
                        isCPFValid = false;
                    }
                } else {
                    campo.removeClass('is-invalid').addClass('is-valid');
                    campo.next('.invalid-feedback').remove(); // Remove a mensagem de erro, se existir
                    isCPFValid = true;
                }
                atualizarbtnCadastrar();
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

            $('#cadastrar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            $('#cadastrar_id_empresa').on('change', function () {
                let idEmpresa = $(this).val();

                if (idEmpresa) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_possiveisTestemunhas.php',
                        type: 'GET',
                        data: { id_empresa: idEmpresa },
                        dataType: 'json',
                        success: function (data) {
                            // Limpa as opções existentes
                            $('#cadastrar_id_testemunha1, #cadastrar_id_testemunha2').empty().append('<option value="">Selecione...</option>');

                            if (!data.error) {
                                // Popula as novas opções com os resultados do AJAX
                                $.each(data, function (index, testemunha) {
                                    let option = `<option value="${testemunha.id_usuario}">${testemunha.nome}</option>`;
                                    $('#cadastrar_id_testemunha1, #cadastrar_id_testemunha2').append(option);
                                });
                            } else {
                                console.error(data.error);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });
                } else {
                    // Limpa os selects caso nenhum ID de empresa seja selecionado
                    $('#cadastrar_id_testemunha1, #cadastrar_id_testemunha2').empty().append('<option value="">Selecione...</option>');
                }
            });

            // Função para converter número para extenso
            function numeroParaExtenso(valor) {
                const unidades = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];
                const especiais = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"];
                const dezenas = ["", "", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
                const centenas = ["", "cento", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];

                function porExtenso(n) {
                    let texto = "";

                    if (n === 0) return "zero";

                    // Milhares
                    if (n >= 1000) {
                        const milhares = Math.floor(n / 1000);
                        if (milhares === 1) {
                            texto += "mil";
                        } else {
                            texto += porExtenso(milhares) + " mil";
                        }
                        n %= 1000;
                        if (n > 0 && n < 100) {
                            texto += " e ";
                        } else if (n > 0) {
                            texto += " ";
                        }
                    }

                    // Centenas
                    if (n >= 100) {
                        if (n === 100) {
                            texto += "cem";
                            n = 0;
                        } else {
                            texto += centenas[Math.floor(n / 100)];
                            n %= 100;
                            if (n > 0) {
                                texto += " e ";
                            }
                        }
                    }

                    // Dezenas
                    if (n >= 20) {
                        texto += dezenas[Math.floor(n / 10)];
                        n %= 10;
                        if (n > 0) {
                            texto += " e ";
                        }
                    } else if (n >= 10) {
                        texto += especiais[n - 10];
                        n = 0;
                    }

                    // Unidades
                    if (n > 0) {
                        texto += unidades[n];
                    }

                    return texto;
                }

                function capitalizeFirstLetter(string) {
                    if (string.length === 0) return "";
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }

                let inteiro = Math.floor(valor);
                let decimal = Math.round((valor - inteiro) * 100);

                let extenso = "";

                if (inteiro > 0) {
                    extenso += porExtenso(inteiro) + (inteiro === 1 ? " real" : " reais");
                }

                if (decimal > 0) {
                    if (extenso !== "") {
                        extenso += " e ";
                    }
                    extenso += porExtenso(decimal) + (decimal === 1 ? " centavo" : " centavos");
                }

                extenso = capitalizeFirstLetter(extenso);

                return extenso;
            }

            // Função para buscar endereço pelo CEP usando ViaCEP
            function buscarEndereco(cep) {
                // Remove caracteres não numéricos
                cep = cep.replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert('CEP inválido. O CEP deve conter 8 dígitos.');
                    isCEPValid = false;
                    atualizarbtnCadastrar();
                    return;
                }

                // Exibe um carregamento ou indicador, se desejar
                $('#preloader').show();

                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (dados) {
                        if (!('erro' in dados)) {
                            // Preenche os campos com os dados retornados
                            $('#cadastrar_endereco').val(dados.logradouro);
                            $('#cadastrar_bairro').val(dados.bairro);
                            $('#cadastrar_cidade').val(dados.localidade);
                            $('#cadastrar_uf').val(dados.uf);
                            isCEPValid = true;
                        } else {
                            // CEP não encontrado
                            alert('CEP não encontrado.');
                            limparCamposEndereco();
                            isCEPValid = false;
                        }
                    },
                    error: function () {
                        alert('Não foi possível buscar o endereço. Tente novamente.');
                        limparCamposEndereco();
                        isCEPValid = false;
                    },
                    complete: function () {
                        // Oculta o carregamento ou indicador, se exibido
                        $('#preloader').hide();
                        atualizarbtnCadastrar()
                    }
                });
            }

            // Função para limpar os campos de endereço em caso de erro
            function limparCamposEndereco() {
                $('#cadastrar_endereco').val('');
                $('#cadastrar_bairro').val('');
                $('#cadastrar_cidade').val('');
                $('#cadastrar_uf').val('');
            }

            $('#cadastrar_cep').on('blur', function () {
                let cep = $(this).val();
                if (cep !== '') {
                    buscarEndereco(cep);
                }
            });

            $('#cadastrar_cep').on('input', function () {
                isCEPValid = false;
                updateSubmitButton();
            });

            // Evento disparado quando a quantidade de parcelas muda
            $('#cadastrar_parcelas').on('change', function () {
                gerarParcelas();
            });

            // Evento disparado quando o valor total muda
            $('#cadastrar_valor').on('input', function () {
                let valor = parseFloat($(this).val());
                if (isNaN(valor)) {
                    valor = 0;
                }
                $('#valor_extenso').text('('+numeroParaExtenso(valor)+')');

                distribuirValorParcelas();
                verificarSaldo();
            });

            // Função para formatar números como moeda (R$)
            function formatarMoeda(valor) {
                return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            }

            // Função para gerar os campos das parcelas com duas parcelas por linha e datas padrão incrementadas mensalmente
            function gerarParcelas() {
                const quantidade = parseInt($('#cadastrar_parcelas').val());
                const container = $('#parcelas_container');
                container.empty(); // Limpa parcelas existentes

                const hoje = new Date(); // Data atual

                for (let i = 1; i <= quantidade; i += 2) { // Incrementa de 2 em 2 para agrupar em pares
                    let rowHTML = `<div class="row mb-3 parcela_row" id="parcela_row_${i}">`;

                    // Parcela ímpar (Primeira parcela do par)
                    for (let j = i; j < i + 2 && j <= quantidade; j++) {
                        // Calcula a data da parcela adicionando 'j' meses à data atual
                        let dataParcela = new Date(hoje);
                        dataParcela.setMonth(dataParcela.getMonth() + j);

                        // Formata a data no padrão YYYY-MM-DD para o input do tipo date
                        const ano = dataParcela.getFullYear();
                        let mes = (dataParcela.getMonth() + 1).toString();
                        let dia = dataParcela.getDate().toString();

                        // Adiciona um zero à esquerda se necessário
                        if (mes.length < 2) mes = '0' + mes;
                        if (dia.length < 2) dia = '0' + dia;

                        const dataFormatada = `${ano}-${mes}-${dia}`;

                        // **Adiciona a lógica para aplicar 'offset-1' apenas na parcela da esquerda**
                        const offsetClass = (j === i) ? 'offset-1' : '';

                        // HTML da parcela com a data pré-preenchida
                        rowHTML += `
                            <div class="col-md-5 ${offsetClass} mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="data_parcela${j}" class="form-label">Data Parcela ${j} *</label>
                                        <input type="date" name="data_parcela${j}" id="data_parcela${j}" class="form-control" required value="${dataFormatada}">
                                    </div>
                                    <div class="col-6">
                                        <label for="valor_parcela${j}" class="form-label">Valor Parcela ${j} *</label>
                                        <input type="number" step="0.01" name="valor_parcela${j}" id="valor_parcela${j}" class="form-control valor_parcela" required>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    rowHTML += `</div>`; // Fecha a linha

                    container.append(rowHTML);
                }

                distribuirValorParcelas();
                verificarSaldo();
            }

            function distribuirValorParcelas() {
                const quantidade = parseInt($('#cadastrar_parcelas').val());
                const valorTotal = parseFloat($('#cadastrar_valor').val()) || 0;
                const sinalEntrada = parseFloat($('#cadastrar_sinal_entrada').val()) || 0;

                // Calcula o saldo restante após o sinal/entrada
                const saldoRestante = Math.max(valorTotal - sinalEntrada, 0);

                // Calcula o valor base para cada parcela
                const valorBase = (saldoRestante / quantidade).toFixed(2);

                // Atualiza os campos das parcelas
                for (let i = 1; i <= quantidade; i++) {
                    $(`#valor_parcela${i}`).val(valorBase);
                }

                verificarSaldo();
            }

            // Evento para atualizar a soma das parcelas quando qualquer parcela muda
            $('#parcelas_container').on('input', '.valor_parcela', function () {
                verificarSaldo();
            });

            function verificarSaldo() {
                const valorTotal = parseFloat($('#cadastrar_valor').val()) || 0;
                const sinalEntrada = parseFloat($('#cadastrar_sinal_entrada').val()) || 0;
                const saldoEsperado = valorTotal - sinalEntrada;

                let somaParcelas = 0;
                let todasParcelasValidas = true; // Flag para verificar se todas as parcelas são > 0

                $('.valor_parcela').each(function () {
                    const valor = parseFloat($(this).val()) || 0;
                    somaParcelas += valor;
                    if (valor <= 0) {
                        todasParcelasValidas = false;
                    }
                });

                const saldo = (somaParcelas - saldoEsperado).toFixed(2);
                const saldoMensagem = $('#saldo_mensagem');

                if (saldo > 0) {
                    saldoMensagem.text(`Saldo excedente: ${formatarMoeda(saldo)}`);
                    isParcelasValid = false;
                } else if (saldo < 0) {
                    saldoMensagem.text(`Saldo faltante: ${formatarMoeda(Math.abs(saldo))}`);
                    isParcelasValid = false;
                } else {
                    if (valorTotal > 0 && todasParcelasValidas) {
                        saldoMensagem.text(''); // Sem saldo pendente
                        isParcelasValid = true;
                    } else {
                        saldoMensagem.text(''); // Sem saldo pendente, mas condições não atendidas
                        isParcelasValid = false;
                    }
                }

                atualizarbtnCadastrar(); // Atualiza o estado do botão
            }

            // Inicializar as parcelas ao abrir o modal (opcional)
            $('#modalCadastrarContrato').on('shown.bs.modal', function () {
                gerarParcelas(); // Gera parcelas com base no valor selecionado inicialmente
            });

            // Função para converter número para extenso (já existente no seu código)
            function numeroParaExtenso(valor) {
               // Arrays para representar os números por extenso
                const unidades = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];
                const especiais = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"];
                const dezenas = ["", "", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
                const centenas = ["", "cento", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];

                // Função auxiliar para transformar valores em extenso
                function porExtenso(n) {
                    let texto = "";

                    if (n === 0) return "zero";

                    // Milhares
                    if (n >= 1000) {
                        const milhares = Math.floor(n / 1000);
                        if (milhares === 1) {
                            texto += "mil";
                        } else {
                            texto += porExtenso(milhares) + " mil";
                        }
                        n %= 1000;
                        if (n > 0 && n < 100) {
                            texto += " e ";
                        } else if (n > 0) {
                            texto += " ";
                        }
                    }

                    // Centenas
                    if (n >= 100) {
                        if (n === 100) {
                            texto += "cem";
                            n = 0;
                        } else {
                            texto += centenas[Math.floor(n / 100)];
                            n %= 100;
                            if (n > 0) {
                                texto += " e ";
                            }
                        }
                    }

                    // Dezenas
                    if (n >= 20) {
                        texto += dezenas[Math.floor(n / 10)];
                        n %= 10;
                        if (n > 0) {
                            texto += " e ";
                        }
                    } else if (n >= 10) {
                        texto += especiais[n - 10];
                        n = 0;
                    }

                    // Unidades
                    if (n > 0) {
                        texto += unidades[n];
                    }

                    return texto;
                }
                // Função para capitalizar a primeira letra
                function capitalizeFirstLetter(string) {
                    if (string.length === 0) return "";
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }

                // Separação da parte inteira e decimal
                let inteiro = Math.floor(valor);
                let decimal = Math.round((valor - inteiro) * 100);

                let extenso = "";

                // Parte inteira (reais)
                if (inteiro > 0) {
                    extenso += porExtenso(inteiro) + (inteiro === 1 ? " real" : " reais");
                }

                // Parte decimal (centavos)
                if (decimal > 0) {
                    if (extenso !== "") {
                        extenso += " e ";
                    }
                    extenso += porExtenso(decimal) + (decimal === 1 ? " centavo" : " centavos");
                }

                // Capitalizar a primeira letra
                extenso = capitalizeFirstLetter(extenso);

                return extenso;
            }

            // Evento para exibir o valor por extenso
            $('#cadastrar_valor').on('input', function () {
                let valor = parseFloat($(this).val());
                if (isNaN(valor)) {
                    valor = 0;
                }
                $('#valor_extenso').text('('+numeroParaExtenso(valor)+')');
            });

            $('#cadastrar_sinal_entrada').on('input', function () {
                distribuirValorParcelas();
            });

            // Evento para atualizar a soma das parcelas quando qualquer parcela muda
            $('#parcelas_container').on('input', '.valor_parcela', function () {
                verificarSaldo();
            });

            $('#modalEditarContrato').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_empresa = button.data('id_empresa')
                let n_contrato = button.data('n_contrato')
                let data = button.data('data')
                let id_testemunha1 = button.data('id_testemunha1')
                // let id_testemunha2 = button.data('id_testemunha2')
                let nome = button.data('nome')
                let data_nascimento = button.data('data_nascimento')
                let cpf = button.data('cpf')
                let cep = button.data('cep')
                let numero = button.data('numero')
                let endereco = button.data('endereco')
                let complemento = button.data('complemento')
                let bairro = button.data('bairro')
                let cidade = button.data('cidade')
                let uf = button.data('uf')
                let telefone_residencial = button.data('telefone_residencial')
                let telefone_comercial = button.data('telefone_comercial')
                let celular1 = button.data('celular1')
                let celular2 = button.data('celular2')

                if (id_empresa) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_possiveisTestemunhas.php',
                        type: 'GET',
                        data: { id_empresa: id_empresa },
                        dataType: 'json',
                        success: function (data) {
                            // Limpa as opções existentes
                            $('#editar_id_testemunha1, #editar_id_testemunha2').empty();

                            if (!data.error) {
                                // Popula as novas opções com os resultados do AJAX
                                $.each(data, function (index, testemunha) {
                                    let option = `<option value="${testemunha.id_usuario}">${testemunha.nome}</option>`;
                                    $('#editar_id_testemunha1, #editar_id_testemunha2').append(option);
                                });
                            } else {
                                console.error(data.error);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });
                } else {
                    // Limpa os selects caso nenhum ID de empresa seja selecionado
                    $('#editar_id_testemunha1, #editar_id_testemunha2').empty().append('<option value="">Selecione...</option>');
                }

                $('#editar_id_empresa').val(id_empresa)
                $('#editar_n_contrato').val(n_contrato)
                $('#editar_data').val(data)
                $('#editar_id_testemunha1').val(id_testemunha1)
                // $('#editar_id_testemunha2').val(id_testemunha2)
                $('#editar_nome').val(nome)
                $('#editar_data_nascimento').val(data_nascimento)
                $('#editar_cpf').val(cpf)
                $('#editar_cep').val(cep)
                $('#editar_numero').val(numero)
                $('#editar_endereco').val(endereco)
                $('#editar_complemento').val(complemento)
                $('#editar_bairro').val(bairro)
                $('#editar_cidade').val(cidade)
                $('#editar_uf').val(uf)
                $('#editar_telefone_residencial').val(telefone_residencial)
                $('#editar_telefone_comercial').val(telefone_comercial)
                $('#editar_celular1').val(celular1)
                $('#editar_celular2').val(celular2)
            });

            $('#modalParcelasContrato').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Botão que acionou o modal
                let idContrato = button.data('id_financeiro_contrato'); // ID do contrato
                let nomeContrato = button.data('nome-contrato'); // Nome do contrato

                // Atualiza o título do modal
                $('#parcelas_titulo').text(nomeContrato);
        
                setInterval(atualizarParcelas(idContrato), 10000);
            });

            $(document).on('click', '.confirmarPagamento', function () {
                let idParcela = $(this).closest('tr').data('id_parcela');
                let valorParcela = $(this).closest('tr').find('td:nth-child(3)').text(); // Valor formatado (ex: R$ 1.000,00)

                // Remove "R$", espaços, e substitui a vírgula por ponto para converter para número
                valorParcela = valorParcela.replace('R$', '').trim().replace('.', '').replace(',', '.');

                // Garante que o valor seja convertido para número
                valorParcela = parseFloat(valorParcela);

                $('#confirmarPagamentoIdParcela').val(idParcela);
                $('#valor_pago').val(valorParcela.toFixed(2)); // Define o valor formatado para o input
                $('#modalConfirmarPagamento').modal('show');
            });

            $('#btnConfirmarPagamento').on('click', function () {
                let formData = $('#formConfirmarPagamento').serialize();

                $.ajax({
                    url: '/GRNacoes/views/ajax/set_confirmarPagamento',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            
                            // Atualiza a tabela de parcelas
                            atualizarParcelas(response.id_contrato);

                            // Fecha o modal de confirmação de pagamento
                            $('#modalConfirmarPagamento').modal('hide');

                            // Remove o atributo "modal-open" do body e ajusta o estilo
                            $('body').removeClass('modal-open').css('padding-right', '');

                            // Remove o backdrop antigo do Bootstrap
                            $('.modal-backdrop').remove();
                        } else {
                            alert(response.message || 'Erro ao confirmar o pagamento.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Erro ao processar a solicitação:', error);
                        alert('Não foi possível confirmar o pagamento. Tente novamente.');
                    }
                });
            });

            function formatarData(dataISO) {
                const [ano, mes, dia] = dataISO.split('-');
                return `${dia}/${mes}/${ano}`;
            }
            function formatarValor(valor) {
                return valor.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            function atualizarParcelas(idContrato) {
                $('#parcelas_table_body').empty();

                $.ajax({
                    url: '/GRNacoes/views/ajax/get_parcelas.php', // Atualize com o caminho correto
                    type: 'GET',
                    data: { id_financeiro_contrato: idContrato },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            let valorPagoTotal = 0;
                            let valorPendenteTotal = 0;
                            let valorTotal = 0;

                            // Itera pelas parcelas e adiciona à tabela
                            response.parcelas.forEach(parcela => {
                                parcela.data = formatarData(parcela.data);
                                let valorParcela = parseFloat(parcela.valor);
                                let valorPago = parseFloat(parcela.valor_pago || 0);

                                // Atualiza os totais
                                valorPagoTotal += valorPago;
                                valorPendenteTotal += Math.max(0, valorParcela - valorPago);
                                valorTotal += valorParcela;

                                // Determina o status
                                let statusBadge = '';
                                switch (parcela.status) {
                                    case 1:
                                        statusBadge = '<span class="badge badge-success">Pago</span>';
                                        break;
                                    case 2:
                                        statusBadge = '<span class="badge badge-danger">Pagamento Incompleto</span>';
                                        break;
                                    case 3:
                                        statusBadge = '<span class="badge badge-primary">Pago com Juros</span>';
                                        break;
                                    default:
                                        statusBadge = '<span class="badge badge-warning">Pendente</span>';
                                }

                                // Linha da tabela
                                let row = `
                                    <tr data-id_parcela="${parcela.id_financeiro_contrato_parcela}">
                                        <td class='text-center'>${parcela.parcela}</td>
                                        <td class='text-center'>${parcela.data}</td>
                                        <td class='text-center'>R$ ${valorPago > 0 ? formatarValor(valorPago) : formatarValor(valorParcela)}</td>
                                        <td class='text-center'>${statusBadge}</td>
                                        <td class='text-center'>
                                            ${parcela.status === 0 || parcela.status === 2 ? "<button class='confirmarPagamento btn btn-sm btn-datatable btn-icon btn-transparent-dark'><i class='fa-solid fa-check'></i></button>" : ""}
                                        </td>
                                    </tr>
                                `;
                                $('#parcelas_table_body').append(row);
                            });

                            // Atualiza o rodapé com os totais
                            let footer = `
                                <tr>
                                    <td colspan="2" class="text-right font-weight-bold">Totais:</td>
                                    <td class="text-center font-weight-bold">R$ ${ formatarValor(valorTotal)}</td>
                                    <td class="text-center font-weight-bold text-success">R$ ${ formatarValor(valorPagoTotal)}</td>
                                    <td class="text-center font-weight-bold text-danger">R$ ${ formatarValor(valorPendenteTotal)}</td>
                                </tr>
                            `;
                            $('#parcelas_table_body').append(footer);
                        } else {
                            let errorRow = `<tr><td colspan="5" class="text-center text-danger">Erro ao carregar parcelas.</td></tr>`;
                            $('#parcelas_table_body').append(errorRow);
                        }
                    },
                    error: function () {
                        let errorRow = `<tr><td colspan="5" class="text-center text-danger">Erro na requisição.</td></tr>`;
                        $('#parcelas_table_body').append(errorRow);
                    }
                });
            }
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-contratos.js"></script>
</body>

</html>