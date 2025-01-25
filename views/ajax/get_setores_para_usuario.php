<?php

if (!isset($_GET['id_usuario']) || !is_numeric($_GET['id_usuario'])) {
    die("ID de usuário inválido.");
}

$id_usuario = (int) $_GET['id_usuario'];

require_once '../../const.php';

$Setor = new Setor();

$pdo = Conexao::conexao();

$sql = "
    SELECT 
        s.id_setor, 
        s.setor, 
        us.id_usuario,
        us.principal
    FROM 
        setores s
    LEFT JOIN 
        usuarios_setores us 
        ON s.id_setor = us.id_setor 
        AND us.id_usuario = :id_usuario 
        AND us.deleted_at IS NULL
    WHERE 
        s.deleted_at IS NULL
    ORDER BY 
        s.setor ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$setores = $stmt->fetchAll(PDO::FETCH_OBJ);

$x = 0;
foreach($setores as $setor){
    // Verifica se o usuário possui o setor (us.id_usuario não é nulo)
    $checked = $setor->id_usuario ? 'checked' : '';

    // Verifica se o setor é principal
    $disabled = ($setor->principal) ? 'disabled' : '';

    // Escapa o nome do setor para evitar problemas de XSS
    $nome_setor = htmlspecialchars($setor->setor, ENT_QUOTES, 'UTF-8');

    echo "
        <div class='custom-control custom-checkbox custom-control-solid'>
            <input 
                class='custom-control-input' 
                value='{$setor->id_setor}' 
                id='setor$x' 
                type='checkbox' 
                {$checked} 
                {$disabled}
            > 
            <label class='custom-control-label' for='setor$x'>{$nome_setor}</label>
        </div>
    ";
    $x++;
}
?>
