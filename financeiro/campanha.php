<?php 
    include_once('../const.php'); 
    $c = $_GET['c'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Campanha <?php echo $c ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

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

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <h3>Campanha <?php echo $c ?></h3>
                    <p>
                        Fechamento: <span style="color:red;font-style:italic;">15/04/2024 - 15/05/2024</span>
                        <br>
                        Pós: <span style="color:red;font-style:italic;">15/06/2024 - 15/07/2024</span>
                    </p>

                    <div class="row mb-4">
                        <div class="col-3">
                            <label for="filtroVendedor" class="form-label">Filtrar por Vendedor:</label>
                            <select id="filtroVendedor" class="form-control">
                                <option value="">Todos</option>
                                <option value="Ana">Ana</option>
                                <option value="Alan">Alan</option>
                                <option value="Suzana">Suzana</option>
                                <!-- Adicione opções para os meses -->
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
                            <select id="filtroEmpresa" class="form-control">
                                <option value="">Todos</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                                <!-- Adicione opções para os meses -->
                            </select>
                        </div>
                        <div class="col-2 offset-4" style="margin-top: 15px;">
                            <button class="btn btn-primary mt-4">Gerar Relatório</button>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Campanha <?php echo $c ?> | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarBoleto" class="collapse-item">Cadastrar Novo Boleto</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>N° Boleto</th>
                                            <th>Vendedor</th>
                                            <th>Empresa</th>
                                            <th>Data do Boleto</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <th>Total: </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>R$ 800,00</th>
                                        <th></th>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td>000615412</td>
                                            <td>Suzana</td>
                                            <td>Clínica Parque</td>
                                            <td class="text-danger">20/05/2024</td>
                                            <td>R$ 500,00</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarBoleto" class="collapse-item"><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirBoleto" class="collapse-item"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>000615664</td>
                                            <td>Alan</td>
                                            <td>Ótica Prestigio</td>
                                            <td class="text-success">16/04/2024</td>
                                            <td>R$ 300,00</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarBoleto" class="collapse-item"><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirBoleto" class="collapse-item"><i class="fa-solid fa-trash"></i></button>
                                            </td>
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
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Modal Cadastrar Boleto -->
    <div class="modal fade" id="modalCadastrarBoleto" tabindex="1" role="dialog" aria-labelledby="modalCadastrarBoletoLabel" aria-hidden="true">
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
                        <div class="col-4">
                            <label for="numero" class="form-label">Numero da Boleto *</label>
                            <input type="text" name="numero" id="cadastrar_numero" class="form-control">
                        </div>
                        <div class="col-4">
                            <label for="Empresa" class="form-label">Empresa *</label>
                            <select name="Empresa" id="cadastrar_Empresa" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="Vendedor" class="form-label">Vendedor *</label>
                            <select name="Vendedor" id="cadastrar_Vendedor" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="Ana">Ana</option>
                                <option value="Alan">Alan</option>
                                <option value="Suzana">Suzana</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="valor" class="form-label">Valor *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor">
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="data_boleto" class="form-label">Data do Boleto *</label>
                            <input type="date" name="data_boleto" id="data_boleto" class="form-control" value="<?php $today = date("Y-m-d"); echo $today ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Cargo -->
    <div class="modal fade" id="modalEditarBoleto" tabindex="1" role="dialog" aria-labelledby="modalEditarBoletoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Boleto: 000615664</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <label for="numero" class="form-label">Numero da Boleto *</label>
                            <select name="vendedor" id="cadastrar_vendedor" class="form-control">
                                <option value="">Selecione...</option>
                                <option value="Ana">Ana</option>
                                <option value="Alan">Alan</option>
                                <option value="Suzana">Suzana</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="Empresa" class="form-label">Empresa *</label>
                            <select name="Empresa" id="cadastrar_Empresa" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="valor" class="form-label">Valor *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor">
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="data_boleto" class="form-label">Data do Boleto *</label>
                            <input type="date" name="data_boleto" id="data_boleto" class="form-control" value="<?php $today = date("Y-m-d"); echo $today ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Excluir Boleto-->
    <div class="modal fade" id="modalExcluirBoleto" tabindex="-1" role="dialog" aria-labelledby="modalExcluirBoletoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExcluirBoletoLabel">Excluir a Boleto: 000615664</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p>Deseja Excluir a Boleto: 000615664</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#finan').addClass('active');
            $('#financeiro_campanhas').addClass('active');
            
            // Evento de alteração nos filtros
            $('#filtroVendedor, #filtroEmpresa').change(function() {
                var filtroVendedor = $('#filtroVendedor').val();
                var filtroEmpresa = $('#filtroEmpresa').val();
                
                // Mostrar todas as linhas inicialmente
                $('#dataTable tbody tr').show();
                
                // Iterar sobre as linhas da tabela para aplicar os filtros
                $('#dataTable tbody tr').each(function() {
                    var Vendedor = $(this).find('td:eq(1)').text();
                    var Empresa = $(this).find('td:eq(2)').text();
                    
                    // Verificar se a linha atende aos critérios de filtragem
                    if ((filtroVendedor && Vendedor !== filtroVendedor) || 
                    (filtroEmpresa && Empresa !== filtroEmpresa)) {
                        $(this).hide(); // Ocultar a linha se não atender aos critérios
                    }
                });
            });
        });

        $('#cadastrar_Empresa').change(function (e) { 
            let Empresa = $(this).val();
            
            // Limpar as opções atuais do select
            $('#cadastrar_categoria').empty();
            
            // Adicionar novas opções baseadas no valor do input
            if (Empresa === 'parque') {
                $('#cadastrar_categoria').append('<option value="">Selecione...</option>');
                $('#cadastrar_categoria').append('<option value="a">Opção A</option>');
                $('#cadastrar_categoria').append('<option value="b">Opção B</option>');
                $('#cadastrar_categoria').append('<option value="c">Opção C</option>');
            }else if (Empresa === 'maua') {
                $('#cadastrar_categoria').append('<option value="">Selecione...</option>');
                $('#cadastrar_categoria').append('<option value="a">Opção E</option>');
                $('#cadastrar_categoria').append('<option value="b">Opção F</option>');
                $('#cadastrar_categoria').append('<option value="c">Opção G</option>');
            } else if (Empresa === 'jardim') {
                $('#cadastrar_categoria').append('<option value="">Selecione...</option>');
                $('#cadastrar_categoria').append('<option value="a">Opção H</option>');
                $('#cadastrar_categoria').append('<option value="b">Opção I</option>');
                $('#cadastrar_categoria').append('<option value="c">Opção J</option>');
            } else {
                $('#cadastrar_categoria').append('<option value="">ERRO!!</option>');
            }
        });
        $('#cadastrar_categoria').change(function (e) { 
            let Empresa = $('#cadastrar_Empresa').val();
            let categoria = $(this).val();
            
            // Limpar as opções atuais do select
            $('#cadastrar_fornecedor').empty();
            
            // Adicionar novas opções baseadas no valor do input
            if (categoria === 'a' && Empresa === 'parque') {
                $('#cadastrar_fornecedor').append('<option value="">Selecione...</option>');
                $('#cadastrar_fornecedor').append('<option value="a">Opção A</option>');
                $('#cadastrar_fornecedor').append('<option value="b">Opção B</option>');
                $('#cadastrar_fornecedor').append('<option value="c">Opção C</option>');
            }else if (categoria === 'b') {
                $('#cadastrar_fornecedor').append('<option value="">Selecione...</option>');
                $('#cadastrar_fornecedor').append('<option value="a">Opção E</option>');
                $('#cadastrar_fornecedor').append('<option value="b">Opção F</option>');
                $('#cadastrar_fornecedor').append('<option value="c">Opção G</option>');
            } else if (categoria === 'c') {
                $('#cadastrar_fornecedor').append('<option value="">Selecione...</option>');
                $('#cadastrar_fornecedor').append('<option value="a">Opção H</option>');
                $('#cadastrar_fornecedor').append('<option value="b">Opção I</option>');
                $('#cadastrar_fornecedor').append('<option value="c">Opção J</option>');
            } else {
                $('#cadastrar_fornecedor').append('<option value="">ERRO!!</option>');
            }
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesBoletos.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>