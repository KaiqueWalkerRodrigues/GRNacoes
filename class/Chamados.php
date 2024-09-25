<?php

class Chamados {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM chamados ORDER BY created_at DESC');        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }    

    public function listarPorUsuario($id_usuario){
        $sql = $this->pdo->prepare('SELECT * FROM chamados WHERE id_usuario = :id_usuario ORDER BY created_at DESC');
        $sql->bindParam(':id_usuario',$id_usuario);        
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }    
    
    public function listarPorSetor($id_setor){
        $sql = $this->pdo->prepare('SELECT * FROM chamados WHERE id_setor = :id_setor ORDER BY created_at DESC');        
        $sql->bindParam(':id_setor',$id_setor);
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    } 

    public function cadastrar(Array $dados)
    {
        $titulo  = ucwords(strtolower(trim($dados['titulo'])));
        $status = 1;
        $id_usuario = $dados['id_usuario'];
        $id_setor = $dados['id_setor'];
        $urgencia = $dados['urgencia'];
        $descricao = trim($dados['descricao']);
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO chamados 
                                    (titulo, status, id_usuario, id_setor, urgencia, descricao, created_at, updated_at)
                                    VALUES
                                    (:titulo, :status, :id_usuario, :id_setor, :urgencia, :descricao, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':titulo', $titulo);          
        $sql->bindParam(':status', $status);          
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':id_setor', $id_setor);
        $sql->bindParam(':urgencia', $urgencia);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $chamado_id = $this->pdo->lastInsertId();

            // Chama a função para criar uma conversa associada ao chamado
            $chamadoConversa = new ChamadoConversas();
            $chamadoConversa->criarConversaParaChamado($chamado_id, $id_usuario, $id_setor);

            // Log do chamado criado
            $descricao_log = "Cadastrou o chamado: $titulo ($chamado_id)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Cadastrar';

            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':descricao', $descricao_log); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
            
            return header('Location:/GRNacoes/chamados/meus_chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function mostrar(int $id_chamado)
    {
    	$sql = $this->pdo->prepare('SELECT * FROM chamados WHERE id_chamado = :id_chamado LIMIT 1');
        $sql->bindParam(':id_chamado', $id_chamado);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE chamados SET
            titulo = :titulo,
            status = :status,
            id_setor = :id_setor,
            urgencia = :urgencia,
            descricao = :descricao,
            updated_at = :updated_at 
        WHERE id_chamado = :id_chamado
        ");

        $agora = date("Y-m-d H:i:s");

        $id_chamado = $dados['id_chamado'];
        $status = $dados['status'];
        $titulo = ucwords(strtolower(trim($dados['titulo'])));
        $id_setor = $dados['id_setor'];
        $urgencia = $dados['urgencia'];
        $descricao = trim($dados['descricao']);
        $updated_at = $agora; 
        $id_usuario = $dados['id_usuario'];

        $sql->bindParam(':id_chamado',$id_chamado);
        $sql->bindParam(':titulo',$titulo);
        $sql->bindParam(':status',$status);
        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam(':urgencia',$urgencia);
        $sql->bindParam(':descricao',$descricao);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao_log = "Editou o chamado: $titulo ($id_chamado)";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');

            $acao = 'Editar';
            $sql->bindParam(':acao', $acao); 
            $sql->bindParam(':id_usuario', $id_usuario); 
            $sql->bindParam(':descricao', $descricao_log); 
            $sql->bindParam(':data', $agora); 
            $sql->execute();
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function desativar(int $id_chamado, $id_usuario)
    {
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE chamados SET deleted_at = :deleted_at, status = 4 WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            $descricao_log = "Desativou o chamado $nome_chamado($id_chamado)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->bindParam(':descricao', $descricao_log);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('location:/GRNacoes/chamados/meus_chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function encaminhar(int $id_chamado, int $id_setor_novo, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo, id_setor FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_setor_antigo = $resultado_chamado['id_setor'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
            $id_setor_antigo = "Desconhecido";
        }

        // Atualizar o setor do chamado
        $sql = $this->pdo->prepare('UPDATE chamados SET id_setor = :id_setor_novo, updated_at = :updated_at, status = 1 WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':id_setor_novo', $id_setor_novo);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            // Registrar log da transferência
            $descricao_log = "Encaminhou o chamado ($id_chamado) do setor ($id_setor_antigo) para o setor ($id_setor_novo)";

            $sql_log = $this->pdo->prepare('INSERT INTO logs 
                                            (acao, id_usuario, descricao, data)
                                            VALUES
                                            (:acao, :id_usuario, :descricao, :data)
                                        ');

            $acao = 'Encaminhar';
            $sql_log->bindParam(':acao', $acao);
            $sql_log->bindParam(':id_usuario', $id_usuario);
            $sql_log->bindParam(':descricao', $descricao_log);
            $sql_log->bindParam(':data', $agora);
            $sql_log->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function concluir(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        // Atualizar o chamado para concluído e adicionar a data de conclusão
        $sql = $this->pdo->prepare('UPDATE chamados SET status = :status, finished_at = :finished_at, updated_at = :updated_at WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $status = 3; // Status 3 para concluído
        $sql->bindParam(':status', $status);
        $sql->bindParam(':finished_at', $agora);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            // Registrar log da conclusão
            $descricao_log = "Concluiu o chamado ($id_chamado): $nome_chamado";

            $sql_log = $this->pdo->prepare('INSERT INTO logs 
                                            (acao, id_usuario, descricao, data)
                                            VALUES
                                            (:acao, :id_usuario, :descricao, :data)
                                        ');

            $acao = 'Concluir';
            $sql_log->bindParam(':acao', $acao);
            $sql_log->bindParam(':id_usuario', $id_usuario);
            $sql_log->bindParam(':descricao', $descricao_log);
            $sql_log->bindParam(':data', $agora);
            $sql_log->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function recusar(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        // Atualizar o chamado para recusado e adicionar a data de atualização
        $sql = $this->pdo->prepare('UPDATE chamados SET status = :status, updated_at = :updated_at, finished_at = :finished_at WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $status = 5; // Status 5 para recusado
        $sql->bindParam(':status', $status);
        $sql->bindParam(':finished_at', $agora);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            // Registrar log da recusa
            $descricao_log = "Recusou o chamado ($id_chamado): $nome_chamado";

            $sql_log = $this->pdo->prepare('INSERT INTO logs 
                                            (acao, id_usuario, descricao, data)
                                            VALUES
                                            (:acao, :id_usuario, :descricao, :data)
                                        ');

            $acao = 'Recusar';
            $sql_log->bindParam(':acao', $acao);
            $sql_log->bindParam(':id_usuario', $id_usuario);
            $sql_log->bindParam(':descricao', $descricao_log);
            $sql_log->bindParam(':data', $agora);
            $sql_log->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function reabrir(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        // Atualizar o chamado para recusado e adicionar a data de atualização
        $sql = $this->pdo->prepare('UPDATE chamados SET status = :status, updated_at = :updated_at, finished_at = NULL, started_at = NULL WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $status = 1; // Status 1 para em análise
        $sql->bindParam(':status', $status);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            // Registrar log da recusa
            $descricao_log = "Reabriu o chamado ($id_chamado): $nome_chamado";

            $sql_log = $this->pdo->prepare('INSERT INTO logs 
                                            (acao, id_usuario, descricao, data)
                                            VALUES
                                            (:acao, :id_usuario, :descricao, :data)
                                        ');

            $acao = 'Reabrir';
            $sql_log->bindParam(':acao', $acao);
            $sql_log->bindParam(':id_usuario', $id_usuario);
            $sql_log->bindParam(':descricao', $descricao_log);
            $sql_log->bindParam(':data', $agora);
            $sql_log->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

    public function iniciar(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
        } else {
            $nome_chamado = "Chamado Desconhecido";
        }

        // Atualizar o chamado para recusado e adicionar a data de atualização
        $sql = $this->pdo->prepare('UPDATE chamados SET status = :status, updated_at = :updated_at, started_at = :started_at WHERE id_chamado = :id_chamado');
        $agora = date("Y-m-d H:i:s");
        $status = 2; // Status 2 para em andamento
        $sql->bindParam(':status', $status);
        $sql->bindParam(':started_at', $agora);
        $sql->bindParam(':updated_at', $agora);
        $sql->bindParam(':id_chamado', $id_chamado);

        if ($sql->execute()) {
            // Registrar log da recusa
            $descricao_log = "Iniciou o chamado ($id_chamado): $nome_chamado";

            $sql_log = $this->pdo->prepare('INSERT INTO logs 
                                            (acao, id_usuario, descricao, data)
                                            VALUES
                                            (:acao, :id_usuario, :descricao, :data)
                                        ');

            $acao = 'Iniciar';
            $sql_log->bindParam(':acao', $acao);
            $sql_log->bindParam(':id_usuario', $id_usuario);
            $sql_log->bindParam(':descricao', $descricao_log);
            $sql_log->bindParam(':data', $agora);
            $sql_log->execute();

            return header('location:/GRNacoes/chamados');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }

}

?>
