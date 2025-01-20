<?php 
    $Medico = new Medico();

    if (isset($_POST['btnCadastrar'])) {
       
    }
    if (isset($_POST['btnEditar'])) {
        
    }
    if (isset($_POST['btnDeletar'])) {
        
    }
?>

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
                                    <select id="filtroMes" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Jan/24">Jan/24</option>
                                        <option value="Fev/24">Fev/24</option>
                                        <option value="Mar/24">Mar/24</option>
                                        <option value="Abr/24">Abr/24</option>
                                        <option value="Mai/24">Mai/24</option>
                                        <option value="Jun/24">Jun/24</option>
                                        <option value="Jul/24">Jul/24</option>
                                        <option value="Ago/24">Ago/24</option>
                                        <option value="Set/24">Set/24</option>
                                        <option value="Out/24">Out/24</option>
                                        <option value="Nov/24">Nov/24</option>
                                        <option value="Dez/24">Dez/24</option>
                                        <option value="Jan/25">Jan/25</option>
                                        <option value="Fev/25">Fev/25</option>
                                        <option value="Mar/25">Mar/25</option>
                                        <option value="Abr/25">Abr/25</option>
                                        <option value="Mai/25">Mai/25</option>
                                        <option value="Jun/25">Jun/25</option>
                                        <option value="Jul/25">Jul/25</option>
                                        <option value="Ago/25">Ago/25</option>
                                        <option value="Set/25">Set/25</option>
                                        <option value="Out/25">Out/25</option>
                                        <option value="Nov/25">Nov/25</option>
                                        <option value="Dez/25">Dez/25</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroFornecedor" class="form-label text-light">Filtrar por Modelo Lente:</label>
                                    <select id="filtroFornecedor" class="form-control">
                                        
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
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

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
                            <div class="col-3 offset-1">
                                <label for="olhos" class="form-label">Olho(s) *</label>
                                <select name="olhos" id="cadastrar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="1">Olho Esquerdo</option>
                                    <option value="2">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-3 d-none" id="cadastrar_olho_esquerdo">
                                <label for="olho_esquerdo" class="form-label">Olho Esquerdo</label>
                                <input type="text" name="olho_esquerdo" id="cadastrar_olho_esquerdo" class="form-control">
                            </div>
                            <div class="col-3 d-none" id="cadastrar_olho_direito">
                                <label for="olho_direito" class="form-label">Olho Direito</label>
                                <input type="text" name="olho_direito" id="cadastrar_olho_direito" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 offset-1">
                                <label for="id_lente" class="form-label">Fornecedor Lente *</label>
                                <select name="id_lente" id="cadastrar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="cadastrar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                            <div class="col-4 offset-3 text-center mt-1">
                                <label for="valor" class="form-label">Valor Total *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor" required>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <label for="forma_pgto" class="form-label">Forma Pagamento *</label>
                                <select name="forma_pgto" id="cadastrar_forma_pgto" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Credito</option>
                                    <option value="1">Debito</option>
                                    <option value="2">Boleto</option>
                                    <option value="3">Pix</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnAgendar" class="btn btn-success">Cadastrar</button>
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
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_oracmentos.js"></script>
</body>

</html>
