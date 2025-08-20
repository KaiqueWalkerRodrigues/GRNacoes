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

        .user-status-dot {
            width: 10px; height: 10px; border-radius: 50%;
            display: inline-block; margin-right: 6px; background: #28a745;
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
                            <!-- NOVO: Card de usuários online -->
                            <div class="col-md-6 offset-md-3 col-sm-12">
                                <div class="card user-card">
                                    <h5 class="mb-3">Usuários online</h5>
                                    <div id="online-users" class="user-list"></div>
                                    <small class="text-muted d-block mt-2" id="online-updated-at"></small>
                                </div>
                            </div>
                            <div class="col-12 mt-3 text-center">
                                <h1 style="font-size: 1.6rem;">Versão: <span id="sistema-versao"></span></h1>
                                <!-- <div class="row">
                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-warning">Pendente</h5>
                                            <ul id="todo-list" class="list-group">
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-primary">Desenvolvimento</h5>
                                            <ul id="inprogress-list" class="list-group">
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="card p-2">
                                            <h5 class="card-title text-success">Concluído</h5>
                                            <ul id="done-list" class="list-group">
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
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
                setVersion('1.3.0'); // Aqui você altera para a versão atual
                
                const todoTasks = [];
                const inProgressTasks = [];
                const doneTasks = [];

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

            // === ONLINE USERS POLLING ===
            const ONLINE_ENDPOINT = '/GRNacoes/views/ajax/usuarios_online.php'; // ajuste conforme suas rotas
            let onlineReq = null; // para abortar requisição anterior e evitar fila

            function renderOnlineUsers(list) {
                if (!Array.isArray(list) || list.length === 0) {
                    $('#online-users').html('<div class="text-muted">Ninguém online agora.</div>');
                    return;
                }

                const html = list.map(u => {
                    const safeAvatar = u.avatar || '<?php echo URL_RESOURCES; ?>/img/avatar-default.png';
                    const safeNome   = u.nome   || 'Sem nome';
                    const safeSetor  = u.setor  || '—';

                    return `
                        <div class="user">
                            <img class="user-img" src="${safeAvatar}" alt="${safeNome}" onerror="this.src='<?php echo URL_RESOURCES; ?>/img/avatar-default.png'">
                            <div class="user-info">
                                <p class="user-name mb-0">${safeNome}</p>
                                <small class="status"><span class="user-status-dot"></span>${safeSetor}</small>
                            </div>
                        </div>
                    `;
                }).join('');

                $('#online-users').html(html);
            }

            function fetchOnlineUsers() {
                // Aborta a requisição anterior se ainda estiver em andamento
                if (onlineReq && onlineReq.readyState !== 4) {
                    onlineReq.abort();
            }

                onlineReq = $.ajax({
                    url: ONLINE_ENDPOINT,
                    method: 'GET',
                    cache: false,
                    dataType: 'json',
                    timeout: 8000
                })
                .done(function(res) {
                    if (res && Array.isArray(res.data)) {
                        renderOnlineUsers(res.data);
                        const ts = res.server_time ? moment(res.server_time).format('HH:mm:ss') : moment().format('HH:mm:ss');
                    } else {
                        renderOnlineUsers([]);
                    }
                })
                .fail(function(xhr, status) {
                    if (status !== 'abort') {
                        // Mostra uma mensagem discreta sem quebrar a UI
                        $('#online-updated-at').text('Falha ao atualizar lista de online...');
                    }
                });
            }

            // Dispara a cada 1 segundo como solicitado
            setInterval(fetchOnlineUsers, 1000);
            // Faz a primeira carga imediatamente
            fetchOnlineUsers();

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