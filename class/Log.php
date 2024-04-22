<?php

class Log {

    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar()
    {
        $sql = $this->pdo->prepare('SELECT * FROM logs');
        $sql->execute();
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        return $dados;
    }

}
?>
