<?php

class Exame
{

    # ATRIBUTOS	
    public $pdo;

    //Construir Conexão com o Banco de Dados.
    public function __construct()
    {
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
     * listar todos os exames
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar()
    {
        $sql = $this->pdo->prepare('SELECT * FROM exames WHERE deleted_at IS NULL ORDER BY exame');
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        // Retorna os dados como JSON
        return $dados;
    }

    /**
     * cadastra um novo exame
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(array $dados)
    {
        $exame  = ucwords(strtolower(trim($dados['exame'])));
        $valor_particular = str_replace(',', '.', $dados['valor_particular']);
        $valor_fidelidade = str_replace(',', '.', $dados['valor_fidelidade']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO exames 
                                    (exame, valor_particular, valor_fidelidade, created_at, updated_at)
                                    VALUES
                                    (:exame, :valor_particular, :valor_fidelidade, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':exame', $exame);
        $sql->bindParam(':valor_particular', $valor_particular);
        $sql->bindParam(':valor_fidelidade', $valor_fidelidade);
        $sql->bindParam(':created_at', $created_at);
        $sql->bindParam(':updated_at', $updated_at);

        if ($sql->execute()) {
            $exame_id = $this->pdo->lastInsertId();

            $descricao = "Cadastrou o exame: $exame ($exame_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('exame Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o exame!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um ITEM
     * @param int $id_do_item
     * @return object
     * @example $variavel = $Obj->mostrar($id_do_item);
     */
    public function mostrar(int $id_exame)
    {
        // Montar o SELECT ou o SQL
        $sql = $this->pdo->prepare('SELECT * FROM exames WHERE id_exame = :id_exame LIMIT 1');
        $sql->bindParam(':id_exame', $id_exame);
        // Executar a consulta
        $sql->execute();
        // Pega os dados retornados
        // Como será retornado apenas UM tabela usamos fetch. para
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualiza um determinado usuario
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE exames SET
            exame = :exame,
            valor_particular = :valor_particular,
            valor_fidelidade = :valor_fidelidade,
            updated_at = :updated_at 
        WHERE id_exame = :id_exame
        ");

        $agora = date("Y-m-d H:i:s");

        $id_exame = $dados['id_exame'];
        $exame = trim($dados['exame']);
        $updated_at = $agora;
        $valor_particular = str_replace(',', '.', $dados['valor_particular']);
        $valor_fidelidade = str_replace(',', '.', $dados['valor_fidelidade']);
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_exame', $id_exame);
        $sql->bindParam(':exame', $exame);
        $sql->bindParam(':valor_particular', $valor_particular);
        $sql->bindParam(':valor_fidelidade', $valor_fidelidade);
        $sql->bindParam(':updated_at', $updated_at);

        if ($sql->execute()) {
            $descricao = "Editou o exame: $exame ($id_exame)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('exame Editado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o exame!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        }
    }

    /**
     * Deleta um ITEM
     *
     * @param integer $id_usuario
     * @return void (esse metodo não retorna nada)
     */
    public function deletar(int $id_exame, $usuario_logado)
    {
        // Consulta para obter o exame
        $consulta_exame = $this->pdo->prepare('SELECT exame FROM exames WHERE id_exame = :id_exame');
        $consulta_exame->bindParam(':id_exame', $id_exame);
        $consulta_exame->execute();
        $resultado_exame = $consulta_exame->fetch(PDO::FETCH_ASSOC);

        // Verifica se o exame foi encontrado
        if ($resultado_exame) {
            $nome_exame = $resultado_exame['exame'];
        } else {
            // Se o exame não for encontrado, use um nome genérico
            $nome_exame = "exame Desconhecido";
        }

        // Atualiza o registro de exame com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE exames SET deleted_at = :deleted_at WHERE id_exame = :id_exame');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_exame', $id_exame);

        if ($sql->execute()) {
            $descricao = "Deletou o exame $nome_exame ($id_exame)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('exame Deletado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o exame!');
                window.location.href = '" . URL . "/configuracoes/exames';
            </script>";
            exit;
        }
    }
}
