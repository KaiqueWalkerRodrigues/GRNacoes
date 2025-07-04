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
    <style>
        .time-card {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .user-card {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .user-list {
            display: flex;
            flex-direction: column;
        }

        .user {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            margin-left: 10px;
        }

        .user-name {
            font-size: 1rem;
            font-weight: bold;
            margin: 0;
        }

        .status {
            font-size: 0.8rem;
        }

        .online {
            color: green;
        }

        .offline {
            color: red;
        }

        /* Estilo para a linha separadora */
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }

        /* Estilo para as colunas do Trello simples */
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .list-group-item {
            font-size: 0.9rem;
        }
    </style>
</head>

<body class="nav-fixed">
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="row mt-2">
                    <div class="col-md-8 col-sm-12 offset-md-2">

                        <div class="row">

                            <div class="col-md-4 offset-md-4 col-sm-8 col-sm-2">
                                <!-- Time and Date -->
                                <div class="card time-card">
                                    <p id="current-time"></p>
                                    <small id="current-date"></small>
                                </div>
                            </div>
                            <div class="col-12 mt-3 text-center">
                                <!-- Sistema Versão -->
                                <h1 style="font-size: 1.6rem;">Versão: <span id="sistema-versao"></span></h1>
                                <div class="row">
                                    <!-- Coluna Pendente -->
                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-warning">Pendente</h5>
                                            <ul id="todo-list" class="list-group">
                                                <!-- As tarefas a fazer serão inseridas aqui -->
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Coluna Desenvolvimento -->
                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-primary">Desenvolvimento</h5>
                                            <ul id="inprogress-list" class="list-group">
                                                <!-- Tarefas em andamento serão inseridas aqui -->
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Coluna Concluído -->
                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-success">Concluído</h5>
                                            <ul id="done-list" class="list-group">
                                                <!-- Tarefas concluídas serão inseridas aqui -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script> 
    <script>
        $(document).ready(function () {
            $('#home').addClass('active')

            updateTime();
            setInterval(updateTime, 1000);

            // Carregar as tarefas no Trello simples
            loadTrelloTasks();

            // Definir o locale do moment para português
            moment.locale('pt-br');

            // Função para popular as listas com tarefas
            function loadTrelloTasks() {
                setVersion('1.2.2'); // Aqui você altera para a versão atual
                
                const todoTasks = ['Ótica Estoque, Venda e Metas','Notifição/Onlines'];
                const inProgressTasks = ['Cargos/Setores Update'];
                const doneTasks = ['Orçamento Lente Ctt','Lançamento Catarata','Contratos Clínica','Transição de Interface','Sistema Captação'];

                $('#todo-list').html(todoTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
                $('#inprogress-list').html(inProgressTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
                $('#done-list').html(doneTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
            }

            // Função para definir Função
            function setVersion(version) {
                $('#sistema-versao').text(version);
            }

            // Capitalizar a primeira letra de cada palavra
            function capitalizeFirstLetter(string) {
                return string.replace(/\b\w/g, function (l) { return l.toUpperCase() });
            }

            function updateTime() {
                const now = moment();
                const formattedDate = capitalizeFirstLetter(now.format('dddd, DD MMMM YYYY'));
                $('#current-time').text(now.format('HH:mm:ss'));
                $('#current-date').text(formattedDate);
            }
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/chart-area-demo.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/datatables-demo.js"></script>
</body>

</html>