<?php
$Exames = new Exame();
$Pacotes = new Pacote_Exame();
$Medicos = new Medico();
$Bloco_Nota = new Callcenter_Bloco_Notas();
$Setores = new Setor();
$Convenios = new Convenio();

if (isset($_POST['btnCadastrarBlocoNota'])) {
    $Bloco_Nota->cadastrar($_POST);
}
if (isset($_POST['btnEditarBlocoNota'])) {
    $Bloco_Nota->editar($_POST);
}
if (isset($_POST['btnDeletarBlocoNota'])) {
    $Bloco_Nota->deletar($_POST['id_callcenter_bloco_nota'], $_SESSION['id_usuario']);
}
if (isset($_POST['btnSalvarDetalhesConvenio'])) {
    $Convenios->editarDetalhes($_POST);
}
if (isset($_POST['btnSalvarDetalhesExame'])) {
    $Exames->editarDetalhes($_POST);
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
                                <span>Central de Atendimento</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="row">
                        <div class="col-8 offset-2 text-center">
                            <button type="button" data-toggle="modal" data-target="#modalMedicos" class="btn btn-white m-1">Médicos</button>
                            <button type="button" data-toggle="modal" data-target="#modalConvenios" class="btn btn-secondary m-1">Convenios</button>
                            <button type="button" data-toggle="modal" data-target="#modalExames" class="btn btn-dark m-1">Exames/Procedimentos</button>
                            <button type="button" data-toggle="modal" data-target="#modalPacotesExames" class="btn btn-success m-1">Pacotes de Exames/Procedimentos</button>
                            <?php if (verificarSetor([1, 21, 12])) { ?>
                                <button type="button" data-toggle="modal" data-target="#modalBlocosNota" class="btn btn-info m-1">Blocos de Notas</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-10">

                    <?php foreach ($Bloco_Nota->listar() as $bloco) {

                        // Array de setores associados ao bloco
                        $setoresBloco = array_filter(array_map('trim', explode(',', $bloco->id_setores_associados)));

                        // Setores do usuário logado
                        $setoresUsuario = $_SESSION['id_setores'] ?? [];

                        // SETORES QUE PODEM VER TUDO
                        $setoresLiberados = [1, 10, 21];

                        // Verifica se o usuário tem permissão total
                        $usuarioTemSuperPermissao = count(array_intersect($setoresUsuario, $setoresLiberados)) > 0;

                        // Verifica permissão final
                        if ($usuarioTemSuperPermissao) {
                            $usuarioPodeVer = true;
                        } else {
                            if (empty($setoresBloco)) {
                                // Bloco sem setores → todos podem ver
                                $usuarioPodeVer = true;
                            } else {
                                // Interseção entre os setores do bloco e os setores do usuário
                                $intersecao = array_intersect($setoresUsuario, $setoresBloco);
                                $usuarioPodeVer = !empty($intersecao);
                            }
                        }

                        // Se o usuário NÃO puder ver, pula para o próximo bloco
                        if (!$usuarioPodeVer) continue;
                    ?>

                        <!-- BOTÃO -->
                        <button class="btn btn-primary m-1"
                            data-toggle="modal"
                            data-target="#modalBloco_<?php echo $bloco->id_callcenter_bloco_nota; ?>">
                            <?php echo htmlspecialchars($bloco->titulo); ?>
                        </button>

                        <!-- MODAL -->
                        <div class="modal fade" id="modalBloco_<?php echo $bloco->id_callcenter_bloco_nota; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <?php echo htmlspecialchars($bloco->titulo); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <?php echo nl2br($bloco->conteudo); ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>

    <!-- Modal Medicos -->
    <div class="modal fade" id="modalMedicos" tabindex="1" role="dialog" aria-labelledby="modalMedicos" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Médicos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableCentralMedicos" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Unidades</th>
                                    <th>Observações</th>
                                    <th>CRM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Medicos->listarAtivos() as $medico) {
                                    $lista = '';
                                    if (!empty($medico->unidades)) {
                                        $codigos = explode(';', $medico->unidades);
                                        $nomes = [];
                                        foreach ($codigos as $c) {
                                            if ($c == 1) $nomes[] = "Parque";
                                            if ($c == 3) $nomes[] = "Mauá";
                                            if ($c == 5) $nomes[] = "Jardim";
                                        }
                                        $lista = implode(", ", $nomes);
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $medico->nome; ?></td>
                                        <td class="text-center"><?php echo $lista; ?></td>
                                        <td><?php echo $medico->observacao ?></td>
                                        <td class="text-center"><?php echo $medico->crm; ?></td>
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

    <!-- Modal Convenios -->
    <div class="modal fade" id="modalConvenios" tabindex="1" role="dialog" aria-labelledby="modalConvenios" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Convenios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableCentralConvenios" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Convenios</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Convenios->listar() as $convenio) {
                                ?>
                                    <tr>
                                        <td><?php echo $convenio->convenio; ?></td>
                                        <td class="text-center">
                                            <button data-toggle="modal" data-target="#modalConveniosDetalhes" class="btn btn-transparent btn-sm btn-icon" data-id_convenio="<?php echo $convenio->id_convenio ?>" data-observacoes="<?php echo $convenio->observacoes ?>" data-nome_convenio="<?php echo $convenio->convenio ?>"><i class="fa-solid fa-eye"></i></button>
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

    <!-- Modal Convenios Detalhes -->
    <div class="modal fade" id="modalConveniosDetalhes" tabindex="1" role="dialog" aria-labelledby="modalConveniosDetalhes" aria-hidden="true">
        <form method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes do Convenio: <span id="editar_convenio_nome"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_convenio" id="editar_convenio_id_convenio">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="editar_convenio_observacoes" class="form-label">Observações</label>
                                <textarea name="observacoes" rows="8" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> id="editar_convenio_observacoes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                        <?php if (verificarSetor([1, 12, 21])) { ?>
                            <button type="submit" name="btnSalvarDetalhesConvenio" class="btn btn-success">Salvar</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
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
                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableCentralExames" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Exame</th>
                                    <th>Valor Particular</th>
                                    <th>Valor Fidelidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Exames->listar() as $exame) { ?>
                                    <tr>
                                        <td><?php echo $exame->exame; ?></td>
                                        <td>R$ <?php echo number_format($exame->valor_particular, 2, ',', '.') ?></td>
                                        <td>R$ <?php echo number_format($exame->valor_fidelidade, 2, ',', '.') ?></td>
                                        <td class="text-center"><button type="button"
                                                data-toggle="modal"
                                                data-target="#modalExameDetalhes"
                                                class="btn btn-icon btn-sm btn-transparent"
                                                data-id_exame="<?php echo $exame->id_exame ?>"
                                                data-nome_exame="<?php echo $exame->exame ?>"
                                                data-tempo="<?php echo $exame->tempo ?>"
                                                data-tempo_jejum="<?php echo $exame->tempo_jejum ?>"
                                                data-observacoes="<?php echo $exame->observacoes ?>"
                                                data-dilatar="<?php echo $exame->dilatar ?>"
                                                data-acompanhante="<?php echo $exame->acompanhante ?>"
                                                data-anestesico="<?php echo $exame->anestesico ?>"
                                                data-sinonimos="<?php echo $exame->sinonimos ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
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

    <!-- Modal Exame Detalhes -->
    <div class="modal fade" id="modalExameDetalhes" tabindex="1" role="dialog" aria-labelledby="modalExameDetalhes" aria-hidden="true">
        <form method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes de Exames</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center text-danger">Exames devem ser agendados 15 dias no minímo após a consulta!</h3>
                        <hr>
                        <div class="row">
                            <input type="hidden" name="id_exame" id="editar_exame_id_exame">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-2 offset-1">
                                <label for="editar_exame_tempo" class="form-label">Tempo (Min)</label>
                                <input type="number" name="tempo" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="editar_exame_sinonimos" class="form-label">Sinonimos</label>
                                <input type="text" name="sinonimos" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="editar_exame_tempo_jejum" class="form-label">Tempo de Jejum (Horas)</label>
                                <input type="number" id="editar_exame_tempo_jejum" name="tempo_jejum" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> class="form-control">
                            </div>
                            <div class="col-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" name="dilatar" type="checkbox" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> id="editar_exame_dilatar">
                                    <label class="form-check-label" for="editar_exame_dilatar">
                                        Precisa Dilatar
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="acompanhante" type="checkbox" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> id="editar_exame_acompanhante">
                                    <label class="form-check-label" for="editar_exame_acompanhante">
                                        Precisa de Acompanhante
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="anestesico" type="checkbox" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> id="editar_exame_anestesico">
                                    <label class="form-check-label" for="editar_exame_anestesico">
                                        Anestésico
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10 offset-1">
                                <label for="editar_exame_observacoes" class="form-label">Observações</label>
                                <textarea name="observacoes" rows="8" <?php if (bloquearSetor([1, 12, 21])) { ?> disabled <?php } ?> id="editar_exame_observacao" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                        <?php if (verificarSetor([1, 21])) { ?>
                            <button type="submit" name="btnSalvarDetalhesExame" class="btn btn-success">Salvar</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Pacotes de Exames -->
    <div class="modal fade" id="modalPacotesExames" tabindex="1" role="dialog" aria-labelledby="modalPacotesExames" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pacotes de Exames</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableCentralPacotes" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Pacote</th>
                                    <th>Valor Fidelidade</th>
                                    <th>Exames</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Pacotes->listartODOS() as $pacote) {
                                    $exames = $Pacotes->listarNomeExamesDoPacote($pacote->id_exames_pacote);
                                ?>
                                    <tr>
                                        <td><?php echo $pacote->pacote; ?> (<?php echo Helper::mostrar_empresa($pacote->id_empresa) ?>)</td>
                                        <td class="text-center">R$ <?php echo number_format($pacote->valor_fidelidade, 2, ',', '.') ?></td>
                                        <td><?php echo !empty($exames) ? implode('<br>', $exames) : '-'; ?></td>
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

    <!-- Modal Blocos de Nota -->
    <div class="modal fade" id="modalBlocosNota" tabindex="1" role="dialog" aria-labelledby="modalBlocosNota" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Blocos de Nota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="datatable table-responsive">
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modalCadastrarBlocosNota">Cadastrar</button>
                        <br><br>
                        <table class="table table-bordered table-hover" id="dataTableBlocoNotas" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Setores</th>
                                    <th style="width: 180px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Bloco_Nota->listar() as $bloco) { ?>
                                    <tr>
                                        <td><?php echo $bloco->titulo; ?></td>
                                        <td><?php echo !empty($bloco->setores_associados) ? $bloco->setores_associados : 'Todos os setores'; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-toggle="modal"
                                                data-target="#modalEditarBlocosNota"
                                                data-id="<?php echo $bloco->id_callcenter_bloco_nota; ?>"
                                                data-titulo="<?php echo $bloco->titulo ?>"
                                                data-conteudo="<?php echo htmlspecialchars($bloco->conteudo, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-setores_ids="<?php echo $bloco->id_setores_associados ?>">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-toggle="modal"
                                                data-target="#modalDeletarBlocoNota"
                                                data-id="<?php echo $bloco->id_callcenter_bloco_nota; ?>"
                                                data-titulo="<?php echo $bloco->titulo; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
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

    <!-- Modal Cadastrar Blocos de Nota -->
    <div class="modal fade" id="modalCadastrarBlocosNota" tabindex="1" role="dialog" aria-labelledby="modalCadastrarBlocosNota" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar Bloco de Notas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="?" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8">
                                <label for="cadastrar_titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" name="titulo" id="cadastrar_titulo" required>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Setores que podem visualizar *</label>
                                <div class="row">
                                    <?php foreach ($Setores->listarMenos([1, 10, 21]) as $setor) { ?>
                                        <div class="col-4">
                                            <label style="cursor:pointer;">
                                                <input type="checkbox" name="id_setores[]" value="<?php echo $setor->id_setor; ?>">
                                                <?php echo $setor->setor; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                                <small class="text-muted">Selecione um ou mais setores.</small>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="cadastrar_conteudo" class="form-label">Conteúdo *</label>
                                <textarea name="conteudo" id="cadastrar_conteudo" rows="10" class="form-control" required></textarea>
                            </div>
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrarBlocoNota" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Blocos de Nota -->
    <div class="modal fade" id="modalEditarBlocosNota" tabindex="1" role="dialog" aria-labelledby="modalEditarBlocosNota" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Bloco de Notas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="?" method="post" id="formEditarBlocoNota">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8">
                                <label for="editar_titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" name="titulo" id="editar_titulo" required>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Setores que podem visualizar *</label>
                                <div class="row" id="setores_editar_container">
                                    <?php foreach ($Setores->listarMenos([1, 10, 21]) as $setor) { ?>
                                        <div class="col-4">
                                            <label style="cursor:pointer;">
                                                <input type="checkbox"
                                                    name="id_setores[]"
                                                    value="<?php echo $setor->id_setor; ?>"
                                                    class="checkbox-setor-editar"
                                                    data-id-setor="<?php echo $setor->id_setor; ?>">
                                                <?php echo $setor->setor; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                                <small class="text-muted">Selecione um ou mais setores.</small>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="editar_conteudo" class="form-label">Conteúdo *</label>
                                <textarea name="conteudo" id="editar_conteudo" rows="15" class="form-control" required></textarea>
                            </div>
                            <input type="hidden" name="id_callcenter_bloco_nota" id="editar_id_bloco_nota">
                            <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?? 0; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditarBlocoNota" class="btn btn-success">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Deletar Bloco de Nota -->
    <div class="modal fade" id="modalDeletarBlocoNota" tabindex="-1" role="dialog" aria-labelledby="modalDeletarBlocoNotaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar Bloco de Nota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja deletar o bloco: <b id="deletar_titulo_bloco"></b>?</p>
                        <input type="hidden" name="id_callcenter_bloco_nota" id="deletar_id_bloco_nota">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnDeletarBlocoNota">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#call').addClass('active')
            $('#rece').addClass('active')
            $('#cc_central').addClass('active')
            $('#recepcao_central').addClass('active')

            $('#modalEditarBlocosNota').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id = button.data('id')
                let titulo = button.data('titulo')
                let conteudo = button.data('conteudo')
                let setores_ids = button.data('setores_ids')

                $('#editar_id_bloco_nota').val(id);
                $('#editar_titulo').val(titulo);
                $('#editar_conteudo').val(conteudo);

                console.log(setores_ids)
                $('.checkbox-setor-editar').prop('checked', false);

                if (setores_ids) {
                    let setoresArray = String(setores_ids).split(',');

                    setoresArray.forEach(function(setorId) {
                        $('.checkbox-setor-editar[data-id-setor="' + setorId.trim() + '"]')
                            .prop('checked', true);
                    });
                }
            });

            $('#modalDeletarBlocoNota').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                $('#deletar_id_bloco_nota').val(button.data('id'));
                $('#deletar_titulo_bloco').text(button.data('titulo'));
            });

            $('#modalConveniosDetalhes').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_convenio = button.data('id_convenio')
                let observacoes = button.data('observacoes')
                let nome_convenio = button.data('nome_convenio')

                $('#editar_convenio_nome').text(nome_convenio)
                $('#editar_convenio_id_convenio').val(id_convenio)
                $('#editar_convenio_observacoes').val(observacoes)
            });

            $('#modalExameDetalhes').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);

                // Pegando os dados do botão
                let id_exame = button.data('id_exame')
                let tempo = button.data('tempo')
                let sinonimos = button.data('sinonimos')
                let tempo_jejum = button.data('tempo_jejum')
                let dilatar = button.data('dilatar')
                let acompanhante = button.data('acompanhante')
                let anestesico = button.data('anestesico')
                let observacoes = button.data('observacoes')
                let nome_exame = button.data('nome_exame')

                $('#editar_exame_id_exame').val(id_exame)

                $('input[name="tempo"]').val(tempo)
                $('input[name="sinonimos"]').val(sinonimos)

                $('#editar_exame_tempo_jejum').val(tempo_jejum)

                $('#editar_exame_dilatar').prop('checked', dilatar == 1);
                $('#editar_exame_acompanhante').prop('checked', acompanhante == 1);
                $('#editar_exame_anestesico').prop('checked', anestesico == 1);

                $('#editar_exame_observacao').val(observacoes)
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-exames.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-medicos.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-bloco_notas.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-convenios.js"></script>
</body>

</html>