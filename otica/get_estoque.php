<?php
include_once('../const.php');  

$Otica_Estoque = new Otica_Estoque();
$Compras_Fornecedores = new Compras_Fornecedores();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mes_ano'])) {
    $mes_ano = $_POST['mes_ano'];
    $fornecedores = $Compras_Fornecedores->listarFornecedoresDeArmacoes();
    
    foreach ($fornecedores as $fornecedor) {
        $estoque = $Otica_Estoque->mostrar($mes_ano, $fornecedor->id_compra_fornecedor);
        $quantidade = $estoque ? $estoque->quantidade : 0; // Se não houver estoque, mostrar 0
        echo "<tr>
                <td>{$fornecedor->fornecedor}</td>
                <td>{$quantidade}</td>
                <td class='mes_ano text-center'>{$mes_ano}</td>
                <td class='text-center'><button class='btn btn-primary'><i class='fa-solid fa-receipt'></i></button></td>
              </tr>";
    }
}
?>
