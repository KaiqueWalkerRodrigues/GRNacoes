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
<script>
    function manterOnline() {
        var id_usuario = <?php echo json_encode($_SESSION['id_usuario']); ?>;
        $.ajax({
            type: "get",
            url: "/GRNacoes/manter_online.php",
            data: { id_usuario: id_usuario },
        });
    }
setInterval(manterOnline, 1000);
</script>