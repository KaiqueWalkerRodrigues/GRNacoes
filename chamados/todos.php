<?php 
    include_once('../const.php');

    $Chamado = new Chamados();
    $Usuario = new Usuario();
    $Cargo = new Cargo();
    $Setor = new Setor();

    $usuario = $Usuario->mostrar($_SESSION['id_usuario']);
    $setor = $Setor->mostrar($usuario->id_setor);

    if(isset($_POST['btnEncaminhar'])){
        $Chamado->encaminhar($_POST['id_chamado'],$_POST['id_setor_novo'],$_POST['id_usuario']);
    }
    if(isset($_POST['btnConcluir'])){
        $Chamado->concluir($_POST['id_chamado'],$_POST['id_usuario']);
    }
    if(isset($_POST['btnRecusar'])){
        $Chamado->recusar($_POST['id_chamado'],$_POST['id_usuario']);
    }
    if(isset($_POST['btnReabrir'])){
        $Chamado->reabrir($_POST['id_chamado'],$_POST['id_usuario']);
    }
    if(isset($_POST['btnIniciar'])){
        $Chamado->iniciar($_POST['id_chamado'],$_POST['id_usuario']);
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

    <title>GRNacoes - Chamados</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Todos os Chamados</h1>
                    </div>

                    <br>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroStatus" class="form-label">Filtrar por Status:</label>
                            <select id="filtroStatus" class="form-control">
                                <option value="">Todos</option>
                                <option value="Em Análise">Em Análise</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Recusado">Recusado</option>
                            </select>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Chamados</h6>
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
                                        <?php foreach($Chamado->listar() as $chamado){
                                            $created_at = new DateTime($chamado->created_at);
                                            $now = new DateTime();
                                            $criado = $created_at->diff($now);
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
                                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalIniciar"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                        data-titulo="<?php echo $chamado->titulo ?>"
                                                    >
                                                        <i class="fa-solid fa-clock"></i>
                                                    </button>
                                                    <button class="btn btn-info" data-toggle="modal" data-target="#modalEncaminhar"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                    ><i class="fa-solid fa-share"></i></button>
                                                <?php } ?>
                                                <?php if($chamado->status <= 2){ ?>
                                                    <button class="btn btn-success" data-toggle="modal" data-target="#modalConcluir"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                        data-titulo="<?php echo $chamado->titulo ?>"
                                                    ><i class="fa-solid fa-check"></i></button>
                                                    <button class="btn btn-dark" data-toggle="modal" data-target="#modalRecusar"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                        data-titulo="<?php echo $chamado->titulo ?>"
                                                    ><i class="fa-solid fa-times"></i></button>
                                                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluir"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                        data-titulo="<?php echo $chamado->titulo ?>"
                                                    ><i class="fa-solid fa-trash"></i></button>
                                                <?php }else{ ?>
                                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalReabrir"
                                                        data-id_chamado="<?php echo $chamado->id_chamado ?>"
                                                        data-titulo="<?php echo $chamado->titulo ?>"
                                                    >
                                                        <i class="fa-solid fa-rotate"></i>
                                                    </button>
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

            <!-- Modal Mostrar Chamado -->


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
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Encaminhar Chamado para o Setor...</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <input type="hidden" id="encaminhar_id_chamado" name="id_chamado">
                                    <label for="encaminhar" class="form-label">Encaminhar para:</label>
                                    <select name="id_setor_novo" id="encaminhar" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach($Setor->listar($setor->id_setor) as $setor){ ?>
                                            <option value="<?php echo $setor->id_setor ?>"><?php echo $setor->setor ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-info" name="btnEncaminhar">Encaminhar</button>
                            </div>
                        </div>
                    </div>
                </form>
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

            <!-- Modal Recusar -->
            <div class="modal fade" id="modalRecusar" tabindex="1" role="dialog" aria-labelledby="modalRecusarLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Recusar o chamado: <span class="recusar_titulo"></span> ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_chamado" id="recusar_id_chamado">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <p>Deseja recusar o chamado: <span class="recusar_titulo"></span> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-dark" name="btnRecusar">Recusar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Reabrir -->
            <div class="modal fade" id="modalReabrir" tabindex="1" role="dialog" aria-labelledby="modalReabrirLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Reabrir o chamado: <span class="reabrir_titulo"></span> ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_chamado" id="reabrir_id_chamado">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <p>Deseja reabrir o chamado: <span class="reabrir_titulo"></span> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-warning" name="btnReabrir">Reabrir</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Iniciar -->
            <div class="modal fade" id="modalIniciar" tabindex="1" role="dialog" aria-labelledby="modalIniciarLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Iniciar o chamado: <span class="iniciar_titulo"></span> ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_chamado" id="iniciar_id_chamado">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <p>Deseja iniciar o chamado: <span class="iniciar_titulo"></span> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="btnIniciar">Iniciar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Concluir -->
            <div class="modal fade" id="modalConcluir" tabindex="1" role="dialog" aria-labelledby="modalConcluirLabel" aria-hidden="true">
                <form action="?" method="post">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Concluir o chamado: <span class="concluir_titulo"></span> ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_chamado" id="concluir_id_chamado">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <p>Deseja concluir o chamado: <span class="concluir_titulo"></span> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" name="btnConcluir">Concluir</button>
                            </div>
                        </div>
                    </div>
                </form>
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
            $('#todos_chamados').addClass('active');

            $('#filtroStatus').change(function() {
                var filtroStatus = $('#filtroStatus').val();
                
                // Mostrar todas as linhas inicialmente
                $('#dataTable tbody tr').show();

                // Iterar sobre as linhas da tabela para aplicar o filtro
                $('#dataTable tbody tr').each(function() {
                    var status = $(this).find('td:eq(2)').text(); // Índice da coluna de status (ajuste se necessário)

                    // Verificar se a linha atende ao critério de status
                    if (filtroStatus && status !== filtroStatus) {
                        $(this).hide(); // Ocultar a linha se o status não for o selecionado
                    }
                });
            });

            $('#modalExcluir').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')

                $('#excluir_id_chamado').val(id_chamado)
                $('.excluir_titulo').text(titulo)
            })

            $('#modalEncaminhar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                $('#encaminhar_id_chamado').val(id_chamado)
            })

            $('#modalConcluir').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')
                $('#concluir_id_chamado').val(id_chamado)
                $('.concluir_titulo').text(titulo)
            })

            $('#modalRecusar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')
                $('#recusar_id_chamado').val(id_chamado)
                $('.recusar_titulo').text(titulo)
            })

            $('#modalReabrir').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')
                $('#reabrir_id_chamado').val(id_chamado)
                $('.reabrir_titulo').text(titulo)
            })

            $('#modalIniciar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_chamado = button.data('id_chamado')
                let titulo = button.data('titulo')
                $('#iniciar_id_chamado').val(id_chamado)
                $('.iniciar_titulo').text(titulo)
            })

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