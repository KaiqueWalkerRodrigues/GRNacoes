<?php

class Compras_Notas {
    
    # ATRIBUTOS
    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao(); // Supondo que Conexao seja uma classe que estabelece a conexão com o banco de dados
    }

    public function listar()
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_notas WHERE deleted_at IS NULL ORDER BY data DESC');
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }

    /**
     * Cadastra uma nova nota de compra
     * @param array $dados
     * @return int id - da nota de compra
     */
    public function cadastrar(array $dados)
    {
        $id_fornecedor = $dados['id_fornecedor'];
        $n_nota = $dados['n_nota'];
        $data = $dados['data'];
        $valor = $dados['valor'];
        $quantidade = $dados['quantidade'];
        $id_empresa = $dados['id_empresa'];
        $descricao = $dados['descricao'];
        $agora = date("Y-m-d H:i:s");
        $usuario_logado = $dados['usuario_logado'];

        $sql = $this->pdo->prepare('INSERT INTO compras_notas 
                                    (id_fornecedor,valor, quantidade, descricao, n_nota, data, id_empresa, created_at, updated_at)
                                    VALUES
                                    (:id_fornecedor,:valor, :quantidade, :descricao, :n_nota, :data, :id_empresa, :created_at, :updated_at)
                                ');

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':n_nota', $n_nota);
        $sql->bindParam(':data', $data);
        $sql->bindParam(':valor', $valor);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':quantidade', $quantidade);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':created_at', $created_at);
        $sql->bindParam(':updated_at', $updated_at);

        if ($sql->execute()) {
            // Registra o log
            $id_nota = $this->pdo->lastInsertId();
            $descricao = "Cadastrou a nota de compra: $n_nota ($id_nota)";
            $this->addlog('Cadastrar', $usuario_logado, $descricao);
            return header('location:/GRNacoes/compras/notas');
        } else {
            // Tratar falha na execução da query, se necessário
            return false;
        }
    }

    /**
     * Retorna os dados de uma nota de compra
     * @param int $id_nota
     * @return object
     */
    public function mostrar(int $id_nota)
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_notas WHERE id_compra_nota = :id_nota LIMIT 1');
        $sql->bindParam(':id_nota', $id_nota);
        $sql->execute();

        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza uma nota de compra
     * @param array $dados
     * @return bool
     */
    public function editar(array $dados)
    {
        $id_nota = $dados['id_compra_nota'];
        $id_fornecedor = $dados['id_fornecedor'];
        $n_nota = $dados['n_nota'];
        $valor = $dados['valor'];
        $data = $dados['data'];
        $id_empresa = $dados['id_empresa'];
        $descricao = $dados['descricao'];
        $updated_at = date("Y-m-d H:i:s");
        $quantidade = $dados['quantidade'];
        $usuario_logado = $dados['usuario_logado'];

        $sql = $this->pdo->prepare("UPDATE compras_notas SET
            id_fornecedor = :id_fornecedor,
            n_nota = :n_nota,
            valor = :valor,
            descricao = :descricao,
            quantidade = :quantidade,
            data = :data,
            id_empresa = :id_empresa,
            updated_at = :updated_at 
        WHERE id_compra_nota = :id_nota
        ");


        $sql->bindParam(':id_nota', $id_nota);
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':n_nota', $n_nota);
        $sql->bindParam(':data', $data);
        $sql->bindParam(':quantidade', $quantidade);
        $sql->bindParam(':valor', $valor);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':updated_at', $updated_at);

        if ($sql->execute()) {
            // Registra o log
            $descricao = "Editou a nota de compra: $n_nota ($id_nota)";
            $this->addlog('Editar', $usuario_logado, $descricao);
            return header('location:/GRNacoes/compras/notas');
        } else {
            // Tratar falha na execução da query, se necessário
            return false;
        }
    }

    /**
     * Desativa uma nota de compra
     * @param integer $id_nota
     * @return bool
     */
    public function desativar(int $id_nota,$usuario_logado)
    {
        $sql = $this->pdo->prepare('UPDATE compras_notas SET deleted_at = NOW() WHERE id_compra_nota = :id_nota');
        $sql->bindParam(':id_nota', $id_nota);
        

        if ($sql->execute()) {
             // Registra o log
             $descricao = "Desativou a nota de compra ($id_nota)";
             $this->addlog('Desativar', $usuario_logado, $descricao);
             return header('location:/GRNacoes/compras/notas');
        } else {
            // Tratar falha na execução da query, se necessário
            return false;
        }
    }

    public function addlog(string $acao, int $id_usuario, string $descricao)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare('INSERT INTO logs (acao, id_usuario, descricao, data) VALUES (:acao, :id_usuario, :descricao, :data)');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->execute();
    }

    public function totalCategoria($id_empresa, $id_categoria, $mes, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(valor) AS total FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_empresa = :id_empresa 
                                    AND id_categoria = :id_categoria 
                                    AND month(data) = :mes
                                    AND year(data) = :ano');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':mes', $mes);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalCategoriaAnual($id_empresa, $id_categoria, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(valor) AS total FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_empresa = :id_empresa 
                                    AND id_categoria = :id_categoria 
                                    AND year(data) = :ano');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalMes($id_empresa, $mes, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(valor) AS total FROM compras_notas 
                                        INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                        INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                        WHERE id_empresa = :id_empresa
                                        AND month(data) = :mes
                                        AND year(data) = :ano');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':mes', $mes);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalEmpresa($id_empresa, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(valor) AS total FROM compras_notas 
                                        INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                        INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                        WHERE id_empresa = :id_empresa
                                        AND year(data) = :ano');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalNotasCategoria($id_empresa,$id_categoria, $ano)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total_notas FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_compra_categoria = :id_categoria
                                    AND id_empresa = :id_empresa
                                    AND YEAR(data) = :ano');

        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total_notas'];
    }

    public function totalClinicas(){
        $sql = $this->pdo->prepare('SELECT sum(valor) as total_clinica FROM compras_notas WHERE id_empresa = 1 OR id_empresa = 3 OR id_empresa = 5');
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total_clinica'];
    }

    public function totalOticas(){
        $sql = $this->pdo->prepare('SELECT sum(valor) as total_otica FROM compras_notas WHERE id_empresa = 2 OR id_empresa = 4 OR id_empresa = 6');
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total_otica'];
    }

    /**
     * Totaliza o valor das notas de um fornecedor em uma categoria específica por mês
     * @param int $empresa_id
     * @param int $id_fornecedor
     * @param int $id_categoria
     * @param int $mes
     * @param int $ano
     * @return float
     */
    public function totalFornecedorCategoria($empresa_id, $id_fornecedor, $id_categoria, $mes, $ano){
        $sql = $this->pdo->prepare('
            SELECT SUM(cn.valor) AS total 
            FROM compras_notas cn 
            WHERE cn.id_fornecedor = :id_fornecedor 
            AND cn.id_categoria = :id_categoria 
            AND cn.empresa_id = :empresa_id 
            AND MONTH(cn.data) = :mes 
            AND YEAR(cn.data) = :ano
        ');
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':empresa_id', $empresa_id);
        $sql->bindParam(':mes', $mes);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    /**
     * Totaliza o valor das notas de um fornecedor em uma categoria específica no ano
     * @param int $empresa_id
     * @param int $id_fornecedor
     * @param int $id_categoria
     * @param int $ano
     * @return float
     */
    public function totalFornecedorAnual($empresa_id, $id_fornecedor, $id_categoria, $ano){
        $sql = $this->pdo->prepare('
            SELECT SUM(cn.valor) AS total 
            FROM compras_notas cn 
            WHERE cn.id_fornecedor = :id_fornecedor 
            AND cn.id_categoria = :id_categoria 
            AND cn.empresa_id = :empresa_id 
            AND YEAR(cn.data) = :ano
        ');
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':empresa_id', $empresa_id);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalFornecedorMes($id_empresa,$id_fornecedor,$mes){
        $sql = $this->pdo->prepare('SELECT sum(valor) AS total
        FROM compras_notas 
        WHERE id_fornecedor = :id_fornecedor
        AND id_empresa = :id_empresa
        AND MONTH(data) = :mes
        ');

        $sql->bindParam(':id_fornecedor',$id_fornecedor);
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':mes',$mes);

        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

}
?>
