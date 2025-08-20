<?php 
    $Setor = new Setor();
    $Notificacao_Chamado = new Notificacao_Chamado();
?>
<style>
  .notif-item {
    display:flex; align-items:center; justify-content:space-between;
  }
  .notif-textos {
    min-width:0; /* evita quebrar layout */
  }
  .notif-acao {
    flex-shrink:0; margin-left:8px;
  }
  .notif-acao .btn {
    padding: .25rem .5rem;
  }
</style>

<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
    <a class="navbar-brand d-none d-sm-block" href="<?php echo URL ?>/">GRNacoes</a>

    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i data-feather="menu"></i></button>
    
    <ul class="navbar-nav align-items-center ml-auto">
        <!-- Notificações -->
        <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
            <a class="btn btn-transparent-dark dropdown-toggle position-relative" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-bell"></i>
                <span id="notif-badge" class="badge badge-pill badge-danger position-absolute" style="top:0; right:0; display:none;">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
                aria-labelledby="navbarDropdownMessages"
                style="min-width:320px; max-height:400px; flex-direction:column;"
                data-bs-auto-close="outside">
            
                <!-- Cabeçalho fixo com botão 'marcar todas' -->
                <div class="d-flex align-items-center justify-content-between px-3 pt-3 pb-2 flex-shrink-0">
                    <h6 class="m-0">Chamados</h6>
                    <button id="btn-marcar-todas"
                            type="button"
                            class="btn btn-sm btn-light border"
                            data-toggle="tooltip"
                            title="Marcar todas como lida">
                    <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <!-- Linha separadora opcional -->
                <div class="dropdown-divider m-0"></div>
                
                <!-- Conteúdo rolável -->
                <div id="notif-container" style="overflow-y:auto; max-height:300px; flex-grow:1;">
                    <div class="px-3 py-2 text-muted small">Carregando...</div>
                </div>

                <!-- Rodapé fixo -->
                <a class="text-dark dropdown-item dropdown-notifications-footer flex-shrink-0"
                    href="<?php echo URL; ?>/chamados/notificacoes">
                    Ler Todas Notificações
                </a>
            </div>


        </li>

        <!-- Usuário -->
        <li class="nav-item dropdown no-caret mr-3 dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-fluid" style="width: 90%;" src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $_SESSION['id_avatar'] ?>.png" />
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $_SESSION['id_avatar'] ?>.png" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo $_SESSION['nome'] ?></div>
                        <div class="dropdown-user-details-email"><?php echo $Setor->mostrar($_SESSION['id_setor'])->setor ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" id="perfil" href="<?php echo URL ?>/perfil">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Perfil
                </a>
                <button class="dropdown-item" data-toggle="modal" data-target="#modalAlterarSenha">
                    <div class="dropdown-item-icon"><i class="fa-solid fa-lock"></i></div>
                    Alterar Senha
                </button>
                <a class="dropdown-item" href="<?php echo URL ?>/sair">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Sair
                </a>
            </div>
        </li>
    </ul>
</nav>

<script>
  const URL_BASE   = "<?php echo URL; ?>";
  const ID_USUARIO = <?php echo (int)($_SESSION['id_usuario'] ?? 0); ?>;
  const ID_SETOR   = <?php echo (int)($_SESSION['id_setor'] ?? 0); ?>;

  // Seletores
  const dropdownMenu = document.querySelector('.dropdown-notifications .dropdown-menu');
  const btnMarcarTodas = document.getElementById('btn-marcar-todas');

  // Impede o dropdown de fechar ao clicar dentro (BS4 e BS5)
  if (dropdownMenu) {
    dropdownMenu.addEventListener('click', (e) => e.stopPropagation());
  }

  // Garante que o clique no botão não feche o dropdown
  if (btnMarcarTodas) {
    btnMarcarTodas.addEventListener('click', (ev) => {
      ev.preventDefault();
      ev.stopPropagation();
      marcarTodasComoLidas();
    });
  }

  function montarLinkNotificacao(n) {
    if (n.id_usuario && parseInt(n.id_usuario) > 0) {
      return URL_BASE + "/chamados/meus_chamados/";
    } else if (n.id_setor && parseInt(n.id_setor) > 0) {
      return URL_BASE + "/chamados/";
    } else {
      return URL_BASE + "/";
    }
  }

  async function carregarNotificacoes() {
    const container = document.getElementById('notif-container');
    const badge = document.getElementById('notif-badge');

    try {
      // Endpoint com unread=1 (lista limitada) e total_unread (contador real)
      const url = `${URL_BASE}/views/ajax/get_notificacoes_chamados.php?unread=1&limit=5`;
      const resp = await fetch(url, { cache: "no-store" });

      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

      const data = await resp.json();
      if (!data.ok) {
        container.innerHTML = `<div class="px-3 py-2 text-danger small">Erro ao carregar.</div>`;
        badge.style.display = "none";
        return;
      }

      const lista = Array.isArray(data.data) ? data.data : [];
      const totalBadge = (typeof data.total_unread === 'number')
        ? data.total_unread
        : lista.length;

      // Render da lista (limitada)
      if (lista.length === 0) {
        container.innerHTML = `<div class="px-3 py-2 text-muted small">Sem novas notificações.</div>`;
      } else {
        const frags = lista.map(n => {
          const href = montarLinkNotificacao(n);
          const texto = n.texto ?? '(sem texto)';
          const quando = n.created_at ? new Date(n.created_at.replace(' ', 'T')) : null;
          const tempo = quando ? quando.toLocaleString() : '';
          return `
            <a class="dropdown-item dropdown-notifications-item notif-item"
              href="${href}"
              data-id="${n.id_notificacao_chamado}">
              <div class="dropdown-notifications-item-content notif-textos">
                <div class="dropdown-notifications-item-content-text">${texto}</div>
                <div class="dropdown-notifications-item-content-details small text-muted">${tempo}</div>
              </div>
            </a>
          `;
        }).join('');
        container.innerHTML = frags;
      }

      // Atualiza o badge com o TOTAL de não lidas (sem limitar a 5)
      if (totalBadge > 0) {
        badge.textContent = totalBadge;
        badge.style.display = "inline-block";
      } else {
        badge.style.display = "none";
      }

    } catch (e) {
      console.error('carregarNotificacoes()', e);
      container.innerHTML = `<div class="px-3 py-2 text-danger small">Erro: ${e.message}</div>`;
      badge.style.display = "none";
    }
  }

  async function marcarTodasComoLidas() {
    const container = document.getElementById('notif-container');
    const badge = document.getElementById('notif-badge');
    const btn = document.getElementById('btn-marcar-todas');

    // Coleta os IDs exibidos (limit 5)
    const itens = container.querySelectorAll('.notif-item[data-id]');
    const ids = Array.from(itens).map(a => parseInt(a.getAttribute('data-id'), 10)).filter(Boolean);

    if (ids.length === 0) return;

    try {
      btn.disabled = true;
      btn.classList.add('disabled');

      // Envia UM id_notificacao por request (compatível com seu PHP atual)
      const promises = ids.map(id => {
        const body = new URLSearchParams();
        body.append('id_notificacao', String(id));
        body.append('id_usuario', String(ID_USUARIO)); // ajuda caso a sessão não chegue

        return fetch(`${URL_BASE}/views/ajax/set_notificacoes_lida.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
          body: body.toString()
        }).then(async r => {
          let json = null;
          try { json = await r.json(); } catch(_) {}
          if (!r.ok || !json?.ok) {
            const msg = json?.error || `Falha ao marcar ${id} (HTTP ${r.status})`;
            throw new Error(msg);
          }
          return json;
        });
      });

      await Promise.all(promises);

      await carregarNotificacoes();

    } catch (e) {
      console.error('marcarTodasComoLidas()', e);
      if (window.toastr?.error) toastr.error(e.message);
      else alert(e.message);
    } finally {
      btn.disabled = false;
      btn.classList.remove('disabled');
    }
  }

  // Carrega ao entrar e faz polling
  carregarNotificacoes();
  setInterval(carregarNotificacoes, 10000);
</script>
