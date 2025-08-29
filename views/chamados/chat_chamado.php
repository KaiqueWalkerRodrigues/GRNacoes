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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
    <style>
        /* ===== Layout geral do chat ===== */
        /* Faz a coluna principal ocupar a altura da viewport e distribui:
           [header fixo/superior] + [mensagens flex 1] + [barra de envio] */
        #layoutSidenav_content main .chat-page{
            display:flex;
            flex-direction:column;
            /* Altura mínima descontando a header fixa (card) + padding aproximado */
            min-height: calc(100vh - 120px);
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto; /* Permite rolagem interna */
            max-height: calc(100vh - 200px); /* Altura máxima ajustada para evitar que a barra de mensagens seja empurrada */
            padding: 8px 0;
        }
        /* Mensagens (cores já usadas por você) */
        .icon-container {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .icon-container.eu { justify-content: flex-end; color: white; }
        .icon-container.outro { justify-content: flex-start; color: black; }
        .icon-container.eu .card { background-color: #98ace9; margin-left: 10px; }
        .icon-container.outro .card { background-color: #FFFFFF; margin-right: 10px; }

        /* ===== Barra de mensagem (layout) ===== */
        .chat-input-area{
            margin-left: 13%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f0f2f5;
            padding: 10px 20px;
            border-top: 1px solid #e9ecef;
            z-index: 1000;
        }
        .chat-input-container{
            display:flex;
            align-items:flex-end;
            gap:10px;
        }

        /* ===== Textarea ===== */
        .chat-textarea{
            flex:1;
            min-height:42px;
            max-height:180px;
            resize:none;
            border:1px solid #dee2e6;
            border-radius:18px;
            padding:10px 12px;
            outline:none;
            background:#fff;
        }

        /* ===== Botões ===== */
        .attachment-button,
        .send-button{
            border:none;
            border-radius:50%;
            width:45px;
            height:45px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            transition:background-color .2s;
        }
        .attachment-button{ background:#e9ecef; color:#495057; }
        .attachment-button:hover{ background:#dee2e6; }
        .send-button{ background:#28a745; color:#fff; }
        .send-button:hover{ background:#218838; }

        /* ===== Dropdown de anexos ===== */
        .attachment-dropdown-container{ position:relative; }
        .attachment-dropdown{
            position:absolute;
            bottom:55px;
            left:0;
            width:240px;
            background:#fff;
            border:1px solid #e9ecef;
            border-radius:14px;
            padding:8px;
            display:none;
            box-shadow:0 8px 24px rgba(0,0,0,.08);
            z-index:20;
        }
        .attachment-dropdown.show{ display:block; }
        .attachment-dropdown .dropdown-item{
            display:flex; align-items:center; gap:10px;
            padding:8px; border-radius:10px; cursor:pointer;
        }
        .attachment-dropdown .dropdown-item:hover{ background:#f8f9fa; }
        .icon-circle{
            width:36px; height:36px; border-radius:50%;
            display:flex; align-items:center; justify-content:center; color:#fff;
        }
        .icon-circle.document{ background:#6c757d; }
        .icon-circle.image{ background:#17a2b8; }
        .icon-circle.video{ background:#6f42c1; }

        /* ===== Overlay de arrastar/soltar ===== */
        .drag-drop-area{
            display:none;
            position:absolute; inset:0;
            background:rgba(0,123,255,.08);
            border:2px dashed #007bff;
            border-radius:8px;
            align-items:center; justify-content:center; flex-direction:column;
            z-index:999; color:#007bff; text-align:center;
        }
        .drag-drop-area i{ font-size:38px; margin-bottom:6px; }

        /* ===== Prévia de arquivos ===== */
        .file-preview-container{
            display:none;
            padding:10px;
            background:#fff;
            border:1px solid #e9ecef;
            border-radius:12px;
            margin-bottom:10px;
            max-height:120px; overflow-y:auto;
        }
        .file-preview-item{
            display:flex; align-items:center; gap:10px;
            padding:6px 8px; border-radius:8px; background:#f8f9fa; margin-bottom:6px;
        }
        .file-preview-icon{
            width:32px; height:32px; border-radius:50%;
            display:flex; align-items:center; justify-content:center; color:#fff;
            background:#6c757d;
        }
        .file-preview-icon.image{ background:#17a2b8; }
        .file-preview-icon.video{ background:#6f42c1; }
        .file-preview-info{ flex:1; min-width:0; }
        .file-preview-name{ font-weight:600; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .file-preview-size{ font-size:12px; color:#6c757d; }
        .file-preview-remove{
            border:none; background:transparent; color:#dc3545; font-size:16px; cursor:pointer;
        }

        /* Remove estilos antigos que forçavam posição/tamanho */
        #msg { display:none; }
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
                        <div class="card p-3 mt-2 d-flex align-items-center" style="margin-top: -15px; position: fixed; z-index: 1000; width: 80%">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span>ID: <b class="text-dark"> #<?php echo $chamado->id_chamado ?></b> | Chamado: <b class="text-dark"> <?php echo $chamado->titulo ?></b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTAINER PRINCIPAL DO CHAT -->
                <div class="container-fluid mt-n10 chat-page">
                    <!-- Espaço para compensar o card fixo do topo -->
                    <div style="height: 72px;"></div>

                    <!-- ÁREA DE MENSAGENS (rolagem própria, flex:1) -->
                    <div id="mensagens" class="chat-messages p-2"></div>

                    <!-- BARRA DE MENSAGEM (no fluxo, abaixo das mensagens) -->
                    <footer class="chat-input-area">
                        <!-- Overlay de arrastar/soltar -->
                        <div class="drag-drop-area" id="dragDropArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Solte os arquivos para anexar</p>
                        </div>

                        <!-- Prévia dos anexos selecionados -->
                        <div class="file-preview-container" id="filePreviewContainer"></div>

                        <!-- Form de envio -->
                        <form id="chatForm" class="chat-input-container" method="post" enctype="multipart/form-data" action="<?php echo URL ?>/class/Mensagens.php">
                            <!-- Hidden necessários para o backend que já existe -->
                            <input type="hidden" name="id_chamado" id="barra_id_chamado" value="<?php echo $id_chamado; ?>">
                            <input type="hidden" name="id_usuario" id="barra_id_usuario" value="<?php echo $id_usuario; ?>">
                            <input type="hidden" name="id_destinatario" id="barra_id_destinatario" value="<?php echo $id_destinatario; ?>">
                            <input type="hidden" name="id_avatar" id="barra_id_avatar" value="<?php echo $Usuario->mostrar($id_usuario)->id_avatar; ?>">
                            <input type="hidden" name="id_destinatario_avatar" id="barra_id_destinatario_avatar" value="<?php echo $Usuario->mostrar($id_destinatario)->id_avatar; ?>">

                            <!-- Botão + dropdown de anexos -->
                            <div class="attachment-dropdown-container">
                                <button type="button" class="attachment-button" id="attachmentBtn" title="Anexar">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <div class="attachment-dropdown" id="attachmentDropdown">
                                    <div class="dropdown-item" onclick="triggerFileInput('document')">
                                        <div class="icon-circle document"><i class="fas fa-file-alt"></i></div>
                                        <span>Documento</span>
                                    </div>
                                    <div class="dropdown-item" onclick="triggerFileInput('image')">
                                        <div class="icon-circle image"><i class="fas fa-image"></i></div>
                                        <span>Imagem</span>
                                    </div>
                                    <div class="dropdown-item" onclick="triggerFileInput('video')">
                                        <div class="icon-circle video"><i class="fas fa-video"></i></div>
                                        <span>Vídeo</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Input real de arquivos (oculto) -->
                            <input type="file" id="fileInput" name="attachments[]" style="display:none" multiple>

                            <!-- Mensagem -->
                            <textarea name="mensagem" id="mensagem" class="chat-textarea" placeholder="Digite uma mensagem..." rows="1"></textarea>

                            <!-- Enviar -->
                            <button type="submit" class="send-button" title="Enviar">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </footer>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>

    <script>
    // ===== Script único com tudo necessário =====
    $(function () {
        $('#cham').addClass('active');

        // IDs ocultos
        var id = $('#barra_id_chamado').val();
        var id_usuario = $('#barra_id_usuario').val();
        var id_destinatario = $('#barra_id_destinatario').val();
        var id_avatar = $('#barra_id_avatar').val();
        var id_destinatario_avatar = $('#barra_id_destinatario_avatar').val();

        // ===== Helpers e estado de anexos =====
        window.selectedFiles = []; // global

        const MAX_FILES = 5;
        const MAX_TOTAL_SIZE = 200 * 1024 * 1024; // 200MB
        const allowedExtensions = ['pdf','doc','docx','txt','rtf','odt','xls','xlsx','csv','png','jpg','jpeg','gif','webp','mp4','mov','avi','mkv'];

        window.isFileAllowed = function (file) {
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            return allowedExtensions.includes(ext);
        };

        window.formatFileSize = function (bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes','KB','MB','GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
        };

        window.updateFilePreview = function () {
            const container = $('#filePreviewContainer');
            if (!window.selectedFiles.length) {
                container.hide().empty();
                return;
            }
            container.show().empty();
            const totalSize = window.selectedFiles.reduce((s, f) => s + f.size, 0);
            container.append(`<div class="small text-muted mb-2">Arquivos: ${window.selectedFiles.length}/${MAX_FILES} | Total: ${formatFileSize(totalSize)}</div>`);
            window.selectedFiles.forEach((file, index) => {
                const fileType = file.type.startsWith('image/') ? 'image' : file.type.startsWith('video/') ? 'video' : 'document';
                container.append(`
                    <div class="file-preview-item">
                        <div class="file-preview-icon ${fileType}"><i class="fas fa-file-alt"></i></div>
                        <div class="file-preview-info">
                            <div class="file-preview-name">${file.name}</div>
                            <div class="file-preview-size">${formatFileSize(file.size)}</div>
                        </div>
                        <button type="button" class="file-preview-remove" onclick="removeFile(${index})" title="Remover">&times;</button>
                    </div>
                `);
            });
        };

        window.removeFile = function (index) {
            window.selectedFiles.splice(index, 1);
            updateFilePreview();
        };

        // ===== Dropdown de anexos =====
        const $dropdown = $('#attachmentDropdown');
        const $attachBtn = $('#attachmentBtn');
        const $fileInput = $('#fileInput');
        const $drag = $('#dragDropArea');
        const $textarea = $('#mensagem');

        $attachBtn.on('click', function () {
            $dropdown.toggleClass('show');
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.attachment-dropdown-container').length) {
                $dropdown.removeClass('show');
            }
        });

        window.triggerFileInput = function (type) {
            let accept = '';
            if (type === 'image') accept = 'image/*';
            else if (type === 'video') accept = 'video/*';
            else accept = '.pdf,.doc,.docx,.txt,.rtf,.odt,.xls,.xlsx,.csv,.ppt,.pptx,application/*';
            $fileInput.attr('accept', accept);
            $fileInput.trigger('click');
            $dropdown.removeClass('show');
        };

        // Seleção via input
        $fileInput.on('change', function () {
            const files = Array.from(this.files || []);
            let currentTotalSize = window.selectedFiles.reduce((sum, f) => sum + f.size, 0);

            for (const file of files) {
                if (!isFileAllowed(file)) { alert(`Tipo de arquivo não permitido: ${file.name}`); continue; }
                if (window.selectedFiles.length >= MAX_FILES) { alert(`Limite de ${MAX_FILES} arquivos atingido.`); break; }
                if (currentTotalSize + file.size > MAX_TOTAL_SIZE) { alert(`Total excede 200MB: ${file.name}`); continue; }
                // evita duplicados simples por nome+tamanho
                if (!window.selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    window.selectedFiles.push(file);
                    currentTotalSize += file.size;
                }
            }
            this.value = ''; // permite re-selecionar o mesmo arquivo
            updateFilePreview();
        });

        // Drag & Drop
        let dragCounter = 0;
        $(document).on('dragenter dragover', function (e) {
            e.preventDefault(); e.stopPropagation();
            dragCounter++;
            $drag.css('display','flex');
        });
        $(document).on('dragleave', function (e) {
            e.preventDefault(); e.stopPropagation();
            dragCounter--;
            if (dragCounter <= 0) { dragCounter = 0; $drag.hide(); }
        });
        $(document).on('drop', function (e) {
            e.preventDefault(); e.stopPropagation();
            dragCounter = 0; $drag.hide();
            const dt = e.originalEvent.dataTransfer;
            if (!dt || !dt.files) return;

            const files = Array.from(dt.files);
            let currentTotalSize = window.selectedFiles.reduce((sum, f) => sum + f.size, 0);

            for (const file of files) {
                if (!isFileAllowed(file)) { continue; }
                if (window.selectedFiles.length >= MAX_FILES) { break; }
                if (currentTotalSize + file.size > MAX_TOTAL_SIZE) { continue; }
                if (!window.selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    window.selectedFiles.push(file);
                    currentTotalSize += file.size;
                }
            }
            updateFilePreview();
        });

        // Enter envia / Shift+Enter quebra linha
        $textarea.on('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                $('#chatForm').trigger('submit');
            }
        });

        // ===== Mensagens (polling + render) =====
        var ultimaContagemMensagens = 0;
        const $msgs = $('.chat-messages');

        function scrollToBottom(){
            $msgs.scrollTop($msgs[0].scrollHeight);
        }

        // ao carregar: rola a área de mensagens (não o body)
        window.addEventListener('load', function(){
            setTimeout(scrollToBottom, 80);
        });

        window.verificarNovasMensagens = function () {
            $.ajax({
                type: "get",
                url: "/GRNacoes/views/ajax/get_count_chamados_mensagens.php",
                data: { id_chamado: id },
                success: function (result) {
                    var contagemAtual = parseInt(result, 10) || 0;
                    if (contagemAtual !== ultimaContagemMensagens) {
                        ultimaContagemMensagens = contagemAtual;
                        window.mostrarMensagens();
                    }
                },
                error: function(){
                    console.log("Erro ao verificar contagem de mensagens");
                }
            });
        };

        window.mostrarMensagens = function () {
            $.ajax({
                type: "GET",
                url: "/GRNacoes/views/ajax/get_mensagens_chamados.php",
                data: { id_chamado: id, id_usuario: id_usuario, id_avatar: id_avatar, id_destinatario_avatar: id_destinatario_avatar },
                success: function (result) {
                    $('#mensagens').html(result);
                    setTimeout(scrollToBottom, 80);
                },
                error: function(){
                    $('#mensagens').html("Error");
                }
            });
        };

        // Carrega inicialmente e agenda polling
        window.verificarNovasMensagens();
        setInterval(window.verificarNovasMensagens, 1000);

        // ===== Envio do formulário com AJAX =====
        $('#chatForm').on('submit', function (e) {
            e.preventDefault();

            const mensagem = $('#mensagem').val().trim();
            if (!mensagem && window.selectedFiles.length === 0) {
                alert("A mensagem não pode estar vazia.");
                return;
            }

            const formData = new FormData(this);
            window.selectedFiles.forEach(file => formData.append('attachments[]', file));

            $.ajax({
                type: 'POST',
                url: "<?php echo URL ?>/class/Mensagens.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    $('#mensagem').val('');
                    window.selectedFiles = [];
                    updateFilePreview();
                    window.mostrarMensagens();
                },
                error: function () {
                    alert("Erro ao enviar mensagem.");
                }
            });
        });
    });
    </script>

    <script>
    $(document).ready(function () {
        Fancybox.bind('[data-fancybox="gallery"]', {
            Carousel: {
                Toolbar: {
                    display: {
                        left: ["counter"],
                        middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                        ],
                        right: ["download", "close"],
                    },
                },
            },
        });
    });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>
</html>
