<?php 
    include_once("const.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Painel</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include_once('topbar.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <br><br><br><br>

                    <div class="row">

                        <div class="col-8 row">

                            <!-- Weather Information -->
                            <div class="col-3">
                                <div class="card weather-card">
                                    <h5 class="card-title">Clima Atual</h5>
                                    <p id="weather">Carregando...</p>
                                    <small id="weather-desc"></small>
                                </div>
                            </div>

                            <!-- Time and Date -->
                            <div class="col-4 offset-4">
                                <div class="card time-card">
                                    <p id="current-time"></p>
                                    <small id="current-date"></small>
                                </div>
                            </div>
                                
                            <!-- Sistema Versão -->
                            <div class="col-12 text-center" style="margin-top: -5%">
                                <h1 style="font-size: 30px;">Versão: <span id="sistema-versao"></span></h1>
                            </div>

                            <!-- Coluna Pendente -->
                            <div class="col-4" style="margin-top: -6%">
                                <div class="card p-3">
                                    <h5 class="card-title text-warning">Pendente</h5>
                                    <ul id="todo-list" class="list-group">
                                        <!-- As tarefas a fazer serão inseridas aqui -->
                                    </ul>
                                </div>
                            </div>

                            <!-- Coluna Desenvolvimento -->
                            <div class="col-4" style="margin-top: -6%">
                                <div class="card p-3">
                                    <h5 class="card-title text-primary">Desenvolvimento</h5>
                                    <ul id="inprogress-list" class="list-group">
                                        <!-- Tarefas em andamento serão inseridas aqui -->
                                    </ul>
                                </div>
                            </div>

                            <!-- Coluna Concluído -->
                            <div class="col-4" style="margin-top: -6%">
                                <div class="card p-3">
                                    <h5 class="card-title text-success">Concluído</h5>
                                    <ul id="done-list" class="list-group">
                                        <!-- Tarefas concluídas serão inseridas aqui -->
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="col-4">

                            <div class="col-12 offset-1">
                                <div class="card user-card p-3">
                                    <div class="user-list" id="user-list">
                                        <!-- A lista de usuários será carregada aqui via AJAX -->
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <br>

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
        $('#painel').addClass('active');

        function loadOnlineUsers() {
            $.ajax({
                url: "usuarios_online.php", // Página PHP que retorna os usuários online
                success: function (data) {
                    $('#user-list').html(data); // Atualiza a lista de usuários
                }
            });
        }

        // API para o Clima com WeatherAPI
        function getWeather() {
            const apiKey = '424929ce680945aabfa25651242609'; // Substitua com sua chave da WeatherAPI
            const city = 'São Paulo';
            const url = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${city}&lang=pt`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    $('#weather').html(`${data.current.temp_c}°C`);
                })
                .catch(error => {
                    console.log('Erro ao obter dados do clima:', error);
                    $('#weather').html('Não foi possível carregar o clima.');
                });
        }

        // Definir o locale do moment para português
        moment.locale('pt-br');

        // Capitalizar a primeira letra de cada palavra
        function capitalizeFirstLetter(string) {
            return string.replace(/\b\w/g, function (l) { return l.toUpperCase() });
        }

        function updateTime() {
            const now = moment();
            const formattedDate = capitalizeFirstLetter(now.format('dddd, DD MMMM YYYY')); // Capitalizar a data
            $('#current-time').text(now.format('HH:mm:ss'));
            $('#current-date').text(formattedDate);
        }

        // Função para popular as listas com tarefas
        function loadTrelloTasks() {
            setVersion('1.0.6'); // Aqui você altera para a versão atual
            
            const todoTasks = ['Sistema Ótica PDF','Orçamento Lente Ctt','Ótica Estoque e Venda','Ótica Metas e Vendas'];
            const inProgressTasks = ['Orçamento Cirurgia'];
            const doneTasks = ['Sistema Captação','Pedidos de Compras','Notas Fiscais Compras','Boletos Financeiro','Atrasos Financeiro','Chats','Chamados'];

            $('#todo-list').html(todoTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
            $('#inprogress-list').html(inProgressTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
            $('#done-list').html(doneTasks.map(task => `<li class="list-group-item">${task}</li>`).join(''));
        }

        function setVersion(version) {
            $('#sistema-versao').text(version);
        }

        $(document).ready(function () {
            getWeather();
            updateTime();
            setInterval(updateTime, 1000);  // Atualiza a cada segundo
            loadOnlineUsers();
            setInterval(loadOnlineUsers, 1000); // Atualiza a cada segundo

            // Carregar as tarefas no Trello simples
            loadTrelloTasks();
            
            // Definir a versão do sistema
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
