<?php 
    $Chamado = new Chamado();
    $Mensagem = new Mensagem();
    $Usuario = new Usuario();

    $id_chamado = (int)$_GET['id'];
    $id_usuario = (int)$_SESSION['id_usuario'];

    $chamado = $Chamado->mostrar($id_chamado);
    $id_destinatario = (int)$Usuario->mostrar($chamado->id_usuario)->id_usuario;

    // Marcar como lidas ao abrir a tela (opcional)
    $Mensagem->marcarComoLidaChamado($id_usuario, $id_chamado);
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
    <style>
        #msg {
            position: fixed;
            bottom: 15px;
            left: 225px; /* Considera a largura da sidebar (normalmente 225px) */
            right: 15px; /* Margem direita */
            width: auto; /* Remove width fixo para usar left/right */
            max-width: 83%; /* Largura máxima para não ficar muito largo em telas grandes */
            margin: 0 auto; /* Centraliza dentro do espaço disponível */
        }

        /* Para telas menores onde a sidebar pode estar oculta */
        @media (max-width: 991.98px) {
            #msg {
                left: 15px; /* Sem sidebar em mobile */
                right: 15px;
            }
        }

        .icon-container {
            display: flex;
            align-items: center;
            margin: 10px 0; /* Adapte conforme necessário */
        }
        
        .icon-container.eu {
            justify-content: flex-start; /* Ícone e mensagem à esquerda */
            color: white;
        }

        .icon-container.outro {
            justify-content: flex-end; /* Ícone e mensagem à direita */
            color: black;
        }

        .icon-container.eu .card {
            background-color: #98ace9;
            margin-left: 10px;
        }

        .icon-container.outro .card {
            background-color: #FFFFFF;
            margin-right: 10px;
        }
    </style>
</head>

<body class="nav-fixed">
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-light">
                    <div class="container-fluid">
                        <div class="card p-3 mt-2 d-flex align-items-center" style="margin-top: -15px; position: fixed; z-index: 1000;width: 80%";>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span>ID: <b class="text-dark"> #<?php echo $chamado->id_chamado ?></b> | Chamado: <b class="text-dark"> <?php echo $chamado->titulo ?></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">

                    <br><br><br><br>

                    <div id="mensagens" class="p-2"></div>

                    <br><br><br><br>

                    <div id="msg" class="card shadow-sm border-0 rounded-3 p-2">
                        
                        <form action="<?php echo URL ?>/class/Mensagens.php" method="post" id="chatForm" 
                            class="d-flex align-items-center" enctype="multipart/form-data">
                            
                            <input type="hidden" id="barra_id_usuario" name="id_usuario" value="<?php echo $id_usuario ?>">
                            <input type="hidden" id="barra_id_destinatario" name="id_destinatario" value="<?php echo $id_destinatario ?>">
                            <input type="hidden" id="barra_id_chamado" name="id_chamado" value="<?php echo $_GET['id'] ?>">
                            <input type="hidden" id="barra_id_avatar" name="id_avatar" value="<?php echo $Usuario->mostrar($id_usuario)->id_avatar; ?>">
                            <input type="hidden" id="barra_id_destinatario_avatar" name="id_destinatario_avatar" value="<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>">

                            <!-- Botão de anexar -->
                            <label for="anexo" class="btn btn-light rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                style="width: 42px; height: 42px; cursor: pointer;">
                                <i class="fa-solid fa-paperclip"></i>
                            </label>
                            <input type="file" name="anexo" id="anexo" style="display: none;">

                            <!-- Caixa de texto -->
                            <textarea 
                                name="mensagem" 
                                id="mensagem" 
                                rows="1" 
                                class="form-control border-0 shadow-none flex-grow-1 rounded-pill px-3" 
                                placeholder="Digite sua mensagem..."></textarea>

                            <!-- Botão de enviar -->
                            <button class="btn btn-success rounded-circle ml-2 d-flex align-items-center justify-content-center" 
                                    id="enviarMensagem" name="enviarMensagem" 
                                    style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#cham').addClass('active');

            var id = $('#barra_id_chamado').val();
            var id_usuario = $('#barra_id_usuario').val();
            var id_destinatario = $('#barra_id_destinatario').val();
            var id_avatar = $('#barra_id_avatar').val();
            var id_destinatario_avatar = $('#barra_id_destinatario_avatar').val();

            $(document).ready(function () {
                var ultimaContagemMensagens = 0;

                window.onload = function() {
                    setTimeout(function() {
                        var container = document.body;
                        // Define o scroll para a altura total do conteúdo
                        container.scrollTop = container.scrollHeight;
                    }, 50);
                };

                function verificarNovasMensagens() {
                    $.ajax({
                        type: "get",
                        url: "/GRNacoes/views/ajax/get_count_chamados_mensagens.php",
                        data: { id_chamado: id },
                        success: function (result) {
                            var contagemAtual = parseInt(result);
                            
                            // Se a contagem mudou ou é a primeira vez, atualiza as mensagens
                            if (contagemAtual !== ultimaContagemMensagens) {
                                ultimaContagemMensagens = contagemAtual;
                                mostrarMensagens();
                            }
                        },
                        error: function(){
                            console.log("Erro ao verificar contagem de mensagens");
                        }
                    });
                }

                function mostrarMensagens() {
                    $.ajax({
                        type: "GET",
                        url: "/GRNacoes/views/ajax/get_mensagens_chamados.php",
                        data: { id_chamado: id, id_usuario: id_usuario, id_avatar: id_avatar, id_destinatario_avatar: id_destinatario_avatar },
                        success: function (result) {
                            $('#mensagens').html(result);
                            
                            // Scroll automático para a última mensagem
                            setTimeout(function() {
                                var container = document.body;
                                container.scrollTop = container.scrollHeight;
                            }, 100);
                        },
                        error: function(){
                            $('#mensagens').html("Error");
                        }
                    });
                }

                // Carrega mensagens inicialmente
                verificarNovasMensagens();
                
                // Verifica a cada 1 segundo se há novas mensagens
                setInterval(verificarNovasMensagens, 1000);

                $('#mensagem').keydown(function (e) {
                    if (e.keyCode == 13 && !e.shiftKey) { 
                        e.preventDefault(); 
                        $('#chatForm').submit(); 
                    }
                });

                $('#chatForm').on('submit', function (e) {
                    e.preventDefault(); // impede o reload

                    let formData = $(this).serialize();

                    if ($('#mensagem').val().trim() === '') {
                        alert("A mensagem não pode estar vazia.");
                        return;
                    }

                    $.ajax({
                        type: "post",
                        url: "<?php echo URL ?>/class/Mensagens.php",
                        data: formData,
                        success: function (response) {
                            $('#mensagem').val('');
                            mostrarMensagens();
                        },
                        error: function () {
                            alert("Erro ao enviar mensagem.");
                        }
                    });
                });


            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>