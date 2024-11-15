<?php 
    $Setor = new Setor();
?>
<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
    <a class="navbar-brand d-none d-sm-block" href="<?php echo URL ?>/">GRNacoes</a>

    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i data-feather="menu"></i></button>
    
    <!-- Dropdown Topbar -->
    <ul class="navbar-nav align-items-center ml-auto">
        <li class="nav-item dropdown no-caret mr-3 dropdown-notifications">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="mail"></i></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                <h6 class="dropdown-header dropdown-notifications-header"><i class="mr-2" data-feather="mail"></i>Message Center</h6>
                <a class="dropdown-item dropdown-notifications-item" href="#!"><img class="dropdown-notifications-item-img" src="https://source.unsplash.com/vTL_qy03D1I/60x60" />
                    <div class="dropdown-notifications-item-content">
                        <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                        <div class="dropdown-notifications-item-content-details">Emily Fowler · 58m</div>
                    </div>
                </a>
                </a><a class="dropdown-item dropdown-notifications-footer" href="#!">Read All Messages</a>
            </div>
        </li>
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
                    Perfil</a>
                <button class="dropdown-item" data-toggle="modal" data-target="#modalAlterarSenha">
                <div class="dropdown-item-icon"><i class="fa-solid fa-lock"></i></div>
                Alterar Senha</button>
                <a class="dropdown-item" href="<?php echo URL ?>/sair">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Sair</a>
            </div>
        </li>
    </ul>

</nav>