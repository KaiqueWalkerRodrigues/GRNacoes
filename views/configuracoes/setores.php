<?php 
    $Usuario = new Usuario();
    $Setor = new Setor();

    if (isset($_POST['btnCadastrar'])) {
        $Setor->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Setor->editar($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Setor->deletar($_POST['id_setor'],$_POST['usuario_logado']);
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
                                    <span>Setores</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Setores  
                                <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarSetor">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Setor</th>
                                                <th>Quantidade Usuários</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($Setor->listar() as $setor){ ?>
                                            <tr>
                                                <td><?php echo $setor->setor ?></td>
                                                <td class="text-center"><?php echo $Usuario->contarUsuariosPorSetor($setor->id_setor);?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarSetor"
                                                        data-setor="<?php echo $setor->setor ?>" 
                                                        data-idsetor="<?php echo $setor->id_setor ?>"
                                                        ><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarSetor"
                                                        data-setor="<?php echo $setor->setor ?>" 
                                                        data-idsetor="<?php echo $setor->id_setor ?>"
                                                        ><i class="fa-solid fa-trash"></i></button>
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

        <!-- Modal Cadastrar Setor -->
        <div class="modal fade" id="modalCadastrarSetor" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarSetorLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCadastrarSetorLabel">Cadastrar Novo Setor</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="row">
                                <div class="col-8 offset-2">
                                    <label for="cadastrar_setor" class="form-label">Setor *</label>
                                    <input type="text" class="form-control" id="cadastrar_setor" name="setor" required>
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

        <!-- Modal Editar Setor -->
        <div class="modal fade" id="modalEditarSetor" tabindex="-1" role="dialog" aria-labelledby="modalEditarSetorLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarSetorLabel">Editar o Setor: <span id="editar_setor_nome"></span></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_setor" id="editar_id_setor">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="row">
                                <div class="col-8 offset-2">
                                    <label for="editar_setor" class="form-label">Setor *</label>
                                    <input type="text" class="form-control" name="setor" id="editar_setor" required>
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

        <!-- Modal Deletar Setor -->
        <div class="modal fade" id="modalDeletarSetor" tabindex="-1" role="dialog" aria-labelledby="modalDeletarSetorLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeletarSetorLabel">Deletar o Setor: <span class="deletar_setor_nome"></span></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" name="id_setor" id="deletar_id_setor">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <p>Você tem certeza que deseja Deletar o setor <br> <b class="deletar_setor_nome"></b>? <br> Essa ação é Irreversível.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                            <button class="btn btn-danger" type="submit" name="btnDeletar">Deletar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Usuários do Setor -->
        <div class="modal fade" id="modalUsuariosSetor" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosSetorLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUsuariosSetorLabel">Usuários do Setor</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body" id="usuariosDoSetor">
                        <!-- Lista de usuários -->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(window).on('load', function () {
                $('#preloader').fadeOut('slow', function() { $(this).remove(); });
            });

            $(document).ready(function () {
                $('#conf').addClass('active')
                $('#configuracoes_setores').addClass('active')

                $('#preloader').fadeOut('slow', function() { $(this).remove(); });

                $('#modalEditarSetor').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let setor = button.data('setor');
                    let id_setor = button.data('idsetor');

                    $('#editar_setor_nome').text(setor);
                    $('#editar_id_setor').val(id_setor);
                    $('#editar_setor').val(setor);
                });

                $('#modalDeletarSetor').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let setor = button.data('setor');
                    let id_setor = button.data('idsetor');

                    $('.deletar_setor_nome').text(setor);
                    $('#deletar_id_setor').val(id_setor);
                    $('#deletar_setor').val(setor);
                });

            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-setores.js"></script>
    </body>
</html>
