<?php 

try{
    $pdo = new PDO("mysql:dbname=GRNacoes;host=192.168.0.60","sistema","@ADgrn1987");
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}