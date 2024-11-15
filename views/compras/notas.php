<?php 
    $Compra_Nota = new Compra_Nota();

    $Compra_Fornecedor = new Compra_Fornecedor();

    $Compra_Categoria = new Compra_Categoria();

    if (isset($_POST['btnCadastrar'])) {
        $Compra_Nota->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Compra_Nota->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Compra_Nota->deletar($_POST['id_compra_nota'],$_POST['usuario_logado']);
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
                                <span>Notas</span>
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
                                    <label for="filtroEmpresa" class="form-label text-light">Filtrar por Empresa:</label>
                                    <select id="filtroEmpresa" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Clínica Parque">Clínica Parque</option>
                                        <option value="Clínica Mauá">Clínica Mauá</option>
                                        <option value="Clínica Jardim">Clínica Jardim</option>
                                        <option value="Ótica Matriz">Ótica Matriz</option>
                                        <option value="Ótica Prestigio">Ótica Prestigio</option>
                                        <option value="Ótica Daily">Ótica Daily</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroCategoria" class="form-label text-light">Filtrar por Categoria:</label>
                                    <select id="filtroCategoria" class="form-control">
                                        <option value="">Todos</option>
                                        <?php foreach($Compra_Categoria->listar() as $cc){ ?>
                                            <option value="<?php echo $cc->categoria ?>"><?php echo $cc->categoria ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroFornecedor" class="form-label text-light">Filtrar por Fornecedor:</label>
                                    <select id="filtroFornecedor" class="form-control">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Notas
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarNota">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Created_at</th>
                                            <th>N° Nota</th>
                                            <th>Valor</th>
                                            <th>Mês</th>
                                            <th>Data</th>
                                            <th>Empresa</th>
                                            <th>Categoria</th>
                                            <th>Fornecedor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Compra_Nota->listar() as $cn){ ?>
                                        <tr>
                                            <td class="d-none"><?php echo $cn->created_at?></td>
                                            <td class="text-center"><?php echo $cn->n_nota ?></td>
                                            <td><?php echo 'R$ ' . number_format($cn->valor, 2, ',', '.'); ?></td>
                                            <td><?php $dt = new DateTime($cn->data); $mesAbreviado = Helper::traduzirMes($dt->format('M')); $anoAbreviado = $dt->format('y'); $dataFormatada = $mesAbreviado . '/' . $anoAbreviado; echo $dataFormatada?></td>
                                            <td><?php $dt = new DateTime($cn->data); echo $dt->format('d/m/Y') ?></td>
                                            <td><?php echo Helper::mostrar_empresa($cn->id_empresa) ?></td>
                                            <td><?php echo $Compra_Categoria->nomeCategoria($Compra_Fornecedor->mostrar($cn->id_fornecedor)->id_categoria) ?></td>
                                            <td><?php echo $Compra_Fornecedor->nomeFornecedor($cn->id_fornecedor)?></td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                                data-descricao_nota="<?php echo $cn->descricao ?>"
                                                data-valor="<?php echo $cn->valor ?>"
                                                data-quantidade="<?php echo $cn->quantidade ?>">
                                                <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarNota" class="collapse-item"
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
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarNota" class="collapse-item"
                                                    data-n_nota="<?php echo $cn->n_nota ?>"
                                                    data-id_nota="<?php echo $cn->id_compra_nota ?>">
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
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
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
                                <div class="invalid-feedback" id="n_nota_feedback">
                                    Este número de nota já está cadastrado.
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="data" class="form-label">Data da Nota *</label>
                                <input type="date" id="cadastrar_data" name="data" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="valor" class="form-label">Valor da Nota *</label>
                                <input type="number" step="0.01" id="cadastrar_valor" lang="pt-br" name="valor" class="form-control" required>
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
                                        <?php foreach($Compra_Fornecedor->listar() as $cf){ ?>
                                            <option value="<?php echo $cf->id_compra_fornecedor ?>"><?php echo $cf->fornecedor ?> (<?php echo $Compra_Categoria->mostrar($cf->id_categoria)->categoria ?>)</option>
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
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-primary">Cadastrar</button>
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
                                <label for="data" class="form-label">Data da Nota *</label>
                                <input type="date" id="editar_data" name="data" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="valor" class="form-label">Valor da Nota *</label>
                                <input type="number" step="0.01" id="editar_valor" name="valor" class="form-control" required>
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
                                        <?php foreach($Compra_Fornecedor->listar() as $cf){ ?>
                                            <option value="<?php echo $cf->id_compra_fornecedor ?>"><?php echo $cf->fornecedor ?> (<?php echo $Compra_Categoria->mostrar($cf->id_categoria)->categoria ?>)</option>
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
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Nota-->
    <div class="modal fade" id="modalDeletarNota" tabindex="-1" role="dialog" aria-labelledby="modalDeletarNotaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar a Nota: <span class="modalDeletarNotaLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_nota" id="Deletar_id_compra_nota">
                        <div class="row">
                            <p>Deseja Deletar a Nota: <span class="modalDeletarNotaLabel"></span></p>
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
            $('#comp').addClass('active')
            $('#compra_notas').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#dataTable_filter').keyup(function (e) { 
                calcularSomaTotal()
            });

            $('#cadastrar_data').on('change', function () {
                const dataSelecionada = new Date($(this).val());
                const dataHoje = new Date();
                dataHoje.setDate(dataHoje.getDate());
                
                if (dataSelecionada > dataHoje) {
                    alert('Data inválida: a data não pode ser maior que hoje.');
                    $('#cadastrar_data').val(''); // Limpa o campo de data
                    $('#btnCadastrar').prop('disabled', true); // Desabilita o botão "Continuar"
                } else {
                    $('#btnCadastrar').prop('disabled', false); // Habilita o botão "Continuar" se a data for válida
                }
            });

            // Evento de alteração nos filtros
            $('#filtroMes, #filtroFornecedor, #filtroEmpresa, #filtroCategoria').change(function() {
                var filtroMes = $('#filtroMes').val();
                var filtroFornecedor = $('#filtroFornecedor').val();
                var filtroEmpresa = $('#filtroEmpresa').val();
                var filtroCategoria = $('#filtroCategoria').val();

                // Aplicar filtros no DataTable usando a API search
                var table = $('#dataTable').DataTable();
                table.column(3).search(filtroMes);
                table.column(7).search(filtroFornecedor);
                table.column(5).search(filtroEmpresa);
                table.column(6).search(filtroCategoria);

                // Redesenhar a tabela e calcular a soma total
                table.draw();
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

            $('#modalDeletarNota').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_nota = button.data('id_nota');
                let n_nota = button.data('n_nota');
                $('.modalDeletarNotaLabel').empty();
                $('.modalDeletarNotaLabel').append(n_nota);
                $('#Deletar_id_compra_nota').empty();
                $('#Deletar_id_compra_nota').val(id_nota);
            });
            // Outras inicializações...

            // Função para atualizar o filtro de fornecedores com base na categoria selecionada
            $('#filtroCategoria').on('change', function () {
                var categoriaSelecionada = $(this).val();

                buscarFornecedores(categoriaSelecionada);
            });

            buscarFornecedores();

            function buscarFornecedores(categoriaSelecionada){
                $.ajax({
                    url: '/GRNacoes/views/ajax/get_fornecedores.php',
                    type: 'GET',
                    data: { categoria: categoriaSelecionada },
                    dataType: 'json',
                    success: function (fornecedores) {
                        // Limpa as opções do seletor de fornecedores
                        $('#filtroFornecedor').empty();
                        
                        // Adiciona a opção de todos no início
                        $('#filtroFornecedor').append('<option value="">Todos</option>');

                        // Popula o seletor com os fornecedores recebidos
                        $.each(fornecedores, function (index, fornecedor) {
                            $('#filtroFornecedor').append('<option value="' + fornecedor.fornecedor + '">' + fornecedor.fornecedor + ' ('+fornecedor.categoria+')</option>');
                        });
                    },
                    error: function () {
                        $('#filtroFornecedor').append('<option value="">ERRO</option>');
                    }
                });
            }

            // ** Início da Implementação da Verificação do n_nota **
            $('#cadastrar_n_nota').on('blur input', function () {
                var n_nota = $(this).val().trim();

                if (n_nota === '') {
                    // Se o campo estiver vazio, remova qualquer feedback de erro
                    $(this).removeClass('is-invalid');
                    $('#btnCadastrar').prop('disabled', false);
                    return;
                }

                $.ajax({
                    url: '/GRNacoes/views/ajax/check_numero_nota.php',
                    type: 'GET',
                    data: { n_nota: n_nota },
                    dataType: 'json',
                    success: function (response) {
                        if (response.exists) {
                            // Se a nota já existir, mostrar feedback de erro
                            $('#cadastrar_n_nota').addClass('is-invalid');
                            $('#btnCadastrar').prop('disabled', true);
                        } else if (response.error) {
                            // Em caso de erro no servidor, você pode optar por permitir ou não
                            // Aqui, vamos apenas remover qualquer feedback de erro
                            $('#cadastrar_n_nota').removeClass('is-invalid');
                            $('#btnCadastrar').prop('disabled', false);
                            alert(response.error);
                        } else {
                            // Se a nota não existir, remover feedback de erro
                            $('#cadastrar_n_nota').removeClass('is-invalid');
                            $('#btnCadastrar').prop('disabled', false);
                        }
                    },
                    error: function () {
                        // Em caso de falha na requisição, você pode optar por mostrar uma mensagem ou não
                        $('#cadastrar_n_nota').removeClass('is-invalid');
                        $('#btnCadastrar').prop('disabled', false);
                        alert('Erro ao verificar o número da nota.');
                    }
                });
            });

            // ** Opcional: Remover o feedback de erro ao digitar novamente **
            $('#cadastrar_n_nota').on('input', function () {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $('#btnCadastrar').prop('disabled', false);
                }
            });
            // ** Fim da Implementação da Verificação do n_nota **

        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-notas.js"></script>
</body>

</html>
