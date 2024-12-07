<?php 
    $Usuario = new Usuario();
    $Catarata_Agenda = new Catarata_Agenda();
    $Catarata_Turma = new Catarata_turma();

    if(isset($_POST['btnCadastrar'])){
        $Catarata_Turma->cadastrar($_POST);
    }
    if(isset($_POST['btnEditar'])){
        $Catarata_Turma->editar($_POST);
    }

    $id_agenda = $_GET['id'];

    $agenda = $Catarata_Agenda->mostrar($id_agenda);
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
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="nav-fixed">
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include_once('resources/sidebar.php') ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-light fa-calendar-day"></i></div>
                                    <span>Agenda <?php echo Helper::formatarData($agenda->data) ?> Catarata
                                    </span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <?php if($agenda->data > date("Y-m-d")){ ?>
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalCadastrarTurma">Abrir Turma</button>
                        <?php } ?>
                        <br><br>
                        <div class="row">
                            <?php foreach($Catarata_Turma->listar($agenda->id_catarata_agenda) as $turma){ ?>
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Turma <?php echo Helper::formatarHorario($turma->horario) ?></span>
                                        <div class="dropdown no-caret">
                                            <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right animated--fade-in-up" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item" data-toggle="modal" data-target="#modalEditarTurma"
                                                    data-id_turma="<?php echo $turma->id_catarata_turma ?>"
                                                    data-horario="<?php echo $turma->horario ?>"
                                                    data-qntd="<?php echo $turma->qntd ?>"
                                                >Editar</button>
                                                <?php if($Catarata_Turma->contarAgendamentos($turma->id_catarata_turma) < 1){  ?>
                                                <button class="dropdown-item" data-toggle="modal" data-target="#modalDeletarTurma"
                                                    data-id_turma="<?php echo $turma->id_catarata_turma ?>"
                                                    data-horario="<?php echo Helper::formatarHorario($turma->horario) ?>"
                                                >Deletar</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul>
                                            <?php foreach($Catarata_Turma->listarAgendamentos($turma->id_catarata_turma) as $agendamento){ ?>
                                                <li><?php echo $agendamento->nome.' ('.Helper::encurtarNome($Usuario->mostrar($agendamento->id_orientador)->nome).')' ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="card-footer text-center">
                                        <b><?php echo $Catarata_Turma->contarAgendamentos($turma->id_catarata_turma) ?>/<?php echo $turma->qntd ?></b>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>

        <!-- Cadastrar Turma -->
        <div class="modal fade" id="modalCadastrarTurma" tabindex="1" role="dialog" aria-labelledby="modalCadastrarTurmaLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Nova Turma</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id_agenda" id="cadastrar_id_agenda" value="<?php echo $agenda->id_catarata_agenda ?>">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <div class="col-4 offset-2">
                                    <label for="horario" class="form-label">Horário *</label>
                                    <input type="time" name="horario" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label for="qntd" class="form-label">Quantidade Pessoas</label>
                                    <input type="number" name="qntd" class="form-control">
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
        
        <!-- Editar Turma -->
        <div class="modal fade" id="modalEditarTurma" tabindex="1" role="dialog" aria-labelledby="modalEditarTurmaLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Turma de Catarata das <span id="editar_turma_horario"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id_agenda" id="editar_id_agenda" value="<?php echo $agenda->id_catarata_agenda ?>">
                                <input type="hidden" name="id_catarata_turma" id="editar_id_turma">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <div class="col-4 offset-2">
                                    <label for="horario" class="form-label">Horário *</label>
                                    <input type="time" name="horario" id="editar_horario" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label for="qntd" class="form-label">Quantidade Pessoas</label>
                                    <input type="number" name="qntd" id="editar_qntd" class="form-control">
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

        <!-- Deletar Turma -->
        <div class="modal fade" id="modalDeletarTurma" tabindex="1" role="dialog" aria-labelledby="modalDeletarTurmaLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar Turma de Catarata das <span id="deletar_turma_horario"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <input type="hidden" name="id_turma" id="deletar_id_turma">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <p>Você tem certeza que deseja Deletar a Turma das <br> <b id="deletar_horario"></b>? <br> Essa ação é Irreversível.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="btnDeletar" class="btn btn-primary">Deletar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#ciru').addClass('active')
                $('#cirurgias_catarata').addClass('active')
                $('#cirurgias_catarata_agendas').addClass('active')

                $('#modalEditarTurma').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let id_turma = button.data('id_turma');
                    let horario = button.data('horario');
                    let qntd = button.data('qntd');

                    $('#editar_turma_horario').text(horario);
                    $('#editar_id_turma').val(id_turma);
                    $('#editar_horario').val(horario);
                    $('#editar_qntd').val(qntd);
                });

                $('#modalDeletarTurma').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let id_turma = button.data('id_turma');
                    let horario = button.data('horario');

                    $('#deletar_turma_horario').text(horario);
                    $('#deletar_id_turma').val(id_turma);
                    $('#deletar_horario').text(horario);
                });
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>
</html>
