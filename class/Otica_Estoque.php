<?php

class Otica_Estoque {

    # ATRIBUTOS	
    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * Lista todos os registros de estoque
     * @return array
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM estoque WHERE deleted_at IS NULL ORDER BY mes_ano');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }

    /**
     * Cadastra um novo registro de estoque
     * @param Array $dados    
     * @return int
     */
    public function cadastrar(Array $dados)
    {
        $id_fornecedor = $dados['id_fornecedor'];
        $mes_ano = $dados['mes_ano'];
        $quantidade = $dados['quantidade'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO estoque 
                                    (id_fornecedor, mes_ano, quantidade, created_at, updated_at)
                                    VALUES
                                    (:id_fornecedor, :mes_ano, :quantidade, :created_at, :updated_at)
                                ');

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':id_fornecedor', $id_fornecedor);          
        $sql->bindParam(':mes_ano', $mes_ano);
        $sql->bindParam(':quantidade', $quantidade);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);     

        if ($sql->execute()) {
            $estoque_id = $this->pdo->lastInsertId();

            return $estoque_id;
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }


    /**
     * Retorna os dados de um registro de estoque
     * @param int $id_otica_estoque
     * @return object
     */
    public function mostrarPorFornecedor($mes_ano,$id_fornecedor)
    {
        $sql = $this->pdo->prepare('SELECT * FROM estoque WHERE mes_ano = :mes_ano AND id_fornecedor = :id_fornecedor LIMIT 1');
        $sql->bindParam(':mes_ano', $mes_ano);
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->execute();

        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado registro de estoque
     * @param array $dados   
     * @return int
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE estoque SET
            id_fornecedor = :id_fornecedor,
            mes_ano = :mes_ano,
            quantidade = :quantidade,
            updated_at = :updated_at 
        WHERE id_otica_estoque = :id_otica_estoque
        ");

        $agora = date("Y-m-d H:i:s");

        $id_otica_estoque = $dados['id_otica_estoque'];
        $id_fornecedor = $dados['id_fornecedor'];
        $mes_ano = $dados['mes_ano'];
        $quantidade = $dados['quantidade'];
        $updated_at = $agora; 

        $sql->bindParam(':id_otica_estoque', $id_otica_estoque);
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':mes_ano', $mes_ano);
        $sql->bindParam(':quantidade', $quantidade);
        $sql->bindParam(':updated_at', $updated_at);  
        
        if ($sql->execute()) {
            return true;
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }


    /**
     * Desativa um registro de estoque
     * @param integer $id_otica_estoque
     * @return void
     */
    public function desativar(int $id_otica_estoque)
    {
        $sql = $this->pdo->prepare('UPDATE estoque SET deleted_at = :deleted_at WHERE id_otica_estoque = :id_otica_estoque');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_otica_estoque', $id_otica_estoque);

        if ($sql->execute()) {
            return true;
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }
}
?>
