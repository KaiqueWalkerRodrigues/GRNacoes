<?php

class Compras_Pedidos {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
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

    public function listarComprados($id_usuario = null, $id_setor = null) {
        if ($id_setor !== null && $id_setor !== 1 && $id_setor !== 3) {
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

    public function listarNegados($id_usuario = null, $id_setor = null) {
        if ($id_setor !== null && $id_setor !== 1 && $id_setor !== 3) {
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
        //Cadastra a Compra/Pedido
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO compras_pedidos 
                                    (titulo, id_usuario, empresa, setor, link, urgencia, descricao, created_at, updated_at)
                                    VALUES
                                    (:titulo, :id_usuario, :empresa, :setor, :link, :urgencia, :descricao, :created_at, :updated_at)
                                ');

        $titulo  = $dados['titulo'];
        $id_usuario  = $dados['usuario_logado'];
        $empresa  = $dados['empresa'];
        $setor  = $dados['setor'];
        $link  = $dados['link'];
        $urgencia  = $dados['urgencia'];
        $descricao  = $dados['descricao'];
        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':titulo', $titulo);          
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':empresa', $empresa);
        $sql->bindParam(':setor', $setor);
        $sql->bindParam(':link', $link); 
        $sql->bindParam(':urgencia', $urgencia); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);      

        if ($sql->execute()) {

            $descricaoLog = "Cadastrou a compra/pedido de Título: $titulo e Empresa: $empresa"; // Adicionado descrição do log

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (id_usuario, acao, descricao, data)
                                        VALUES
                                        (:id_usuario, :acao, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':descricao', $descricaoLog); // Adicionado descrição do log
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('location:/GRNacoes/compras/pedidos');
        } else {
            // Tratar falha na execução da query, se necessário
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
            setor = :setor,
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
        $setor = $dados['setor'];
        $link = $dados['link']; 
        $urgencia = $dados['urgencia']; 
        $descricao = $dados['descricao']; 
        $updated_at = $agora; 

        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);
        $sql->bindParam(':titulo', $titulo);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':empresa', $empresa);
        $sql->bindParam(':setor', $setor);
        $sql->bindParam(':link', $link); 
        $sql->bindParam(':urgencia', $urgencia); 
        $sql->bindParam(':descricao', $descricao); 
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricaoLog = "Editou a compra/pedido de ID: $id_compra_pedido e Empresa: $empresa"; // Adicionado descrição do log

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':descricao', $descricaoLog); // Adicionado descrição do log
            $sql->bindParam(':data', $agora); 
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s"); // Adicionado aqui para obter o momento atual

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET deleted_at = :deleted_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Desativou a compra/pedido de ID: $id_compra_pedido";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();

            return header('location:/GRNacoes/compras_pedidos');
        } else {
            // Tratar falha na execução da query, se necessário
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
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Confirmar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
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
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Negar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    // Novo método para cancelar a compra
    public function cancelarCompra(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = NULL, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Cancelou a confirmação da compra do pedido de ID: $id_compra_pedido";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Cancelar Compra';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    // Novo método para cancelar a negação
    public function cancelarNegacao(int $id_compra_pedido, $id_usuario)
    {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('UPDATE compras_pedidos SET status = NULL, updated_at = :updated_at WHERE id_compra_pedido = :id_compra_pedido');
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_compra_pedido', $id_compra_pedido);

        if ($sql->execute()) {
            $descricao = "Cancelou a negação do pedido de ID: $id_compra_pedido";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data, id_usuario) 
                                        VALUES
                                        (:acao, :descricao, :data, :id_usuario)
                                    ');
            $acao = 'Cancelar Negação';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

}

?>
