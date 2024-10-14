<?php 
    include_once('../const.php');  

    $Compras_Notas = new Compras_Notas();

    $Compras_Fornecedores = new Compras_Fornecedores();

    $Compras_Categorias = new Compras_Categorias();

    if (isset($_POST['btnCadastrar'])) {
        $Compras_Notas->cadastrar($_POST);
        header('location:/GRNacoes/compras/notas');
    }
    if (isset($_POST['btnEditar'])) {
        $Compras_Notas->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Compras_Notas->desativar($_POST['id_compra_nota'],$_POST['usuario_logado']);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Notas Fiscais</title>

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

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroMes" class="form-label">Filtrar por Mês:</label>
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
                            <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
                            <select id="filtroEmpresa" class="form-control">
                                <option value="">Todos</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                                <!-- Adicione opções para as Empresas -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroCategoria" class="form-label">Filtrar por Categoria:</label>
                            <select id="filtroCategoria" class="form-control">
                                <option value="">Todos</option>
                                <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                    <option value="<?php echo $cc->categoria ?>"><?php echo $cc->categoria ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroFornecedor" class="form-label">Filtrar por Fornecedor:</label>
                            <select id="filtroFornecedor" class="form-control">
                                <option value="">Todos</option>
                                    <?php foreach($Compras_Fornecedores->listar() as $cf){ ?>
                                        <option value="<?php echo $cf->fornecedor ?>"><?php echo $cf->fornecedor ?> (<?php echo $Compras_Categorias->mostrar($cf->id_categoria)->categoria ?>)</option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Notas Fiscais | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarNota" class="collapse-item">Cadastrar Nova Nota</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">#</th>
                                            <th>N° Nota</th>
                                            <th>Valor</th>
                                            <th>Mês</th>
                                            <th>Data</th>
                                            <th>Empresa</th>
                                            <th>Categoria</th>
                                            <th>Fornecedor</th>
                                            <th class="d-none">Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Total:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="d-none"></th>
                                            <th>R$ <span id="totalValor"></span></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach($Compras_Notas->listar() as $cn){ ?>
                                        <tr>
                                            <td class="d-none"><?php echo $cn->id_compra_nota ?></td>
                                            <td class="text-center"><?php echo $cn->n_nota ?></td>
                                            <td><?php echo 'R$ ' . number_format($cn->valor, 2, ',', '.'); ?></td>
                                            <td><?php $dt = new DateTime($cn->data); $mesAbreviado = Helper::traduzirMes($dt->format('M')); $anoAbreviado = $dt->format('y'); $dataFormatada = $mesAbreviado . '/' . $anoAbreviado; echo $dataFormatada?></td>
                                            <td><?php $dt = new DateTime($cn->data); echo $dt->format('d/m/Y') ?></td>
                                            <td><?php echo Helper::mostrar_empresa($cn->id_empresa) ?></td>
                                            <td><?php echo $Compras_Categorias->nomeCategoria($Compras_Fornecedores->mostrar($cn->id_fornecedor)->id_categoria) ?></td>
                                            <td><?php echo $Compras_Fornecedores->nomeFornecedor($cn->id_fornecedor)?></td>
                                            <td class="d-none"><?php echo $cn->valor ?></td>
                                            <td class="text-center"><button class="btn btn-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                                data-descricao_nota="<?php echo $cn->descricao ?>"
                                                data-valor="<?php echo $cn->valor ?>"
                                                data-quantidade="<?php echo $cn->quantidade ?>">
                                                <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarNota" class="collapse-item"
                                                    data-id_compra_nota="<?php echo $cn->id_compra_nota ?>"
                                                    data-id_fornecedor="<?php echo $cn->id_fornecedor ?>"
                                                    data-n_nota="<?php echo $cn->n_nota ?>"
                                                    data-valor="<?php echo $cn->valor ?>"
                                                    data-data="<?php echo $cn->data ?>"
                                                    data-id_empresa="<?php echo $cn->id_empresa ?>"
                                                    data-quantidade="<?php echo $cn->quantidade ?>"
                                                    data-descricao="<?php echo $cn->descricao ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarNota" class="collapse-item"
                                                    data-n_nota="<?php echo $cn->n_nota ?>"
                                                    data-id_nota="<?php echo $cn->id_compra_nota ?>">
                                                    <i class="fa-solid fa-power-off"></i>
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

    <!-- Modal Ver Descrição -->
    <div class="modal fade" id="modalVerDescricao" tabindex="-1" role="dialog" aria-labelledby="modalVerDescricaoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Descrição da Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div><p id="descricao_nota"></p></div>
                </div>
                <div class="modal-footer">
                    <p>Qntd: <span id="ver_quantidade"></span> | R$ <span id="ver_valor"></span></p>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Cadastrar Nota -->
    <div class="modal fade" id="modalCadastrarNota" tabindex="1" role="dialog" aria-labelledby="modalCadastrarNotaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Nota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-4">
                                <label for="n_nota" class="form-label">Numero da Nota *</label>
                                <input type="number" id="cadastrar_n_nota" name="n_nota" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="valor" class="form-label">Valor da Nota *</label>
                                <input type="number" step="0.01" id="cadastrar_valor" lang="pt-br" name="valor" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="data" class="form-label">Data da Nota *</label>
                                <input type="date" id="cadastrar_data" name="data" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="cadastrar_id_empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="cadastrar_id_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="cadastrar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                        <?php foreach($Compras_Fornecedores->listar() as $cf){ ?>
                                            <option value="<?php echo $cf->id_compra_fornecedor ?>"><?php echo $cf->fornecedor ?> (<?php echo $Compras_Categorias->mostrar($cf->id_categoria)->categoria ?>)</option>
                                        <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" step="0.01" lang="pt-br" id="cadastrar_quantidade" name="quantidade" class="form-control" value="1">
                            </div>
                            <div class="col-8 offset-2">
                                <label for="cadastrar_descricao" class="form-label">Descricao</label>
                                <textarea name="descricao" id="cadastrar_descricao" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Nota -->
    <div class="modal fade" id="modalEditarNota" tabindex="1" role="dialog" aria-labelledby="modalEditarNotaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Nota: <span class="modalEditarNotaLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_nota" id="editar_id_compra_nota">
                        <div class="row">
                            <div class="col-4">
                                <label for="n_nota" class="form-label">Numero da Nota *</label>
                                <input type="number" id="editar_n_nota" name="n_nota" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="valor" class="form-label">Valor da Nota *</label>
                                <input type="number" step="0.01" id="editar_valor" name="valor" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="data" class="form-label">Data da Nota *</label>
                                <input type="date" id="editar_data" name="data" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="editar_id_empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" id="editar_id_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="editar_id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="editar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                        <?php foreach($Compras_Fornecedores->listar() as $cf){ ?>
                                            <option value="<?php echo $cf->id_compra_fornecedor ?>"><?php echo $cf->fornecedor ?> (<?php echo $Compras_Categorias->mostrar($cf->id_categoria)->categoria ?>)</option>
                                        <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" step="0.01" lang="pt-br" id="editar_quantidade" name="quantidade" class="form-control">
                            </div>
                            <div class="col-8 offset-2">
                                <label for="editar_descricao" class="form-label">Descricao</label>
                                <textarea name="descricao" id="editar_descricao" class="form-control" cols="30" rows="5"></textarea>
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

    <!-- Modal Excluir Nota-->
    <div class="modal fade" id="modalDesativarNota" tabindex="-1" role="dialog" aria-labelledby="modalDesativarNotaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar a Nota: <span class="modalDesativarNotaLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_nota" id="desativar_id_compra_nota">
                        <div class="row">
                            <p>Deseja Desativar a Nota: <span class="modalDesativarNotaLabel"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDesativar" class="btn btn-danger">Desativar</button>
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesNotas.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
$(document).ready(function() {
    $('#comp').addClass('active');
    $('#compras_notas').addClass('active');

    // Função para calcular e exibir a soma total dos valores
    function calcularSomaTotal() {
        var table = $('#dataTable').DataTable();
        var somaTotal = table
            .column(7, { search: 'applied' }) // Ajuste o índice da coluna conforme necessário
            .data()
            .reduce(function(a, b) {
                var x = parseFloat(a) || 0;
                var y = parseFloat(b) || 0;
                return x + y;
            }, 0);

        $('#totalValor').text(somaTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
    }

    // Verificar se o DataTable já está inicializado e inicializá-lo se não estiver
    if (!$.fn.DataTable.isDataTable('#dataTable')) {
        var table = $('#dataTable').DataTable({
            "drawCallback": function() {
                calcularSomaTotal();
            }
        });
    } else {
        var table = $('#dataTable').DataTable();
    }

    $('#dataTable_filter').keyup(function (e) { 
        calcularSomaTotal()
    });

    // Evento de alteração nos filtros
    $('#filtroMes, #filtroFornecedor, #filtroEmpresa, #filtroCategoria').change(function() {
        var filtroMes = $('#filtroMes').val();
        var filtroFornecedor = $('#filtroFornecedor').val();
        var filtroEmpresa = $('#filtroEmpresa').val();
        var filtroCategoria = $('#filtroCategoria').val();

        // Aplicar filtros no DataTable usando a API search
        table.column(3).search(filtroMes);
        table.column(7).search(filtroFornecedor);
        table.column(5).search(filtroEmpresa);
        table.column(6).search(filtroCategoria);

        // Redesenhar a tabela e calcular a soma total
        table.draw();
        calcularSomaTotal();
    });

    // Inicializar modais e outras funcionalidades
    $('#modalVerDescricao').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let descricao_nota = button.data('descricao_nota');
        let valor = button.data('valor');
        let quantidade = button.data('quantidade');

        // Formatar o valor com o padrão brasileiro
        let valorFormatado = parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

        $('#descricao_nota').text(descricao_nota);
        $('#ver_valor').text(valorFormatado);
        $('#ver_quantidade').text(quantidade);

        console.log(descricao_nota);
    });

    $('#modalEditarNota').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id_compra_nota = button.data('id_compra_nota');
        let quantidade = button.data('quantidade');
        let id_fornecedor = button.data('id_fornecedor');
        let n_nota = button.data('n_nota');
        let valor = button.data('valor');
        let data = button.data('data');
        let id_empresa = button.data('id_empresa');
        let descricao = button.data('descricao');

        $('#editar_id_compra_nota').val(id_compra_nota);
        $('#editar_id_fornecedor').val(id_fornecedor);
        $('#editar_quantidade').val(quantidade);
        $('#editar_n_nota').val(n_nota);
        $('#editar_valor').val(valor);
        $('#editar_data').val(data);
        $('#editar_id_empresa').val(id_empresa);
        $('#editar_descricao').val(descricao);
    });

    $('#modalDesativarNota').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id_nota = button.data('id_nota');
        let n_nota = button.data('n_nota');
        $('.modalDesativarNotaLabel').empty();
        $('.modalDesativarNotaLabel').append(n_nota);
        $('#desativar_id_compra_nota').empty();
        $('#desativar_id_compra_nota').val(id_nota);
    });

    // Calcular a soma total ao carregar a página
    calcularSomaTotal();
});

    </script>



</body>

</html>