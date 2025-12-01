<?php

class Callcenter_Bloco_Notas
{

    # ATRIBUTOS
    public $pdo;

    //Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        // Assume-se que a classe Conexao e o método conexao() estão definidos no ambiente
        $this->pdo = Conexao::conexao();
    }

    //Registrar Logs(Ações) do Sistema.
    private function addLog($acao, $descricao, $id_usuario)
    {
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
     * listar todos os blocos de notas ativos.
     * Inclui o nome dos setores associados (apenas o primeiro setor para simplificação).
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar()
    {
        // Consulta para listar blocos de notas e o nome do primeiro setor associado (GROUP_CONCAT para todos os setores)
        $sql = $this->pdo->prepare('
            SELECT
                cbn.*,
                GROUP_CONCAT(s.setor SEPARATOR ", ") AS setores_associados,
                GROUP_CONCAT(s.id_setor SEPARATOR ", ") AS id_setores_associados
            FROM
                Callcenter_blocos_notas cbn
            LEFT JOIN
                callcenter_blocos_notas_setores cbns ON cbn.id_callcenter_bloco_nota = cbns.id_bloco_nota AND cbns.deleted_at IS NULL
            LEFT JOIN
                setores s ON cbns.id_setor = s.id_setor
            WHERE
                cbn.deleted_at IS NULL
            GROUP BY
                cbn.id_callcenter_bloco_nota
            ORDER BY
                cbn.titulo
        ');
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        // Retorna os dados
        return $dados;
    }

    /**
     * cadastra um novo bloco de notas e suas associações de setores
     * @param Array $dados
     * - 'titulo', 'conteudo', 'id_setores' (array de IDs), 'usuario_logado'
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(array $dados)
    {
        $titulo  = ucwords(strtolower(trim($dados['titulo'])));
        $conteudo = trim($dados['conteudo']);
        $id_setores = $dados['id_setores'] ?? []; // Array de IDs de setores
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        try {
            $this->pdo->beginTransaction();

            // 1. Inserir na tabela Callcenter_blocos_notas
            $sql_nota = $this->pdo->prepare('INSERT INTO Callcenter_blocos_notas
                                        (titulo, conteudo, created_at, updated_at)
                                        VALUES
                                        (:titulo, :conteudo, :created_at, :updated_at)
                                    ');

            $created_at  = $agora;
            $updated_at  = $agora;

            $sql_nota->bindParam(':titulo', $titulo);
            $sql_nota->bindParam(':conteudo', $conteudo);
            $sql_nota->bindParam(':created_at', $created_at);
            $sql_nota->bindParam(':updated_at', $updated_at);

            if (!$sql_nota->execute()) {
                throw new Exception("Erro ao cadastrar bloco de notas principal.");
            }

            $bloco_nota_id = $this->pdo->lastInsertId();

            // 2. Inserir na tabela callcenter_blocos_notas_setores
            if (!empty($id_setores)) {
                $sql_setores = $this->pdo->prepare('INSERT INTO callcenter_blocos_notas_setores
                                                    (id_bloco_nota, id_setor, created_at, updated_at)
                                                    VALUES
                                                    (:id_bloco_nota, :id_setor, :created_at, :updated_at)
                                                ');

                foreach ($id_setores as $id_setor) {
                    $sql_setores->bindParam(':id_bloco_nota', $bloco_nota_id);
                    $sql_setores->bindParam(':id_setor', $id_setor);
                    $sql_setores->bindParam(':created_at', $created_at);
                    $sql_setores->bindParam(':updated_at', $updated_at);
                    if (!$sql_setores->execute()) {
                        throw new Exception("Erro ao cadastrar setores associados.");
                    }
                }
            }

            // 3. Registrar Log
            $descricao = "Cadastrou o Bloco de Notas: $titulo ($bloco_nota_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            $this->pdo->commit();

            echo "
            <script>
                alert('Bloco de Notas Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/cc/central';
            </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // Em ambiente de produção, logar $e->getMessage() em vez de exibi-lo
            echo "
            <script>
                alert('Não foi possível Cadastrar o Bloco de Notas! Erro: ' + '{$e->getMessage()}');
                window.location.href = '" . URL . "/cc/central';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um Bloco de Notas, incluindo os IDs dos setores associados
     * @param int $id_do_item
     * @return object
     * @example $variavel = $Obj->mostrar($id_do_item);
     */
    public function mostrar(int $id_bloco_nota)
    {
        // 1. Dados do Bloco de Notas
        $sql_nota = $this->pdo->prepare('SELECT * FROM Callcenter_blocos_notas WHERE id_callcenter_bloco_nota = :id LIMIT 1');
        $sql_nota->bindParam(':id', $id_bloco_nota);
        $sql_nota->execute();
        $dados = $sql_nota->fetch(PDO::FETCH_OBJ);

        if (!$dados) {
            return null; // Retorna null se não encontrar o bloco de notas
        }

        // 2. IDs dos Setores Associados
        $sql_setores = $this->pdo->prepare('SELECT id_setor FROM callcenter_blocos_notas_setores
                                            WHERE id_bloco_nota = :id_bloco_nota AND deleted_at IS NULL');
        $sql_setores->bindParam(':id_bloco_nota', $id_bloco_nota);
        $sql_setores->execute();
        $setores_ids = $sql_setores->fetchAll(PDO::FETCH_COLUMN, 0); // Pega apenas a coluna 'id_setor'

        // Adiciona os IDs dos setores ao objeto principal
        $dados->id_setores = $setores_ids;

        return $dados;
    }

    public function editar(array $dados)
    {
        // --- VALIDAÇÃO BÁSICA DOS CAMPOS ---
        if (empty($dados['id_callcenter_bloco_nota'])) {
            echo "
        <script>
            alert('Bloco de Notas inválido.');
            window.location.href = '" . URL . "/cc/central';
        </script>";
            exit;
        }

        $id_bloco_nota = (int) $dados['id_callcenter_bloco_nota'];

        $titulo   = isset($dados['titulo'])   ? ucwords(strtolower(trim($dados['titulo']))) : '';
        $conteudo = isset($dados['conteudo']) ? trim($dados['conteudo']) : '';

        // Sempre deve ser array
        $id_setores = isset($dados['id_setores']) && is_array($dados['id_setores'])
            ? $dados['id_setores']
            : [];

        $usuario_logado = isset($dados['usuario_logado']) ? (int)$dados['usuario_logado'] : 0;
        $agora = date("Y-m-d H:i:s");

        // Regra de negócio: precisa ter pelo menos 1 setor
        if ($titulo === '' || $conteudo === '' || empty($id_setores)) {
            echo "
        <script>
            alert('Preencha Título, Conteúdo e selecione ao menos um Setor.');
            window.history.back();
        </script>";
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // 1) Atualizar a tabela principal Callcenter_blocos_notas
            $sql_nota = $this->pdo->prepare("
            UPDATE Callcenter_blocos_notas SET
                titulo     = :titulo,
                conteudo   = :conteudo,
                updated_at = :updated_at
            WHERE id_callcenter_bloco_nota = :id_bloco_nota
        ");

            $sql_nota->bindValue(':id_bloco_nota', $id_bloco_nota, PDO::PARAM_INT);
            $sql_nota->bindValue(':titulo',       $titulo);
            $sql_nota->bindValue(':conteudo',     $conteudo);
            $sql_nota->bindValue(':updated_at',   $agora);

            if (!$sql_nota->execute()) {
                throw new Exception("Erro ao editar bloco de notas principal.");
            }

            // 2) Soft delete de TODAS as associações atuais
            $sql_del_setores = $this->pdo->prepare("
            UPDATE callcenter_blocos_notas_setores
               SET deleted_at = :deleted_at
             WHERE id_bloco_nota = :id_bloco_nota
               AND deleted_at IS NULL
        ");
            $sql_del_setores->bindValue(':deleted_at',   $agora);
            $sql_del_setores->bindValue(':id_bloco_nota', $id_bloco_nota, PDO::PARAM_INT);

            if (!$sql_del_setores->execute()) {
                throw new Exception("Erro ao remover associações antigas de setores.");
            }

            // 3) Inserir as NOVAS associações de setores
            $sql_setores = $this->pdo->prepare('
            INSERT INTO callcenter_blocos_notas_setores
                (id_bloco_nota, id_setor, created_at, updated_at)
            VALUES
                (:id_bloco_nota, :id_setor, :created_at, :updated_at)
        ');

            foreach ($id_setores as $id_setor) {
                $id_setor = (int)$id_setor;
                if ($id_setor <= 0) {
                    continue;
                }

                // bindValue é mais adequado aqui (valor por execução)
                $sql_setores->bindValue(':id_bloco_nota', $id_bloco_nota, PDO::PARAM_INT);
                $sql_setores->bindValue(':id_setor',      $id_setor, PDO::PARAM_INT);
                $sql_setores->bindValue(':created_at',    $agora);
                $sql_setores->bindValue(':updated_at',    $agora);

                if (!$sql_setores->execute()) {
                    throw new Exception("Erro ao cadastrar associações de setores.");
                }
            }

            // 4) Log
            $descricao = "Editou o Bloco de Notas: $titulo ($id_bloco_nota)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            $this->pdo->commit();

            echo "
        <script>
            alert('Bloco de Notas Editado com Sucesso!');
            window.location.href = '" . URL . "/cc/central';
        </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "
        <script>
            alert('Não foi possível Editar o Bloco de Notas! Erro: ' + '" . addslashes($e->getMessage()) . "');
            window.location.href = '" . URL . "/cc/central';
        </script>";
            exit;
        }
    }


    /**
     * Deleta (Soft Delete) um Bloco de Notas e suas associações de setores
     *
     * @param integer $id_bloco_nota
     * @param integer $usuario_logado
     * @return void
     */
    public function deletar(int $id_bloco_nota, $usuario_logado)
    {
        $agora = date("Y-m-d H:i:s");

        try {
            $this->pdo->beginTransaction();

            // 1. Consulta para obter o nome do bloco de notas para o log
            $consulta_nota = $this->pdo->prepare('SELECT titulo FROM Callcenter_blocos_notas WHERE id_callcenter_bloco_nota = :id');
            $consulta_nota->bindParam(':id', $id_bloco_nota);
            $consulta_nota->execute();
            $resultado_nota = $consulta_nota->fetch(PDO::FETCH_ASSOC);
            $nome_nota = $resultado_nota ? $resultado_nota['titulo'] : "Bloco Desconhecido";

            // 2. Soft Delete do Bloco de Notas principal
            $sql_nota = $this->pdo->prepare('UPDATE Callcenter_blocos_notas SET deleted_at = :deleted_at WHERE id_callcenter_bloco_nota = :id_bloco_nota');
            $sql_nota->bindParam(':deleted_at', $agora);
            $sql_nota->bindParam(':id_bloco_nota', $id_bloco_nota);
            if (!$sql_nota->execute()) {
                throw new Exception("Erro ao deletar bloco de notas principal.");
            }

            // 3. Soft Delete das associações de setores
            $sql_setores = $this->pdo->prepare('UPDATE callcenter_blocos_notas_setores SET deleted_at = :deleted_at WHERE id_bloco_nota = :id_bloco_nota AND deleted_at IS NULL');
            $sql_setores->bindParam(':deleted_at', $agora);
            $sql_setores->bindParam(':id_bloco_nota', $id_bloco_nota);
            if (!$sql_setores->execute()) {
                throw new Exception("Erro ao deletar associações de setores.");
            }

            // 4. Registrar Log
            $descricao = "Deletou o Bloco de Notas $nome_nota ($id_bloco_nota)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            $this->pdo->commit();

            echo "
            <script>
                alert('Bloco de Notas Deletado com Sucesso!');
                window.location.href = '" . URL . "/cc/central';
            </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "
            <script>
                alert('Não foi possível Deletar o Bloco de Notas! Erro: ' + '{$e->getMessage()}');
                window.location.href = '" . URL . "/cc/central';
            </script>";
            exit;
        }
    }

    public function listarPorSetor(int $id_setor)
    {
        $sql = $this->pdo->prepare('
        SELECT
            cbn.*,
            GROUP_CONCAT(s.setor SEPARATOR ", ") AS setores_associados,
            GROUP_CONCAT(s.id_setor SEPARATOR ", ") AS id_setores_associados
        FROM
            Callcenter_blocos_notas cbn
        LEFT JOIN
            callcenter_blocos_notas_setores cbns 
                ON cbn.id_callcenter_bloco_nota = cbns.id_bloco_nota 
                AND cbns.deleted_at IS NULL
        LEFT JOIN
            setores s ON cbns.id_setor = s.id_setor
        WHERE
            cbn.deleted_at IS NULL
            AND (
                cbns.id_setor = :id_setor
            )
        GROUP BY
            cbn.id_callcenter_bloco_nota
        ORDER BY
            cbn.titulo
    ');

        $sql->bindParam(':id_setor', $id_setor);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}
