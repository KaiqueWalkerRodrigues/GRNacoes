<?php 
    const URL = "/GRNacoes";

    include_once(__DIR__."/class/classes.php");

    Helper::logado();

    $Usuario = new Usuario();

    if(isset($_POST['btnAlterar'])){
        $Usuario->alterarSenha($_POST);
    }
?>
<link rel="shortcut icon" href="/GRNacoes/img/logo.ico" type="image/x-icon">