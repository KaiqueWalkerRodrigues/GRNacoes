<?php
$Usuario = new Usuario();
$Exame = new Exame();

if (isset($_POST['btnCadastrar'])) {
    $Exame->cadastrar($_POST);
}
if (isset($_POST['btnEditar'])) {
    $Exame->editar($_POST);
}
if (isset($_POST['btnDeletar'])) {
    $Exame->deletar($_POST['id_exame'], $_POST['usuario_logado']);
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
</head>

<body class="nav-fixed">
    <!-- Tela de Carregamento -->
    <!-- <div id="preloader">
        <div class="spinner"></div>
    </div> -->
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
                                <span>Exames</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Exames
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarExame">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Exame</th>
                                            <th>Valor Particular</th>
                                            <th>Valor Fidelidade</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($Exame->listar() as $exame) { ?>
                                            <tr>
                                                <td><?php echo $exame->exame ?></td>
                                                <td class="text-center">R$ <?php echo number_format($exame->valor_particular, 2, ',', '.'); ?> </td>
                                                <td class="text-center">R$ <?php echo number_format($exame->valor_fidelidade, 2, ',', '.'); ?> </td>
                                                <td>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarExame"
                                                        data-exame="<?php echo $exame->exame ?>"
                                                        data-idexame="<?php echo $exame->id_exame ?>"
                                                        data-valor_particular="<?php echo $exame->valor_particular ?>"
                                                        data-valor_fidelidade="<?php echo $exame->valor_fidelidade ?>"><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarExame"
                                                        data-exame="<?php echo $exame->exame ?>"
                                                        data-idexame="<?php echo $exame->id_exame ?>"><i class="fa-solid fa-trash"></i></button>
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

    <!-- Modal Cadastrar Exame -->
    <div class="modal fade" id="modalCadastrarExame" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarExameLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarExameLabel">Cadastrar Novo Exame</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="exame" class="form-label">Exame *</label>
                                <input type="text" class="form-control" name="exame" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="offset-1 col-4">
                                <label for="valor_particular" class="form-label">Valor Particular (R$)*</label>
                                <input type="text" name="valor_particular" step="0.01" class="form-control">
                            </div>
                            <div class="col-4">
                                <label for="valor_fidelidade" class="form-label">Valor Fidelidade (R$)*</label>
                                <input type="text" name="valor_fidelidade" step="0.01" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrar">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Exame -->
    <div class="modal fade" id="modalEditarExame" tabindex="-1" role="dialog" aria-labelledby="modalEditarExameLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarExameLabel">Editar o Exame: <span id="editar_exame_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_exame" id="editar_id_exame">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="editar_exame" class="form-label">Exame *</label>
                                <input type="text" class="form-control" name="exame" id="editar_exame" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4 offset-1">
                                <label for="valor_particular" class="form-label">Valor Particular (R$)*</label>
                                <input type="text" id="editar_valor_particular" name="valor_particular" step="0.01" class="form-control">
                            </div>
                            <div class="col-4">
                                <label for="valor_fidelidade" class="form-label">Valo Fidelidade (R$)*</label>
                                <input type="text" id="editar_valor_fidelidade" name="valor_fidelidade" step="0.01" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary" type="submit" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Exame -->
    <div class="modal fade" id="modalDeletarExame" tabindex="-1" role="dialog" aria-labelledby="modalDeletarExameLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarExameLabel">Deletar o Exame: <span class="deletar_exame_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_exame" id="deletar_id_exame">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar o Exame <br> <b class="deletar_Exame_nome"></b>? <br> Essa ação é Irreversível.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletar">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function() {
            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });
        });

        $(document).ready(function() {
            $('#conf').addClass('active')
            $('#configuracoes_exames').addClass('active')

            $('#preloader').fadeOut('slow', function() {
                $(this).remove();
            });

            $('#modalEditarExame').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let exame = button.data('exame');
                let valor_particular = button.data('valor_particular');
                let valor_fidelidade = button.data('valor_fidelidade');
                let id_exame = button.data('idexame');

                $('#editar_exame_nome').text(exame);
                $('#editar_id_exame').val(id_exame);
                $('#editar_exame').val(exame);
                $('#editar_valor_particular').val(valor_particular);
                $('#editar_valor_fidelidade').val(valor_fidelidade);
            });

            $('#modalDeletarExame').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let exame = button.data('exame');
                let id_exame = button.data('idexame');

                $('.deletar_exame_nome').text(exame);
                $('#deletar_id_exame').val(id_exame);
                $('#deletar_exame').val(exame);
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-exames.js"></script>
</body>

</html>