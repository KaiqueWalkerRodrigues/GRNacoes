<?php 
    $Faturamento_Competencia = new Faturamento_Competencia();
    $Faturamento_Nota_Servico = new Faturamento_Nota_Servico();

    if(isset($_POST['btnCadastrar'])){
        $Faturamento_Competencia->cadastrar($_POST);
    }
    if(isset($_POST['btnEditar'])){
        $Faturamento_Competencia->editar($_POST);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
                                <span>Competências
                                    <button class="btn btn-datatable btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarCompetencia">Nova Competência</button>
                                </span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="col-6 offset-3 mb-3"><input id="buscar_nota" type="number" placeholder="Digite o numero da nota" class="form-control"></div>
                    <div class="row">
                        <?php foreach($Faturamento_Competencia->listar() as $competencia){ ?>
                        <div class="col-3 mb-4">
                            <div class="card">
                                <div class="card-header"><?php echo $competencia->nome ?></div>
                                <div class="card-body" style="font-size: 15px;">
                                    <b>Início: <?php echo Helper::formatarData($competencia->periodo_inicio) ?></b>
                                    <br>
                                    <b>Fim: <?php echo Helper::formatarData($competencia->periodo_fim) ?></b>
                                    <br>
                                    <b>Mês Pagamento: <?php echo Helper::formatarDataParaMesAno($competencia->mes_pagamento) ?></b>
                                    <hr>
                                    <input class="notas-ids" type="hidden" value="<?php foreach($Faturamento_Nota_Servico->listar($competencia->id_faturamento_competencia) as $nota){ echo $nota->bf_nf.";";  } ?>">
                                    <div class="col-12 text-center">
                                        <a class="btn btn-icon btn-primary" href="<?php echo URL ?>/faturamento/competencia?id=<?php echo $competencia->id_faturamento_competencia ?>"><i class="fa-solid fa-file-invoice"></i></a>
                                        <a href="<?php URL ?>relatorios/gerar_relatorio_competencia?id_competencia=<?php echo $competencia->id_faturamento_competencia ?>" class="btn btn-icon btn-success"><i class="fa-solid fa-file-excel"></i></a>
                                        <button class="btn btn-icon btn-dark" type="button" data-toggle="modal" data-target="#modalEditarCompetencia"
                                            data-id_faturamento_competencia="<?php echo $competencia->id_faturamento_competencia ?>"
                                            data-nome="<?php echo $competencia->nome ?>"
                                            data-periodo_inicio="<?php echo $competencia->periodo_inicio ?>"
                                            data-periodo_fim="<?php echo $competencia->periodo_fim ?>"
                                            data-mes_pagamento="<?php echo $competencia->mes_pagamento ?>"
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

    <!-- Cadastrar Competência -->
    <div class="modal fade" id="modalCadastrarCompetencia" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCompetenciaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Competência</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="nome" class="form-label">Nome *</label>
                                <input type="text" name="nome" class="form-control" value="Compêtencia " required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_inicio" class="form-label">Início</label>
                                <input type="date" class="form-control" name="periodo_inicio" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_fim" class="form-label">Fim</label>
                                <input type="date" class="form-control" name="periodo_fim" required>
                            </div>
                            <div class="col-4 offset-4 mt-2">
                                <label for="mes_pagamento" class="form-label">Mês Pagamento</label>
                                <input type="month" name="mes_pagamento" class="form-control" required>
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
    
    <!-- Editar Competência -->
    <div class="modal fade" id="modalEditarCompetencia" tabindex="1" role="dialog" aria-labelledby="modalEditarCompetenciaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar <span id="editar_competencia_nome"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <input type="hidden" name="id_faturamento_competencia" id="editar_id_faturamento_competencia">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="nome" class="form-label">Nome *</label>
                                <input type="text" name="nome" id="editar_nome" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_inicio" class="form-label">Início</label>
                                <input type="date" class="form-control" name="periodo_inicio" id="editar_periodo_inicio" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_fim" class="form-label">Fim</label>
                                <input type="date" class="form-control" name="periodo_fim" id="editar_periodo_fim" required>
                            </div>
                            <div class="col-4 offset-4 mt-2">
                                <label for="mes_pagamento" class="form-label">Mês Pagamento</label>
                                <input type="month" name="mes_pagamento" id="editar_mes_pagamento" class="form-control" required>
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
            $('#fatu').addClass('active')
            $('#faturamento_competencias').addClass('active')

            $('#modalEditarCompetencia').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_faturamento_competencia = button.data('id_faturamento_competencia');
                let nome = button.data('nome');
                let periodo_inicio = button.data('periodo_inicio');
                let periodo_fim = button.data('periodo_fim');
                let mes_pagamento = button.data('mes_pagamento');

                $('#editar_competencia_nome').text(nome);
                $('#editar_id_faturamento_competencia').val(id_faturamento_competencia);
                $('#editar_nome').val(nome);
                $('#editar_periodo_inicio').val(periodo_inicio);
                $('#editar_periodo_fim').val(periodo_fim);
                $('#editar_mes_pagamento').val(mes_pagamento.substring(0, 7));
            });

            $('#buscar_nota').on('input change keyup search', function () {
                const q = ($(this).val() ?? '').toString().trim();
                
                const $cards = $('.container-fluid.mt-n10 .row > [class*="col-"]').has('.card');

                if (q === '') {
                    $cards.show();
                    return;
                }

                $cards.each(function () {
                    // pega o input que guarda a lista de IDs (hidden OU text)
                    const $inputNotas = $(this).find('.card-body .notas-ids');
                    const notasStr = ($inputNotas.val() || '').toString();
                    
                    // normaliza e separa por ';'
                    const notas = notasStr
                        .split(';')
                        .map(s => s.trim())
                        .filter(Boolean);

                    const contem = notas.some(nota => nota.includes(q));
                    $(this).toggle(contem);
                });
            });

        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>
</html>