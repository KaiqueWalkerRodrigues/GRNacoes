<?php 
    $Lc_modelo = new Lente_contato_Modelo();
    $Lc_fornecedor = new Lente_contato_fornecedor();
    $Lc_classificacao = new Lente_contato_classificacao();

    if (isset($_POST['btnCadastrar'])) {
       $Lc_modelo->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_modelo->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_modelo->deletar($_POST['id_lente_contato_modelo'],$_POST['usuario_logado']);
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
                                <span>Modelos de Lente de Contato</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Modelos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarModelo">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php if(verificarSetor([1])){ ?>
                                                <th>#</th>
                                            <?php } ?>
                                            <th>Cód Simah</th>
                                            <th>Nome Modelo</th>
                                            <th>Fornecedor</th>
                                            <th>Valor Custo</th>
                                            <th>Valor Venda</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_modelo->listar() as $modelo){ ?>
                                        <tr>
                                            <?php if(verificarSetor([1])){ ?>
                                                <td><?php echo $modelo->id_lente_contato_modelo ?></td>
                                            <?php } ?>
                                            <td><?php echo $modelo->codigo_simah ?></td>
                                            <td><?php echo $modelo->modelo ?></td>
                                            <td><?php echo $Lc_fornecedor->mostrar($modelo->id_fornecedor)->fornecedor ?></td>
                                            <td class="text-center">R$ <?php echo number_format($modelo->valor_custo, 2, ',', '.'); ?></td>
                                            <td class="text-center">R$ <?php echo number_format($modelo->valor_venda, 2, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarModelo"
                                                        data-id_lente_contato_modelo="<?php echo $modelo->id_lente_contato_modelo ?>"
                                                        data-codigo_simah="<?php echo $modelo->codigo_simah ?>"
                                                        data-modelo="<?php echo $modelo->modelo ?>"
                                                        data-id_fornecedor="<?php echo $modelo->id_fornecedor ?>"
                                                        data-id_classificacao="<?php echo $modelo->id_classificacao ?>"
                                                        data-unidade="<?php echo $modelo->unidade ?>"
                                                        data-valor_custo="<?php echo $modelo->valor_custo ?>"
                                                        data-valor_venda="<?php echo $modelo->valor_venda ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarModelo"
                                                        data-id_lente_contato_modelo="<?php echo $modelo->id_lente_contato_modelo ?>"
                                                        data-modelo="<?php echo $modelo->modelo ?>">
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

    <!-- Modal Cadastrar Modelo -->
    <div class="modal fade" id="modalCadastrarModelo" tabindex="1" role="dialog" aria-labelledby="modalCadastrarModeloLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Modelo<h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-2 offset-1">
                                <label for="cadastrar_codigo_simah" class="form-label">Cód. Simah *</label>
                                <input type="number" name="codigo_simah" id="cadastrar_codigo_simah" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="cadastrar_modelo" class="form-label">Nome Modelo *</label>
                                <input type="text" name="modelo" id="cadastrar_modelo" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="cadastrar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_id_classificacao" class="form-label">Classificação *</label>
                                <select name="id_classificacao" id="cadastrar_id_classificacao" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_classificacao->listar() as $classificacao){ ?>
                                        <option value="<?php echo $classificacao->id_lente_contato_classificacao ?>"><?php echo $classificacao->classificacao ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2 offset-2">
                                <label for="cadastrar_unidade" class="form-label">Unidade/Caixa *</label>
                                <select name="unidade" id="cadastrar_unidade" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Unidade</option>
                                    <option value="1">Caixa</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_valor_custo" class="form-label">Valor de Custo *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor_custo" id="cadastrar_valor_custo" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_valor_venda" class="form-label">Valor de Venda *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor_venda" id="cadastrar_valor_venda" required>
                                </div>
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

    <!-- Modal Editar Modelo -->
    <div class="modal fade" id="modalEditarModelo" tabindex="-1" role="dialog" aria-labelledby="modalEditarModeloLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Modelo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <input type="hidden" name="id_lente_contato_modelo" id="editar_id_lente_contato_modelo">
                            <div class="col-2 offset-1">
                                <label for="editar_codigo_simah" class="form-label">Cód. Simah *</label>
                                <input type="number" name="codigo_simah" id="editar_codigo_simah" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="editar_modelo" class="form-label">Nome Modelo *</label>
                                <input type="text" name="modelo" id="editar_modelo" class="form-control" required>
                            </div>
                            <div class="col-2">
                                <label for="editar_id_fornecedor" class="form-label">Fornecedor *</label>
                                <select name="id_fornecedor" id="editar_id_fornecedor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                        <option value="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"><?php echo $fornecedor->fornecedor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="editar_id_classificacao" class="form-label">Classificação *</label>
                                <select name="id_classificacao" id="editar_id_classificacao" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Lc_classificacao->listar() as $classificacao){ ?>
                                        <option value="<?php echo $classificacao->id_lente_contato_classificacao ?>"><?php echo $classificacao->classificacao ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2 offset-2">
                                <label for="editar_unidade" class="form-label">Unidade/Caixa *</label>
                                <select name="unidade" id="editar_unidade" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Unidade</option>
                                    <option value="1">Caixa</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="editar_valor_custo" class="form-label">Valor de Custo *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor_custo" id="editar_valor_custo" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="editar_valor_venda" class="form-label">Valor de Venda *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" step="0.01" name="valor_venda" id="editar_valor_venda" required>
                                </div>
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

    <!-- Modal Deletar Modelo -->
    <div class="modal fade" id="modalDeletarModelo" tabindex="-1" role="dialog" aria-labelledby="modalDeletarModeloLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Modelo: <span class="modalDeletarModeloLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_lente_contato_modelo" id="deletar_id_lente_contato_modelo">
                        <input type="hidden" name="usuario_logado" value="<?php echo htmlspecialchars($_SESSION['id_usuario']) ?>" required>
                        <div class="container row">
                            <p>Deseja deletar a modelo: <strong><span class="modalDeletarModeloLabel"></span></strong>?</p>
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
            $('#lente_contato_configuracoes').addClass('active')
            $('#lente_contato_configuracoes_modelos').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarModelo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou a modal
                var id_lente_contato_modelo = button.data('id_lente_contato_modelo');
                var codigo_simah = button.data('codigo_simah');
                var modelo = button.data('modelo');
                var id_fornecedor = button.data('id_fornecedor');
                var id_classificacao = button.data('id_classificacao');
                var unidade = button.data('unidade');
                var valor_custo = button.data('valor_custo');
                var valor_venda = button.data('valor_venda');

               $('#editar_id_lente_contato_modelo').val(id_lente_contato_modelo);
               $('#editar_codigo_simah').val(codigo_simah);
               $('#editar_modelo').val(modelo);
               $('#editar_id_fornecedor').val(id_fornecedor);
               $('#editar_id_classificacao').val(id_classificacao);
               $('#editar_unidade').val(unidade);
               $('#editar_valor_custo').val(valor_custo);
               $('#editar_valor_venda').val(valor_venda);
            });

            $('#modalDeletarModelo').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou a modal
                var id_lente_contato_modelo = button.data('id_lente_contato_modelo');
                var modelo = button.data('modelo');

                $('#deletar_id_lente_contato_modelo').val(id_lente_contato_modelo);
                $('.modalDeletarModeloLabel').text(modelo)
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_modelos.js"></script>
</body>

</html>
