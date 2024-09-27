<?php 
    include_once('../const.php');

    $Chamado = new Chamados();
    $Setor = new Setor();
    $Usuario = new Usuario();

    $usuario = $Usuario->mostrar($_SESSION['id_usuario']);
    $setor = $Setor->mostrar($usuario->id_setor);

    if(isset($_POST['AbrirChamado'])){
        $Chamado->cadastrar($_POST);
    }
    if(isset($_POST['btnExcluir'])){
        $Chamado->desativar($_POST['id_chamado'],$_POST['id_usuario']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Meus Chamados</title>

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

                    <br><br><br><br>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Meus Chamados</h1>
                    </div>

                    <br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Meus Chamados | <button class="btn btn-primary" data-toggle="modal" data-target="#modalAbrirChamado">Abrir Chamado</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Urgência</th>
                                            <th>Título</th>
                                            <th>Usuário</th>
                                            <th>Status</th>
                                            <th>Setor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Chamado->listarPorUsuario($_SESSION['id_usuario']) as $chamado){
                                            $created_at = new DateTime($chamado->created_at);
                                            $now = new DateTime();
                                            $interval = $created_at->diff($now);
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo Helper::Urgencia($chamado->urgencia) ?></td>
                                            <td><?php echo $chamado->titulo ?></td>
                                            <td><?php echo $Usuario->mostrar($chamado->id_usuario)->nome ?></td>
                                            <td><?php echo Helper::statusChamado($chamado->status) ?></td>
                                            <td><?php echo $Setor->mostrar($chamado->id_setor)->setor ?></td>
                                            <td>
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalVisualizarChamado"
                                                    data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                    data-titulo="<?php echo $chamado->titulo ?>"
                                                    data-status="<?php echo Helper::TextoStatusChamado($chamado->status) ?>"
                                                    data-usuario="<?php echo $usuario->nome ?> (<?php echo $Setor->mostrar($usuario->id_setor)->setor ?>)"
                                                    data-setor="<?php echo $setor->setor ?>"
                                                    data-urgencia="<?php echo Helper::TextoUrgencia($chamado->urgencia) ?>"
                                                    data-descricao="<?php echo $chamado->descricao ?>"
                                                    data-created_at="<?php echo Helper::formatarData($chamado->created_at) ?>"
                                                    data-deleted_at="<?php echo Helper::formatarData($chamado->deleted_at) ?>"
                                                    data-started_at="<?php echo Helper::formatarData($chamado->started_at) ?>"
                                                    data-finished_at="<?php echo Helper::formatarData($chamado->finished_at) ?>">
                                                    <i class="fa-solid fa-newspaper"></i>
                                                </button>
                                                <a href="chat_chamado?id=<?php echo $chamado->id_chamado ?>" class="btn btn-primary">
                                                    <i class="fa-solid fa-comment"></i>
                                                </a>
                                                <?php if($chamado->status == 1){ ?>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluir"
                                                    data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                    data-titulo="<?php echo $chamado->titulo ?>"
                                                ><i class="fa-solid fa-trash"></i></button>
                                                <?php } ?>
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

            <!-- Modal Abrir Chamado -->
            <form action="?" method="post">
                <div class="modal fade" id="modalAbrirChamado" tabindex="-1" role="dialog" aria-labelledby="abrirchamadoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Abrir Chamado</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <div class="col-6 mb-2">
                                        <label for="cadastrar_titulo" class="form-label">Título do Chamado *</label>
                                        <input type="text" name="titulo" id="cadastrar_titulo" class="form-control" required>
                                    </div>
                                    <div class="col-3 mb-2">
                                        <label for="cadastrar_id_setor" class="form-label">Destinatário *</label>
                                        <select name="id_setor" id="cadastrar_id_setor" class="form-control" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($Setor->listar() as $setor){ 
                                                echo "<option value='$setor->id_setor'>$setor->setor</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="cadastrar_urgencia" class="form-label">Urgência *</label>
                                        <select name="urgencia" id="cadastrar_urgencia" class="form-control" required>
                                            <option value="">Selecione...</option>
                                            <option value="1">Baixa</option>
                                            <option value="2">Média</option>
                                            <option value="3">Alta</option>
                                            <option value="4">Urgente</option>
                                        </select>
                                    </div>
                                    <!-- <div class="col-4">
                                        <label for="cadastrar_print" class="form-label">Prints</label>
                                        <input type="file" name="cadastrar-print" multiple accept="image/" id="cadastrar-print" class="form-control">
                                    </div> -->
                                    <div class="col-12 mt-1">
                                        <label for="cadastrar-descricao" class="form-label">Descreva o Problema *</label>
                                        <textarea name="descricao" id="cadastrar-descricao" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="AbrirChamado">Abrir Chamado</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal encaminhar -->
            <div class="modal fade" id="modalEncaminhar" tabindex="1" role="dialog" aria-labelledby="modalencaminharLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Encaminhar Chamado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <label for="encaminhar" class="form-label">Encaminhar para:</label>
                                <select name="encaminhar" id="encaminhar" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="ti">TI</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-info">Encaminhar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Visualizar Chamado -->
            <div class="modal fade" id="modalVisualizarChamado" tabindex="-1" role="dialog" aria-labelledby="visualizarchamadoLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Visualizar Chamado: <span id="titulo_modal"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id_chamado" id="visualizar_id_chamado">
                                <div class="col-5 mb-2">
                                    <label for="visualizar_titulo" class="form-label">Título do Chamado *</label>
                                    <input type="text" name="titulo" id="visualizar_titulo" class="form-control" disabled>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="visualizar_status" class="form-label">Status *</label>
                                    <input type="text" name="status" id="visualizar_status" class="form-control" disabled>
                                </div>
                                <div class="col-3 mb-2">
                                    <label for="visualizar_id_setor" class="form-label">Setor *</label>
                                    <input type="text" name="setor" id="visualizar_id_setor" class="form-control" disabled>
                                </div>
                                <div class="col-2">
                                    <label for="visualizar_urgencia" class="form-label">Urgência *</label>
                                    <input type="text" name="urgencia" id="visualizar_urgencia" class="form-control" disabled>
                                </div>
                                <div class="col-6 offset-3 mb-2">
                                    <label for="visualizar_usuario" class="form-label">Usuário *</label>
                                    <input type="text" name="usuario" id="visualizar_usuario" class="form-control" disabled>
                                </div>
                                <div class="col-12 mt-1">
                                    <label for="visualizar_descricao" class="form-label">Descrição *</label>
                                    <textarea name="descricao" id="visualizar_descricao" cols="30" rows="10" class="form-control" disabled></textarea>
                                </div>
                                <div class="col-3 mt-2">
                                    <label for="visualizar_created_at" class="form-label">Criado em</label>
                                    <input type="text" name="created_at" id="visualizar_created_at" class="form-control" disabled>
                                </div>
                                <div class="col-3 mt-2">
                                    <label for="visualizar_deleted_at" class="form-label">Cancelado em</label>
                                    <input type="text" name="deleted_at" id="visualizar_deleted_at" class="form-control" disabled>
                                </div>
                                <div class="col-3 mt-2">
                                    <label for="visualizar_started_at" class="form-label">Iniciado em</label>
                                    <input type="text" name="started_at" id="visualizar_started_at" class="form-control" disabled>
                                </div>
                                <div class="col-3 mt-2">
                                    <label for="visualizar_finished_at" class="form-label">Finalizado em</label>
                                    <input type="text" name="finished_at" id="visualizar_finished_at" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Excluir Chamado -->
            <div class="modal fade" id="modalExcluir" tabindex="1" role="dialog" aria-labelledby="modalExcluirLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Excluir o chamado: <span class="excluir_titulo"></span> ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_chamado" id="excluir_id_chamado">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <p>Deseja excluir o chamado: <span class="excluir_titulo"></span> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" name="btnExcluir" class="btn btn-danger">Excluir</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#cham').addClass('active');
            $('#meus_chamados').addClass('active');

            $('#modalExcluir').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')

                $('#excluir_id_chamado').val(id_chamado)
                $('.excluir_titulo').text(titulo)
            })

            // Função para abrir o modal de visualizar chamado com dados preenchidos
            $('#modalVisualizarChamado').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget); // Botão que acionou o modal
                let id_chamado = button.data('id_chamado');
                let titulo = button.data('titulo');
                let status = button.data('status');
                let usuario = button.data('usuario');
                let setor = button.data('setor');
                let urgencia = button.data('urgencia');
                let descricao = button.data('descricao');
                let created_at = button.data('created_at');
                let deleted_at = button.data('deleted_at');
                let finished_at = button.data('finished_at');
                let started_at = button.data('started_at');

                // Preencher os campos do modal com os dados do chamado
                $('#visualizar_id_chamado').val(id_chamado);
                $('#visualizar_titulo').val(titulo);
                $('#titulo_modal').text(titulo);
                $('#visualizar_status').val(status);
                $('#visualizar_usuario').val(usuario);
                $('#visualizar_id_setor').val(setor);
                $('#visualizar_urgencia').val(urgencia);
                $('#visualizar_descricao').val(descricao);
                $('#visualizar_created_at').val(created_at);
                $('#visualizar_deleted_at').val(deleted_at);
                $('#visualizar_started_at').val(started_at);
                $('#visualizar_finished_at').val(finished_at);
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