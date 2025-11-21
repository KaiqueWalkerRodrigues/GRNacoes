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
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            background: #28a745;
        }

        /* mostra ~5 itens e ativa rolagem no card de online */
        .user-card .user-list {
            max-height: 270px;
            /* altura suficiente para ~5 usuários */
            overflow-y: auto;
            padding-right: 6px;
            /* respiro para a barra de rolagem */
        }

        /* (opcional) scrollbar mais discreta no WebKit */
        .user-card .user-list::-webkit-scrollbar {
            width: 8px;
        }

        .user-card .user-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        .user-card .user-list::-webkit-scrollbar-thumb {
            background: #c9ced6;
            border-radius: 8px;
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

                        <br>

                        <div class="card p-3">
                            <h3>Mapa de Ramais</h3>
                            <hr>
                            <div class="datatable">
                                <table class="table table-bordered table-hover" id="dataTableRamais" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Local</th>
                                            <th>Setor</th>
                                            <th>Ramal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">TI</td>
                                            <td>Suporte</td>
                                            <th class="text-center" scope="row">2320</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Marketing</td>
                                            <th class="text-center" scope="row">2321</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Faturamento</td>
                                            <th class="text-center" scope="row">2322</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Coordenadora Financeiro</td>
                                            <th class="text-center" scope="row">2323</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Coordenadora Faturamento</td>
                                            <th class="text-center" scope="row">2324</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Financeiro (Contas a Pagar)</td>
                                            <th class="text-center" scope="row">2325</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">TI</td>
                                            <td>Suporte</td>
                                            <th class="text-center" scope="row">2326</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Financeiro (Contas a Receber)</td>
                                            <th class="text-center" scope="row">2327</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Compras</td>
                                            <th class="text-center" scope="row">2328</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">ADM</td>
                                            <td>Recursos Humano</td>
                                            <th class="text-center" scope="row">2329</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2520</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2521</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2522</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Exames A</td>
                                            <th class="text-center" scope="row">2524</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Exames B</td>
                                            <th class="text-center" scope="row">2523</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Captação</td>
                                            <th class="text-center" scope="row">2530</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Lente de Contato</td>
                                            <th class="text-center" scope="row">2525</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Parque</td>
                                            <td>Orientação Cirurgica</td>
                                            <th class="text-center" scope="row">2532</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2920</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2921</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">2922</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Captação</td>
                                            <th class="text-center" scope="row">2925</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Exames</td>
                                            <th class="text-center" scope="row">2923</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Mauá</td>
                                            <td>Orientação Cirurgica</td>
                                            <th class="text-center" scope="row">2927</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Jardim</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">1320</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Jardim</td>
                                            <td>Recepção</td>
                                            <th class="text-center" scope="row">1321</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Jardim</td>
                                            <td>Lente de Contato</td>
                                            <th class="text-center" scope="row">1322</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Jardim</td>
                                            <td>Exames</td>
                                            <th class="text-center" scope="row">1324</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Clínica Jardim</td>
                                            <td>Orientação Cirurgica/Coordenadora</td>
                                            <th class="text-center" scope="row">1330</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Ótica Matriz</td>
                                            <td>Loja</td>
                                            <th class="text-center" scope="row">2720</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Ótica Matriz</td>
                                            <td>Loja</td>
                                            <th class="text-center" scope="row">2721</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Ótica Matriz</td>
                                            <td>Loja</td>
                                            <th class="text-center" scope="row">2722</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Ótica Prestigio</td>
                                            <td>Loja</td>
                                            <th class="text-center" scope="row">1120</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Ótica Daily</td>
                                            <td>Loja</td>
                                            <th class="text-center" scope="row">1520</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>Coordenadora CallCenter</td>
                                            <th class="text-center" scope="row">2120</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>Coordenadora CallCenter</td>
                                            <th class="text-center" scope="row">2134</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2121</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2122</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2123</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2124</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2125</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2126</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2127</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2128</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2129</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2130</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2131</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2132</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">CallCenter</td>
                                            <td>CallCenter</td>
                                            <th class="text-center" scope="row">2133</th>
                                        </tr>
                                    </tbody>
                                </table>
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
        $(document).ready(function() {
            $('#home').addClass('active')

            updateTime();
            setInterval(updateTime, 1000);

            // Carregar as tarefas no Trello simples
            loadTrelloTasks();

            // Definir o locale do moment para português
            moment.locale('pt-br');

            // Função para popular as listas com tarefas
            function loadTrelloTasks() {
                setVersion('1.4');

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
                return string.replace(/\b\w/g, function(l) {
                    return l.toUpperCase()
                });
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
                    const safeNome = u.nome || 'Sem nome';
                    const safeSetor = u.setor || '—';

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
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/datatables-demo.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-ramais.js"></script>
</body>

</html>