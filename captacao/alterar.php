<?php 
    include_once('../const.php');

    $Chamado = new Chamados();
    $Cargo = new Cargo();
    $Setor = new Setor();
    $Medico = new Medicos();
    $Captacao = new Captacao();
    $Usuario = new Usuario();

    // Adicione um controle para pegar a data enviada pelo modal
    $dataSelecionada = isset($_POST['data_atendimento']) ? $_POST['data_atendimento'] : (isset($_GET['data_atendimento']) ? $_GET['data_atendimento'] : date('Y-m-d'));

    // Determina se o modal deve ser exibido
    $mostrarModalDataAtendimento = !isset($_GET['data_atendimento']);

    if ($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12) {
        $capHoje = $Captacao->listarDoDiaAdmin($dataSelecionada);
    } else {
        $capHoje = $Captacao->listarDoDia($_SESSION['id_empresa'], $dataSelecionada);
    }

    $usuario = $Usuario->mostrar($_SESSION['id_usuario']);
    $setor = $Setor->mostrar($usuario->id_setor);

    if(isset($_POST['btnEditar'])){
        $Captacao->editar($_POST);
        header('location:/GRNacoes/captacao/');
    }

    if(isset($_POST['btnExcluir'])){
        $Captacao->desativar($_POST['id_captado'],$_POST['id_usuario']);
        header('location:/GRNacoes/captacao/');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Alterar Captação</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('../sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('../topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <br><br><br><br>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="fw-bold">Registro de Captação (<?php echo Helper::formatarData($dataSelecionada) ?>)</h5>
                            <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){ ?>
                                <h5 class="fw-bold">Total Receitas: <?php echo $Captacao->contarTotalReceitas($dataSelecionada) ?> </h5>
                            <?php }else{ ?>
                                <h5 class="fw-bold">Total Receitas: <?php echo $Captacao->contarTotalReceitas($dataSelecionada, $_SESSION['id_empresa']) ?></h5>
                            <?php } ?>
                        </div>
                        
                        <div class="card bg-success text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarCaptacoes($dataSelecionada, $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Captados: </b><span><?php echo $Captacao->contarTotalCaptacoes($dataSelecionada); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarCaptacoes($dataSelecionada, $usuario->id_usuario,$_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Captados: </b><span><?php echo $Captacao->contarTotalCaptacoes($dataSelecionada,$_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-danger text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarNaoCaptacoes($dataSelecionada, $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Não Captados: </b><span><?php echo $Captacao->contarTotalNaoCaptacoes($dataSelecionada); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarNaoCaptacoes($dataSelecionada, $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Não Captados: </b><span><?php echo $Captacao->contarTotalNaoCaptacoes($dataSelecionada,$_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-dark text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                                    ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarCaptaveis($dataSelecionada, $usuario->id_usuario); ?></span></span>
                                <?php 
                                }
                                ?>
                            <span><b>Total Captaveis: </b><span><?php echo $Captacao->contarTotalCaptaveis($dataSelecionada); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                                    ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarCaptaveis($dataSelecionada, $usuario->id_usuario, $_SESSION['id_empresa']); ?></span></span>
                                <?php 
                                }
                                ?>
                            <span><b>Total Captaveis: </b><span><?php echo $Captacao->contarTotalCaptaveis($dataSelecionada,$_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-primary text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarLentes($dataSelecionada, $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Lentes de Contato: </b><span><?php echo $Captacao->contarTotalLentes($dataSelecionada); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarLentes($dataSelecionada, $usuario->id_usuario,$_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Lentes de Contato: </b><span><?php echo $Captacao->contarTotalLentes($dataSelecionada,$_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>

                        <div class="card bg-warning text-white p-2 text-end">
                            <?php 
                            if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12){
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarGarantias($dataSelecionada, $usuario->id_usuario); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Garantias: </b><span><?php echo $Captacao->contarTotalGarantias($dataSelecionada); ?></span></span>
                            <?php
                            } else {
                                foreach($Usuario->listarAtivosDoSetorDaEmpresa(8, $_SESSION['id_empresa']) as $usuario) {
                            ?>
                                <span><?php echo $usuario->nome ?>: <span><?php echo $Captacao->contarGarantias($dataSelecionada, $usuario->id_usuario,$_SESSION['id_empresa']); ?></span></span>
                            <?php 
                                }
                            ?>
                            <span><b>Garantias: </b><span><?php echo $Captacao->contarTotalGarantias($dataSelecionada,$_SESSION['id_empresa']); ?></span></span>
                            <?php
                            } 
                            ?>
                        </div>
                    </div>


                    <form id="dataForm" class="row" method="POST">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        <input type="hidden" name="dia" value="<?php echo $dataSelecionada ?>">
                        <input type="hidden" name="captacao_cadastrarAlteracao" value="1">
                        <div class="col-3">
                            <label class="form-label" for="name">Nome do Paciente:</label>
                            <input class="form-control" type="text" id="name" name="nome_paciente" required>
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="captado">Captado:</label>
                            <select class="form-control" id="captado" name="captado">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                                <option value="2">Lente de Contato - Sim</option>
                                <option value="3">Lente de Contato - Não</option>
                                <option value="4">Garantia</option>
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
                        <div class="col-2">
                            <label class="form-label" for="medico">Horario:</label>
                            <input type="time" name="horario" id="horario" class="form-control" required>
                        </div>
                        <div class="col-2">
                            <label class="form-label" for="observacao">Observação:</label>
                            <input class="form-control" type="text" id="observacao" name="observacao">
                        </div>
                    </form>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Captações</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hórario</th>
                                            <?php if($_SESSION['id_setor'] == 1){ ?>
                                                <th>Empresa</th>
                                            <?php } ?>
                                            <th>Captador</th>
                                            <th>Paciente</th>
                                            <th>Captado</th>
                                            <th>Médico</th>
                                            <th>Observação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($capHoje as $cap) { ?>
                                        <tr class="text-center">
                                            <td><?php echo Helper::formatarHorario($cap->created_at) ?></td>
                                            <?php if($_SESSION['id_setor'] == 1){ ?>
                                                <td><?php echo Helper::mostrar_empresa($cap->id_empresa) ?></td>
                                            <?php } ?>
                                            <td><?php echo $Usuario->mostrar($cap->id_captador)->nome; ?></td>
                                            <td><?php echo $cap->nome_paciente; ?></td>
                                            <td><?php echo Helper::captado($cap->captado); ?></td>
                                            <td><?php echo $cap->nome_medico; ?></td>
                                            <td><?php echo $cap->observacao; ?></td>
                                            <td class="text-center">
                                                <?php if($cap->id_captador == $_SESSION['id_usuario'] OR $_SESSION['id_setor'] == 1){ ?>
                                                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditar"
                                                            data-id="<?php echo $cap->id_captado; ?>"
                                                            data-nome="<?php echo $cap->nome_paciente; ?>"
                                                            data-captado="<?php echo $cap->captado; ?>"
                                                            data-medico="<?php echo $cap->id_medico; ?>"
                                                            data-observacao="<?php echo $cap->observacao; ?>"
                                                            ><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluir" data-id="<?php echo $cap->id_captado; ?>"><i class="fa-solid fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

            <!-- Modal Data do Atendimento -->
            <div class="modal fade" id="modalDataAtendimento" tabindex="-1" role="dialog" aria-labelledby="modalDataAtendimentoLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDataAtendimentoLabel">Data do Atendimento</h5>
                            <a href="<?php echo URL ?>/" class="close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8 offset-2">
                                    <label for="dataAtendimento">Por favor, informe a data do atendimento:</label>
                                    <input type="date" id="dataAtendimento" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="<?php echo URL ?>/" class="btn btn-secondary">Fechar</a>
                            <button type="button" class="btn btn-primary" id="btnContinuar" disabled>Continuar</button>
                        </div>
                    </div>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger" name="btnExcluir">Excluir</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $('#cap').addClass('active');
        $('#captacao_alterar').addClass('active');

        $(document).ready(function () {
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

                $('#excluir_id_captado').val(id);
            });

            // Abre o modal se a condição for verdadeira
            <?php if ($mostrarModalDataAtendimento): ?>
                $('#modalDataAtendimento').modal('show');
            <?php endif; ?>

            // Verifica se a data foi preenchida e habilita o botão "Continuar"
            $('#dataAtendimento').on('input', function () {
                const data = $(this).val();
                $('#btnContinuar').prop('disabled', !data); // Habilita o botão se a data estiver preenchida
            });

            // Redireciona ao clicar em "Continuar"
            $('#btnContinuar').on('click', function () {
                const dataAtendimento = $('#dataAtendimento').val();
                if (dataAtendimento) {
                    // Redireciona para a mesma página com a data como parâmetro
                    window.location.href = window.location.href + '?data_atendimento=' + dataAtendimento;
                }
            });

            // Redireciona ao clicar em "Fechar"
            $('#btnFecharModal').on('click', function () {
                window.location.href = '/'; // Redireciona para a página '/'
            });
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
                const horario = document.getElementById('horario').value;

                // Condição para verificar os campos obrigatórios
                if (!nomePaciente || !idMedico || !horario) {
                    alert('Por favor, preencha todos os campos obrigatórios.'); // Exibe um alerta
                } else {
                    document.getElementById('dataForm').submit(); // Submete o formulário se tudo estiver preenchido
                }
            }
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesChamados.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>