<?php 
    /**
     * Chat estilo WhatsApp Web — Versão refatorada, organizada e enxuta
     */
    $Usuario  = new Usuario();
    $Setor    = new Setor();
    $Chat     = new Conversa();
    $Mensagem = new Mensagem();

    if (isset($_POST['btnAbrir'])) {
        $Chat->cadastrarPrivado($_POST);
    }

    // Contexto de sessão/URL
    $id_usuario_sessao = (int) ($_SESSION['id_usuario'] ?? 0);
    $id_avatar_sessao  = $_SESSION['id_avatar'] ?? '';

    // Seleção/roteamento
    $chat_selecionado  = isset($_GET['id']) ? (int) $_GET['id'] : null;
    $id_destinatario   = isset($_GET['id_destinatario']) ? (int) $_GET['id_destinatario'] : null;

    $destinatario = null;
    if ($id_destinatario) {
        $destinatario = $Usuario->mostrar($id_destinatario);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= htmlspecialchars($pageTitle ?? 'Chat') ?></title>

    <link href="<?= URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <!-- Font Awesome 5 -->
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <!-- Feather -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>

    <style>
        /* Layout geral */
        .whatsapp-container { height: 93vh; display: flex; background:#f0f2f5; }
        /* Sidebar */
        .contacts-sidebar { width:30%; min-width:320px; background:#fff; border-right:1px solid #e9ecef; display:flex; flex-direction:column; overflow:hidden; }
        .contacts-header { background:#007bff; color:#fff; padding:15px 20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 4px rgba(0,0,0,.1); }
        .contacts-list { flex:1; overflow-y:auto; }
        .contact-item { padding:12px 20px; border-bottom:1px solid #f0f0f0; cursor:pointer; transition:background-color .2s; color:inherit; display:block; text-decoration:none; }
        .contact-item:hover { background:#f5f5f5; text-decoration: none; }
        .contact-item.active { background:#e3f2fd; border-left:4px solid #007bff; }
        .contact-avatar { width:50px; height:50px; border-radius:50%; margin-right:12px; }
        .contact-info { flex:1; min-width:0; }
        .contact-name { font-weight:600; font-size:16px; color:#111b21; }
        .contact-last-message { font-size:14px; color:#667781; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .contact-meta { display:flex; flex-direction:column; align-items:flex-end; min-width:60px; }
        .contact-time { font-size:12px; color:#667781; }
        .unread-badge { background:#25d366; color:#fff; border-radius:50%; min-width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; }
        /* Área do chat */
        .chat-area { flex:1; display:flex; flex-direction:column; background:#efeae2; background-image:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><defs><pattern id="p" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23p)"/></svg>'); }
        .chat-header { background:#f0f2f5; padding:15px 20px; border-bottom:1px solid #e9ecef; display:flex; align-items:center; box-shadow:0 1px 2px rgba(0,0,0,.1); }
        .chat-header-avatar { width:40px; height:40px; border-radius:50%; margin-right:12px; }
        .chat-header-info h6 { margin:0; font-weight:600; color:#111b21; }
        .chat-header-status { font-size:13px; color:#667781; margin:0; }
        .chat-messages { flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:8px; }
        .message-container { display:flex; margin-bottom:8px; }
        .message-container.sent { justify-content:flex-end; }
        .message-container.received { justify-content:flex-start; }
        .message-bubble { max-width:65%; padding:8px 12px; border-radius:8px; position:relative; word-wrap:break-word; }
        .message-bubble.sent { background:#d9fdd3; color:#111b21; }
        .message-bubble.received { background:#fff; color:#111b21; }
        .message-time { font-size:11px; color:#667781; margin-top:4px; text-align:right; display:flex; align-items:center; justify-content:flex-end; gap:4px; }
        .chat-input-area { background:#f0f2f5; padding:10px 20px; border-top:1px solid #e9ecef; }
        .chat-input-container { display:flex; align-items:flex-end; gap:10px; }
        .attachment-button, .send-button { border:none; border-radius:50%; width:45px; height:45px; display:flex; align-items:center; justify-content:center; color:#fff; cursor:pointer; transition:background-color .2s; }
        .attachment-button { background:#6c757d; }
        .attachment-button:hover { background:#5a6268; }
        .send-button { background:#007bff; }
        .send-button:hover { background:#0056b3; }
        .chat-input { flex:1; border:none; border-radius:20px; padding:10px 15px; resize:none; max-height:100px; background:#fff; font-size:15px; }
        .chat-input:focus { outline:none; box-shadow:0 0 0 2px #007bff; }
        .empty-chat { display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:#667781; text-align:center; padding:40px; }
        .empty-chat-icon { font-size:80px; margin-bottom:20px; opacity:.3; }
        .date-separator { text-align:center; margin:20px 0 10px; }
        .date-separator span { background:#e1f4fb; color:#54656f; padding:5px 12px; border-radius:7px; font-size:12px; font-weight:500; }
        /* Responsivo */
        @media (max-width: 768px) {
            .whatsapp-container { flex-direction:column; }
            .contacts-sidebar { width:100%; height:40%; }
            .chat-area { height:60%; }
        }
        /* Scrollbar */
        .contacts-list::-webkit-scrollbar, .chat-messages::-webkit-scrollbar { width:6px; }
        .contacts-list::-webkit-scrollbar-track, .chat-messages::-webkit-scrollbar-track { background:#f1f1f1; }
        .contacts-list::-webkit-scrollbar-thumb, .chat-messages::-webkit-scrollbar-thumb { background:#c1c1c1; border-radius:3px; }
        .contacts-list::-webkit-scrollbar-thumb:hover, .chat-messages::-webkit-scrollbar-thumb:hover { background:#a8a8a8; }
        .contact-avatar-wrap { position: relative; margin-right: 12px; }
        .online-dot { position: absolute; right: 0; bottom: 0; width: 12px; height: 12px; background: #25d366; border: 2px solid #fff; border-radius: 50%; display: none; }
        .online-dot.show { display: block; }
        
        /* Dropdown de anexos */
        .attachment-dropdown-container { position: relative; }
        .attachment-dropdown { 
            position: absolute; 
            bottom: 55px; 
            left: 0; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.12); 
            padding: 8px 0; 
            min-width: 180px; 
            opacity: 0; 
            visibility: hidden; 
            transform: translateY(10px); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            z-index: 1000;
            border: 1px solid #e9ecef;
        }
        .attachment-dropdown.show { 
            opacity: 1; 
            visibility: visible; 
            transform: translateY(0); 
        }
        .dropdown-item { 
            display: flex; 
            align-items: center; 
            padding: 12px 16px; 
            cursor: pointer; 
            transition: background-color 0.2s; 
            color: #111b21;
        }
        .dropdown-item:hover { 
            background: #f5f5f5; 
        }
        .dropdown-item i { 
            width: 20px; 
            margin-right: 12px; 
            color: #007bff; 
        }
        .dropdown-item span { 
            font-size: 14px; 
            font-weight: 500; 
        }
              /* Estilos para os novos ícones do dropdown */
        .attachment-dropdown {
            display: flex;
            flex-direction: column;
            gap: 8px; /* Espaçamento entre os itens */
        }

        .attachment-dropdown .dropdown-item {
            flex-direction: row;
            padding: 12px 16px;
            text-align: left;
            flex: none;
            border-radius: 0;
        }

        .attachment-dropdown .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
            margin-right: 12px;
            font-size: 16px;
            color: #fff;
            margin-left: 0;
        }

        .icon-circle.document { background-color: #7f66ff; } /* Roxo */
        .icon-circle.gallery { background-color: #e01b6a; } /* Rosa (usado para Imagem) */
        .icon-circle.video { background-color: #dc3545; } /* Vermelho */

        .attachment-dropdown .dropdown-item i {
            margin-right: 0;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #007bff;
            font-weight: 500;
            z-index: 999;
        }
        .drag-drop-area.active {
            display: flex;
        }
        .drag-drop-area i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.7;
        }
        .drag-drop-area p {
            margin: 0;
            font-size: 16px;
        }
        
        /* Preview de arquivos */
        .file-preview-container {
            display: none;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            max-height: 120px;
            overflow-y: auto;
        }
        .file-preview-item {
            display: flex;
            align-items: center;
            padding: 8px;
            background: #fff;
            border-radius: 6px;
            margin-bottom: 6px;
            border: 1px solid #e9ecef;
        }
        .file-preview-item:last-child {
            margin-bottom: 0;
        }
        .file-preview-icon {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
        }
        .file-preview-icon.image { background: #28a745; }
        .file-preview-icon.video { background: #dc3545; }
        .file-preview-icon.document { background: #6c757d; }
        .file-preview-info {
            flex: 1;
            min-width: 0;
        }
        .file-preview-name {
            font-size: 13px;
            font-weight: 500;
            color: #111b21;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-preview-size {
            font-size: 11px;
            color: #667781;
        }
        .file-preview-remove {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .file-preview-remove:hover {
            background: #f8d7da;
        }
        .drag-drop-area {
            display: none;              /* escondida por padrão */
            position: absolute;
            left: 20px; right: 20px;    /* bordas internas da área de input */
            bottom: 70px;               /* acima do footer */
            top: 20px;
            background: rgba(255,255,255,0.9);
            border: 2px dashed #7f66ff;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 999;
        }
        .drag-drop-area.active {
            display: flex !important;   /* força aparecimento mesmo com d-none */
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
        <main style="padding:0;">
            <div class="whatsapp-container">
                <!-- Sidebar -->
                <aside class="contacts-sidebar">
                    <div class="contacts-header">
                        <h5 style="color:#fff;" class="mb-0"><i class="fas fa-comments mr-2"></i>Chats</h5>
                        <input type="text" class="form-control" id="searchUserInput" placeholder="Buscar ou iniciar nova conversa...">
                    </div>
                    <div class="contacts-list" id="lista_chats"></div>
                </aside>

                <!-- Área do Chat -->
                <section class="chat-area">
                    <?php if ($chat_selecionado && $destinatario): ?>
                        <header class="chat-header">
                            <img class="chat-header-avatar" src="<?= URL_RESOURCES ?>/assets/img/avatars/<?= htmlspecialchars($destinatario->id_avatar) ?>.png" alt="Avatar">
                            <div class="chat-header-info">
                                <h6 class="mb-0"><?= htmlspecialchars($destinatario->nome) ?></h6>
                                <p class="chat-header-status" id="online">online</p>
                            </div>
                        </header>

                        <div class="chat-messages" id="mensagens"></div>

                        <footer class="chat-input-area">
                            <div class="file-preview-container" id="filePreviewContainer"></div>
                            <form id="chatForm" class="chat-input-container" method="post" action="<?= URL ?>/class/Mensagens.php" enctype="multipart/form-data">
                                <input type="hidden" name="id_conversa" id="id_conversa" value="<?= (int)$chat_selecionado ?>">
                                <input type="hidden" name="id_destinatario" id="id_destinatario" value="<?= (int)$id_destinatario ?>">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario_sessao ?>">
                                <input type="hidden" name="id_avatar" id="id_avatar" value="<?= htmlspecialchars($id_avatar_sessao) ?>">
                                <input type="hidden" name="id_avatar_destinatario" id="id_avatar_destinatario" value="<?= htmlspecialchars($destinatario->id_avatar) ?>">

                                <div class="attachment-dropdown-container">
                                    <button type="button" class="attachment-button" id="attachmentBtn" title="Anexar">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                   <div class="attachment-dropdown" id="attachmentDropdown">
                                        <div class="dropdown-item" data-type="document">
                                            <div class="icon-circle document"><i class="fas fa-file-alt"></i></div>
                                            <span>Documento</span>
                                        </div>
                                        <div class="dropdown-item" data-type="image">
                                            <div class="icon-circle gallery"><i class="fas fa-image"></i></div>
                                            <span>Imagem</span>
                                        </div>
                                        <div class="dropdown-item" data-type="video">
                                            <div class="icon-circle video"><i class="fas fa-video"></i></div>
                                            <span>Vídeo</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" id="fileInput" name="attachments[]" style="display: none;" multiple>
                                <div class="drag-drop-area d-none" id="dragDropArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Arraste arquivos aqui ou clique no botão de anexo</p>
                                </div>
                                <textarea name="mensagem" id="mensagem" class="chat-input" placeholder="Digite uma mensagem..." rows="1"></textarea>
                                <button type="submit" class="send-button" title="Enviar">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </footer>
                    <?php else: ?>
                        <div class="empty-chat">
                            <div class="empty-chat-icon"><i class="fas fa-comments"></i></div>
                            <h3>Chats</h3>
                            <p>Selecione um chat para começar ou busque por outro colaborador para iniciar novo chat.</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script>
(function () {
    'use strict';

    // Contexto
    const URL_RESOURCES = '<?= URL_RESOURCES ?>';
    const URL_BASE      = '<?= URL ?>';

    const idUsuario = <?= $id_usuario_sessao ?>;
    const idAvatar  = '<?= addslashes($id_avatar_sessao) ?>';

    // Estado
    let currentChatId           = $('#id_conversa').val() || null;
    let currentDestinatarioId   = $('#id_destinatario').val() || null;
    let idAvatarDestinatario    = $('#id_avatar_destinatario').val() || null;

    let lastCount = null;
    let isLoading = false;
    let autoScroll = true;
    let isSearching = false;
    let refreshIntervalId = null;
    window.chatIntervals = [];

    // Inicialização
    $(document).ready(function () {
        $('#chats').addClass('active');
        bindSearchField();
        if (currentChatId) { setupChatEvents(); startChatIntervals(); checarContagem(); verificarOnline(); }
        resumeAutoRefresh();
        loadChats();
        setTimeout(scrollToBottom, 100);
        $('#searchUserInput').on('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
    });

    // ===== Util =====
    function scrollToBottom() {
        const el = document.querySelector('.chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    }

    function checkAutoScroll() {
        const el = document.querySelector('.chat-messages');
        if (!el) return;
        autoScroll = (el.scrollHeight - (el.scrollTop + el.clientHeight)) < 120;
    }

    // ===== Auto refresh lista =====
    function pauseAutoRefresh() { if (refreshIntervalId) { clearInterval(refreshIntervalId); refreshIntervalId = null; } }
    function resumeAutoRefresh() { if (!isSearching && !refreshIntervalId) { refreshIntervalId = setInterval(loadChats, 5000); } }

    // ===== Eventos do chat =====
    function setupChatEvents() {
        $('#mensagem').off('input').on('input', function () { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 100) + 'px'; });
        $('#mensagem').off('keydown').on('keydown', function (e) { if (e.keyCode === 13 && !e.shiftKey) { e.preventDefault(); $('#chatForm').submit(); } });
        $('#chatForm').off('submit').on('submit', function (e) {
            e.preventDefault();
            const mensagem = $('#mensagem').val().trim();
            
            // Verificar se há mensagem ou arquivos
            if (!mensagem && selectedFiles.length === 0) return;
            
            // Criar FormData para envio
            const formData = new FormData();
            
            // Adicionar dados do formulário
            formData.append('id_conversa', $('#id_conversa').val());
            formData.append('id_destinatario', $('#id_destinatario').val());
            formData.append('id_usuario', $('#id_usuario').val());
            formData.append('id_avatar', $('#id_avatar').val());
            formData.append('id_avatar_destinatario', $('#id_avatar_destinatario').val());
            formData.append('mensagem', mensagem);
            
            // Adicionar arquivos
            selectedFiles.forEach((file, index) => {
                formData.append('attachments[]', file);
            });
            
            $.ajax({
                type: 'post',
                url: URL_BASE + '/class/Mensagens.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    $('#mensagem').val('').focus().css('height','auto');
                    selectedFiles = [];
                    updateFilePreview();
                    checarContagem();
                    loadChats();
                },
                error: function () { alert('Erro ao enviar mensagem!'); }
            });
        });
        $('.chat-messages').off('scroll').on('scroll', checkAutoScroll);
    }

    function startChatIntervals() {
        clearChatIntervals();
        window.chatIntervals.push(setInterval(checarContagem, 1000));
        window.chatIntervals.push(setInterval(checarLeituras, 1000));
        window.chatIntervals.push(setInterval(verificarOnline, 1000));
    }

    function clearChatIntervals() {
        window.chatIntervals.forEach(i => clearInterval(i));
        window.chatIntervals = [];
    }

    // ===== Sidebar: lista de chats =====
    function loadChats() {
        $.ajax({
            url: '/GRNacoes/views/ajax/get_chats_whatsapp.php',
            method: 'GET',
            data: { current_chat: currentChatId },
            success: function (data) {
                $('#lista_chats').html(data);
                
                $('#lista_chats [data-destinatario-id]').each(function () {
                    const uid = $(this).data('destinatario-id');
                    $.get('/GRNacoes/views/ajax/check_online.php', { id_destinatario: uid }, function (result) {
                        const data = (typeof result === 'string') ? JSON.parse(result) : result;
                        $('#online-' + uid).toggleClass('show', data.ok && data.is_online === true);
                    });
                });

            }
        });
    }

    // ===== Online =====
    function verificarOnline() {
        if (!currentDestinatarioId) return;

        $.get('/GRNacoes/views/ajax/check_online.php', { id_destinatario: currentDestinatarioId }, function (res) {
            try {
                const data = (typeof res === 'string') ? JSON.parse(res) : res;

                if (data.ok) {
                    if (data.is_online) {
                        $('#online').text('online');
                    } else {
                        // Usa o campo "visto_ha" do JSON
                        $('#online').text('Última vez online há ' + data.visto_ha);
                    }
                } else {
                    $('#online').text('');
                }
            } catch (e) {
                console.error('Erro ao processar check_online:', e, res);
                $('#online').text('');
            }
        });
    }


    // ===== Mensagens =====
    function mostrarMensagens() {
        if (isLoading || !currentChatId) return;
        isLoading = true;
        $.ajax({
            type: 'get',
            url: '/GRNacoes/views/ajax/get_mensagens_whatsapp.php',
            data: { id_conversa: currentChatId, id_usuario: idUsuario, id_avatar: idAvatar, id_avatar_destinatario: idAvatarDestinatario },
            success: function (html) {
                const stayBottom = autoScroll;
                $('#mensagens').html(html);
                if (stayBottom) scrollToBottom();
            },
            complete: function () { isLoading = false; }
        });
    }

    function checarContagem() {
        if (!currentChatId) return;
        $.ajax({
            type: 'get',
            url: '/GRNacoes/views/ajax/get_count_mensagens.php',
            data: { id_conversa: currentChatId },
            success: function (res) {
                try {
                    const data = (typeof res === 'string') ? JSON.parse(res) : res;
                    if (data && data.ok === true) {
                        const total = parseInt(data.total, 10);
                        if (Number.isNaN(total)) return;
                        if (lastCount === null) { lastCount = total; mostrarMensagens(); return; }
                        if (total !== lastCount) { lastCount = total; checkAutoScroll(); mostrarMensagens(); }
                    }
                } catch (_) { /* silencioso */ }
            }
        });
    }

    function checarLeituras() {
        if (!currentChatId || !idUsuario) return;
        $.ajax({
            type: 'get',
            url: '/GRNacoes/views/ajax/get_mensagens_lidas.php',
            data: { id_conversa: currentChatId, id_usuario: idUsuario },
            success: function (res) {
                try {
                    const data = (typeof res === 'string') ? JSON.parse(res) : res;
                    if (data && data.ok && Array.isArray(data.ids_lidas)) {
                        data.ids_lidas.forEach(function (idMsg) {
                            const $status = $('#status-' + idMsg);
                            if ($status.length) {
                                // Font Awesome 5: "fas fa-check"
                                if ($status.html().indexOf('fa-check') === -1) {
                                    $status.html('<i class="fas fa-check" style="color:#53bdeb;"></i>');
                                }
                            }
                        });
                    }
                } catch (_) {}
            }
        });
    }

    // ===== Anexos e Dropdown =====
    let selectedFiles = [];
    let currentFileType = null;

    // Toggle dropdown
    $('#attachmentBtn').on('click', function(e) {
        e.stopPropagation();
        $('#attachmentDropdown').toggleClass('show');
    });

    $('#attachmentDropdown').on('click', '.dropdown-item', function () {
        const type = $(this).data('type'); // 'document' | 'image' | 'video'
        triggerFileInput(type);
    });

    // Fechar dropdown ao clicar fora
    $(document).on('click', function() {
        $('#attachmentDropdown').removeClass('show');
    });

    window.triggerFileInput = function(type) {
    currentFileType = type;
    const fileInput = $('#fileInput')[0];

    switch(type) {
        case 'document':
            fileInput.accept = '.pdf,.doc,.docx,.txt,.rtf,.odt,.xls,.xlsx,.csv';
            break;
        case 'image':
            fileInput.accept = 'image/*';
            break;
        case 'video':
            fileInput.accept = 'video/*';
            break;
        default:
            fileInput.accept = '*/*';
    }

    // Permite múltiplos
    fileInput.multiple = true;

    // Abre o seletor e fecha o dropdown
    $('#attachmentDropdown').removeClass('show');
    fileInput.click();
};


    // Manipular seleção de arquivos
    $('#fileInput').on('change', function() {
        const files = Array.from(this.files);
        addFilesToPreview(files);
        this.value = ''; // Limpar input para permitir selecionar o mesmo arquivo novamente
    });

    // =============== Drag and Drop (só para arquivos) ===============
    const chatInputArea = $('.chat-input-area')[0];
    const $dragDropArea = $('#dragDropArea');

    function isFileDrag(e) {
        const dt = (e.originalEvent && e.originalEvent.dataTransfer) || e.dataTransfer;
        if (!dt) return false;
        // Em Windows/Chrome/Edge/Firefox, 'Files' indica que há arquivos
        const types = Array.from(dt.types || []);
        return types.includes('Files');
    }

    function showDropArea() {
        // remove d-none e ativa
        $dragDropArea.removeClass('d-none').addClass('active');
    }
    function hideDropArea() {
        $dragDropArea.removeClass('active').addClass('d-none');
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Vamos contar entradas/saídas para evitar sumir ao passar por filhos
    let dragCounter = 0;

    // Eventos globais: só bloqueia e mostra se for arquivos
    $(window).on('dragenter', function(e) {
        if (isFileDrag(e)) {
            dragCounter++;
            preventDefaults(e);
            showDropArea();
        }
    });

    $(window).on('dragover', function(e) {
        if (isFileDrag(e)) {
            preventDefaults(e);
        }
    });

    $(window).on('dragleave', function(e) {
        if (isFileDrag(e)) {
            dragCounter--;
            if (dragCounter <= 0) {
                hideDropArea();
                dragCounter = 0;
            }
        }
    });

    $(window).on('drop', function(e) {
        if (isFileDrag(e)) {
            preventDefaults(e);
            hideDropArea();
            dragCounter = 0;

            const dt = e.originalEvent ? e.originalEvent.dataTransfer : e.dataTransfer;
            const files = Array.from(dt.files || []);
            if (files.length > 0) addFilesToPreview(files);
        }
    });

    // Também permitir soltar diretamente sobre a área do input
    chatInputArea.addEventListener('drop', function(e) {
        if (isFileDrag(e)) {
            preventDefaults(e);
            hideDropArea();
            dragCounter = 0;

            const dt = e.dataTransfer;
            const files = Array.from(dt.files || []);
            if (files.length > 0) addFilesToPreview(files);
        }
    }, false);
    // =============== Fim Drag and Drop ===============


    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        $('#dragDropArea').addClass('active');
    }

    function unhighlight(e) {
        $('#dragDropArea').removeClass('active');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = Array.from(dt.files);
        addFilesToPreview(files);
    }

    // Adicionar arquivos ao preview
    function addFilesToPreview(files) {
        files.forEach(file => {
            if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        });
        updateFilePreview();
    }

    // Atualizar preview de arquivos
    function updateFilePreview() {
        const container = $('#filePreviewContainer');
        
        if (selectedFiles.length === 0) {
            container.hide().empty();
            return;
        }

        container.show().empty();

        selectedFiles.forEach((file, index) => {
            const fileType = getFileType(file);
            const fileSize = formatFileSize(file.size);
            
            const previewItem = $(`
                <div class="file-preview-item">
                    <div class="file-preview-icon ${fileType}">
                        <i class="fas ${getFileIcon(fileType)}"></i>
                    </div>
                    <div class="file-preview-info">
                        <div class="file-preview-name">${file.name}</div>
                        <div class="file-preview-size">${fileSize}</div>
                    </div>
                    <button type="button" class="file-preview-remove" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
            
            container.append(previewItem);
        });
    }

    // Remover arquivo
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFilePreview();
    };

    // Utilitários
    function getFileType(file) {
        if (file.type.startsWith('image/')) return 'image';
        if (file.type.startsWith('video/')) return 'video';
        return 'document';
    }

    function getFileIcon(type) {
        switch(type) {
            case 'image': return 'fa-image';
            case 'video': return 'fa-video';
            default: return 'fa-file-alt';
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // ===== Fim Anexos =====
    function bindSearchField() {
        let searchTimeout;
        $('#searchUserInput').on('input', function () {
            const term = $(this).val().trim();
            clearTimeout(searchTimeout);

            if (term.length === 0) { isSearching = false; $('#lista_chats').empty(); loadChats(); resumeAutoRefresh(); return; }
            if (term.length < 2) return; // mínimo 2 chars

            isSearching = true; pauseAutoRefresh();
            searchTimeout = setTimeout(() => searchUsers(term), 300);
        });
    }

    function searchUsers(term) {
        $.ajax({
            url: '/GRNacoes/views/ajax/search_users.php',
            method: 'GET',
            data: { search: term },
            success: function (response) {
                try {
                    const data = (typeof response === 'string') ? JSON.parse(response) : response;
                    if (data.ok && data.users) renderSearchResults(data.users);
                } catch (e) { console.error('Erro ao processar resultados da busca:', e); }
            },
            error: function () { console.error('Erro ao buscar usuários'); }
        });
    }

    function renderSearchResults(users) {
        const seen = new Set();
        const unique = [];
        users.forEach(u => { if (!seen.has(u.id_usuario)) { seen.add(u.id_usuario); unique.push(u); } });

        if (unique.length === 0) {
            $('#lista_chats').html('<div style="padding:40px 20px; text-align:center; color:#667781;"><i class="fas fa-user-slash" style="font-size:48px; margin-bottom:16px; opacity:.3;"></i><p>Nenhum usuário encontrado.</p></div>');
            return;
        }

        const html = unique.map(user => {
            const isActive = (String(currentChatId) === String(user.id_conversa)) ? 'active' : '';
            const badge = (user.qtd_nao_lidas > 0) ? `<div class="unread-badge">${user.qtd_nao_lidas > 99 ? '99+' : user.qtd_nao_lidas}</div>` : '';
            let iconeLeitura = '';
            if (user.ultima_mensagem && Number(user.author_id) === Number(idUsuario)) {
                iconeLeitura = (user.status_leitura === 'lida')
                    ? '<i class="fas fa-check" style="color:#00ffb3; margin-right:4px;"></i>'
                    : '<i class="fas fa-check" style="color:#808080; margin-right:4px;"></i>';
            }
            const lastMessage = user.ultima_mensagem
                ? (user.ultima_mensagem.length > 30 ? user.ultima_mensagem.substring(0, 30) + '...' : user.ultima_mensagem)
                : (user.tem_conversa ? '<em>Nenhuma mensagem</em>' : '<em>Clique para iniciar conversa</em>');

            const clickAction = user.tem_conversa
                ? `selecionarChat(${user.id_conversa}, ${user.id_usuario}, '${String(user.nome).replace(/'/g, "\\'")}', '${user.id_avatar}')`
                : `iniciarNovaConversa(${user.id_usuario}, '${String(user.nome).replace(/'/g, "\\'")}', '${user.id_avatar}')`;

            return `
                <a href="javascript:void(0)" class="contact-item ${isActive}" onclick="${clickAction}" data-chat-id="${user.id_conversa || ''}">
                    <div style="display:flex; align-items:center;">
                        <div class="contact-avatar-wrap">
                            <img class="contact-avatar" src="${URL_RESOURCES}/assets/img/avatars/${user.id_avatar}.png" alt="">
                            <span class="online-dot" id="online-${user.id_usuario}"></span>
                        </div>
                        <div class="contact-info">
                            <div class="contact-name">${user.nome}</div>
                            <div class="contact-last-message">${iconeLeitura}${lastMessage}</div>
                        </div>
                        <div class="contact-meta">
                            ${badge}
                            <div class="contact-time">${user.data_envio || ''}</div>
                        </div>
                    </div>
                </a>`;
        }).join('');

        $('#lista_chats').html(html);
        $('#lista_chats [data-destinatario-id]').each(function () {
            const uid = $(this).data('destinatario-id');
            $.get('/GRNacoes/views/ajax/check_online.php', { id_destinatario: uid }, function (result) {
                const data = (typeof result === 'string') ? JSON.parse(result) : result;
                $('#online-' + uid).toggleClass('show', data.ok && data.is_online === true);
            });
        });
    }

    // ===== Ações globais (expostas no window) =====
    window.selecionarChat = function (chatId, destinatarioId, destinatarioNome, destinatarioAvatar) {
        clearChatIntervals();

        currentChatId = chatId;
        currentDestinatarioId = destinatarioId;
        idAvatarDestinatario = destinatarioAvatar;

        $('#id_conversa').val(chatId);
        $('#id_destinatario').val(destinatarioId);
        $('#id_avatar_destinatario').val(destinatarioAvatar);

        $('.contact-item').removeClass('active');
        $(`[data-chat-id="${chatId}"]`).addClass('active');

        // Render header + área (caso estivesse vazio)
        if ($('.empty-chat').length > 0) {
            $('.chat-area').html(`
                <header class="chat-header">
                    <img class="chat-header-avatar" src="${URL_RESOURCES}/assets/img/avatars/${destinatarioAvatar}.png" alt="">
                    <div class="chat-header-info">
                        <h6>${destinatarioNome}</h6>
                        <p class="chat-header-status" id="online"></p>
                    </div>
                </header>
                <div class="chat-messages" id="mensagens"></div>
                <footer class="chat-input-area">
                    <form id="chatForm" class="chat-input-container">
                        <input type="hidden" name="id_conversa" id="id_conversa" value="${chatId}">
                        <input type="hidden" name="id_destinatario" id="id_destinatario" value="${destinatarioId}">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario_sessao ?>">
                        <input type="hidden" name="id_avatar" id="id_avatar" value="<?= addslashes($id_avatar_sessao) ?>">
                        <input type="hidden" name="id_avatar_destinatario" id="id_avatar_destinatario" value="${destinatarioAvatar}">
                        <button type="button" class="attachment-button" onclick="alert('Funcionalidade de anexo em desenvolvimento!')"><i class="fas fa-paperclip"></i></button>
                        <textarea name="mensagem" id="mensagem" class="chat-input" placeholder="Digite uma mensagem..." rows="1"></textarea>
                        <button type="submit" class="send-button"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </footer>`);
            setupChatEvents();
        } else {
            // Atualiza apenas header
            $('.chat-header').html(`
                <img class="chat-header-avatar" src="${URL_RESOURCES}/assets/img/avatars/${destinatarioAvatar}.png" alt="">
                <div class="chat-header-info">
                    <h6>${destinatarioNome}</h6>
                    <p class="chat-header-status" id="online"></p>
                </div>`);
        }

        lastCount = null;
        checarContagem();
        startChatIntervals();

        // Atualiza URL
        if (history.pushState) {
            const newUrl = `${location.protocol}//${location.host}${location.pathname}?id=${chatId}&id_destinatario=${destinatarioId}`;
            history.pushState({ path: newUrl }, '', newUrl);
        }
    };

    window.iniciarNovaConversa = function (destinatarioId, destinatarioNome, destinatarioAvatar) {
        $.ajax({
            type: 'post',
            url: '/GRNacoes/views/ajax/abrir_conversa_privada.php',
            data: { id_destinatario: destinatarioId },
            success: function (res) {
                try {
                    const data = (typeof res === 'string') ? JSON.parse(res) : res;
                    if (data.ok && data.id_conversa) {
                        selecionarChat(data.id_conversa, destinatarioId, destinatarioNome, destinatarioAvatar);
                        $(`[data-destinatario-id="${destinatarioId}"]`).addClass('active');
                        
                        loadChats(); // atualiza lista normalmente
                        $('#searchUserInput').val('');
                        isSearching = false; 
                        resumeAutoRefresh();
                    }else {
                        alert(data.error || 'Não foi possível abrir a conversa.');
                    }
                } catch (e) { alert('Erro ao processar criação de conversa.'); }
            },
            error: function () { alert('Erro ao criar nova conversa!'); }
        });
    };
})();
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= URL_RESOURCES ?>/js/scripts.js"></script>
</body>
</html>