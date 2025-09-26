<?php 
    $Usuario = new Usuario();

    if(isset($_POST['btnAcessar'])){
        $Usuario->logar($_POST['usuario'],$_POST['senha']);
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
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center">
                                        <h3 class="font-weight-light my-4"><b>GRNacoes</b>
                                            <img src="<?php echo URL_RESOURCES ?>/img/icone_clinica.png" style="width: 40px; margin-left: 5px;" alt="">
                                            +
                                            <img src="<?php echo URL_RESOURCES ?>/img/icone_otica.png" style="width: 80px;" alt="">
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="?">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputUsuario">Usuário</label>
                                                <input class="form-control py-4" id="inputUsuario" type="text" placeholder="Usuario" name="usuario" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputSenha">Senha</label>
                                                <input class="form-control py-4" id="inputSenha" type="password" placeholder="Senha" name="senha" />
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                                    <label class="custom-control-label" for="rememberPasswordCheck">Lembrar Senha</label>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="#">Esqueceu a senha?</a>
                                                <a class="small text-success" href="<?php echo URL ?>/solicitacao">Cadastrar-se</a>
                                                <button type="submit" class="btn btn-primary" name="btnAcessar">Acessar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    </body>
</html>
