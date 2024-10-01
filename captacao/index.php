<?php 
    include_once("../const.php");

    $Medico = new Medicos();
    $Captacao = new Captacao();

    // Captura as captações realizadas hoje
    $capHoje = $Captacao->listarHoje();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Captar</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .weather-card {
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            color: white;
            padding: 11px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .time-card {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        #weather {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin-left: 30%;
        }

        .user-card {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Estilo da tabela */
        .data-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Estilo do contador de captações */
        .counter {
            text-align: right;
            font-size: 1.0rem;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('../sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include_once('../topbar.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <br><br><br><br>

                    <!-- Contador de captações -->
                    <div class="row">
                        <div class="col-12 counter">
                            Total de Pessoas Cadastradas: <?php echo $Captacao->contarPessoas(); ?>
                            <br>
                            Total Captados: <?php echo $Captacao->contarCaptacoes(); ?>
                            <br>
                            Total Não Captados: <?php echo $Captacao->contarNaoCaptacoes(); ?>
                            <br>
                            Total de Lentes: <?php echo $Captacao->contarLentes(); ?>
                            <br>
                            Total de Garantias: <?php echo $Captacao->contarGarantias(); ?>
                            <br>
                            <b style="font-size: 16px;"><?php echo $Captacao->contarCaptacoes(); ?>/<?php echo $Captacao->contarCaptaveis(); ?></b>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Formulário para adicionar informações -->
                        <div class="col-12">
                            <h5 class="fw-bold text-dark">Registro de Captação</h5>
                            <br>
                            <form id="dataForm" class="row" method="POST">
                                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <div class="col-3">
                                    <label class="form-label" for="name">Nome do Paciente:</label>
                                    <input class="form-control" type="text" id="name" name="nome_paciente" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label" for="captado">Captado:</label>
                                    <select class="form-control" id="captado" name="captado">
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                        <option value="3">Lente de Contato - Sim</option>
                                        <option value="4">Lente de Contato - Não</option>
                                        <option value="5">Garantia</option>
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
                                <div class="col-3">
                                    <label class="form-label" for="observacao">Observação:</label>
                                    <input class="form-control" type="text" id="observacao" name="observacao">
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary" id="btnCadastrar">Cadastrar</button>
                                </div>
                            </form>

                            <table class="data-table mt-4">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Captado</th>
                                        <th>Médico</th>
                                        <th>Observação</th>
                                    </tr>
                                </thead>
                                <tbody id="dataTableBody">
                                    <!-- Listar as captações realizadas hoje -->
                                    <?php foreach($capHoje as $cap) { ?>
                                        <tr>
                                            <td><?php echo $cap->nome_paciente; ?></td>
                                            <td><?php echo Helper::captado($cap->captado); ?></td>
                                            <td><?php echo $cap->nome_medico; ?></td>
                                            <td><?php echo $cap->observacao; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script> <!-- Carregar o locale pt-br -->

    <script>
        $('#cap').addClass('active');
        $('#captacao-index').addClass('active');

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
                event.preventDefault();
                document.getElementById('dataForm').submit(); // Submete o formulário
            }
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>
