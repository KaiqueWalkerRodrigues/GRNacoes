<?php 
    $Medico = new Medico();

    if (isset($_POST['btnReativar'])) {
        $Medico->reativar($_POST);
    }
    if (isset($_POST['btnCadastrar'])) {
        $Medico->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Medico->editar($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Medico->deletar($_POST['id_medico'],$_POST['usuario_logado']);
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
                                    <span>Médicos</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Médicos
                                <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarMedico">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                |
                                <button class="btn btn-datatable btn-dark ml-2" type="button" data-toggle="modal" data-target="#modalMedicosDesativados">
                                    Desativados
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Título</th>
                                                <th>CRM</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($Medico->listarAtivos() as $medico){ ?>
                                            <tr>
                                                <td><?php echo $medico->id_medico ?></td>
                                                <td><?php echo $medico->nome ?></td>
                                                <td><?php echo $medico->titulo ?></td>
                                                <td><?php echo $medico->crm ?></td>
                                                <td>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarMedico"
                                                        data-nome="<?php echo $medico->nome ?>" 
                                                        data-titulo="<?php $titulo = explode(' ',$medico->titulo); echo $titulo[0]  ?>" 
                                                        data-crm="<?php echo $medico->crm ?>" 
                                                        data-ativo="<?php echo $medico->ativo ?>" 
                                                        data-idmedico="<?php echo $medico->id_medico ?>"
                                                        ><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarMedico"
                                                        data-nome="<?php echo $medico->nome ?>" 
                                                        data-idmedico="<?php echo $medico->id_medico ?>"
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

        <!-- Modal Médicos Desativados -->
        <div class="modal fade" id="modalMedicosDesativados" tabindex="1" role="dialog" aria-labelledby="modalMedicosDesativadosLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Médicos Desativados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="datatable table-responsive">
                            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Médico</th>
                                        <th>CRM</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($Medico->listarDesativados() as $medicoDesativado){ ?>
                                    <tr>
                                        <td><?php echo $medicoDesativado->nome ?></td>
                                        <td><?php echo $medicoDesativado->crm ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarmedicoDesativado"
                                                data-nome="<?php echo $medicoDesativado->nome ?>" 
                                                data-crm="<?php echo $medicoDesativado->crm ?>" 
                                                data-ativo="<?php echo $medicoDesativado->ativo ?>" 
                                                data-id_medico="<?php echo $medicoDesativado->id_medico ?>"
                                                ><i class="fa-solid fa-gear"></i></button>
                                            <form action="?" method="post" style="display:inline;">
                                                <input type="hidden" name="id_medico" value="<?php echo $medicoDesativado->id_medico ?>">
                                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                                                <button class="btn btn-datatable btn-transparent-dark" type="submit" name="btnReativar">
                                                    <i class="fa-solid fa-power-on"></i> Reativar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cadastrar Medico -->
        <div class="modal fade" id="modalCadastrarMedico" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarMedicoLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCadastrarMedicoLabel">Cadastrar Novo Médico</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="row">
                                <div class="col-5 offset-1">
                                    <label for="nome" class="form-label">Nome *</label>
                                    <input type="text" class="form-control" name="nome">
                                </div>
                                <div class="col-2">
                                    <label for="titulo" class="form-label">Prefixo *</label>
                                    <select name="titulo" id="cadastrar_titulo" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Dra">Dra</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="crm" class="form-label">CRM *</label>
                                    <input type="text" class="form-control" name="crm" required>
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

        <!-- Modal Editar Médico -->
        <div class="modal fade" id="modalEditarMedico" tabindex="-1" role="dialog" aria-labelledby="modalEditarMedicoLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarMedicoLabel">Editar o Médico: <span id="editar_medico_nome"></span></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_medico" id="editar_id_medico">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="row">
                                <div class="col-5 offset-1">
                                    <label for="editar_nome" class="form-label">Nome *</label>
                                    <input type="text" class="form-control" name="nome" id="editar_nome" required>
                                </div>
                                <div class="col-2">
                                    <label for="editar_titulo" class="form-label">Prefixo *</label>
                                    <select name="titulo" id="editar_titulo" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Dra">Dra</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="editar_crm" class="form-label">CRM *</label>
                                    <input type="text" class="form-control" name="crm" id="editar_crm" required>
                                </div>
                                <div class="col-2 offset-1 mt-3">
                                    <label for="editar_ativo" class="form-label">Ativo</label>
                                    <select name="ativo" id="editar_ativo" class="form-control">
                                        <option value="1">Ativo</option>
                                        <option value="0">Desativo</option>
                                    </select>
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

        <!-- Modal Deletar Medico -->
        <div class="modal fade" id="modalDeletarMedico" tabindex="-1" role="dialog" aria-labelledby="modalDeletarMedicoLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeletarMedicoLabel">Deletar o Médico: <span class="deletar_medico_nome"></span></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" name="id_medico" id="deletar_id_medico">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <p>Você tem certeza que deseja Deletar o Médico <br> <b class="deletar_medico_nome"></b>? <br> Essa ação é Irreversível.</p>
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
            $(window).on('load', function () {
                $('#preloader').fadeOut('slow', function() { $(this).remove(); });
            });

            $(document).ready(function () {
                $('#conf').addClass('active')
                $('#configuracoes_medicos').addClass('active')

                $('#preloader').fadeOut('slow', function() { $(this).remove(); });

                $('#modalEditarMedico').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let id_medico = button.data('idmedico');
                    let nome = button.data('nome');
                    let titulo = button.data('titulo');
                    let crm = button.data('crm');
                    let ativo = button.data('ativo');

                    $('#editar_medico_nome').text(nome);
                    $('#editar_id_medico').val(id_medico);
                    $('#editar_nome').val(nome);
                    $('#editar_titulo').val(titulo);
                    $('#editar_crm').val(crm);
                    $('#editar_ativo').val(ativo);
                });

                $('#modalDeletarMedico').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let nome = button.data('nome');
                    let id_medico = button.data('idmedico');

                    $('.deletar_medico_nome').text(nome);
                    $('#deletar_id_medico').val(id_medico);
                });

            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-medicos.js"></script>
    </body>
</html>
