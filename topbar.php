<?php 
    $Usuario = new Usuario; 
?>
<style>
    .topbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%; /* Largura total da topbar */
        z-index: 500; /* Garante que a topbar esteja acima do conteúdo */
    }

    .navbar-collapse {
        max-height: none; /* Garante que o conteúdo da topbar seja exibido corretamente */
    }

    .avatar{
        width: 30px;
    }

    .destinatario{
        margin-left: -220%;
        margin-right: 220%;
    }
</style>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>

<!-- Topbar Search -->
<!-- <form
    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
            aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
</form> -->

<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

<!-- Nav Item - Search Dropdown (Visible Only XS) -->
<li class="nav-item dropdown no-arrow d-sm-none">
    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
    </a>
    <!-- Dropdown - Messages -->
    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
        aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small"
                    placeholder="Search for..." aria-label="Search"
                    aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</li>

<!-- Nav Item - Alerts -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter">3+</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts Center
        </h6>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">December 12, 2019</div>
                <span class="font-weight-bold">A new monthly report is ready to download!</span>
            </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-success">
                    <i class="fas fa-donate text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">December 7, 2019</div>
                $290.29 has been deposited into your account!
            </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-warning">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">December 2, 2019</div>
                Spending Alert: We've noticed unusually high spending for your account.
            </div>
        </a>
        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
    </div>
</li>

<!-- Nav Item - Messages -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope fa-fw"></i>
        <!-- Counter - Messages -->
        <span class="badge badge-danger badge-counter">7</span>
    </a>
    <!-- Dropdown - Messages -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header">
            Centro de Mensagens
        </h6>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="img/undraw_profile_1.svg"
                    alt="...">
                <div class="status-indicator bg-success"></div>
            </div>
            <div class="font-weight-bold">
                <div class="text-truncate">Hi there! I am wondering if you can help me with a
                    problem I've been having.</div>
                <div class="small text-gray-500">Emily Fowler · 58m</div>
            </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="img/undraw_profile_2.svg"
                    alt="...">
                <div class="status-indicator"></div>
            </div>
            <div>
                <div class="text-truncate">I have the photos that you ordered last month, how
                    would you like them sent to you?</div>
                <div class="small text-gray-500">Jae Chun · 1d</div>
            </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="img/undraw_profile_3.svg"
                    alt="...">
                <div class="status-indicator bg-warning"></div>
            </div>
            <div>
                <div class="text-truncate">Last month's report looks great, I am very happy with
                    the progress so far, keep up the good work!</div>
                <div class="small text-gray-500">Morgan Alvarez · 2d</div>
            </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                    alt="...">
                <div class="status-indicator bg-success"></div>
            </div>
            <div>
                <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                    told me that people say this to all dogs, even if they aren't good...</div>
                <div class="small text-gray-500">Chicken the Dog · 2w</div>
            </div>
        </a>
        <a class="dropdown-item text-center small text-gray-500" href="/GRNacoes/chats">Leia mais Mensagens</a>
    </div>
</li>

<div class="topbar-divider d-none d-sm-block"></div>

<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nome']; ?></span>
        <img class="img-profile rounded-circle"
            src="<?php echo URL ?>/img/avatar/<?php echo $Usuario->mostrar($_SESSION['id_usuario'])->id_avatar; ?>.png">
    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="userDropdown">
        <a class="dropdown-item" href="<?php echo URL ?>/perfil?id=<?php echo $_SESSION['id_usuario'] ?>">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Perfil
        </a>
        <a class="dropdown-item" data-toggle="modal" data-target="#modalAlterarSenha">
            <i class="fa-solid fa-lock text-gray-400 mr-2"></i> 
            Alterar Senha
        </a>
        <!-- <a class="dropdown-item" href="#">
            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
            Minhas Logs
        </a> -->
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?php echo URL ?>/login">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Sair
        </a>
    </div>
</li>

</ul>

</nav>
<!-- End of Topbar -->
 <!-- Cadastrar Campanha -->
 <div class="modal fade" id="modalAlterarSenha" tabindex="1" role="dialog" aria-labelledby="modalAlterarSenhaLabel" aria-hidden="true">
    <form action="?" method="post">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Senha de Acesso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8 offset-2 text-center d-none mb-3" id="senhasDifentes">
                            <span class="alert alert-danger">As Senhas são diferentes!</span>    
                        </div>
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="col-8 offset-2">
                            <label for="senha_atual" class="form-label">Senha Atual *</label>
                            <input type="password" name="senha_atual" class="form-control" required>
                        </div>
                        <div class="col-8 offset-2 mt-3">
                            <label for="senha" class="form-label">Nova Senha *</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        <div class="col-8 offset-2">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha *</label>
                            <input type="password" id="confirma_senha" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btnAlterar" id="btnAlterar" class="btn btn-success disabled">Alterar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#senha').keyup(function (e) { 
                let senha = $('#senha').val()
                let confirma_senha = $('#confirma_senha').val()
                
                if(senha == confirma_senha){
                    $('#senhasDifentes').addClass('d-none')
                    $('#btnAlterar').removeClass('disabled')
                }else{
                    $('#senhasDifentes').removeClass('d-none')
                    $('#btnAlterar').addClass('disabled')
                }
            });
            $('#confirma_senha').keyup(function (e) { 
                let senha = $('#senha').val()
                let confirma_senha = $('#confirma_senha').val()

                if(senha == confirma_senha){
                    $('#senhasDifentes').addClass('d-none')
                    $('#btnAlterar').removeClass('disabled')
                }else{
                    $('#senhasDifentes').removeClass('d-none')
                    $('#btnAlterar').addClass('disabled')
                }
            });
        });
    </script>

