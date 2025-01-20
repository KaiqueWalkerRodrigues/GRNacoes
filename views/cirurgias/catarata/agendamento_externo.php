<?php 
    $Medico = new Medico();
    $Convenio = new Convenio();
    $Catarata_Lente = new Catarata_Lente();
    $Catarata_Agenda = new Catarata_agenda();
    $Catarata_Turma = new Catarata_turma();
    $Catarata_Agendamento = new Catarata_Agendamento();

    if(isset($_POST['btnAgendar'])){
        $Catarata_Agendamento->cadastrarExterno($_POST);
    }
    if(isset($_POST['btnEditar'])){
        $Catarata_Agendamento->editarExterno($_POST);
    }
    if(isset($_POST['btnDeletar'])){
        $Catarata_Agendamento->deletarExterno($_POST['id_agendamento'],$_SESSION['id_usuario']);
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
    <!-- Tela de Carregamento -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Agendamento Externo Cirurgia</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Cirurgias de Catarata Externo
                            <button class="btn btn-datatable btn-icon btn-sm btn-info ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarCatarata">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nome Paciente</th>
                                            <th>Médico Solicitante</th>
                                            <th>Data Cirurgia</th>
                                            <th>Turma</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Catarata_Agendamento->listarExternos() as $agendamento){ ?>
                                            <tr>
                                                <td><?php echo $agendamento->id_catarata_agendamento ?></td>
                                                <td><?php echo $agendamento->nome ?></td>
                                                <td><?php echo Helper::encurtarNome($Medico->mostrar($agendamento->id_solicitante)->nome) ?></td>
                                                <td><?php echo Helper::formatarData($Catarata_Agenda->mostrar($agendamento->id_agenda)->data) ?></td>
                                                <td><?php echo Helper::formatarHorario($Catarata_Turma->mostrar($agendamento->id_turma)->horario) ?></td>
                                                <td class="text-center"><?php if($agendamento->dioptria_esquerda != 0 OR $agendamento->dioptria_direita != 0){ echo "<b class='text-success'>Completo</b>"; }else{ echo "<b class='text-warning'>Incompleto</b>"; } ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark 2" type="button" data-toggle="modal" data-target="#modalEditarAgendamento"
                                                        data-id_agendamento="<?php echo $agendamento->id_catarata_agendamento ?>"
                                                        data-nome="<?php echo $agendamento->nome ?>"
                                                        data-cpf="<?php echo $agendamento->cpf ?>"
                                                        data-contato="<?php echo $agendamento->contato ?>"
                                                        data-id_solicitante="<?php echo $agendamento->id_solicitante ?>"
                                                        data-id_convenio="<?php echo $agendamento->id_convenio ?>"
                                                        data-olhos="<?php echo $agendamento->olhos ?>"
                                                        data-dioptria_esquerda="<?php echo $agendamento->dioptria_esquerda ?>"
                                                        data-dioptria_direita="<?php echo $agendamento->dioptria_direita ?>"
                                                        data-id_lente="<?php echo $agendamento->id_lente ?>"
                                                        data-id_agenda="<?php echo $agendamento->id_agenda ?>"
                                                        data-id_turma="<?php echo $agendamento->id_turma ?>"
                                                        data-valor="<?php echo $agendamento->valor ?>"
                                                        data-forma_pgto="<?php echo $agendamento->forma_pgto ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalDeletarAgendamento"
                                                        data-id_agendamento="<?php echo $agendamento->id_catarata_agendamento ?>"
                                                        data-nome="<?php echo $agendamento->nome ?>"
                                                    ><i class="fa-solid fa-trash"></i></button>
                                                </td>
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

    <!-- Modal Agendar Cirurgia -->
    <div class="modal fade" id="modalCadastrarCatarata" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCatarataLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agendar Externo Cirurgia Catarata</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_orientador" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <br>
                            <div class="col-4 offset-1 mb-2">
                                <label for="nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="cadastrar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="id_solicitante" class="form-label">Médico Solicitante *</label>
                                <select id="cadastrar_id_solicitante" name="id_solicitante" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Cirurgia:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-3 offset-1">
                                <label for="olhos" class="form-label">Olhos *</label>
                                <select name="olhos" id="cadastrar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="1">Olho Esquerdo</option>
                                    <option value="2">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-2 d-none" id="div_cadastrar_dioptria_esquerda">
                                <label for="dioptria_esquerda" class="form-label">Dioptria Esquerda</label>
                                <input type="number" step="0.01" max="35" min="-6" name="dioptria_esquerda" id="cadastrar_dioptria_esquerda" class="form-control">
                            </div>
                            <div class="col-2 d-none" id="div_cadastrar_dioptria_direita">
                                <label for="dioptria_direita" class="form-label">Dioptria Direita</label>
                                <input type="number" step="0.01" max="35" min="-6" name="dioptria_direita" id="cadastrar_dioptria_direita" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="cadastrar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Catarata_Lente->listar() as $Catarata_lente){ ?>
                                        <option value="<?php echo $Catarata_lente->id_catarata_lente ?>"><?php echo $Catarata_lente->lente ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-2 offset-1">
                                        <label for="id_agenda" class="form-label">Data da Cirurgia *</label>
                                        <select name="id_agenda" id="cadastrar_id_agenda" class="form-control" required>
                                            <option value="">Selcione...</option>
                                            <?php foreach($Catarata_Agenda->listarProximas() as $Catarata_agenda){ ?>
                                                <option value="<?php echo $Catarata_agenda->id_catarata_agenda ?>"><?php echo helper::formatarData($Catarata_agenda->data); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-2 d-none">
                                        <label for="id_turma" class="form-label">Turma *</label>
                                        <select name="id_turma" id="cadastrar_turma" class="form-control" required>
                                            <option value="">Selecione...</option>
                                        </select>
                                    </div>
                                    <div class="col-4 text-center">
                                        <label for="valor" class="form-label">Valor Total *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" class="form-control" step="0.01" name="valor" id="cadastrar_valor" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnAgendar" class="btn btn-primary">Agendar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Agendamento Cirurgia -->
    <div class="modal fade" id="modalEditarAgendamento" tabindex="-1" role="dialog" aria-labelledby="modalEditarAgendamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Agendamento Externo Cirurgia Catarata</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Campos ocultos necessários -->
                        <input type="hidden" name="id_agendamento" id="editar_id_agendamento">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_orientador" value="<?php echo $_SESSION['id_usuario'] ?>">
                        
                        <div class="row">
                            <div class="offset-1 col-10">
                                <b class="text-dark">Ficha Paciente:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <br>
                            <div class="col-4 offset-1 mb-2">
                                <label for="nome" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="editar_nome" name="nome" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="id_solicitante" class="form-label">Médico Solicitante *</label>
                                <select id="editar_id_solicitante" name="id_solicitante" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="offset-1 col-10 mt-4">
                                <b class="text-dark">Ficha Cirurgia:</b>
                                <hr style="margin-top: -0.2%;">
                            </div>
                            <div class="col-3 offset-1">
                                <label for="olhos" class="form-label">Olhos *</label>
                                <select name="olhos" id="editar_olhos" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">Ambos os Olhos</option>
                                    <option value="1">Olho Esquerdo</option>
                                    <option value="2">Olho Direito</option>
                                </select>
                            </div>
                            <div class="col-2 d-none" id="div_editar_dioptria_esquerda">
                                <label for="dioptria_esquerda" class="form-label">Dioptria Esquerda</label>
                                <input type="number" step="0.01" max="35" min="-6" name="dioptria_esquerda" id="editar_dioptria_esquerda" class="form-control">
                            </div>
                            <div class="col-2 d-none" id="div_editar_dioptria_direita">
                                <label for="dioptria_direita" class="form-label">Dioptria Direita</label>
                                <input type="number" step="0.01" max="35" min="-6" name="dioptria_direita" id="editar_dioptria_direita" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="id_lente" class="form-label">Modelo Lente *</label>
                                <select name="id_lente" id="editar_id_lente" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Catarata_Lente->listar() as $Catarata_lente){ ?>
                                        <option value="<?php echo $Catarata_lente->id_catarata_lente ?>"><?php echo $Catarata_lente->lente ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-2 offset-1">
                                        <label for="id_agenda" class="form-label">Data da Cirurgia *</label>
                                        <select name="id_agenda" id="editar_id_agenda" class="form-control" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($Catarata_Agenda->listarProximas() as $Catarata_agenda){ ?>
                                                <option value="<?php echo $Catarata_agenda->id_catarata_agenda ?>">
                                                    <?php echo helper::formatarData($Catarata_agenda->data); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-2 d-none">
                                        <label for="id_turma" class="form-label">Turma *</label>
                                        <select name="id_turma" id="editar_turma" class="form-control" required>
                                            <option value="">Selecione...</option>
                                        </select>
                                    </div>
                                    <div class="col-4 text-center">
                                        <label for="valor" class="form-label">Valor Total *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" class="form-control" step="0.01" name="valor" id="editar_valor" required>
                                        </div>
                                    </div>
                                </div>
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



    <!-- Modal Deletar Catarata -->
    <div class="modal fade" id="modalDeletarAgendamento" tabindex="-1" role="dialog" aria-labelledby="modalDeletarAgendamentoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarAgendamentoLabel">Deletar o Agendamento de <span class="deletar_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_agendamento" id="deletar_id_agendamento">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar o Agendamento de <br> <b class="deletar_nome"></b>? <br> Essa ação é Irreversível.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletar">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#ciru').addClass('active')
            $('#cirurgias_catarata').addClass('active')
            $('#cirurgias_catarata_orcamento').addClass('active')

            $('#cadastrar_id_agenda').on('change', function () {
                const idAgenda = $(this).val();

                if (idAgenda) {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_catarata_turmas.php',
                        type: 'GET',
                        data: { id_agenda: idAgenda },
                        dataType: 'json',
                        success: function (response) {
                            const turmaSelect = $('#cadastrar_turma');
                            turmaSelect.empty(); // Limpa as opções atuais
                            if (response.error) {
                                alert(response.error);
                                turmaSelect.closest('.col-2').addClass('d-none');
                            } else if (response.length > 0) {
                                let options = '<option value="">Selecione...</option>';
                                response.forEach(function (turma) {
                                    if (turma.vagas_disponiveis > 0) {
                                        options += `<option value="${turma.id_turma}">
                                            ${turma.horario} (${turma.vagas_disponiveis} vagas)
                                        </option>`;
                                    } else {
                                        options += `<option value="" disabled>
                                            ${turma.horario} (Lotado)
                                        </option>`;
                                    }
                                });
                                turmaSelect.html(options).closest('.col-2').removeClass('d-none');
                            } else {
                                turmaSelect.html('<option value="">Nenhuma turma disponível</option>').closest('.col-2').removeClass('d-none');
                            }
                        },
                        error: function () {
                            alert('Erro ao buscar turmas. Tente novamente.');
                        }
                    });
                } else {
                    $('#cadastrar_turma').empty().closest('.col-2').addClass('d-none');
                }
            });

            $('#cadastrar_olhos').change(function (e) { 
                let olhos = $(this).val()
                
                if(olhos == 0){
                    $('#div_cadastrar_dioptria_esquerda').removeClass('d-none')
                    $('#div_cadastrar_dioptria_direita').removeClass('d-none')
                }else if(olhos == 1){
                    $('#div_cadastrar_dioptria_esquerda').removeClass('d-none')
                    $('#cadastrar_dioptria_direita').val('')
                    $('#div_cadastrar_dioptria_direita').addClass('d-none');
                }else if(olhos == 2){
                    $('#div_cadastrar_dioptria_esquerda').addClass('d-none')
                    $('#cadastrar_dioptria_esquerda').val('')
                    $('#div_cadastrar_dioptria_direita').removeClass('d-none')
                }else{
                    $('#div_cadastrar_dioptria_esquerda').addClass('d-none');
                    $('#cadastrar_dioptria_esquerda').val('');
                    $('#div_cadastrar_dioptria_direita').addClass('d-none');
                    $('#cadastrar_dioptria_direita').val('');
                }
            });

            $('#editar_olhos').change(function () {
                let olhos = $(this).val();
                EditarDioptriaOlhos(olhos)
            });

            function EditarDioptriaOlhos(olhos){
                if (olhos == 0) {
                    $('#div_editar_dioptria_esquerda').removeClass('d-none');
                    $('#div_editar_dioptria_direita').removeClass('d-none');
                } else if (olhos == 1) {
                    $('#div_editar_dioptria_esquerda').removeClass('d-none');
                    $('#editar_dioptria_direita').val('');
                    $('#div_editar_dioptria_direita').addClass('d-none');
                } else if (olhos == 2) {
                    $('#div_editar_dioptria_esquerda').addClass('d-none');
                    $('#editar_dioptria_esquerda').val('');
                    $('#div_editar_dioptria_direita').removeClass('d-none');
                } else {
                    $('#div_editar_dioptria_esquerda').addClass('d-none');
                    $('#editar_dioptria_esquerda').val('');
                    $('#div_editar_dioptria_direita').addClass('d-none');
                    $('#editar_dioptria_direita').val('');
                }
            }

            $('#modalEditarAgendamento').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                // Captura os dados do botão que acionou o modal
                let id_agendamento = button.data('id_agendamento');
                let nome = button.data('nome');
                let id_solicitante = button.data('id_solicitante');
                let olhos = button.data('olhos');
                let dioptria_esquerda = button.data('dioptria_esquerda');
                let dioptria_direita = button.data('dioptria_direita');
                let id_lente = button.data('id_lente');
                let id_agenda = button.data('id_agenda');
                let id_turma = button.data('id_turma');
                let valor = button.data('valor');

                // Preenche os campos do modal com os dados do agendamento
                $('#editar_id_agendamento').val(id_agendamento);
                $('#editar_nome').val(nome);
                $('#editar_id_solicitante').val(id_solicitante);
                $('#editar_olhos').val(olhos).change(); // Dispara mudança para ajustar dioptrias
                $('#editar_dioptria_esquerda').val(dioptria_esquerda);
                $('#editar_dioptria_direita').val(dioptria_direita);
                $('#editar_id_lente').val(id_lente);
                $('#editar_id_agenda').val(id_agenda).change(); // Dispara mudança para carregar turmas
                $('#editar_valor').val(valor);

                // Após carregar as turmas via AJAX, definir a turma selecionada
                $.ajax({
                    url: '/GRNacoes/views/ajax/get_catarata_turmas.php',
                    type: 'GET',
                    data: { id_agenda: id_agenda },
                    dataType: 'json',
                    success: function (response) {
                        const turmaSelect = $('#editar_turma');
                        turmaSelect.empty();
                        if (response.error) {
                            alert(response.error);
                            turmaSelect.closest('.col-2').addClass('d-none');
                        } else if (response.length > 0) {
                            let options = '<option value="">Selecione...</option>';
                            response.forEach(function (turma) {
                                // Permite selecionar a turma atual, mesmo se estiver lotada
                                if (turma.vagas_disponiveis > 0 || turma.id_turma == id_turma) {
                                    options += `<option value="${turma.id_turma}">
                                                    ${turma.horario} (${turma.vagas_disponiveis} vagas)
                                                </option>`;
                                } else {
                                    options += `<option value="${turma.id_turma}" disabled>
                                                    ${turma.horario} (Lotado)
                                                </option>`;
                                }
                            });
                            turmaSelect.html(options).closest('.col-2').removeClass('d-none');
                            turmaSelect.val(id_turma);
                        } else {
                            turmaSelect.html('<option value="">Nenhuma turma disponível</option>').closest('.col-2').removeClass('d-none');
                        }
                    },
                    error: function () {
                        alert('Erro ao buscar turmas. Tente novamente.');
                    }
                });
            });


            $('#editar_cpf').on('input', function() {
                EditarformatarCPF($(this));
            });
            $('#editar_contato').on('input', function() {
                EditarformatarCelular($(this));
            });

            $('#modalDeletarAgendamento').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_agendamento = button.data('id_agendamento')
                let nome = button.data('nome')

                $('#deletar_id_agendamento').val(id_agendamento)
                $('.deletar_nome').html(nome)
            }); 

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-agendamentos_externo.js"></script>
</body>

</html>
