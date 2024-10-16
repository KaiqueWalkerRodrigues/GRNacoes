<?php

class Compras_Fornecedores {

    # ATRIBUTOS	
    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * Lista todos os fornecedores de compras
     * @return array
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM compras_fornecedores WHERE deleted_at IS NULL ORDER BY fornecedor');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }   

    /**
     * Lista todos os fornecedores de compras com categoria armações
     * @return array
     */
    public function listarFornecedoresDeArmacoes(){
        $sql = $this->pdo->prepare('SELECT * FROM compras_fornecedores WHERE id_categoria = 5 AND deleted_at IS NULL ORDER BY fornecedor');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    /**
     * Cadastra um novo fornecedor de compra
     * @param Array $dados    
     * @return int
     */
    public function cadastrar(Array $dados)
    {
        $fornecedor = ucwords(strtolower(trim($dados['fornecedor'])));
        $usuario_logado = $dados['usuario_logado'];
        $id_categoria = $dados['id_categoria'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO compras_fornecedores 
                                    (fornecedor, id_categoria, created_at, updated_at)
                                    VALUES
                                    (:fornecedor, :id_categoria, :created_at, :updated_at)
                                ');

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':fornecedor', $fornecedor);          
        $sql->bindParam(':id_categoria', $id_categoria);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);     

        if ($sql->execute()) {
            $fornecedor_id = $this->pdo->lastInsertId();

            $descricao = "Cadastrou o fornecedor de compra: $fornecedor ($fornecedor_id)";
            
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $usuario_logado); 
            $sql->bindParam(':descricao', $descricao); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('Location:/GRNacoes/compras/fornecedores');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    /**
     * Retorna os dados de um fornecedor de compra
     * @param int $id_fornecedor
     * @return object
     */
    public function mostrar(int $id_fornecedor)
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_fornecedores WHERE id_compra_fornecedor = :id_fornecedor LIMIT 1');
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->execute();

        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado fornecedor de compra
     * @param array $dados   
     * @return int id - do fornecedor
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE compras_fornecedores SET
            fornecedor = :fornecedor,
            id_categoria = :id_categoria,
            updated_at = :updated_at 
        WHERE id_compra_fornecedor = :id_fornecedor
        ");

        $agora = date("Y-m-d H:i:s");

        $id_fornecedor = $dados['id_compra_fornecedor'];
        $fornecedor = ucwords(strtolower(trim($dados['fornecedor'])));
        $updated_at = $agora; 
        $id_categoria = $dados['id_categoria'];
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_fornecedor',$id_fornecedor);
        $sql->bindParam(':fornecedor',$fornecedor);
        $sql->bindParam(':id_categoria',$id_categoria);
        $sql->bindParam(':updated_at', $updated_at);  
        
        
        if ($sql->execute()) {
            $descricao = "Editou o fornecedor de compra: $fornecedor ($id_fornecedor)";
            
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $usuario_logado); 
            $sql->bindParam(':descricao', $descricao); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();

            return header('Location:/GRNacoes/compras/fornecedores');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    /**
     * Desativa um fornecedor de compra
     * @param integer $id_fornecedor
     * @param integer $usuario_logado
     * @return void
     */
    public function desativar(int $id_fornecedor, $usuario_logado)
    {
        $consulta_fornecedor = $this->pdo->prepare('SELECT fornecedor FROM compras_fornecedores WHERE id_compra_fornecedor = :id_fornecedor');
        $consulta_fornecedor->bindParam(':id_fornecedor', $id_fornecedor);
        $consulta_fornecedor->execute();
        $resultado_fornecedor = $consulta_fornecedor->fetch(PDO::FETCH_ASSOC);

        if ($resultado_fornecedor) {
            $nome_fornecedor = $resultado_fornecedor['fornecedor'];
        } else {
            $nome_fornecedor = "Fornecedor Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE compras_fornecedores SET deleted_at = :deleted_at WHERE id_compra_fornecedor = :id_fornecedor');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_fornecedor', $id_fornecedor);

        if ($sql->execute()) {
            $descricao = "Desativou o fornecedor de compra $nome_fornecedor ($id_fornecedor)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $usuario_logado);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('Location:/GRNacoes/compras/fornecedores');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    /**
     * Retorna o nome de um fornecedor de compra
     * @param int $id_fornecedor
     * @return string
     */
    public function nomeFornecedor(int $id_fornecedor)
    {
        $sql = $this->pdo->prepare('SELECT fornecedor FROM compras_fornecedores WHERE id_compra_fornecedor = :id_fornecedor');
        $sql->bindParam(':id_fornecedor',$id_fornecedor);
        $sql->execute();

        $fornecedor = $sql->fetch(PDO::FETCH_OBJ);
    
        return $fornecedor->fornecedor;
    }

    public function nomeCategoria(int $id_fornecedor)
    {
        $sql = $this->pdo->prepare('SELECT id_categoria FROM compras_fornecedores WHERE id_compra_fornecedor = :id_fornecedor');
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->execute();

        $fornecedor = $sql->fetch(PDO::FETCH_OBJ);

        // Verificando se o fornecedor foi encontrado
        if ($fornecedor) {
            $sql = $this->pdo->prepare('SELECT categoria FROM compras_categorias WHERE id_compra_categoria = :id_categoria');
            $sql->bindParam(':id_categoria', $fornecedor->id_categoria);
            $sql->execute();

            $categoria = $sql->fetch(PDO::FETCH_OBJ);

            // Verificando se a categoria foi encontrada
            if ($categoria) {
                return $categoria->categoria;
            } else {
                return "Categoria não encontrada";
            }
        } else {
            return "Fornecedor não encontrado";
        }
    }

    public function listarPorCategoria($id_empresa, $id_categoria, $ano){
        $sql = $this->pdo->prepare('
            SELECT DISTINCT cf.* 
            FROM compras_fornecedores cf
            INNER JOIN compras_notas cn ON cf.id_compra_fornecedor = cn.id_fornecedor
            WHERE cf.id_categoria = :id_categoria 
            AND cn.id_empresa = :id_empresa 
            AND YEAR(cn.data) = :ano 
            AND cf.deleted_at IS NULL
            ORDER BY cf.fornecedor
        ');        
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }
    
    public function listarTudoPorCategoria($id_empresa, $id_categoria, $ano){
        $sql = $this->pdo->prepare('
            SELECT DISTINCT cf.* 
            FROM compras_fornecedores cf
            LEFT JOIN compras_notas cn ON cf.id_compra_fornecedor = cn.id_fornecedor 
                AND cn.id_empresa = :id_empresa 
                AND YEAR(cn.data) = :ano
            WHERE cf.id_categoria = :id_categoria 
            AND cf.deleted_at IS NULL
            ORDER BY cf.fornecedor
        ');        
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }

    /**
     * Conta o número de notas associadas a um fornecedor
     * @param int $id_fornecedor
     * @return int
     */
    public function contarNotasPorFornecedor(int $id_fornecedor)
    {
        $sql = $this->pdo->prepare('
            SELECT COUNT(*) as total_notas 
            FROM compras_notas 
            WHERE id_fornecedor = :id_fornecedor
        ');

        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total_notas : 0;
    }


}

?>
