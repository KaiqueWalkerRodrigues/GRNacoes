<?php 
    $Chamado = new Chamado();
    $Mensagem = new Mensagem();
    $Usuario = new Usuario();

    $id_chamado = $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    $chamado = $Chamado->mostrar($id_chamado);
    $id_destinatario = $Usuario->mostrar($chamado->id_usuario)->id_usuario;
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
            bottom: 0;
            width: 100%;
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
        <div id="layoutSidenav_nav">
            <?php include_once('resources/sidebar.php') ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-light">
                    <div class="container-fluid">
                        <div class="card p-3 mt-2 d-flex align-items-center" style="margin-top: -15px; position: fixed; z-index: 1000;width: 80%";>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span>Procolo: <b class="text-dark"> <?php echo $chamado->id_chamado ?></b> | Chamado: <b class="text-dark"> <?php echo $chamado->titulo ?></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">

                    <br><br><br><br>

                    <div id="mensagens" class="p-2"></div>

                    <br><br><br><br>

                    <div id="msg" class="card" style="width: 80%;">
                        <form action="<?php echo URL ?>/class/Mensagens.php" class="p-2" method="post" id="chatForm">
                            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario ?>">
                            <input type="hidden" name="id_destinatario" id="id_destinatario" value="<?php echo $id_destinatario ?>">
                            <input type="hidden" name="id_chamado" id="id_chamado" value="<?php echo $_GET['id'] ?>">
                            <input type="hidden" name="id_avatar" id="id_avatar" value="<?php echo $Usuario->mostrar($id_usuario)->id_avatar; ?>">
                            <input type="hidden" name="id_destinatario_avatar" id="id_destinatario_avatar" value="<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>">
                            <div class="row align-items-center text-center">
                                <div style="width: 85%;" class="align-items-center">
                                    <textarea name="mensagem" id="mensagem" rows="2" class="form-control ml-3"></textarea>
                                </div>
                                <div class="row ml-3 align-items-center" style="width: 10%">
                                    <div class="col-6">
                                        <button class="btn btn-lg btn-icon btn-success" id="enviarMensagem" name="enviarMensagem">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#cham').addClass('active');

        var id = $('#id_chamado').val();
        var id_usuario = $('#id_usuario').val();
        var id_destinatario = $('#id_destinatario').val();
        var id_avatar = $('#id_avatar').val();
        var id_destinatario_avatar = $('#id_destinatario_avatar').val();

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
                    type: "get",
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
                if ($('#mensagem').val().trim() === '') {
                    e.preventDefault(); 
                    alert("A mensagem não pode estar vazia.");
                } else {
                    // Após enviar a mensagem, verifica imediatamente por novas mensagens
                    setTimeout(function() {
                        verificarNovasMensagens();
                    }, 500);
                }
            });

        });
    });
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</html>