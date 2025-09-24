<?php 
    $Usuario = new Usuario();
?>
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <br>
                <a class="nav-link" id="ajuda" href="javascript:void(0);">
                    Ajuda
                </a>
                <a class="nav-link" id="chat" href="javascript:void(0);">
                    Chat
                </a>
                <div class="sidenav-menu-heading">Interfaces</div>
                <a class="nav-link collapsed" id="cham" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                    Chamados
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDashboards" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" id="como-abrir-chamado" href="javascript:void(0);">Como Abrir</a>
                        <a class="nav-link" id="como-ver-chamado" href="javascript:void(0);">Como ver Chamados</a>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>