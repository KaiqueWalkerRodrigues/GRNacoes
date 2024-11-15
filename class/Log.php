<?php

class Log {

    public $pdo;
    
    //Construir Conexão com o Banco de Dados.
    public function __construct(){
        $this->pdo = Conexao::conexao();               
    }

    //Listar Logs do Sistema
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM logs');
        $sql->execute();
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        return $dados;
    }

}
?>
