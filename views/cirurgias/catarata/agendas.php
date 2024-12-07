<?php 
    $Catarata_Agenda = new Catarata_agenda();

    if(isset($_POST['btnCadastrar'])){
        $Catarata_Agenda->cadastrar($_POST);
    }
    if(isset($_POST['btnEditar'])){
        $Catarata_Agenda->editar($_POST);
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
                                    <span>Agendas Catarata
                                        <button class="btn btn-datatable btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarAgenda">Nova Agenda</button>
                                    </span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="row">
                            <?php foreach($Catarata_Agenda->listar() as $agenda){ ?>
                            <div class="col-4 mb-4">
                                <div class="card">
                                    <div class="card-header"><?php echo Helper::formatarData($agenda->data) ?></div>
                                    <div class="card-body">
                                        <b>Quantidade de Agendamentos: <?php echo $Catarata_Agenda->contarAgendamentos($agenda->id_catarata_agenda) ?>/<?php echo $Catarata_Agenda->somarQntdVagas($agenda->id_catarata_agenda) ?></b>
                                        <hr>
                                        <div class="col-12 text-center">
                                            <?php if($agenda->data > date("Y-m-d")){ ?>
                                                <a class="btn btn-icon btn-primary" href="<?php echo URL ?>/cirurgias/catarata/agenda?id=<?php echo $agenda->id_catarata_agenda ?>"><i class="fa-regular fa-clipboard-list"></i></a>
                                                <button class="btn btn-icon btn-dark" type="button" data-toggle="modal" data-target="#modalEditarAgenda"
                                                    data-id_catarata_agenda="<?php echo $agenda->id_catarata_agenda ?>"
                                                    data-data="<?php echo $agenda->data ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                <?php }else{ ?>
                                                <a class="btn btn-icon btn-success" href="<?php echo URL ?>/cirurgias/catarata/agenda?id=<?php echo $agenda->id_catarata_agenda ?>"><i class="fa-solid fa-check"></i></a>
                                            <?php }?>
                                        </div>
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

        <!-- Cadastrar Agenda -->
        <div class="modal fade" id="modalCadastrarAgenda" tabindex="1" role="dialog" aria-labelledby="modalCadastrarAgendaLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Nova Agenda</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <label for="data" class="form-label">Data da Agenda *</label>
                                    <input type="date" name="data" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
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

        
        <!-- Editar Agenda -->
        <div class="modal fade" id="modalEditarAgenda" tabindex="1" role="dialog" aria-labelledby="modalEditarAgendaLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Agenda de Catarata para o dia <span id="editar_agenda_data"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <input type="hidden" name="id_catarata_agenda" id="editar_id_catarata_agenda">
                                    <label for="data" class="form-label">Data da Agenda *</label>
                                    <input type="date" name="data" id="editar_data" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
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
                $('#cirurgias_catarata_agenda').addClass('active')

                $('#modalEditarAgenda').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let id_catarata_agenda = button.data('id_catarata_agenda');
                    let data = button.data('data');

                    // Verifica se a data é válida (não anterior a hoje)
                    if (data < '<?php echo date("Y-m-d"); ?>') {
                        alert('Não é permitido editar agendas para datas anteriores.');
                        $(this).modal('hide');
                        return;
                    }

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
