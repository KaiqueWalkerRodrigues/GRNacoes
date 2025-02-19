<?php

class Compra_Nota {
    
    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    private function addlog(string $acao, int $id_usuario, string $descricao)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare('INSERT INTO logs (acao, id_usuario, descricao, data) VALUES (:acao, :id_usuario, :descricao, :data)');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->execute();
    }

    public function listar()
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_notas WHERE deleted_at IS NULL ORDER BY data DESC');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    // public function cadastrar(array $dados)
    // {
    //     $id_fornecedor = $dados['id_fornecedor'];
    //     $n_nota = $dados['n_nota'];
    //     $data = $dados['data'];
    //     $valor = $dados['valor'];
    //     $quantidade = $dados['quantidade'];
    //     $id_empresa = $dados['id_empresa'];
    //     $usuario_logado = $dados['usuario_logado'];
    //     $agora = date("Y-m-d H:i:s");

    //     try {
    //         $sql = $this->pdo->prepare('INSERT INTO compras_notas 
    //                                     (id_fornecedor, valor, quantidade, n_nota, data, id_empresa, created_at, updated_at)
    //                                     VALUES
    //                                     (:id_fornecedor, :valor, :quantidade, :n_nota, :data, :id_empresa, :created_at, :updated_at)
    //                                 ');

    //         $sql->bindParam(':id_fornecedor', $id_fornecedor);
    //         $sql->bindParam(':n_nota', $n_nota);
    //         $sql->bindParam(':data', $data);
    //         $sql->bindParam(':valor', $valor);
    //         $sql->bindParam(':id_empresa', $id_empresa);
    //         $sql->bindParam(':quantidade', $quantidade);
    //         $sql->bindParam(':created_at', $agora);
    //         $sql->bindParam(':updated_at', $agora);

    //         $sql->execute();

    //         $id_nota = $this->pdo->lastInsertId();
    //         $descricao_log = "Cadastrou a nota de compra: $n_nota ($id_nota)";
    //         $this->addlog('Cadastrar', $usuario_logado, $descricao_log);

    //         echo "
    //         <script>
    //             alert('Nota de compra cadastrada com sucesso!');
    //             window.location.href = '" . URL . "/compras/notas';
    //         </script>";
    //         exit;

    //     } catch (PDOException $e) {
    //         // Verifica se o erro é de entrada duplicada
    //         if ($e->getCode() == 23000) { // Código SQLSTATE para violação de restrição de integridade
    //             // Opcional: Você pode verificar se a mensagem de erro contém o código específico 1062
    //             if (strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
    //                 echo "
    //                 <script>
    //                     alert('A nota de compra já foi cadastrada!');
    //                     window.location.href = '" . URL . "/compras/notas';
    //                 </script>";
    //                 exit;
    //             }
    //         }

    //         // Para outros tipos de erro, exibe uma mensagem genérica ou específica
    //         echo "
    //         <script>
    //             alert('Não foi possível cadastrar a nota de compra! Erro: " . addslashes($e->getMessage()) . "');
    //             window.location.href = '" . URL . "/compras/notas';
    //         </script>";
    //         exit;
    //     }
    // }

    public function cadastrar(array $dados)
    {
        try {
            $this->pdo->beginTransaction();
            $agora = date("Y-m-d H:i:s");

            // Inserção da nota de compra
            $sql = $this->pdo->prepare('INSERT INTO compras_notas 
                (id_fornecedor, valor, n_nota, data, id_empresa, created_at, updated_at)
                VALUES
                (:id_fornecedor, :valor, :n_nota, :data, :id_empresa, :created_at, :updated_at)');
            $sql->execute([
                ':id_fornecedor' => $dados['id_fornecedor'],
                ':valor'         => $dados['valor'],
                ':n_nota'        => $dados['n_nota'],
                ':data'          => $dados['data'],
                ':id_empresa'    => $dados['id_empresa'],
                ':created_at'    => $agora,
                ':updated_at'    => $agora,
            ]);

            $id_nota = $this->pdo->lastInsertId();

            // Monta o array de itens (assumindo que os campos seguem o padrão: item1, quantidade1, valuni1, etc.)
            $itens = [];
            foreach ($dados as $chave => $valor) {
                // Procura as chaves que iniciam com 'item'
                if (preg_match('/^item(\d+)$/', $chave, $matches)) {
                    $indice = $matches[1];
                    $itens[] = [
                        'item'       => $valor,
                        'quantidade' => isset($dados["quantidade{$indice}"]) ? $dados["quantidade{$indice}"] : null,
                        'valor_uni'  => isset($dados["valuni{$indice}"]) ? $dados["valuni{$indice}"] : null,
                        // Se houver um campo para descrição, por exemplo 'descricao1'
                        'descricao'  => isset($dados["descricao{$indice}"]) ? $dados["descricao{$indice}"] : '',
                    ];
                }
            }

            // Inserção dos itens, se houver
            if (!empty($itens)) {
                foreach ($itens as $item) {
                    $sqlItem = $this->pdo->prepare("INSERT INTO compras_notas_itens 
                        (id_nota, item, valor_uni, quantidade, descricao, created_at, updated_at, deleted_at)
                        VALUES (:id_nota, :item, :valor_uni, :quantidade, :descricao, :created_at, :updated_at, :deleted_at)");
                    $sqlItem->execute([
                        ':id_nota'    => $id_nota,
                        ':item'       => $item['item'],
                        ':valor_uni'  => $item['valor_uni'],
                        ':quantidade' => $item['quantidade'],
                        ':descricao'  => $item['descricao'],
                        ':created_at' => $agora,
                        ':updated_at' => $agora,
                        ':deleted_at' => null,
                    ]);
                }
            }

            // Log da operação
            $descricao_log = "Cadastrou a nota de compra: {$dados['n_nota']} ($id_nota)";
            $this->addLog('Cadastrar', $dados['usuario_logado'], $descricao_log);

            $this->pdo->commit();

            echo "<script>
                    alert('Nota de compra cadastrada com sucesso!');
                    window.location.href = '" . URL . "/compras/new_notas';
                </script>";
            exit;

        } catch (PDOException $e) {
            $this->pdo->rollBack();

            if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
                echo "<script>
                        alert('A nota de compra já foi cadastrada!');
                        window.location.href = '" . URL . "/compras/new_notas';
                    </script>";
                exit;
            }

            echo "<script>
                    alert('Não foi possível cadastrar a nota de compra! Erro: " . addslashes($e->getMessage()) . "');
                    window.location.href = '" . URL . "/compras/new_notas';
                </script>";
            exit;
        }
    }

    public function mostrar(int $id_nota)
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_notas WHERE id_compra_nota = :id_nota LIMIT 1');
        $sql->bindParam(':id_nota', $id_nota);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    // public function editar(array $dados)
    // {
    //     $id_nota = $dados['id_compra_nota'];
    //     $id_fornecedor = $dados['id_fornecedor'];
    //     $n_nota = $dados['n_nota'];
    //     $valor = $dados['valor'];
    //     $data = $dados['data'];
    //     $id_empresa = $dados['id_empresa'];
    //     $descricao = $dados['descricao'];
    //     $quantidade = $dados['quantidade'];
    //     $usuario_logado = $dados['usuario_logado'];
    //     $updated_at = date("Y-m-d H:i:s");

    //     try {

    //     $sql = $this->pdo->prepare("UPDATE compras_notas SET
    //         id_fornecedor = :id_fornecedor,
    //         n_nota = :n_nota,
    //         valor = :valor,
    //         descricao = :descricao,
    //         quantidade = :quantidade,
    //         data = :data,
    //         id_empresa = :id_empresa,
    //         updated_at = :updated_at 
    //     WHERE id_compra_nota = :id_nota
    //     ");

    //     $sql->bindParam(':id_nota', $id_nota);
    //     $sql->bindParam(':id_fornecedor', $id_fornecedor);
    //     $sql->bindParam(':n_nota', $n_nota);
    //     $sql->bindParam(':data', $data);
    //     $sql->bindParam(':quantidade', $quantidade);
    //     $sql->bindParam(':valor', $valor);
    //     $sql->bindParam(':id_empresa', $id_empresa);
    //     $sql->bindParam(':descricao', $descricao);
    //     $sql->bindParam(':updated_at', $updated_at);

    //     $sql->execute();
    //     $descricao_log = "Editou a nota de compra: $n_nota ($id_nota)";
    //     $this->addlog('Editar', $usuario_logado, $descricao_log);

    //     echo "
    //     <script>
    //         alert('Nota de compra editada com sucesso!');
    //         window.location.href = '" . URL . "/compras/notas';
    //     </script>";
    //     exit;

    //     } catch (PDOException $e) {

    //         if ($e->getCode() == 23000) { // Código SQLSTATE para violação de restrição de integridade
    //             // Opcional: Você pode verificar se a mensagem de erro contém o código específico 1062
    //             if (strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
    //                 echo "
    //                 <script>
    //                     alert('A nota de compra já foi cadastrada!');
    //                     window.location.href = '" . URL . "/compras/notas';
    //                 </script>";
    //                 exit;
    //             }
    //         }

    //         // Para outros tipos de erro, exibe uma mensagem genérica ou específica
    //         echo "
    //         <script>
    //             alert('Não foi possível editar a nota de compra! Erro: " . addslashes($e->getMessage()) . "');
    //             window.location.href = '" . URL . "/compras/notas';
    //         </script>";
    //         exit;
    //     }
    // }

    public function editar(array $dados)
    {
        $id_nota       = $dados['id_compra_nota'];
        $id_fornecedor = $dados['id_fornecedor'];
        $n_nota        = $dados['n_nota'];
        $valor         = $dados['valor'];
        $data          = $dados['data'];
        $id_empresa    = $dados['id_empresa'];
        $usuario_logado= $dados['usuario_logado'];
        $updated_at    = date("Y-m-d H:i:s");

        try {
            // Inicia a transação
            $this->pdo->beginTransaction();

            // Atualiza a nota principal
            $sql = $this->pdo->prepare("UPDATE compras_notas SET
                id_fornecedor = :id_fornecedor,
                n_nota        = :n_nota,
                valor         = :valor,
                data          = :data,
                id_empresa    = :id_empresa,
                updated_at    = :updated_at 
            WHERE id_compra_nota = :id_nota");
            $sql->execute([
                ':id_fornecedor' => $id_fornecedor,
                ':n_nota'        => $n_nota,
                ':valor'         => $valor,
                ':data'          => $data,
                ':id_empresa'    => $id_empresa,
                ':updated_at'    => $updated_at,
                ':id_nota'       => $id_nota
            ]);

            // Remove os itens antigos da nota para re-inserir os novos itens
            $sqlDelete = $this->pdo->prepare("DELETE FROM compras_notas_itens WHERE id_nota = :id_nota");
            $sqlDelete->execute([':id_nota' => $id_nota]);

            // Monta o array de itens com base nos campos do formulário
            $itens = [];
            foreach ($dados as $chave => $valorCampo) {
                if (preg_match('/^item(\d+)$/', $chave, $matches)) {
                    $indice = $matches[1];
                    $itens[] = [
                        'item'       => $valorCampo,
                        'quantidade' => isset($dados["quantidade{$indice}"]) ? $dados["quantidade{$indice}"] : null,
                        'valor_uni'  => isset($dados["valuni{$indice}"]) ? $dados["valuni{$indice}"] : null,
                        'descricao'  => isset($dados["descricao{$indice}"]) ? $dados["descricao{$indice}"] : '',
                    ];
                }
            }

            $agora = date("Y-m-d H:i:s");

            // Insere os itens novos
            if (!empty($itens)) {
                foreach ($itens as $item) {
                    $sqlItem = $this->pdo->prepare("INSERT INTO compras_notas_itens 
                        (id_nota, item, valor_uni, quantidade, descricao, created_at, updated_at, deleted_at)
                        VALUES (:id_nota, :item, :valor_uni, :quantidade, :descricao, :created_at, :updated_at, :deleted_at)");
                    $sqlItem->execute([
                        ':id_nota'    => $id_nota,
                        ':item'       => $item['item'],
                        ':valor_uni'  => $item['valor_uni'],
                        ':quantidade' => $item['quantidade'],
                        ':descricao'  => $item['descricao'],
                        ':created_at' => $agora,
                        ':updated_at' => $agora,
                        ':deleted_at' => null,
                    ]);
                }
            }

            // Registra o log da operação
            $descricao_log = "Editou a nota de compra: $n_nota ($id_nota)";
            $this->addLog('Editar', $usuario_logado, $descricao_log);

            // Confirma a transação
            $this->pdo->commit();

            echo "<script>
                    alert('Nota de compra editada com sucesso!');
                    window.location.href = '" . URL . "/compras/notas';
                </script>";
            exit;
        } catch (PDOException $e) {
            $this->pdo->rollBack();

            if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062 Duplicate entry') !== false) {
                echo "<script>
                        alert('A nota de compra já foi cadastrada!');
                        window.location.href = '" . URL . "/compras/notas';
                    </script>";
                exit;
            }

            echo "<script>
                    alert('Não foi possível editar a nota de compra! Erro: " . addslashes($e->getMessage()) . "');
                    window.location.href = '" . URL . "/compras/notas';
                </script>";
            exit;
        }
    }

    public function deletar(int $id_nota, $usuario_logado)
    {
        $sql = $this->pdo->prepare('UPDATE compras_notas SET deleted_at = NOW() WHERE id_compra_nota = :id_nota');
        $sql->bindParam(':id_nota', $id_nota);

        if ($sql->execute()) {
            $descricao_log = "Deletou a nota de compra ($id_nota)";
            $this->addlog('Deletar', $usuario_logado, $descricao_log);

            echo "
            <script>
                alert('Nota de compra deletada com sucesso!');
                window.location.href = '" . URL . "/compras/notas';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar a nota de compra!');
                window.location.href = '" . URL . "/compras/notas';
            </script>";
            exit;
        }
    }

    public function totalCategoria($id_empresa, $id_categoria, $mes, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(valor) AS total FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_empresa = :id_empresa 
                                    AND id_categoria = :id_categoria 
                                    AND month(data) = :mes
                                    AND year(data) = :ano
                                    AND compras_notas.deleted_at IS NULL');

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
                                    AND year(data) = :ano
                                    AND compras_notas.deleted_at IS NULL');

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
                                        AND year(data) = :ano
                                        AND compras_notas.deleted_at IS NULL');

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
                                        AND year(data) = :ano
                                        AND compras_notas.deleted_at IS NULL');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }

    public function totalNotasCategoria($id_empresa, $id_categoria, $ano)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total_notas FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_compra_categoria = :id_categoria
                                    AND id_empresa = :id_empresa
                                    AND YEAR(data) = :ano
                                    AND compras_notas.deleted_at IS NULL');

        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_notas'];
    }

    public function totalClinicas($ano)
    {
        $sql = $this->pdo->prepare('
            SELECT SUM(valor) AS total_clinica 
            FROM compras_notas 
            WHERE id_empresa IN (1, 3, 5) AND YEAR(data) = :ano AND deleted_at IS NULL
        ');
        $sql->bindValue(':ano', $ano, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_clinica'] ?? 0; // Retorna 0 se o resultado for nulo
    }

    public function totalOticas($ano)
    {
        $sql = $this->pdo->prepare('
            SELECT SUM(valor) AS total_otica 
            FROM compras_notas 
            WHERE id_empresa IN (2, 4, 6) AND YEAR(data) = :ano AND deleted_at IS NULL
        ');
        $sql->bindValue(':ano', $ano, PDO::PARAM_INT);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_otica'] ?? 0; // Retorna 0 se o resultado for nulo
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
            AND deleted_at IS NULL
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
     * @param int $id_empresa
     * @param int $id_fornecedor
     * @param int $ano
     * @return float
     */
    public function totalFornecedorAnual($id_empresa, $id_fornecedor, $ano){
        $sql = $this->pdo->prepare('
            SELECT SUM(cn.valor) AS total 
            FROM compras_notas cn 
            WHERE cn.id_fornecedor = :id_fornecedor 
            AND cn.id_empresa = :id_empresa 
            AND YEAR(cn.data) = :ano
            AND deleted_at IS NULL
        ');
        $sql->bindParam(':id_fornecedor', $id_fornecedor);
        $sql->bindParam(':id_empresa', $id_empresa);
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
        AND deleted_at IS NULL
        ');

        $sql->bindParam(':id_fornecedor',$id_fornecedor);
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':mes',$mes);

        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalQntdCategoria($id_empresa, $id_categoria, $mes, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(quantidade) AS total FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_empresa = :id_empresa 
                                    AND id_categoria = :id_categoria 
                                    AND month(data) = :mes
                                    AND year(data) = :ano
                                    AND compras_notas.deleted_at IS NULL');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':mes', $mes);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalQntdCategoriaAnual($id_empresa, $id_categoria, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(quantidade) AS total FROM compras_notas 
                                    INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                    INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                    WHERE id_empresa = :id_empresa 
                                    AND id_categoria = :id_categoria 
                                    AND year(data) = :ano
                                    AND deleted_at IS NULL');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':id_categoria', $id_categoria);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }

    public function totalQntdFornecedorMes($id_empresa,$id_fornecedor,$mes){
        $sql = $this->pdo->prepare('SELECT sum(quantidade) AS total
        FROM compras_notas 
        WHERE id_fornecedor = :id_fornecedor
        AND id_empresa = :id_empresa
        AND MONTH(data) = :mes
        AND deleted_at IS NULL
        ');

        $sql->bindParam(':id_fornecedor',$id_fornecedor);
        $sql->bindParam(':id_empresa',$id_empresa);
        $sql->bindParam(':mes',$mes);

        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado ? $resultado->total : 0;
    }

    public function totalQntdMes($id_empresa, $mes, $ano)
    {
        $sql = $this->pdo->prepare('SELECT SUM(quantidade) AS total FROM compras_notas 
                                        INNER JOIN compras_fornecedores ON compras_notas.id_fornecedor = compras_fornecedores.id_compra_fornecedor 
                                        INNER JOIN compras_categorias ON compras_fornecedores.id_categoria = compras_categorias.id_compra_categoria 
                                        WHERE id_empresa = :id_empresa
                                        AND month(data) = :mes
                                        AND year(data) = :ano
                                        AND compras_notas.deleted_at IS NULL');

        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->bindParam(':mes', $mes);
        $sql->bindParam(':ano', $ano);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }
}
?>
