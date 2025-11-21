<?php
$Exames = new Exame();
$Pacotes = new Pacote_Exame();
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
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header page-header-dark bg-gradient-primary-to-secondary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                <span>Central de Atendimento</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-8 offset-2 text-center">
                            <button type="button" data-toggle="modal" data-target="#modal" class="btn btn-primary">Médicos</button>
                            <button type="button" data-toggle="modal" data-target="#modalExames" class="btn btn-success">Exames</button>
                            <button type="button" data-toggle="modal" data-target="#modalPacotesExames" class="btn btn-success">Pacotes</button>
                            <button type="button" data-toggle="modal" data-target="#modal" class="btn btn-secondary">Procedimentos</button>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Exames -->
    <div class="modal fade" id="modalExames" tabindex="1" role="dialog" aria-labelledby="modalExames" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exames</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable">
                        <table class="table table-bordered table-hover" id="dataTableCentralExames" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Exame</th>
                                    <th>Valor Particular</th>
                                    <th>Valor Fidelidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Exames->listar() as $exame) { ?>
                                    <tr>
                                        <td><?php echo $exame->exame; ?></td>
                                        <td>R$ <?php echo number_format($exame->valor_particular, 2, ',', '.') ?></td>
                                        <td>R$ <?php echo number_format($exame->valor_fidelidade, 2, ',', '.') ?></td>
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

    <!-- Modal Pacotes de Exames -->
    <div class="modal fade" id="modalPacotesExames" tabindex="1" role="dialog" aria-labelledby="modalPacotesExames" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exames</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable">
                        <table class="table table-bordered table-hover" id="dataTableCentralPacotes" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Pacote</th>
                                    <th>Valor Fidelidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Pacotes->listartODOS() as $pacote) {
                                    $exames = $Pacotes->listarNomeExamesDoPacote($pacote->id_exames_pacote);
                                ?>
                                    <tr>
                                        <td><?php echo $pacote->pacote; ?> (<?php echo Helper::mostrar_empresa($pacote->id_empresa) ?>)</td>
                                        <td class="text-center">R$ <?php echo number_format($pacote->valor_fidelidade, 2, ',', '.') ?></td>
                                        <td class="text-center"><?php echo !empty($exames) ? implode('<br>', $exames) : '-'; ?></td>
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

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#callcenter').addClass('active')
            $('#cc_central').addClass('active')
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-exames.js"></script>
</body>

</html>