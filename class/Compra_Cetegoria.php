<?php

class Compra_Categoria {

    # ATRIBUTOS	
    public $pdo;
    
    // Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    // Registrar Logs(Ações) do Sistema.
    private function addLog($acao, $descricao, $id_usuario){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario)
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)
                                ');

        $sql->bindParam(':acao', $acao); 
        $sql->bindParam(':id_usuario', $id_usuario); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':data', $agora); 
        $sql->execute();
    }

    /**
     * listar todas as categorias de compras
     * @return array
     * @example $variavel = $Obj->listar();
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM compras_categorias WHERE deleted_at IS NULL ORDER BY categoria');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    /**
     * cadastra uma nova categoria de compra
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(Array $dados){
        $categoria = ucwords(strtolower(trim($dados['categoria'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO compras_categorias 
                                    (categoria, created_at, updated_at)
                                    VALUES
                                    (:categoria, :created_at, :updated_at)
                                ');

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':categoria', $categoria);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $categoria_id = $this->pdo->lastInsertId();
            $descricao = "Cadastrou a categoria de compra: $categoria ($categoria_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Categoria Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar a Categoria!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma categoria de compra
     * @param int $id_categoria
     * @return object
     * @example $variavel = $Obj->mostrar($id_categoria);
     */
    public function mostrar(int $id_categoria)
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_categorias WHERE id_compra_categoria = :id_categoria LIMIT 1');
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->execute();

        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza uma determinada categoria de compra
     * @param array $dados   
     * @return int id - da categoria
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE compras_categorias SET
            categoria = :categoria,
            updated_at = :updated_at 
        WHERE id_compra_categoria = :id_categoria
        ");

        $agora = date("Y-m-d H:i:s");

        $id_categoria = $dados['id_compra_categoria'];
        $categoria = ucwords(strtolower(trim($dados['categoria'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_categoria',$id_categoria);
        $sql->bindParam(':categoria',$categoria);
        $sql->bindParam(':updated_at', $updated_at);   
        
        if ($sql->execute()) {
            $descricao = "Editou a categoria de compra: $categoria ($id_categoria)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Categoria Editada com Sucesso!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar a Categoria!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        }
    }

    /**
     * Deleta uma categoria de compra
     * @param integer $id_categoria
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_categoria, $usuario_logado){
        $consulta_categoria = $this->pdo->prepare('SELECT categoria FROM compras_categorias WHERE id_compra_categoria = :id_categoria');
        $consulta_categoria->bindParam(':id_categoria', $id_categoria);
        $consulta_categoria->execute();
        $resultado_categoria = $consulta_categoria->fetch(PDO::FETCH_ASSOC);

        $nome_categoria = $resultado_categoria ? $resultado_categoria['categoria'] : "Categoria Desconhecida";

        $sql = $this->pdo->prepare('UPDATE compras_categorias SET deleted_at = :deleted_at WHERE id_compra_categoria = :id_categoria');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_categoria', $id_categoria);

        if ($sql->execute()) {
            $descricao = "Deletou a categoria de compra $nome_categoria ($id_categoria)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Categoria Deletada com Sucesso!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Categoria!');
                window.location.href = '" . URL . "/compras/configuracoes/categorias';
            </script>";
            exit;
        }
    }

    /**
     * Retorna o nome de uma categoria de compra
     * @param int $id_categoria
     * @return string
     */
    public function nomeCategoria(int $id_categoria){
        $sql = $this->pdo->prepare('SELECT categoria FROM compras_categorias WHERE id_compra_categoria = :id_categoria');
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->execute();

        $categoria = $sql->fetch(PDO::FETCH_OBJ);
    
        return $categoria->categoria;
    }

    /**
     * Conta a quantidade de fornecedores cadastrados em uma categoria
     * @param int $id_categoria
     * @return int
     */
    public function contarFornecedores(int $id_categoria){
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM compras_fornecedores WHERE id_categoria = :id_categoria');
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->execute();
        
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        
        return (int) $resultado->total;
    }
}
?>
