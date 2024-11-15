<?php

class Compra_Pedido {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    // Método para registrar logs
    private function addLog($acao, $descricao, $id_usuario){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO logs 
                                    (acao, descricao, data, id_usuario) 
                                    VALUES
                                    (:acao, :descricao, :data, :id_usuario)
                                ');
        $sql->bindParam(':acao', $acao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':data', $agora);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
    }

    public function listar() {
        $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE deleted_at IS NULL AND status IS NULL');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }

    public function listarPedidosDoUsuario($id_usuario) {
        $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE id_usuario = :id_usuario AND deleted_at IS NULL AND status IS NULL');
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
        
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        
        return $dados;
    }

    public function listarComprados($id_usuario = null, $id_id_setor = null) {
        if ($id_id_setor !== null && $id_id_setor !== 1 && $id_id_setor !== 3) {
            $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE id_usuario = :id_usuario AND status = :status');
            $sql->bindParam(':id_usuario', $id_usuario);
        } else {
            $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE status = :status');
        }

        $status = 'comprado';
        $sql->bindParam(':status', $status);
        $sql->execute();
        
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        
        return $dados;
    }

    public function listarNegados($id_usuario = null, $id_id_setor = null) {
        if ($id_id_setor !== null && $id_id_setor !== 1 && $id_id_setor !== 3) {
            $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE id_usuario = :id_usuario AND status = :status');
            $sql->bindParam(':id_usuario', $id_usuario);
        } else {
            $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE status = :status');
        }

        $status = 'negado';
        $sql->bindParam(':status', $status);
        $sql->execute();
        
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
        
        return $dados;
    }

    public function cadastrar(Array $dados)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO compras_pedidos 
                                    (titulo, id_usuario, empresa, id_setor, link, urgencia, descricao, created_at, updated_at)
                                    VALUES
                                    (:titulo, :id_usuario, :empresa, :id_setor, :link, :urgencia, :descricao, :created_at, :updated_at)
                                ');

        $titulo  = $dados['titulo'];
        $id_usuario  = $dados['usuario_logado'];
        $empresa  = $dados['empresa'];
        $id_setor  = $dados['id_setor'];
        $link  = $dados['link'];
        $urgencia  = $dados['urgencia'];
        $descricao  = $dados['descricao'];
        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':titulo', $titulo);          
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':empresa', $empresa);
        $sql->bindParam(':id_setor', $id_setor);
        $sql->bindParam(':link', $link); 
        $sql->bindParam(':urgencia', $urgencia); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);      

        if ($sql->execute()) {
            $descricaoLog = "Cadastrou a compra/pedido de Título: $titulo e Empresa: $empresa";
            $this->addLog('Cadastrar', $descricaoLog, $id_usuario);

            echo "
            <script>
                alert('Pedido cadastrado com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cadastrar o pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function mostrar(int $id_compra_pedido)
    {
        $sql = $this->pdo->prepare('SELECT * FROM compras_pedidos WHERE id_compra_pedido = :id_compra_pedido LIMIT 1');
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);
        $sql->execute();
        $dados = $sql->fetch(PDO::FETCH_OBJ);
        return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE compras_pedidos SET
            titulo = :titulo,
            id_usuario = :id_usuario,
            empresa = :empresa,
            id_setor = :id_setor,
            link = :link, 
            urgencia = :urgencia, 
            descricao = :descricao, 
            updated_at = :updated_at 
        WHERE id_compra_pedido = :id_compra_pedido
        ");

        $agora = date("Y-m-d H:i:s");

        $id_compra_pedido = $dados['id_compra_pedido'];
        $titulo = $dados['titulo'];
        $id_usuario = $dados['usuario_logado'];
        $empresa = $dados['empresa'];
        $id_setor = $dados['id_setor'];
        $link = $dados['link']; 
        $urgencia = $dados['urgencia']; 
        $descricao = $dados['descricao']; 
        $updated_at = $agora; 

        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);
        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':empresa', $empresa);
        $sql->bindParam(':id_setor', $id_setor);
        $sql->bindParam(':link', $link); 
        $sql->bindParam(':urgencia', $urgencia); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricaoLog = "Editou a compra/pedido de ID: $id_compra_pedido e Empresa: $empresa";
            $this->addLog('Editar', $descricaoLog, $id_usuario);
            
            echo "
            <script>
                alert('Pedido editado com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível editar o pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function deletar(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET deleted_at = :deleted_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Deletou a compra/pedido de ID: $id_compra_pedido";
            $this->addLog('Deletar', $descricao, $id_usuario);

            echo "
            <script>
                alert('Pedido deletado com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível deletar o pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function confirmarCompra(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = :status, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $status = 'comprado';
        $sql->bindParam(':status', $status);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Confirmou a compra do pedido de ID: $id_compra_pedido";
            $this->addLog('Confirmar', $descricao, $id_usuario);

            echo "
            <script>
                alert('Pedido Confirmado a Compra com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Confirmar a Compra do pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function negarCompra(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = :status, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $status = 'negado';
        $sql->bindParam(':status', $status);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Negou a compra do pedido de ID: $id_compra_pedido";
            $this->addLog('Negar', $descricao, $id_usuario);

            echo "
            <script>
                alert('Pedido Negado a Compra com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Negar a Compra do pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function cancelarCompra(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = NULL, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Cancelou a confirmação da compra do pedido de ID: $id_compra_pedido";
            $this->addLog('Cancelar', $descricao, $id_usuario);

            echo "
            <script>
                alert('Pedido Compra Cencelada com sucesso!');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível cancelar a Compra do pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

    public function cancelarNegacao(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = NULL, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Cancelou a negação do pedido de ID: $id_compra_pedido";
            $this->addLog('Cancelar', $descricao, $id_usuario);
        } else {
            echo "
            <script>
                alert('Não foi possível Cancelar a Negação do pedido.');
                window.location.href = '" . URL . "/compras/pedidos';
            </script>";
            exit;
        }
    }

}

?>
