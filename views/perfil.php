<?php 
    $Usuario = new Usuario();
    $Setor = new Setor();
    $Cargo = new Cargo();
    
    if(isset($_POST['btnAvatar'])){
        $Usuario->editarAvatar($_POST['id_usuario'],$_POST['id_avatar']);
    }

    $id = $_SESSION['id_usuario'];
    
    $usuario = $Usuario->mostrar($id);
    $setor = $Setor->mostrar($_SESSION['id_setor']);
    $cargo = $Cargo->mostrar($usuario->id_cargo);
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
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="nav-fixed">
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include_once('resources/sidebar.php') ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                    <span>Perfil</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10 text-dark p-3 card">

                        <div class="row">
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">Nome Completo</label>
                                <input type="text" disabled class="form-control" value="<?php echo $usuario->nome ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">N° Folha</label>
                                <input type="text" disabled class="form-control" value="<?php echo $usuario->n_folha ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">Email cadastrado</label>
                                <input type="text" disabled class="form-control" value="<?php echo $usuario->email ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">Cargo</label>
                                <input type="text" disabled class="form-control" value="<?php echo $cargo->cargo ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">Data de Admissão</label>
                                <input type="text" disabled class="form-control" value="<?php echo Helper::converterData($usuario->data_admissao) ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <?php $celular_formatado = preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2 $3-$4', $usuario->celular); ?>
                                <label for="" class="form-label">Celular</label>
                                <input type="text" disabled class="form-control" value="<?php echo $celular_formatado; ?>">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="" class="form-label">Setor</label>
                                <input type="text" disabled class="form-control" value="<?php echo $setor->setor ?>">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-4">
                                <label for="" class="form-label">Usuário</label>
                                <input type="text" disabled class="form-control" value="<?php echo $usuario->usuario ?>">
                                <img style="width: 150px;" src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $_SESSION['id_avatar'] ?>.png" alt="">
                            </div>
                            <div class="col-4 mt-2">
                                <br>
                                <button class="btn btn-dark" data-toggle="modal" data-target="#modalAvatar" class="collapse-item">Alterar Foto</button>
                            </div>
                        </div>

                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>

        <!-- Alterar Avatar -->
        <div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="modalAvatarLabel" aria-hidden="true">
            <form action="?" method="post">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <h5 class="modal-title">Escolha um avatar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <?php for($x = 0;$x < 19;$x++){ ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="id_avatar" id="inlineRadio<?php echo $x ?>" value="<?php echo $x ?>">
                                    <label class="form-check-label" for="inlineRadio<?php echo $x ?>">
                                        <img src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $x ?>.png" style="width: 100px" alt="">
                                    </label>
                                </div>
                        <?php } ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" name="btnAvatar">Alterar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>

</html>