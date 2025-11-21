<?php

class Pacote_Exame
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    // Logs
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

    public function listarTodos()
    {
        $sql = $this->pdo->prepare('
        SELECT ep.*,
               CASE ep.id_empresa
                    WHEN 1 THEN "Clínica Parque"
                    WHEN 3 THEN "Clínica Mauá"
                    WHEN 5 THEN "Clínica Marechal"
               END AS empresa_nome
        FROM exames_pacotes ep
        WHERE ep.deleted_at IS NULL
        ORDER BY ep.pacote
    ');

        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Listar todos os pacotes de exames
     */
    public function listar($id_empresa)
    {
        $sql = $this->pdo->prepare('SELECT * 
                                    FROM exames_pacotes 
                                    WHERE deleted_at IS NULL AND id_empresa = :id_empresa
                                    ORDER BY pacote');
        $sql->bindParam(':id_empresa', $id_empresa);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function listarExamesDoPacote(int $id_pacote)
    {
        $sql = $this->pdo->prepare('
        SELECT id_exame
        FROM exames_pacotes_exames
        WHERE id_exame_pacote = :id_pacote
          AND deleted_at IS NULL
    ');

        $sql->bindParam(':id_pacote', $id_pacote);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    public function listarNomeExamesDoPacote(int $id_pacote)
    {
        $sql = $this->pdo->prepare('
        SELECT exame FROM exames as e
            INNER JOIN exames_pacotes_exames as epe ON epe.id_exame = e.id_exame
            INNER JOIN exames_pacotes as ep ON ep.id_exames_pacote = epe.id_exame_pacote
            WHERE ep.id_exames_pacote = :id_exame_pacote;
    ');

        $sql->bindParam(':id_exame_pacote', $id_pacote, PDO::PARAM_INT);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    public function cadastrar(array $dados)
    {
        $pacote  = ucwords(strtolower(trim($dados['pacote'])));
        $valor_fidelidade = str_replace(',', '.', $dados['valor_fidelidade']);
        $id_empresa = $dados['id_empresa'];
        $usuario_logado = $dados['usuario_logado'];
        $exames = isset($dados['exames']) ? $dados['exames'] : [];

        $agora = date("Y-m-d H:i:s");
        $created_at = $agora;
        $updated_at = $agora;

        try {
            $this->pdo->beginTransaction();

            $sql = $this->pdo->prepare('INSERT INTO exames_pacotes
                                    (pacote, id_empresa, valor_fidelidade, created_at, updated_at)
                                    VALUES
                                    (:pacote, :id_empresa, :valor_fidelidade, :created_at, :updated_at)
                                ');

            $sql->bindParam(':pacote', $pacote);
            $sql->bindParam(':id_empresa', $id_empresa);
            $sql->bindParam(':valor_fidelidade', $valor_fidelidade);
            $sql->bindParam(':created_at', $created_at);
            $sql->bindParam(':updated_at', $updated_at);

            if (!$sql->execute()) {
                $this->pdo->rollBack();
                throw new Exception('Erro ao cadastrar pacote.');
            }

            $id_exames_pacote = $this->pdo->lastInsertId();

            if (!empty($exames)) {
                $sqlExames = $this->pdo->prepare('INSERT INTO exames_pacotes_exames
                                              (id_exame_pacote, id_exame, created_at, updated_at)
                                              VALUES
                                              (:id_exame_pacote, :id_exame, :created_at, :updated_at)
                                             ');

                foreach ($exames as $id_exame) {
                    $sqlExames->bindParam(':id_exame_pacote', $id_exames_pacote);
                    $sqlExames->bindParam(':id_exame', $id_exame);
                    $sqlExames->bindParam(':created_at', $created_at);
                    $sqlExames->bindParam(':updated_at', $updated_at);
                    $sqlExames->execute();
                }
            }

            $this->pdo->commit();

            $descricao = "Cadastrou o pacote de exames: $pacote ($id_exames_pacote)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
        <script>
            alert('Pacote cadastrado com sucesso!');
            window.location.href = '" . URL . "/configuracoes/exames';
        </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "
        <script>
            alert('Não foi possível cadastrar o pacote de exames!');
            window.location.href = '" . URL . "/configuracoes/exames';
        </script>";
            exit;
        }
    }


    /**
     * Editar pacote de exames
     */
    public function editar(array $dados)
    {
        $id_exames_pacote = (int)$dados['id_pacote'];
        $pacote  = ucwords(strtolower(trim($dados['pacote'])));
        $valor_fidelidade = str_replace(',', '.', $dados['valor_fidelidade']);
        $id_empresa = $dados['id_empresa'];
        $usuario_logado = $dados['usuario_logado'];
        $exames = isset($dados['exames']) ? $dados['exames'] : [];

        $agora = date("Y-m-d H:i:s");
        $updated_at = $agora;

        try {
            $this->pdo->beginTransaction();

            // Atualiza pacote
            $sql = $this->pdo->prepare("UPDATE exames_pacotes SET
                pacote = :pacote,
                valor_fidelidade = :valor_fidelidade,
                id_empresa = :id_empresa,
                updated_at = :updated_at
                WHERE id_exames_pacote = :id_exames_pacote
            ");

            $sql->bindParam(':pacote', $pacote);
            $sql->bindParam(':id_empresa', $id_empresa);
            $sql->bindParam(':valor_fidelidade', $valor_fidelidade);
            $sql->bindParam(':updated_at', $updated_at);
            $sql->bindParam(':id_exames_pacote', $id_exames_pacote);

            if (!$sql->execute()) {
                $this->pdo->rollBack();
                throw new Exception('Erro ao editar pacote.');
            }

            // "Apaga" vínculos antigos
            $sqlDel = $this->pdo->prepare('UPDATE exames_pacotes_exames 
                                           SET deleted_at = :deleted_at 
                                           WHERE id_exame_pacote = :id_exame_pacote
                                             AND deleted_at IS NULL');
            $sqlDel->bindParam(':deleted_at', $updated_at);
            $sqlDel->bindParam(':id_exame_pacote', $id_exames_pacote);
            $sqlDel->execute();

            // Insere novos vínculos
            if (!empty($exames)) {
                $sqlExames = $this->pdo->prepare('INSERT INTO exames_pacotes_exames
                                                  (id_exame_pacote, id_exame, created_at, updated_at)
                                                  VALUES
                                                  (:id_exame_pacote, :id_exame, :created_at, :updated_at)
                                                 ');

                foreach ($exames as $id_exame) {
                    $sqlExames->bindParam(':id_exame_pacote', $id_exames_pacote);
                    $sqlExames->bindParam(':id_exame', $id_exame);
                    $sqlExames->bindParam(':created_at', $agora);
                    $sqlExames->bindParam(':updated_at', $agora);
                    $sqlExames->execute();
                }
            }

            $this->pdo->commit();

            $descricao = "Editou o pacote de exames: $pacote ($id_exames_pacote)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Pacote editado com sucesso!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "
            <script>
                alert('Não foi possível editar o pacote de exames!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        }
    }

    /**
     * Deletar pacote de exames (soft delete)
     */
    public function deletar(int $id_exames_pacote, $usuario_logado)
    {
        // Busca nome do pacote
        $consulta = $this->pdo->prepare('SELECT pacote FROM exames_pacotes WHERE id_exames_pacote = :id LIMIT 1');
        $consulta->bindParam(':id', $id_exames_pacote);
        $consulta->execute();
        $row = $consulta->fetch(PDO::FETCH_ASSOC);

        $nome_pacote = $row ? $row['pacote'] : 'Pacote Desconhecido';

        $agora = date("Y-m-d H:i:s");

        try {
            $this->pdo->beginTransaction();

            // Soft delete do pacote
            $sql = $this->pdo->prepare('UPDATE exames_pacotes 
                                        SET deleted_at = :deleted_at 
                                        WHERE id_exames_pacote = :id');
            $sql->bindParam(':deleted_at', $agora);
            $sql->bindParam(':id', $id_exames_pacote);

            if (!$sql->execute()) {
                $this->pdo->rollBack();
                throw new Exception('Erro ao deletar pacote.');
            }

            // Soft delete dos vinculos
            $sqlVinc = $this->pdo->prepare('UPDATE exames_pacotes_exames 
                                            SET deleted_at = :deleted_at 
                                            WHERE id_exame_pacote = :id_exame_pacote
                                              AND deleted_at IS NULL');
            $sqlVinc->bindParam(':deleted_at', $agora);
            $sqlVinc->bindParam(':id_exame_pacote', $id_exames_pacote);
            $sqlVinc->execute();

            $this->pdo->commit();

            $descricao = "Deletou o pacote de exames $nome_pacote ($id_exames_pacote)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Pacote deletado com sucesso!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "
            <script>
                alert('Não foi possível deletar o pacote de exames!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        }
    }
}
