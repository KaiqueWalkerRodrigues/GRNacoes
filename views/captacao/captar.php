<?php 
    $Chamado = new Chamado();
    $Cargo = new Cargo();
    $Setor = new Setor();
    $Medico = new Medico();
    $Captacao = new Captacao();
    $Usuario = new Usuario();

    if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
        $capHoje = $Captacao->listarHojeAdmin();
    }else{
        $capHoje = $Captacao->listarHoje($_SESSION['id_empresa']);
    }

    $usuario = $Usuario->mostrar($_SESSION['id_usuario']);
    $setor = $Setor->mostrar($usuario->id_setor);

    if(isset($_POST['btnEditar'])){
        $Captacao->editar($_POST);
        header('location:'.URL.'/captacao/captar');
    }

    if(isset($_POST['btnExcluir'])){
        $Captacao->deletar($_POST['id_captado'],$_POST['id_usuario']);
        header('location:'.URL.'/captacao/captar');
    }
?>
<!DOCTYPE html>
<html lang="en">
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
        <?php include_once('resources/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Captar</span>
                            </h1>
                            <br>
                            <form id="dataForm" class="row text-light" method="POST">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                                <input type="hidden" name="captacao_cadastrar" value="1">
                                <div class="col-3">
                                    <label class="form-label" for="name">Nome do Paciente:</label>
                                    <input class="form-control" type="text" id="name" name="nome_paciente" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label" for="captado">Captado:</label>
                                    <select class="form-control" id="captado" name="captado">
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                        <option value="2">Lente de Contato - Sim</option>
                                        <option value="3">Lente de Contato - Não</option>
                                        <option value="4">Garantia</option>
                                    </select>
                                </div>
                                <div class="col-3" id="motivoContainer" style="display:none;">
                                    <label class="form-label" for="motivo">Motivo:</label>
                                    <select class="form-control" id="motivo" name="id_motivo">
                                        <option value="">Selecione...</option>
                                        <option value="1">Pressa</option>
                                        <option value="2">Tem outra Ótica</option>
                                        <option value="3">Não mudou Grau</option>
                                        <option value="4">Não passou no Balção</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label" for="medico">Médico:</label>
                                    <select class="form-control" id="medico" name="id_medico" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach($Medico->listar() as $medico){ ?>
                                            <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-3" id="observacaoContainer">
                                    <label class="form-label" for="observacao">Observação:</label>
                                    <input class="form-control" type="text" id="observacao">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Captações
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hórario</th>
                                            <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){ ?>
                                                <th>Empresa</th>
                                            <?php } ?>
                                            <th>Captador</th>
                                            <th>Paciente</th>
                                            <th>Captado</th>
                                            <th>Motivo</th>
                                            <th>Médico</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($capHoje as $cap) { ?>
                                            <tr class="text-center">
                                                <td><?php echo Helper::formatarHorario($cap->created_at) ?></td>
                                                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){ ?>
                                                    <td><?php echo Helper::mostrar_empresa($cap->id_empresa) ?></td>
                                                <?php } ?>
                                                <td><?php echo $Usuario->mostrar($cap->id_captador)->nome; ?></td>
                                                <td><?php echo $cap->nome_paciente; ?></td>
                                                <td><?php echo Helper::captado($cap->captado); ?></td>
                                                <td><?php echo Helper::motivo($cap->id_motivo) ?></td>
                                                <td><?php echo $cap->nome_medico; ?></td>
                                                <?php if($cap->id_captador == $_SESSION['id_usuario'] OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 13){ ?>
                                                    <td class="text-center">
                                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditar"
                                                                data-id="<?php echo $cap->id_captado; ?>"
                                                                data-nome="<?php echo $cap->nome_paciente; ?>"
                                                                data-captado="<?php echo $cap->captado; ?>"
                                                                data-medico="<?php echo $cap->id_medico; ?>"
                                                                data-observacao="<?php echo $cap->observacao; ?>"
                                                                ><i class="fa-solid fa-gear"></i></button>
                                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalExcluir" data-id="<?php echo $cap->id_captado; ?>"><i class="fa-solid fa-trash"></i></button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        
                        <div class="col-12">
                            <h5 class="fw-bold">Registro de Captação (<?php $agora = date("d/m/Y"); echo $agora ?>)</h5>
                            <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){ ?>
                                <h5 class="fw-bold">Total Pacientes: <?php echo $Captacao->contarTotalPacientes(date('Y-m-d')) ?></h5>
                            <?php }else{ ?>
                                <h5 class="fw-bold">Total Pacientes: <?php echo $Captacao->contarTotalPacientes(date('Y-m-d'), $_SESSION['id_empresa']) ?></h5>
                            <?php } ?>
                        </div>                       

                        <div class="card bg-success text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptacoes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptacoes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Captados: </b><span><?php echo $Captacao->contarTotalCaptacoes(date('Y-m-d')); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptacoes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptacoes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Captados: </b><span><?php echo $Captacao->contarTotalCaptacoes(date('Y-m-d'), $_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-danger text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarNaoCaptacoes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarNaoCaptacoes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Não Captados: </b><span><?php echo $Captacao->contarTotalNaoCaptacoes(date('Y-m-d')); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarNaoCaptacoes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php 
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarNaoCaptacoes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Não Captados: </b><span><?php echo $Captacao->contarTotalNaoCaptacoes(date('Y-m-d'), $_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-dark text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptaveis(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptaveis(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Total Captaveis: </b><span><?php echo $Captacao->contarTotalCaptaveis(date('Y-m-d')); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptaveis(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarCaptaveis(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Total Captaveis: </b><span><?php echo $Captacao->contarTotalCaptaveis(date('Y-m-d'), $_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-primary text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarLentes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarLentes(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Lentes de Contato: </b><span><?php echo $Captacao->contarTotalLentes(date('Y-m-d')); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarLentes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarLentes(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Lentes de Contato: </b><span><?php echo $Captacao->contarTotalLentes(date('Y-m-d'), $_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-warning text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarGarantias(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarGarantias(date('Y-m-d'), $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Garantias: </b><span><?php echo $Captacao->contarTotalGarantias(date('Y-m-d')); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarGarantias(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <?php 
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(13, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                    <span><?php echo Helper::encurtarNome($usuario->nome) ?>: <span><?php echo $Captacao->contarGarantias(date('Y-m-d'), $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Garantias: </b><span><?php echo $Captacao->contarTotalGarantias(date('Y-m-d'), $_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php') ?>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editando Captação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden"  name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden"  name="id_captado" id="editar_id_captado">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <label for="nome_paciente" class="form-label">Nome do Paciente *</label>
                                <input type="text" id="editar_nome_paciente" name="nome_paciente" class="form-control">
                            </div>
                            <div class="col-4 offset-2">
                                <label for="captado" class="form-label">Captado *</label>
                                <select name="captado" id="editar_captado" class="form-control">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                    <option value="3">Lente de Contato - Sim</option>
                                    <option value="4">Lente de Contato - Não</option>
                                    <option value="5">Garantia</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="id_medico" class="form-label">Médico *</label>
                                <select name="id_medico" id="editar_id_medico" class="form-control">
                                    <?php foreach($Medico->listar() as $medico){ ?>
                                        <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-6 offset-3">
                                <label for="observacao" class="form-label">Observação</label>
                                <input type="text" class="form-control" name="observacao" id="editar_observacao">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Excluir -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="modalExcluirLabel" aria-hidden="true">
        <form action="?" method="post">
            <input type="hidden" name="id_captado" id="excluir_id_captado">
            <input type="hidden" name="id_usuario" id="excluir_id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
            <div class="modal-dialog modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExcluirLabel">Excluir Captação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja excluir esta captação?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" name="btnExcluir">Excluir</button>
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
            $('#capt').addClass('active')
            $('#captacao_captar').addClass('active')
    
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id = button.data('id');
                let nome = button.data('nome');
                let captado = button.data('captado');
                let medico = button.data('medico');
                let observacao = button.data('observacao');
                
                $('#editar_id_captado').val(id);
                $('#editar_nome_paciente').val(nome);
                $('#editar_captado').val(captado);
                $('#editar_id_medico').val(medico);
                $('#editar_observacao').val(observacao);
            });

            $('#modalExcluir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id');

                $('#excluir_id_captado').val(id)
            });

        });

        document.getElementById('captado').addEventListener('change', function() {
            const captadoValue = this.value;
            const motivoContainer = document.getElementById('motivoContainer');
            const observacaoContainer = document.getElementById('observacaoContainer');

            if (captadoValue == "0") { // Verifica se a opção é "Não"
                motivoContainer.style.display = "block"; // Exibe o campo "Motivo"
            } else {
                motivoContainer.style.display = "none"; // Esconde o campo "Motivo"
            }
        });



        // Função para adicionar dados à tabela dinamicamente
        function addDataToTable(name, captado, medico, observacao) {
            const tableBody = document.getElementById('dataTableBody');
            const row = document.createElement('tr');
            
            // Criar células para cada dado
            const nameCell = document.createElement('td');
            nameCell.innerText = name;
            const captadoCell = document.createElement('td');
            captadoCell.innerText = captado;
            const medicoCell = document.createElement('td');
            medicoCell.innerText = medico;
            const observacaoCell = document.createElement('td');
            observacaoCell.innerText = observacao;

            // Adicionar células à linha
            row.appendChild(nameCell);
            row.appendChild(captadoCell);
            row.appendChild(medicoCell);
            row.appendChild(observacaoCell);

            // Adicionar a linha à tabela
            tableBody.appendChild(row);
        }

        // Submeter formulário com Enter ou com o botão
        document.getElementById('dataForm').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Evita o envio do formulário

                // Verifica se todos os campos obrigatórios estão preenchidos
                const nomePaciente = document.getElementById('name').value;
                const idMedico = document.getElementById('medico').value;
                const captado = document.getElementById('captado').value;

                // Condição para verificar os campos obrigatórios
                if (!nomePaciente || !idMedico || !captado) {
                    alert('Por favor, preencha todos os campos obrigatórios.'); // Exibe um alerta
                } else {
                    document.getElementById('dataForm').submit(); // Submete o formulário se tudo estiver preenchido
                }
            }
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-captar.js"></script>
</body>

</html>