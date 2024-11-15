<?php 
    $Usuario = new Usuario();
    $Log = new Log();
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
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                    <span>Logs</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Logs</div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Data</th>
                                                <th>Hora</th>
                                                <th>Tipo</th>
                                                <th>Usuário</th>
                                                <th>Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($Log->listar() as $log){ $data = new DateTime($log->data);?>
                                            <tr>
                                                <td><?php echo $log->id_log ?></td>
                                                <td><?php $dia = $data->format("d/m/Y"); echo $dia; ?></td>
                                                <td><?php $hora = $data->format("H:i"); echo $hora; ?></td>
                                                <td><?php echo $log->acao ?></td>
                                                <td><?php echo Helper::encurtarNome($Usuario->mostrar($log->id_usuario)->nome) ?> (<?php echo $log->id_usuario ?>)</td>
                                                <td><?php echo $log->descricao ?></td>
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
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(window).on('load', function () {
                $('#preloader').fadeOut('slow', function() { $(this).remove(); });
            });

            $(document).ready(function () {
                $('#logs').addClass('active')

                $('#preloader').fadeOut('slow', function() { $(this).remove(); });
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-logs.js"></script>
    </body>
</html>
