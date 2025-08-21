<?php 
    $Financeiro_Campanha = new Financeiro_Campanha();

    if(isset($_POST['btnCadastrar'])){
        $Financeiro_Campanha->cadastrar($_POST);
    }
    if(isset($_POST['btnEditar'])){
        $Financeiro_Campanha->editar($_POST);
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
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                    <span>Campanhas
                                        <button class="btn btn-datatable btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarCampanha">Nova Campanha</button>
                                    </span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">

                        <div class="row">

                            <?php foreach($Financeiro_Campanha->listar() as $campanha){ ?>
                            <div class="col-4 mb-4">
                                <div class="card">
                                    <div class="card-header"><?php echo $campanha->nome ?></div>
                                    <div class="card-body" style="font-size: 15px;">
                                        <b>Início: <?php echo Helper::formatarData($campanha->periodo_inicio) ?></b>
                                        <br>
                                        <b>Fim: <?php echo Helper::formatarData($campanha->periodo_fim) ?></b>
                                        <br>
                                        <b>Pagamento da Conversão: <?php echo Helper::formatarData($campanha->data_pagamento) ?></b>
                                        <br>
                                        <b>Pagamento da Conversão Pós: <?php echo Helper::formatarData($campanha->data_pagamento_pos) ?></b>
                                        <hr>
                                        <div class="col-12 text-center">
                                            <a class="btn btn-icon btn-primary" href="<?php echo URL ?>/financeiro/campanha?id=<?php echo $campanha->id_financeiro_campanha ?>"><i class="fa-solid fa-piggy-bank"></i></a>
                                            <button class="btn btn-icon btn-dark" type="button" data-toggle="modal" data-target="#modalEditarCampanha"
                                                data-id_financeiro_campanha="<?php echo $campanha->id_financeiro_campanha ?>"
                                                data-nome="<?php echo $campanha->nome ?>"
                                                data-periodo_inicio="<?php echo $campanha->periodo_inicio ?>"
                                                data-periodo_fim="<?php echo $campanha->periodo_fim ?>"
                                                data-data_pagamento="<?php echo $campanha->data_pagamento ?>"
                                                data-data_pagamento_pos="<?php echo $campanha->data_pagamento_pos ?>"
                                                ><i class="fa-solid fa-gear"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>

                    </div>
                </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>

    <!-- Cadastrar Campanha -->
    <div class="modal fade" id="modalCadastrarCampanha" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCampanhaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Campanha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="nome" class="form-label">Nome da Campanha *</label>
                                <input type="text" name="nome" class="form-control" value="Campanha " required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_inicio" class="form-label">Do dia</label>
                                <input type="date" class="form-control" name="periodo_inicio" id="cadastrar_periodo_inicio" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_fim" class="form-label">Até o dia</label>
                                <input type="date" class="form-control" name="periodo_fim" id="cadastrar_periodo_fim" required>
                            </div>
                            <div class="col-3 offset-4 mt-2">
                                <label for="data_pagamento" class="form-label">Data do Pagamento da Conversão</label>
                                <input type="date" name="data_pagamento" id="cadastrar_data_pagamento" class="form-control" required>
                            </div>
                            <div class="col-4 mt-2">
                                <label for="data_pagamento_pos" class="form-label">Data do Pagamento da Conversão PÓS</label>
                                <input type="date" name="data_pagamento_pos" id="cadastrar_data_pagamento_pos" class="form-control" required>
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
    
    <!-- Editar Campanha -->
    <div class="modal fade" id="modalEditarCampanha" tabindex="1" role="dialog" aria-labelledby="modalEditarCampanhaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar <span id="editar_campanha_nome"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <input type="hidden" name="id_financeiro_campanha" id="editar_id_financeiro_campanha">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="nome" class="form-label">Nome da Campanha *</label>
                                <input type="text" name="nome" id="editar_nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_inicio" class="form-label">Do dia</label>
                                <input type="date" class="form-control" name="periodo_inicio" id="editar_periodo_inicio" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_fim" class="form-label">Até o dia</label>
                                <input type="date" class="form-control" name="periodo_fim" id="editar_periodo_fim" required>
                            </div>
                            <div class="col-3 offset-4 mt-2">
                                <label for="data_pagamento" class="form-label">Data do Pagamento da Conversão</label>
                                <input type="date" name="data_pagamento" id="editar_data_pagamento" class="form-control" required>
                            </div>
                            <div class="col-4 mt-2">
                                <label for="data_pagamento_pos" class="form-label">Data do Pagamento da Conversão PÓS</label>
                                <input type="date" name="data_pagamento_pos" id="editar_data_pagamento_pos" class="form-control" required>
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
            $('#fina').addClass('active')
            $('#financeiro_campanhas').addClass('active')

            $('#modalEditarCampanha').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_financeiro_campanha = button.data('id_financeiro_campanha');
                let nome = button.data('nome');
                let periodo_inicio = button.data('periodo_inicio');
                let periodo_fim = button.data('periodo_fim');
                let data_pagamento = button.data('data_pagamento');
                let data_pagamento_pos = button.data('data_pagamento_pos');

                $('#editar_campanha_nome').text(nome);
                $('#editar_id_financeiro_campanha').val(id_financeiro_campanha);
                $('#editar_nome').val(nome);
                $('#editar_periodo_inicio').val(periodo_inicio);
                $('#editar_periodo_fim').val(periodo_fim);
                $('#editar_data_pagamento').val(data_pagamento);
                $('#editar_data_pagamento_pos').val(data_pagamento_pos);
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>