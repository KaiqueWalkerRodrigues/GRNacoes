<?php 
    $Usuario = new Usuario();
    $Setor = new Setor();
    $Chat = new Conversa();

    if(isset($_POST['btnAbrir'])){
        $Chat->cadastrarPrivado($_POST);
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
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
        <style>
            /* Estilo de hover para o card */
            .card:hover {
                background-color: #f0f0f0;
                transform: scale(1.02);
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
            }
        </style>
    </head>
    <body class="nav-fixed">
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include_once('resources/sidebar.php') ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                    <span>Chats</span>
                                    <button class="btn btn-icon btn-success ml-3" data-toggle="modal" data-target="#modalAbrirChat">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">

                        <div id="lista_chats"></div>

                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>

        <!-- Modal Abrir Novo Chat -->
        <div class="modal fade" id="modalAbrirChat" tabindex="1" role="dialog" aria-labelledby="modalAbrirChatLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Abrir novo chat</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                                    <label for="categoria" class="form-label">Usuário *</label>
                                    <select class="form-control" name="id_destinatario" id="id_destinatario" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach($Usuario->listarAtivosMenosEu($_SESSION['id_usuario']) as $u){ ?>
                                            <option value="<?php echo $u->id_usuario ?>"><?php echo $u->nome ?> | <?php echo $Setor->mostrar($Usuario->mostrarSetorPrincipal($u->id_usuario)->id_setor)->setor; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="btnAbrir" class="btn btn-success">Abrir Chat</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#chats').addClass('active')

                function loadChats() {
                    $.ajax({
                        url: '/GRNacoes/views/ajax/get_chats.php',
                        method: 'GET',
                        success: function(data) {
                            $('#lista_chats').html(data);
                        }
                    });
                }

                // Chama a função a cada 1 segundo (1000 ms)
                setInterval(loadChats, 5000);

                loadChats();
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>
</html>
