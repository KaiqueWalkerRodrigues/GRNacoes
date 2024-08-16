<?php 
include_once('../const.php');

$Usuario = new Usuario();

if (isset($_GET['id_empresa'])) {
    $idEmpresa = $_GET['id_empresa'];

    if ($idEmpresa) {
        $vendedores = $Usuario->ListarVendedores($idEmpresa);
    } else {
        $vendedores = $Usuario->ListarVendedores();
    }

    foreach ($vendedores as $vendedor) {
        echo '<option value="' . $vendedor->id_usuario . '">' . $vendedor->nome . '</option>';
    }
}
?>
