<?php

class Tecnologia_Socket {

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
     * Listar todos os sockets
     * @return array
     * @example $variavel = $Obj->listar();
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM tecnologia_sockets WHERE deleted_at IS NULL ORDER BY socket');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados
        return $dados;
    }      

    /**
     * Cadastrar um novo socket
     * @param array $dados    
     * @return void
     * @example $Obj->cadastrar($_POST);
     */
    public function cadastrar(array $dados){
        $socket  = ucwords(strtolower(trim($dados['socket'])));
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO tecnologia_sockets 
                                    (socket, created_at, updated_at)
                                    VALUES
                                    (:socket, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':socket', $socket);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $socket_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Socket: $socket ($socket_id)";
            $this->addLog("Cadastrar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Socket Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cadastrar o Socket!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        }
    }

    /**
     * Retornar os dados de um socket específico
     * @param int $id_tecnologia_socket
     * @return object
     * @example $variavel = $Obj->mostrar($id_tecnologia_socket);
     */
    public function mostrar(int $id_tecnologia_socket){
        $sql = $this->pdo->prepare('SELECT * FROM tecnologia_sockets WHERE id_tecnologia_socket = :id_tecnologia_socket LIMIT 1');
        $sql->bindParam(':id_tecnologia_socket', $id_tecnologia_socket);
        $sql->execute();
        
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    /**
     * Atualizar um socket existente
     *
     * @param array $dados   
     * @return void
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE tecnologia_sockets SET
            socket = :socket,
            updated_at = :updated_at 
        WHERE id_tecnologia_socket = :id_tecnologia_socket
        ");

        $agora = date("Y-m-d H:i:s");

        $id_tecnologia_socket = $dados['id_tecnologia_socket'];
        $socket = ucwords(strtolower(trim($dados['socket'])));
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_tecnologia_socket', $id_tecnologia_socket);
        $sql->bindParam(':socket', $socket);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Socket: $socket ($id_tecnologia_socket)";
            $this->addLog("Editar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Socket Editado com Sucesso!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar o Socket!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        }
    }

    /**
     * Deletar um socket (soft delete)
     *
     * @param int $id_tecnologia_socket
     * @param int $usuario_logado
     * @return void
     */
    public function deletar(int $id_tecnologia_socket, $usuario_logado){
        // Consulta para obter o nome do socket
        $consulta_socket = $this->pdo->prepare('SELECT socket FROM tecnologia_sockets WHERE id_tecnologia_socket = :id_tecnologia_socket');
        $consulta_socket->bindParam(':id_tecnologia_socket', $id_tecnologia_socket);
        $consulta_socket->execute();
        $resultado_socket = $consulta_socket->fetch(PDO::FETCH_ASSOC);

        // Verifica se o socket foi encontrado
        if ($resultado_socket) {
            $nome_socket = $resultado_socket['socket'];
        }else{
            echo "
            <script>
                alert('Não foi possível deletar o Socket!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        }


        // Atualiza o registro de socket com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE tecnologia_sockets SET deleted_at = :deleted_at WHERE id_tecnologia_socket = :id_tecnologia_socket');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_tecnologia_socket', $id_tecnologia_socket);

        if ($sql->execute()) {
            $descricao = "Deletou o socket $nome_socket ($id_tecnologia_socket)";
            $this->addLog("Deletar", $descricao, $usuario_logado);

            echo "
            <script>
                alert('Socket Deletado com Sucesso!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar o Socket!');
                window.location.href = '" . URL . "/tecnologia/sockets';
            </script>";
            exit;
        }
    }

}

?>
