<?php

class Faturamento_Nota_Servico
{

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    // Método para registrar logs
    private function addLog($acao, $descricao, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario) 
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
    }

    /**
     * Listar todas as notas de serviço
     * @return array
     */
    public function listar($id_competencia)
    {
        $sql = $this->pdo->prepare('SELECT * FROM faturamento_notas_servicos WHERE id_competencia = :id_competencia AND deleted_at IS NULL');
        $sql->bindParam(':id_competencia', $id_competencia);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Cadastrar nova nota de serviço
     * @param array $dados
     * @return void
     */
    public function cadastrar(array $dados)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare('INSERT INTO faturamento_notas_servicos 
            (id_competencia, id_convenio, tipo, data_pagamento_previsto, bf_nf, valor_faturado, valor_imposto, valor_pago, data_pago, feedback, created_at, updated_at)
            VALUES
            (:id_competencia, :id_convenio, :tipo, :data_pagamento_previsto, :bf_nf, :valor_faturado, :valor_imposto, :valor_pago, :data_pago, :feedback, :created_at, :updated_at)
        ');

        $id_competencia = $dados['id_competencia'];

        $sql->bindParam(':id_competencia', $id_competencia);
        $sql->bindParam(':id_convenio', $dados['id_convenio']);
        $sql->bindParam(':tipo', $dados['tipo']);
        $sql->bindParam(':data_pagamento_previsto', $dados['data_pagamento_previsto']);
        $sql->bindParam(':bf_nf', $dados['bf_nf']);
        $sql->bindParam(':valor_faturado', $dados['valor_faturado']);
        $sql->bindParam(':valor_imposto', $dados['valor_imposto']);
        $sql->bindParam(':valor_pago', $dados['valor_pago']);
        $sql->bindParam(':data_pago', $dados['data_pago']);
        $sql->bindParam(':feedback', $dados['feedback']);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $id_nota_servico = $this->pdo->lastInsertId();
            $descricao = "Cadastrou nova nota de serviço ($id_nota_servico)";
            $this->addLog("Cadastrar", $descricao, $dados['usuario_logado']);

            echo "
            <script>
                alert('Nota de serviço cadastrada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cadastrar a nota de serviço!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        }
    }

    /**
     * Mostrar uma nota de serviço
     * @param int $id_faturamento_nota_servico
     * @return object
     */
    public function mostrar(int $id_faturamento_nota_servico)
    {
        $sql = $this->pdo->prepare('SELECT * FROM faturamento_notas_servicos WHERE id_faturamento_nota_servico = :id_faturamento_nota_servico LIMIT 1');
        $sql->bindParam(':id_faturamento_nota_servico', $id_faturamento_nota_servico);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Editar uma nota de serviço
     * @param array $dados
     * @return void
     */
    public function editar(array $dados)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare("UPDATE faturamento_notas_servicos SET
            id_competencia = :id_competencia,
            id_convenio = :id_convenio,
            tipo = :tipo,
            data_pagamento_previsto = :data_pagamento_previsto,
            bf_nf = :bf_nf,
            valor_faturado = :valor_faturado,
            valor_imposto = :valor_imposto,
            feedback = :feedback,
            updated_at = :updated_at
        WHERE id_faturamento_nota_servico = :id_faturamento_nota_servico
        ");

        $id_competencia = $dados['id_competencia'];

        $sql->bindParam(':id_faturamento_nota_servico', $dados['id_faturamento_nota_servico']);
        $sql->bindParam(':id_competencia', $id_competencia);
        $sql->bindParam(':id_convenio', $dados['id_convenio']);
        $sql->bindParam(':tipo', $dados['tipo']);
        $sql->bindParam(':data_pagamento_previsto', $dados['data_pagamento_previsto']);
        $sql->bindParam(':bf_nf', $dados['bf_nf']);
        $sql->bindParam(':valor_faturado', $dados['valor_faturado']);
        $sql->bindParam(':valor_imposto', $dados['valor_imposto']);
        $sql->bindParam(':feedback', $dados['feedback']);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Editou a nota de serviço ($dados[id_faturamento_nota_servico])";
            $this->addLog("Editar", $descricao, $dados['usuario_logado']);

            echo "
            <script>
                alert('Nota de serviço editada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar a nota de serviço!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        }
    }

    /**
     * Editar uma nota de serviço
     * @param array $dados
     * @return void
     */
    public function editarPagamento(array $dados)
    {
        $agora = date("Y-m-d H:i:s");
        $sql = $this->pdo->prepare("UPDATE faturamento_notas_servicos SET
            valor_pago = :valor_pago,
            data_pago = :data_pago,
            updated_at = :updated_at
        WHERE id_faturamento_nota_servico = :id_faturamento_nota_servico
        ");

        $id_competencia = $dados['id_competencia'];

        $sql->bindParam(':id_faturamento_nota_servico', $dados['id_faturamento_nota_servico']);
        $sql->bindParam(':valor_pago', $dados['valor_pago']);
        $sql->bindParam(':data_pago', $dados['data_pago']);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $descricao = "Editou pagamento da nota de serviço ($dados[id_faturamento_nota_servico])";
            $this->addLog("Editar", $descricao, $dados['usuario_logado']);

            echo "
            <script>
                alert('Nota de serviço editada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar a nota de serviço!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        }
    }

    /**
     * Deletar uma nota de serviço
     * @param int $id_faturamento_nota_servico
     * @param int $usuario_logado
     * @return void
     */
    public function deletar(int $id_faturamento_nota_servico, $usuario_logado, $id_competencia)
    {
        $consulta = $this->pdo->prepare('SELECT bf_nf FROM faturamento_notas_servicos WHERE id_faturamento_nota_servico = :id_faturamento_nota_servico');
        $consulta->bindParam(':id_faturamento_nota_servico', $id_faturamento_nota_servico);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $bf_nf = $resultado ? $resultado['bf_nf'] : "Nota desconhecida";

        $sql = $this->pdo->prepare('UPDATE faturamento_notas_servicos SET deleted_at = :deleted_at WHERE id_faturamento_nota_servico = :id_faturamento_nota_servico');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_faturamento_nota_servico', $id_faturamento_nota_servico);

        if ($sql->execute()) {
            $descricao = "Deletou a nota de serviço $bf_nf ($id_faturamento_nota_servico)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Nota de serviço deletada com sucesso!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar a nota de serviço!');
                window.location.href = '" . URL . "/faturamento/competencia?id=$id_competencia';
            </script>";
            exit;
        }
    }
}
