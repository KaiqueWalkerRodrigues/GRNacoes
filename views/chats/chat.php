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
                            <form id="chatForm" class="chat-input-container">
                                <input type="hidden" name="id_conversa" id="id_conversa" value="<?= (int)$chat_selecionado ?>">
                                <input type="hidden" name="id_destinatario" id="id_destinatario" value="<?= (int)$id_destinatario ?>">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario_sessao ?>">
                                <input type="hidden" name="id_avatar" id="id_avatar" value="<?= htmlspecialchars($id_avatar_sessao) ?>">
                                <input type="hidden" name="id_avatar_destinatario" id="id_avatar_destinatario" value="<?= htmlspecialchars($destinatario->id_avatar) ?>">

                                <button type="button" class="attachment-button" title="Anexar" onclick="alert('Funcionalidade de anexo em desenvolvimento!')">
                                    <i class="fas fa-paperclip"></i>
                                </button>
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
            if (!mensagem) return;
            $.ajax({
                type: 'post',
                url: URL_BASE + '/class/Mensagens.php',
                data: $(this).serialize(),
                success: function () {
                    $('#mensagem').val('').focus().css('height','auto');
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
        $.ajax({ type: 'get', url: '/GRNacoes/views/ajax/check_online.php', data: { id_destinatario: currentDestinatarioId }, success: r => { $('#online').html(r); } });
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

    // ===== Busca de usuários =====
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
