<?php 

try{
    $pdo = new PDO("mysql:dbname=GRNacoes;host=192.168.0.60","sistema","GRNacoes@1234");
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}