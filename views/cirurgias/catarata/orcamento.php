<?php 
    $Medico = new Medico();
    $Convenio = new Convenio();
    $Lente = new Catarata_Lente();
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
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Cirurgias</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Cirurgias de Catarata
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarCatarata">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
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

    <!-- Modal Lançar Cirurgia -->
    <div class="modal fade" id="modalCadastrarCatarata" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCatarataLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lançar Cirurgia Catarata</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_orientador" value="<?php echo $_SESSION['id_usuario'] ?>">
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
                                <label for="id_medico" class="form-label">Médico Solicitante *</label>
                                <select id="cadastrar_id_medico" name="id_medico" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="id_convenio" class="form-label">Convênio *</label>
                                <select id="cadastrar_id_convenio" name="id_convenio" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Convenio->listar() as $convenio){ ?>
                                        <option value="<?php echo $convenio->id_convenio ?>"><?php echo $convenio->convenio ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Cirurgia:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-2 offset-1">
                                <label for="olho" class="form-label">Olhos *</label>
                                <select name="olho" id="cadastrar_olho" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="ao">Ambos os Olhos</option>
                                    <option value="e">Olho Esquerdo</option>
                                    <option value="d">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="dioptria" class="form-label">Dioptria *</label>
                                <input type="number" step="0.01" max="35" min="-6" name="dioptria" id="cadastrar_dioptria" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="id_modelo" class="form-label">Modelo *</label>
                                <select name="id_modelo" id="cadastrar_id_modelo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lente->listar() as $lente){ ?>
                                        <option value="<?php echo $lente->id_catarata_lente ?>"><?php echo $lente->lente ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="data" class="form-label">Data da Cirurgia *</label>
                                <input type="date" id="cadastrar_data" name="data" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="id_forma_pagamento" class="form-label">Tipo de Pagamento *</label>
                                <select name="id_forma_pagamento" id="cadastrar_id_forma_pagamento" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="">Credito</option>
                                    <option value="">Debito</option>
                                    <option value="">Boleto</option>
                                    <option value="">Pix</option>
                                    <option value="">Credito AO</option>
                                    <option value="">Debito AO</option>
                                    <option value="">Boleto AO</option>
                                    <option value="">Pix AO</option>
                                </select>
                            </div>
                            <div class="col-12 mt-2 text-center">
                                <div class="col-4 offset-4">
                                    <label for="valor_total" class="form-label">Valor Total *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" step="0.01" name="valor_total" id="cadastrar_valor_total" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnLancar" class="btn btn-primary">Lançar</button>
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
            $('#ciru').addClass('active')
            $('#cirurgias_catarata').addClass('active')
            $('#cirurgias_catarata_orcamento').addClass('active')

            $('#cadastrar_cpf').on('input', function() {
                formatarCPF($(this));
            });

            $('#cadastrar_contato').on('input', function() {
                formatarCelular($(this));
            });

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

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-cargos.js"></script>
</body>

</html>
