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
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $pageTitle ?></title>
    
    <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
    
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    
    <style>
        /* Layout geral */
        .whatsapp-container { height: 93vh; display: flex; background: #f0f2f5; }

        /* Sidebar */
        .contacts-sidebar { width: 30%; min-width: 320px; background: #fff; border-right: 1px solid #e9ecef; display: flex; flex-direction: column; overflow: hidden; }
        .contacts-header { background: #007bff; color: #fff; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0, 0, 0, .1); }
        .contacts-list { flex: 1; overflow-y: auto; }
        .contact-item { padding: 12px 20px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background-color .2s; color: inherit; display: block; text-decoration: none; }
        .contact-item:hover { background: #f5f5f5; text-decoration: none; }
        .contact-item.active { background: #e3f2fd; border-left: 4px solid #007bff; }
        .contact-avatar-wrap { position: relative; margin-right: 12px; }
        .contact-avatar { width: 50px; height: 50px; border-radius: 50%; }
        .contact-info { flex: 1; min-width: 0; }
        .contact-name { font-weight: 600; font-size: 16px; color: #111b21; }
        .contact-last-message { font-size: 14px; color: #667781; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .contact-meta { display: flex; flex-direction: column; align-items: flex-end; min-width: 60px; }
        .contact-time { font-size: 12px; color: #667781; }
        .unread-badge { background: #25d366; color: #fff; border-radius: 50%; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; }
        .online-dot { position: absolute; right: 0; bottom: 0; width: 12px; height: 12px; background: #25d366; border: 2px solid #fff; border-radius: 50%; display: none; }
        .online-dot.show { display: block; }

        /* Área do chat */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: #efeae2; background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><defs><pattern id="p" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23p)"/></svg>'); }
        .chat-header { background: #f0f2f5; padding: 15px 20px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; box-shadow: 0 1px 2px rgba(0, 0, 0, .1); }
        .chat-header-avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; }
        .chat-header-info h6 { margin: 0; font-weight: 600; color: #111b21; }
        .chat-header-status { font-size: 13px; color: #667781; margin: 0; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 8px; }

        /* Mensagens */
        .message-container { display: flex; margin-bottom: 8px; }
        .message-container.sent { justify-content: flex-end; }
        .message-container.received { justify-content: flex-start; }
        .message-bubble { max-width: 65%; padding: 8px 12px; border-radius: 8px; position: relative; word-wrap: break-word; }
        .message-bubble.sent { background: #d9fdd3; color: #111b21; }
        .message-bubble.received { background: #fff; color: #111b21; }
        .message-time { font-size: 11px; color: #667781; margin-top: 4px; text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
        .date-separator { text-align: center; margin: 20px 0 10px; }
        .date-separator span { background: #e1f4fb; color: #54656f; padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 500; }
        
        /* Rodapé e Input */
        .chat-input-area { position: relative; /* <-- CORREÇÃO PRINCIPAL AQUI */ background: #f0f2f5; padding: 10px 20px; border-top: 1px solid #e9ecef; }
        .chat-input-container { display: flex; align-items: flex-end; gap: 10px; }
        .chat-input { flex: 1; border: none; border-radius: 20px; padding: 10px 15px; resize: none; max-height: 100px; background: #fff; font-size: 15px; }
        .chat-input:focus { outline: none; box-shadow: 0 0 0 2px #007bff; }
        .attachment-button, .send-button { border: none; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; transition: background-color .2s; }
        .attachment-button { background: #6c757d; }
        .attachment-button:hover { background: #5a6268; }
        .send-button { background: #007bff; }
        .send-button:hover { background: #0056b3; }
        
        /* Tela de chat vazio */
        .empty-chat { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #667781; text-align: center; padding: 40px; }
        .empty-chat-icon { font-size: 80px; margin-bottom: 20px; opacity: .3; }
        
        /* Dropdown de anexos */
        .attachment-dropdown-container { position: relative; }
        .attachment-dropdown { position: absolute; bottom: 55px; left: 0; background: #fff; border-radius: 12px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12); padding: 8px; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1000; border: 1px solid #e9ecef; display: flex; flex-direction: column; gap: 8px; }
        .attachment-dropdown.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item { display: flex; align-items: center; padding: 10px 12px; cursor: pointer; transition: background-color 0.2s; color: #111b21; border-radius: 8px; }
        .dropdown-item:hover { background: #f5f5f5; }
        .icon-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 18px; color: #fff; }
        .icon-circle.document { background-color: #7f66ff; }
        .icon-circle.gallery { background-color: #e01b6a; }
        .icon-circle.video { background-color: #dc3545; }
        .dropdown-item span { font-size: 14px; font-weight: 500; }
        
        /* Drag & Drop Overlay */
        .drag-drop-area { display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(240, 242, 245, 0.95); border: 2px dashed #007bff; border-radius: 12px; align-items: center; justify-content: center; flex-direction: column; z-index: 999; color: #007bff; }
        .drag-drop-area.active { display: flex; }
        .drag-drop-area i { font-size: 48px; margin-bottom: 12px; }
        .drag-drop-area p { margin: 0; font-size: 16px; font-weight: 500; }
        
        /* Preview de arquivos */
        .file-preview-container { display: none; padding: 10px; background: #e9ecef; border-radius: 8px; margin-bottom: 10px; max-height: 120px; overflow-y: auto; }
        .file-preview-item { display: flex; align-items: center; padding: 8px; background: #fff; border-radius: 6px; margin-bottom: 6px; border: 1px solid #dee2e6; }
        .file-preview-item:last-child { margin-bottom: 0; }
        .file-preview-icon { width: 32px; height: 32px; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px; color: #fff; }
        .file-preview-icon.image { background: #28a745; }
        .file-preview-icon.video { background: #dc3545; }
        .file-preview-icon.document { background: #6c757d; }
        .file-preview-info { flex: 1; min-width: 0; }
        .file-preview-name { font-size: 13px; font-weight: 500; color: #111b21; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .file-preview-size { font-size: 11px; color: #667781; }
        .file-preview-remove { background: none; border: none; color: #dc3545; cursor: pointer; padding: 4px; border-radius: 4px; transition: background-color 0.2s; }
        .file-preview-remove:hover { background: #f8d7da; }

        /* Scrollbar */
        .contacts-list::-webkit-scrollbar, .chat-messages::-webkit-scrollbar { width: 6px; }
        .contacts-list::-webkit-scrollbar-track, .chat-messages::-webkit-scrollbar-track { background: #f1f1f1; }
        .contacts-list::-webkit-scrollbar-thumb, .chat-messages::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
        .contacts-list::-webkit-scrollbar-thumb:hover, .chat-messages::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        /* Responsivo */
        @media (max-width: 768px) {
            .whatsapp-container { flex-direction: column; }
            .contacts-sidebar { width: 100%; height: 40%; min-width: unset; }
            .chat-area { height: 60%; }
        }
    </style>
</head>

<body class="nav-fixed">
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main style="padding:0;">
                <div class="whatsapp-container">
                    <aside class="contacts-sidebar">
                        <div class="contacts-header">
                            <h5 style="color:#fff;" class="mb-0"><i class="fas fa-comments mr-2"></i>Chats</h5>
                            <input type="text" class="form-control form-control-sm" id="searchUserInput" placeholder="Buscar ou iniciar conversa...">
                        </div>
                        <div class="contacts-list" id="lista_chats"></div>
                    </aside>
                    <section class="chat-area">
                        <?php if ($chat_selecionado && $destinatario): ?>
                            <header class="chat-header">
                                <img class="chat-header-avatar" src="<?= URL_RESOURCES ?>/assets/img/avatars/<?= htmlspecialchars($destinatario->id_avatar) ?>.png" alt="Avatar">
                                <div class="chat-header-info">
                                    <h6 class="mb-0"><?= htmlspecialchars($destinatario->nome) ?></h6>
                                    <p class="chat-header-status" id="onlineStatus">online</p>
                                </div>
                            </header>

                            <div class="chat-messages" id="mensagens"></div>

                            <footer class="chat-input-area">
                                <div class="drag-drop-area" id="dragDropArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Solte os arquivos para anexar</p>
                                </div>
                                <div class="file-preview-container" id="filePreviewContainer"></div>
                                <form id="chatForm" class="chat-input-container" method="post" enctype="multipart/form-data">
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
                                            <div class="dropdown-item" onclick="triggerFileInput('document')">
                                                <div class="icon-circle document"><i class="fas fa-file-alt"></i></div>
                                                <span>Documento</span>
                                            </div>
                                            <div class="dropdown-item" onclick="triggerFileInput('image')">
                                                <div class="icon-circle gallery"><i class="fas fa-image"></i></div>
                                                <span>Imagem</span>
                                            </div>
                                            <div class="dropdown-item" onclick="triggerFileInput('video')">
                                                <div class="icon-circle video"><i class="fas fa-video"></i></div>
                                                <span>Vídeo</span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" id="fileInput" name="attachments[]" style="display: none;" multiple>

                                    <textarea name="mensagem" id="mensagem" class="chat-input" placeholder="Digite uma mensagem..." rows="1"></textarea>
                                    
                                    <button type="submit" class="send-button" title="Enviar">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </footer>
                        <?php else: ?>
                            <div class="empty-chat">
                                <div class="empty-chat-icon"><i class="fas fa-comments"></i></div>
                                <h3>Meus Chats</h3>
                                <p>Selecione uma conversa para começar a interagir.</p>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        (function() {
            'use strict';

            // =========================================================================
            // 1. CONFIGURAÇÃO E ESTADO GLOBAL
            // =========================================================================
            const URL_BASE = '<?= URL ?>';
            const URL_RESOURCES = '<?= URL_RESOURCES ?>';
            const idUsuarioSessao = <?= $id_usuario_sessao ?>;
            const idAvatarSessao = '<?= addslashes($id_avatar_sessao) ?>';

            let currentChatId = $('#id_conversa').val() || null;
            let currentDestinatarioId = $('#id_destinatario').val() || null;
            let idAvatarDestinatario = $('#id_avatar_destinatario').val() || null;
            
            let lastMessageCount = null;
            let isLoadingMessages = false;
            let autoScrollEnabled = true;
            let isUserSearching = false;
            
            let chatListIntervalId = null;
            window.chatIntervals = []; // Armazena intervalos específicos do chat ativo

            let selectedFiles = [];
            const MAX_FILES = 5;
            const MAX_TOTAL_SIZE = 200 * 1024 * 1024; // 200MB

            const allowedExtensions = [
                'pdf','doc','docx','txt','rtf','odt','xls','xlsx','csv',
                'png','jpg','jpeg','gif','webp','mp4','mov','avi','mkv'
            ];

            function isFileAllowed(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                return allowedExtensions.includes(ext);
            }


            // =========================================================================
            // 2. INICIALIZAÇÃO (DOCUMENT READY)
            // =========================================================================
            $(document).ready(function() {
                $('#chats').addClass('active');

                setupGlobalPlugins();
                setupGlobalEventHandlers();
                
                if (currentChatId) {
                    setupChatAreaEvents();
                    startChatIntervals();
                    checkMessageCount();
                    checkOnlineStatus();
                }
                
                resumeChatListRefresh();
                loadChatList();
                
                setTimeout(scrollToBottom, 200);
            });

            function setupGlobalPlugins() {
                Fancybox.bind('[data-fancybox="chat-gallery"]', { 
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
                },
            )};

            // =========================================================================
            // 3. FUNÇÕES PRINCIPAIS DE CONTROLE DO CHAT
            // =========================================================================
            window.selecionarChat = function(chatId, destinatarioId, destinatarioNome, destinatarioAvatar) {
                clearChatIntervals();

                currentChatId = chatId;
                currentDestinatarioId = destinatarioId;
                idAvatarDestinatario = destinatarioAvatar;

                // 1) Atualiza UI primeiro
                if ($('.empty-chat').length > 0) {
                    rebuildChatArea(destinatarioNome, destinatarioAvatar);
                } else {
                    updateChatHeader(destinatarioNome, destinatarioAvatar);
                }

                // 2) SÓ AGORA setar os hidden
                $('#id_conversa').val(chatId);
                $('#id_destinatario').val(destinatarioId);
                $('#id_avatar_destinatario').val(destinatarioAvatar);

                // Destaca chat ativo
                $('.contact-item').removeClass('active');
                $(`.contact-item[data-chat-id="${chatId}"]`).addClass('active');

                lastMessageCount = null;
                checkMessageCount();
                startChatIntervals();

                const newUrl = `${location.protocol}//${location.host}${location.pathname}?id=${chatId}&id_destinatario=${destinatarioId}`;
                history.pushState({ path: newUrl }, '', newUrl);
            };


            window.iniciarNovaConversa = function(destinatarioId, destinatarioNome, destinatarioAvatar) {
                $.ajax({
                    type: 'POST',
                    url: `${URL_BASE}/views/ajax/abrir_conversa_privada.php`,
                    data: { id_destinatario: destinatarioId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.ok && data.id_conversa) {
                            selecionarChat(data.id_conversa, destinatarioId, destinatarioNome, destinatarioAvatar);
                            $('#searchUserInput').val('');
                            isUserSearching = false;
                            resumeChatListRefresh();
                            loadChatList(); // Recarrega a lista para mostrar a nova conversa
                        } else {
                            alert(data.error || 'Não foi possível iniciar a conversa.');
                        }
                    },
                    error: function() {
                        alert('Erro de comunicação ao tentar iniciar a conversa.');
                    }
                });
            };
            
            // =========================================================================
            // 4. ATUALIZAÇÃO DA INTERFACE (UI)
            // =========================================================================
            function scrollToBottom() {
                const messagesContainer = document.querySelector('.chat-messages');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            function updateChatHeader(nome, avatar) {
                 $('.chat-header').html(`
                    <img class="chat-header-avatar" src="${URL_RESOURCES}/assets/img/avatars/${avatar}.png" alt="Avatar">
                    <div class="chat-header-info">
                        <h6 class="mb-0">${nome}</h6>
                        <p class="chat-header-status" id="onlineStatus"></p>
                    </div>`);
            }

            function rebuildChatArea(nome, avatar) {
                $('.chat-area').html(`
                    <header class="chat-header">
                        <img class="chat-header-avatar" src="${URL_RESOURCES}/assets/img/avatars/${avatar}.png" alt="Avatar">
                        <div class="chat-header-info">
                            <h6 class="mb-0">${nome}</h6>
                            <p class="chat-header-status" id="onlineStatus"></p>
                        </div>
                    </header>
                    <div class="chat-messages" id="mensagens"></div>
                    <footer class="chat-input-area">
                        <div class="drag-drop-area" id="dragDropArea">
                             <i class="fas fa-cloud-upload-alt"></i>
                             <p>Solte os arquivos para anexar</p>
                        </div>
                        <div class="file-preview-container" id="filePreviewContainer"></div>
                        <form id="chatForm" class="chat-input-container">
                            <input type="hidden" name="id_conversa" id="id_conversa">
                            <input type="hidden" name="id_destinatario" id="id_destinatario">
                            <input type="hidden" name="id_usuario" id="id_usuario" value="${idUsuarioSessao}">
                            <input type="hidden" name="id_avatar" id="id_avatar" value="${idAvatarSessao}">
                            <input type="hidden" name="id_avatar_destinatario" id="id_avatar_destinatario">
                            
                            <div class="attachment-dropdown-container">
                                <button type="button" class="attachment-button" id="attachmentBtn" title="Anexar"><i class="fas fa-paperclip"></i></button>
                                <div class="attachment-dropdown" id="attachmentDropdown">
                                     <div class="dropdown-item" onclick="triggerFileInput('document')"><div class="icon-circle document"><i class="fas fa-file-alt"></i></div><span>Documento</span></div>
                                     <div class="dropdown-item" onclick="triggerFileInput('image')"><div class="icon-circle gallery"><i class="fas fa-image"></i></div><span>Imagem</span></div>
                                     <div class="dropdown-item" onclick="triggerFileInput('video')"><div class="icon-circle video"><i class="fas fa-video"></i></div><span>Vídeo</span></div>
                                </div>
                            </div>
                            <input type="file" id="fileInput" name="attachments[]" style="display: none;" multiple>
                            <textarea name="mensagem" id="mensagem" class="chat-input" placeholder="Digite uma mensagem..." rows="1"></textarea>
                            <button type="submit" class="send-button" title="Enviar"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </footer>
                `);
                setupChatAreaEvents(); // Reassocia os eventos aos novos elementos
            }

            function renderSearchResults(users) {
                if (users.length === 0) {
                    $('#lista_chats').html('<div class="p-4 text-center text-muted"><i class="fas fa-user-slash fa-2x mb-2"></i><p>Nenhum usuário encontrado.</p></div>');
                    return;
                }

                const html = users.map(user => {
                    const isActive = (String(currentDestinatarioId) === String(user.id_usuario)) ? 'active' : '';
                    const clickAction = user.tem_conversa ?
                        `selecionarChat(${user.id_conversa}, ${user.id_usuario}, '${String(user.nome).replace(/'/g, "\\'")}', '${user.id_avatar}')` :
                        `iniciarNovaConversa(${user.id_usuario}, '${String(user.nome).replace(/'/g, "\\'")}', '${user.id_avatar}')`;
                    
                    const lastMessageText = user.ultima_mensagem || (user.tem_conversa ? '<em>Nenhuma mensagem</em>' : '<em>Clique para iniciar</em>');

                    return `
                        <a href="javascript:void(0)" class="contact-item ${isActive}" onclick="${clickAction}" data-chat-id="${user.id_conversa || ''}" data-destinatario-id="${user.id_usuario}">
                            <div class="d-flex align-items-center">
                                <div class="contact-avatar-wrap">
                                    <img class="contact-avatar" src="${URL_RESOURCES}/assets/img/avatars/${user.id_avatar}.png" alt="">
                                    <span class="online-dot" id="online-dot-${user.id_usuario}"></span>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-name">${user.nome}</div>
                                    <div class="contact-last-message">${lastMessageText}</div>
                                </div>
                            </div>
                        </a>`;
                }).join('');

                $('#lista_chats').html(html);
                updateOnlineDotsOnList();
            }

            // =========================================================================
            // 5. LÓGICA DE ANEXOS E DRAG & DROP
            // =========================================================================
            window.triggerFileInput = function(type) {
                const fileInput = $('#fileInput');
                const acceptTypes = {
                    document: '.pdf,.doc,.docx,.txt,.rtf,.odt,.xls,.xlsx,.csv',
                    image: 'image/*',
                    video: 'video/*'
                };
                fileInput.attr('accept', acceptTypes[type] || '*/*').click();
            };

            function addFilesToPreview(files) {
                let currentTotalSize = selectedFiles.reduce((sum, f) => sum + f.size, 0);

                for (const file of files) {
                    const ext = file.name.split('.').pop().toLowerCase();

                    if (!isFileAllowed(file)) {
                        alert(`O tipo de arquivo "${file.name}" não é permitido.`);
                        continue;
                    }

                    if (selectedFiles.length >= MAX_FILES) {
                        alert(`Limite de ${MAX_FILES} arquivos atingido.`);
                        break;
                    }
                    if (currentTotalSize + file.size > MAX_TOTAL_SIZE) {
                        alert(`O tamanho total dos arquivos excede 200MB. O arquivo "${file.name}" não foi adicionado.`);
                        continue;
                    }
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                        currentTotalSize += file.size;
                    }
                }
                updateFilePreview();
            }

            function updateFilePreview() {
                const container = $('#filePreviewContainer');
                if (selectedFiles.length === 0) {
                    container.hide().empty();
                    return;
                }
                
                container.show().empty();
                const totalSize = selectedFiles.reduce((s, f) => s + f.size, 0);
                
                container.append(`<div class="small text-muted mb-2">Arquivos: ${selectedFiles.length}/${MAX_FILES} | Total: ${formatFileSize(totalSize)}</div>`);

                selectedFiles.forEach((file, index) => {
                    const fileType = getFileType(file);
                    const itemHtml = `
                        <div class="file-preview-item">
                            <div class="file-preview-icon ${fileType}"><i class="fas ${getFileIcon(fileType)}"></i></div>
                            <div class="file-preview-info">
                                <div class="file-preview-name">${file.name}</div>
                                <div class="file-preview-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="file-preview-remove" onclick="removeFile(${index})"><i class="fas fa-times"></i></button>
                        </div>`;
                    container.append(itemHtml);
                });
            }

            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                updateFilePreview();
            };
            
            // =========================================================================
            // 6. BUSCA DE USUÁRIOS
            // =========================================================================
            function searchUsers(term) {
                $.ajax({
                    url: `${URL_BASE}/views/ajax/search_users.php`,
                    method: 'GET',
                    data: { search: term },
                    dataType: 'json',
                    success: function(data) {
                        if (data.ok && data.users) {
                            renderSearchResults(data.users);
                        }
                    },
                    error: function() { console.error('Erro ao buscar usuários.'); }
                });
            }

            // =========================================================================
            // 7. CHAMADAS AJAX E INTERVALOS
            // =========================================================================
            function loadChatList() {
                if (isUserSearching) return;
                $.ajax({
                    url: `${URL_BASE}/views/ajax/get_chats_whatsapp.php`,
                    method: 'GET',
                    data: { current_chat: currentChatId },
                    success: function(html) {
                        $('#lista_chats').html(html);
                        updateOnlineDotsOnList();
                    }
                });
            }
            
            function fetchMessages() {
                if (isLoadingMessages || !currentChatId) return;
                isLoadingMessages = true;
                
                $.ajax({
                    type: 'GET',
                    url: `${URL_BASE}/views/ajax/get_mensagens_whatsapp.php`,
                    data: {
                        id_conversa: currentChatId,
                        id_usuario: idUsuarioSessao,
                    },
                    success: function(html) {
                        const shouldScroll = autoScrollEnabled;
                        $('#mensagens').html(html);
                        if (shouldScroll) {
                            // Atraso para garantir que o DOM foi renderizado antes de rolar
                            setTimeout(scrollToBottom, 100); 
                        }
                    },
                    complete: function() {
                        isLoadingMessages = false;
                    }
                });
            }
            
            function checkMessageCount() {
                if (!currentChatId) return;
                $.ajax({
                    type: 'GET',
                    url: `${URL_BASE}/views/ajax/get_count_mensagens.php`,
                    data: { id_conversa: currentChatId },
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.ok) {
                            const total = parseInt(data.total, 10);
                            if (!isNaN(total) && total !== lastMessageCount) {
                                lastMessageCount = total;
                                fetchMessages();
                            }
                        }
                    }
                });
            }

            function checkReadStatus() {
                if (!currentChatId) return;
                $.ajax({
                    type: 'GET',
                    url: `${URL_BASE}/views/ajax/get_mensagens_lidas.php`,
                    data: { id_conversa: currentChatId, id_usuario: idUsuarioSessao },
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.ok && Array.isArray(data.ids_lidas)) {
                            data.ids_lidas.forEach(idMsg => {
                                $(`#status-${idMsg} i`).css('color', '#53bdeb'); // Azul para lido
                            });
                        }
                    }
                });
            }

            function checkOnlineStatus() {
                if (!currentDestinatarioId) return;
                $.ajax({
                    url: `${URL_BASE}/views/ajax/check_online.php`,
                    data: { id_destinatario: currentDestinatarioId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.ok) {
                            $('#onlineStatus').text(data.is_online ? 'online' : `visto por último ${data.visto_ha}`);
                        } else {
                            $('#onlineStatus').text('');
                        }
                    },
                    error: function() { $('#onlineStatus').text(''); }
                });
            }

            function updateOnlineDotsOnList() {
                 $('#lista_chats .contact-item[data-destinatario-id]').each(function() {
                    const uid = $(this).data('destinatario-id');
                    $.get(`${URL_BASE}/views/ajax/check_online.php`, { id_destinatario: uid }, function(result) {
                        try {
                            const data = (typeof result === 'string') ? JSON.parse(result) : result;
                            $(`#online-dot-${uid}`).toggleClass('show', data.ok && data.is_online === true);
                        } catch(e) {/* silent */}
                    });
                });
            }

            function pauseChatListRefresh() { if (chatListIntervalId) clearInterval(chatListIntervalId); }
            function resumeChatListRefresh() {
                pauseChatListRefresh();
                if (!isUserSearching) {
                    chatListIntervalId = setInterval(loadChatList, 5000);
                }
            }

            function startChatIntervals() {
                clearChatIntervals();
                window.chatIntervals.push(setInterval(checkMessageCount, 1500));
                window.chatIntervals.push(setInterval(checkReadStatus, 2000));
                window.chatIntervals.push(setInterval(checkOnlineStatus, 5000));
            }
            
            function clearChatIntervals() {
                window.chatIntervals.forEach(clearInterval);
                window.chatIntervals = [];
            }
            
            // =========================================================================
            // 8. MANIPULADORES DE EVENTOS (EVENT HANDLERS)
            // =========================================================================
            function setupGlobalEventHandlers() {
                // Campo de busca de usuário
                let searchTimeout;
                $('#searchUserInput').on('input', function() {
                    const term = $(this).val().trim();
                    clearTimeout(searchTimeout);
                    
                    if (term.length === 0) {
                        isUserSearching = false;
                        $('#lista_chats').empty();
                        loadChatList();
                        resumeChatListRefresh();
                        return;
                    }
                    
                    if (term.length < 2) return;
                    
                    isUserSearching = true;
                    pauseChatListRefresh();
                    searchTimeout = setTimeout(() => searchUsers(term), 300);
                }).on('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });

                // Fecha dropdown de anexo ao clicar fora
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.attachment-dropdown-container').length) {
                        $('#attachmentDropdown').removeClass('show');
                    }
                });

                // Drag and Drop
                const $dropArea = $('#dragDropArea');
                let dragCounter = 0;

                $(window).on('dragenter', function(e) {
                    const dt = e.originalEvent.dataTransfer;
                    if (dt && dt.types.includes('Files')) {
                        dragCounter++;
                        $dropArea.addClass('active');
                    }
                }).on('dragleave', function(e) {
                    dragCounter--;
                    if (dragCounter <= 0) {
                        $dropArea.removeClass('active');
                    }
                }).on('dragover', function(e) {
                    e.preventDefault();
                }).on('drop', function(e) {
                    e.preventDefault();
                    dragCounter = 0;
                    $dropArea.removeClass('active');
                    const dt = e.originalEvent.dataTransfer;
                    if (dt && dt.files.length) {
                        const validFiles = Array.from(dt.files).filter(f => isFileAllowed(f));
                        const invalidFiles = Array.from(dt.files).filter(f => !isFileAllowed(f));

                        if (invalidFiles.length > 0) {
                            alert("Alguns arquivos foram ignorados por não serem permitidos.");
                        }

                        addFilesToPreview(validFiles);
                    }
                });
            }

            function setupChatAreaEvents() {
                const $chatArea = $('.chat-area');

                // Auto-ajuste do textarea
                $chatArea.off('input', '#mensagem').on('input', '#mensagem', function() {
                    this.style.height = 'auto';
                    this.style.height = `${Math.min(this.scrollHeight, 100)}px`;
                });

                // Envio com Enter (sem Shift)
                $chatArea.off('keydown', '#mensagem').on('keydown', '#mensagem', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        $('#chatForm').trigger('submit');
                    }
                });

                // Scroll para acompanhar mensagens
                $chatArea.off('scroll', '.chat-messages').on('scroll', '.chat-messages', function() {
                    const el = this;
                    autoScrollEnabled = (el.scrollHeight - el.scrollTop - el.clientHeight) < 150;
                });
                
                // Toggle do dropdown de anexo
                $chatArea.off('click', '#attachmentBtn').on('click', '#attachmentBtn', function() {
                    $('#attachmentDropdown').toggleClass('show');
                });

                // Seleção de arquivos
                $chatArea.off('change', '#fileInput').on('change', '#fileInput', function() {
                    addFilesToPreview(Array.from(this.files));
                    this.value = ''; // Limpa para permitir re-seleção
                });

                // Submissão do formulário de chat
                $chatArea.off('submit', '#chatForm').on('submit', '#chatForm', function(e) {
                    e.preventDefault();
                    const mensagem = $('#mensagem').val().trim();

                    if (!mensagem && selectedFiles.length === 0) return;

                    const totalSize = selectedFiles.reduce((sum, f) => sum + f.size, 0);
                    if (selectedFiles.length > MAX_FILES || totalSize > MAX_TOTAL_SIZE) {
                        alert(`Verifique os limites de anexo: máximo de ${MAX_FILES} arquivos e 200MB no total.`);
                        return;
                    }

                    const formData = new FormData(this);
                    selectedFiles.forEach(file => formData.append('attachments[]', file));

                    $.ajax({
                        type: 'POST',
                        url: `${URL_BASE}/class/Mensagens.php`,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function() {
                            $('#mensagem').val('').css('height', 'auto').focus();
                            selectedFiles = [];
                            updateFilePreview();
                            checkMessageCount(); // Força atualização imediata
                            loadChatList();
                        },
                        error: function() { alert('Erro ao enviar mensagem.'); }
                    });
                });
            }

            // =========================================================================
            // 9. FUNÇÕES UTILITÁRIAS
            // =========================================================================
            function getFileType(file) {
                if (file.type.startsWith('image/')) return 'image';
                if (file.type.startsWith('video/')) return 'video';
                return 'document';
            }

            function getFileIcon(type) {
                const icons = { image: 'fa-image', video: 'fa-video', document: 'fa-file-alt' };
                return icons[type] || 'fa-file';
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
            }

        })();
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>