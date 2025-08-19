<?php

class Compra_Nota_Item {

    # ATRIBUTOS	
	public $pdo;
    
    //Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * listar todos os items da nota
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listarDaNota($id_nota){
        $sql = $this->pdo->prepare('SELECT * FROM compras_notas_itens WHERE id_nota = :id_nota AND deleted_at IS NULL');        
        $sql->bindParam(":id_nota",$id_nota);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      
 }

?>