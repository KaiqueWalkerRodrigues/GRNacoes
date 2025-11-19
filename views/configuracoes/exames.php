<?php
$Usuario = new Usuario();
$Exame = new Exame();
$Pacote = new Pacote_Exame();

if (isset($_POST['btnCadastrar'])) {
    $Exame->cadastrar($_POST);
}
if (isset($_POST['btnEditar'])) {
    $Exame->editar($_POST);
}
if (isset($_POST['btnDeletar'])) {
    $Exame->deletar($_POST['id_exame'], $_POST['usuario_logado']);
}
if (isset($_POST['btnCadastrarPacote'])) {
    $Pacote->cadastrar($_POST);
}

if (isset($_POST['btnEditarPacote'])) {
    $Pacote->editar($_POST);
}

if (isset($_POST['btnDeletarPacote'])) {
    $Pacote->deletar((int)$_POST['id_pacote'], $_POST['usuario_logado']);
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
                            <button class="btn btn-datatable btn-sm btn-primary ml-2" type="button" data-toggle="modal" data-target="#modalPacotes">
                                Pacotes
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

    <!-- Modal Pacotes -->
    <div class="modal fade" id="modalPacotes" tabindex="-1" role="dialog" aria-labelledby="modalPacotesLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPacotesLabel">Pacotes</h5>
                    <button class="close text-white" type="button" data-dismiss="modal"><span>×</span></button>
                </div>

                <div class="modal-body">
                    <!-- Botão cadastrar -->
                    <button class="btn btn-success btn-icon btn-sm mb-3" type="button" data-toggle="modal" data-target="#modalCadastrarPacote">
                        <i class="fa-solid fa-plus"></i>
                    </button>

                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTablePacote" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Pacote</th>
                                    <th>Valor Fidelidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Pacote->listarTodos() as $pacote) { ?>
                                    <tr>
                                        <td><?php echo $pacote->pacote ?> (<?php echo $pacote->empresa_nome ?>)</td>
                                        <td class="text-center">R$ <?php echo number_format($pacote->valor_fidelidade, 2, ',', '.') ?></td>
                                        <td>
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                data-toggle="modal"
                                                data-target="#modalEditarPacote"
                                                data-idpacote="<?php echo $pacote->id_exames_pacote ?>"
                                                data-pacote="<?php echo $pacote->pacote ?>"
                                                data-empresa="<?php echo $pacote->id_empresa ?>"
                                                data-valor_fidelidade="<?php echo $pacote->valor_fidelidade ?>"
                                                data-exames="<?php echo implode(',', $Pacote->listarExamesDoPacote($pacote->id_exames_pacote, $pacote->id_empresa)) ?>">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>

                                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-toggle="modal"
                                                data-target="#modalDeletarPacote"
                                                data-idpacote="<?php echo $pacote->id_exames_pacote ?>"
                                                data-pacote="<?php echo $pacote->pacote ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar Pacote -->
    <div class="modal fade" id="modalCadastrarPacote" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarPacoteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarPacoteLabel">Cadastrar Novo Pacote</h5>
                        <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">

                        <div class="row">
                            <div class="col-6">
                                <label for="pacote" class="form-label">Nome do Pacote *</label>
                                <input type="text" name="pacote" class="form-control" required>
                            </div>

                            <div class="col-3">
                                <label for="empresa" class="form-label">Empresa *</label>
                                <select name="id_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <label for="valor_fidelidade" class="form-label">Valor Fidelidade (R$)*</label>
                                <input type="text" name="valor_fidelidade" class="form-control" required>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Selecione os Exames do Pacote</h5>

                        <div class="row">
                            <?php foreach ($Exame->listar() as $exame) { ?>
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="exames[]"
                                            value="<?php echo $exame->id_exame; ?>"
                                            id="ex<?php echo $exame->id_exame; ?>">

                                        <label class="form-check-label" for="ex<?php echo $exame->id_exame; ?>">
                                            <?php echo $exame->exame; ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrarPacote">Cadastrar</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Pacote -->
    <div class="modal fade" id="modalEditarPacote" tabindex="-1" aria-labelledby="modalEditarPacoteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarPacoteLabel">Editar Pacote: <span id="edit_nome_pacote"></span></h5>
                        <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_pacote" id="edit_id_pacote">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Pacote *</label>
                                <input type="text" class="form-control" id="edit_pacote" name="pacote" required>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Empresa *</label>
                                <select name="id_empresa" id="edit_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <label class="form-label">Valor Fidelidade (R$)*</label>
                                <input type="text" class="form-control" id="edit_valor_fidelidade" name="valor_fidelidade" required>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Exames do Pacote</h5>

                        <div class="row">
                            <?php foreach ($Exame->listar() as $exame) { ?>
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input edit_exame_checkbox"
                                            type="checkbox"
                                            name="exames[]"
                                            value="<?php echo $exame->id_exame; ?>"
                                            id="edit_ex<?php echo $exame->id_exame; ?>">

                                        <label class="form-check-label" for="edit_ex<?php echo $exame->id_exame; ?>">
                                            <?php echo $exame->exame; ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary" type="submit" name="btnEditarPacote">Salvar</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Pacote -->
    <div class="modal fade" id="modalDeletarPacote" tabindex="-1" aria-labelledby="modalDeletarPacoteLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Pacote: <span id="del_nome_pacote"></span></h5>
                        <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                    </div>

                    <div class="modal-body text-center">
                        <input type="hidden" name="id_pacote" id="del_id_pacote">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">

                        <p>
                            Tem certeza que deseja excluir o pacote <br>
                            <b id="del_nome_pacote_bold"></b>?<br>
                            Esta ação é irreversível.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletarPacote">Deletar</button>
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

            $('#modalEditarPacote').on('show.bs.modal', function(event) {

                let button = $(event.relatedTarget);

                let id = button.data('idpacote');
                let nome = button.data('pacote');
                let empresa = button.data('empresa');
                let valor = button.data('valor_fidelidade');

                let exames = button.data('exames').toString().split(','); // array de IDs

                $('#edit_id_pacote').val(id);
                $('#edit_pacote').val(nome);
                $('#edit_valor_fidelidade').val(valor);
                $('#edit_empresa').val(empresa);
                $('#edit_nome_pacote').text(nome);

                // Desmarca tudo
                $('.edit_exame_checkbox').prop('checked', false);

                // Marca somente os exames do pacote
                exames.forEach(idEx => {
                    $('#edit_ex' + idEx).prop('checked', true);
                });

            });


        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-exames.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-pacote_exames.js"></script>
</body>

</html>