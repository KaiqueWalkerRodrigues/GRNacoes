<?php 
    include_once('../const.php');

    $Medico = new Medicos();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Lançamento Cirurgia Catarata</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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

                    <br><br><br><br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cirurgias Cataratas| <button class="btn btn-primary" data-toggle="modal" data-target="#modalLancarCirurgia" class="collapse-item">Lançar Cirurgia Catarata</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hórario</th>
                                            <th>Empresa</th>
                                            <th>Captador</th>
                                            <th>Paciente</th>
                                            <th>Captado</th>
                                            <th>Motivo</th>
                                            <th>Médico</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

            <!-- Modal Mostrar Chamado -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal Lançar Cirurgia-->
    <div class="modal fade" id="modalLancarCirurgia" tabindex="1" role="dialog" aria-labelledby="modalLancarCirurgiaLabel" aria-hidden="true">
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
                                <b class="text-secondary">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <br>
                            <div class="col-4 offset-1">
                                <label for="nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="cadastrar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="contato" class="form-label">Contato *</label>
                                <input type="text" id="cadastrar_contato" name="contato" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="id_medico" class="form-label">Médico Solicitante *</label>
                                <select id="cadastrar_id_medico" name="id_medico" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3 offset-1">
                                <label for="id_convenio" class="form-label">Convênio *</label>
                                <select id="cadastrar_id_convenio" name="id_convenio" class="form-control" required>
                                    <option value="">Selecione...</option>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-secondary">Ficha Cirurgia:</b>
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
                                    <option value="">BAUSCH/I-VISON</option>
                                    <option value="">I-VISON</option>
                                    <option value="">HANITA</option>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnLancar" class="btn btn-primary">Lançar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $('#ori').addClass('active');
        $('#orientacao_lanc').addClass('active');

        $(document).ready(function () {
            $('#modalEditar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id = button.data('id');
                
                $('#editar_id_captado').val(id);
            });

            $('#modalExcluir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id');

                $('#excluir_id_captado').val(id)
            });

            // Inicializar modais e outras funcionalidades
            $('#modalLancarCirurgia').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
            });
            
            $('#cadastrar_contato').on('input', function() {
                let contato = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres que não são dígitos

                // Formatação quando atingir 10 ou 11 dígitos
                if (contato.length === 10) {
                    // Formato sem o dígito 9
                    contato = contato.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else if (contato.length === 11) {
                    // Formato com o dígito 9
                    contato = contato.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }

                // Atualiza o campo com o valor formatado
                $(this).val(contato);
            });
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesChamados.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>