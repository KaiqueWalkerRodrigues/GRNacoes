<?php 
    include_once('../const.php');  

    $Compras_Fornecedores = new Compras_Fornecedores();
    $Otica_Estoque = new Otica_Estoque();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Estoque Ótica</title>

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

                    <div class="col-md-3">
                        <label for="filtroMes" class="form-label">Mês:</label>
                        <select id="filtroMes" class="form-control" required>
                            <option value="">Selecione...</option>
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

                        <br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4 d-none" id="estoque_datatable">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Estoque por Fornecedor</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Fornecedor</th>
                                            <th>Quantidade</th>
                                            <th>Mês/Ano</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
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

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal Editar  -->
    <div class="modal fade" id="modalEditarFornecedor" tabindex="1" role="dialog" aria-labelledby="modalEditarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Fornecedor: <span class="modalEditarFornecedorLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_fornecedor" id="editar_id_compra_fornecedor">
                        <div class="row">
                            <div class="col-5 offset-1">
                                <label for="fornecedor" class="form-label">Nome do Fornecedor *</label>
                                <input type="text" name="fornecedor" id="editar_fornecedor" class="form-control" required>
                            </div>
                            <div class="col-5">
                                <label for="editar_id_categoria" class="form-label">Categoria *</label>
                                <select name="id_categoria" id="editar_id_categoria" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                        <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesOtica.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function() {
            $('#otic').addClass('active');
            $('#otica_estoque').addClass('active');

            $('#modalEditarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_fornecedor = button.data('id_fornecedor')
                let fornecedor = button.data('fornecedor')
                let id_categoria = button.data('id_categoria')
                $('.modalEditarFornecedorLabel').empty()
                $('.modalEditarFornecedorLabel').append(fornecedor)
                $('#editar_id_compra_fornecedor').empty()
                $('#editar_id_compra_fornecedor').val(id_fornecedor)
                $('#editar_fornecedor').val(fornecedor)
                $('#editar_id_categoria').val(id_categoria)
            })

            $('#modalDesativarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_fornecedor = button.data('id_fornecedor')
                let fornecedor = button.data('fornecedor')
                $('.modalDesativarFornecedorLabel').empty()
                $('.modalDesativarFornecedorLabel').append(fornecedor)
                $('#desativar_id_compra_fornecedor').empty()
                $('#desativar_id_compra_fornecedor').val(id_fornecedor)
            })

            $('#filtroMes').change(function (e) { 
                let mes_ano = $('#filtroMes').val();
                if(mes_ano == ''){
                    $('#estoque_datatable').addClass('d-none');
                } else {
                    // Atualiza o campo mes_ano nas linhas da tabela
                    $('.mes_ano').text(mes_ano);

                    // Faz a requisição AJAX para buscar os dados com base no mes_ano
                    $.ajax({
                        url: 'get_estoque.php', // Atualize com o caminho correto do seu arquivo PHP
                        type: 'POST',
                        data: { mes_ano: mes_ano },
                        success: function(response) {
                            // Atualiza a tabela com os dados recebidos
                            $('#estoque_datatable tbody').html(response);
                            $('#estoque_datatable').removeClass('d-none');
                        },
                        error: function(xhr, status, error) {
                            console.log('Erro ao carregar os dados: ' + error);
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>