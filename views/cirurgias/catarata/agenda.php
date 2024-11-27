<?php 
    $Catarata_Agenda = new Catarata_Agenda();
    $Catarata_Turma = new Catarata_turma();

    if(isset($_POST['btnCadastrar'])){
        $Catarata_Turma->cadastrar($_POST);
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
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalCadastrarTurma">Abrir Turma</button>
                        <br><br>
                        <div class="row">
                            <?php foreach($Catarata_Turma->listar($agenda->id_catarata_agenda) as $turma){ ?>
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Turma <?php echo Helper::formatarHorario($turma->horario) ?></span>
                                    </div>
                                    <div class="card-body">
                                        <ul>
                                            <?php foreach($Catarata_Turma->listarAgendamentos($turma->id_catarata_turma) as $agendamento){ ?>
                                                <li><?php echo $agendamento->nome ?></li>
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
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Turma de Catarata para o dia <span id="editar_turma_data"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <input type="hidden" name="id_catarata_turma" id="editar_id_catarata_turma">
                                    <label for="data" class="form-label">Data da Turma *</label>
                                    <input type="date" name="data" id="editar_data" class="form-control" required>
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

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#ciru').addClass('active')
                $('#cirurgias_catarata').addClass('active')
                $('#cirurgias_catarata_agendas').addClass('active')

                $('#modalEditarAgenda').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let id_catarata_agenda = button.data('id_catarata_agenda');
                    let data = button.data('data');

                    $('#editar_agenda_data').text(data);
                    $('#editar_id_catarata_agenda').val(id_catarata_agenda);
                    $('#editar_data').val(data);
                });
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>
</html>
