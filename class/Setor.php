<?php

class Setor {

    public $pdo;
    
    //Construir Conexão com o Banco de Dados.
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    //Registrar Logs(Ações) do Sistema.
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

    //Listar Setores Não Deletados
    public function listar($id_setor = 0){
        if($id_setor > 0){
            $sql = $this->pdo->prepare('SELECT * FROM setores WHERE id_setor != :id_setor AND deleted_at IS NULL ORDER BY setor ASC');
            $sql->bindParam(':id_setor',$id_setor);  
        }else{
            $sql = $this->pdo->prepare('SELECT * FROM setores WHERE deleted_at IS NULL ORDER BY setor ASC');     
        }   
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }      

    //Cadastrar novo Setor
    public function cadastrar(Array $dados)
    {
        $setor  = ucwords(strtolower(trim($dados['setor'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO setores 
                                    (setor, created_at, updated_at)
                                    VALUES
                                    (:setor, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':setor', $setor);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $id_setor = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Setor: $setor ($id_setor)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Setor Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Setor!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        }
    }

    //Mostrar informações de um Setor
    public function mostrar(int $id_setor)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM setores WHERE id_setor = :id_setor LIMIT 1');
        $sql->bindParam(':id_setor', $id_setor);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }
    
    //Editar informações um Setor
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE setores SET
            setor = :setor,
            updated_at = :updated_at 
        WHERE id_setor = :id_setor
        ");

        $agora = date("Y-m-d H:i:s");

        $id_setor = $dados['id_setor'];
        $setor = ucwords(strtolower(trim($dados['setor'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam(':setor',$setor);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Setor: $setor ($id_setor)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Setor Editado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Setor!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        }
    }

    //Deletar um Setor Ativo
    public function deletar(int $id_setor, $usuario_logado)
    {
        $consulta_setor = $this->pdo->prepare('SELECT setor FROM setores WHERE id_setor = :id_setor');
        $consulta_setor->bindParam(':id_setor', $id_setor);
        $consulta_setor->execute();
        $resultado_setor = $consulta_setor->fetch(PDO::FETCH_ASSOC);

        if ($resultado_setor) {
            $nome_setor = $resultado_setor['setor'];
        } else {
            $nome_setor = "Setor Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE setores SET deleted_at = :deleted_at WHERE id_setor = :id_setor');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_setor', $id_setor);
        if ($sql->execute()) {
            $descricao = "Deletou o Setor: $nome_setor ($id_setor)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Setor Deletado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Setor!');
                window.location.href = '" . URL . "/configuracoes/setores';
            </script>";
            exit;
        }
    }
}

?>
