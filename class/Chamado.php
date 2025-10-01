<?php

class Chamado {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

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

    private function notificar($tipo,$id_chamado,$id_setor = null,$id_usuario = null,$status = null){
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO notificacoes_chamados
                                    (id_chamado,id_usuario,id_setor,texto,tipo,created_at,updated_at)
                                    VALUES
                                    (:id_chamado,:id_usuario,:id_setor,:texto,:tipo,:created_at,:updated_at)
        ');

        switch($tipo){
            case 0:
                $texto = "(#{$id_chamado}) Novo Chamado Aberto";
            break;
            case 1:
                $texto = "(#{$id_chamado}) Nova Mensagem";
            break;
            case 2:
                $texto = "(#{$id_chamado}) Alterado para {$status}";
            break;
            case 3:
                $texto = "(#{$id_chamado}) Foi Encaminhado";
            break;
        }

        $sql->bindParam(':id_chamado', $id_chamado); 
        $sql->bindParam(':id_usuario', $id_usuario); 
        $sql->bindParam(':id_setor', $id_setor);  
        $sql->bindParam(':texto', $texto);  
        $sql->bindParam(':tipo', $tipo); 
        $sql->bindParam(':created_at', $agora); 
        $sql->bindParam(':updated_at', $agora); 

        $sql->execute();
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
        $urgencia = 0;
        $id_usuario = $dados['id_usuario'];
        $id_setor = $dados['id_setor'];
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
            $id_chamado = $this->pdo->lastInsertId();

            $this->notificar(0,$id_chamado,$id_setor,null,null);

            // Log do chamado criado
            $descricao= "Cadastrou o chamado: $titulo ($id_chamado)";
            $this->addLog('Cadastrar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Aberto com Sucesso!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possivel Abrir o Chamado!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
            exit;
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
            $descricao = "Editou o chamado: $titulo ($id_chamado)";
            $this->addLog('Editar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Editado com Sucesso!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Editar o Chamado!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
            exit;
        }
    }

    public function deletar(int $id_chamado, $id_usuario)
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
            $descricao = "Deletou o chamado $nome_chamado($id_chamado)";
            $this->addLog('Deletar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Deletado com Sucesso!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Deletar o Chamado!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
            exit;
        }
    }

    public function encaminhar(int $id_chamado, int $id_setor_novo, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo, id_setor, id_usuario FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_setor_antigo = $resultado_chamado['id_setor'];
            $id_criador = $resultado_chamado['id_setor'];
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
            $this->notificar(3,$id_chamado,null,$id_criador,null);

            $descricao = "Encaminhou o chamado ($id_chamado) do setor ($id_setor_antigo) para o setor ($id_setor_novo)";
            $this->addLog('Encaminhar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Encaminhado com Sucesso!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Deletar o Chamado!');
                window.location.href = '" . URL . "/chamados/meus_chamados';
            </script>";
        }
    }

    public function concluir(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo,id_usuario FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_criador = $resultado_chamado['id_usuario'];
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
            $this->notificar(2,$id_chamado,null,$id_criador,Helper::statusChamado($status));

            $descricao = "Concluiu o chamado ($id_chamado): $nome_chamado";
            $this->addLog('Concluir',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Concluído com Sucesso!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Concluir o Chamado!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        }
    }

    public function recusar(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo,id_usuario FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_criador = $resultado_chamado['id_usuario'];
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
            $this->notificar(2,$id_chamado,null,$id_criador,Helper::statusChamado($status));

            $descricao = "Recusou o chamado ($id_chamado): $nome_chamado";
            $this->addLog('Recusar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Recusado com Sucesso!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Recusar o Chamado!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        }
    }

    public function reabrir(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo,id_usuario FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_criador = $resultado_chamado['id_usuario'];
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
            $this->notificar(2,$id_chamado,null,$id_criador,Helper::statusChamado($status));

            $descricao = "Reabriu o chamado ($id_chamado): $nome_chamado";
            $this->addLog('Reabrir',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Reaberto com Sucesso!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Reabrir o Chamado!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        }
    }

    public function iniciar(int $id_chamado, int $id_usuario)
    {
        // Consultar informações do chamado atual
        $consulta_chamado = $this->pdo->prepare('SELECT titulo,id_usuario FROM chamados WHERE id_chamado = :id_chamado');
        $consulta_chamado->bindParam(':id_chamado', $id_chamado);
        $consulta_chamado->execute();
        $resultado_chamado = $consulta_chamado->fetch(PDO::FETCH_ASSOC);

        if ($resultado_chamado) {
            $nome_chamado = $resultado_chamado['titulo'];
            $id_criador = $resultado_chamado['id_usuario'];
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
            $this->notificar(2,$id_chamado,null,$id_criador,Helper::statusChamado($status));

            $descricao = "Iniciou o chamado ($id_chamado): $nome_chamado";
            $this->addLog('Iniciar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Iniciado com Sucesso!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Iniciar o Chamado!');
                window.location.href = '" . URL . "/chamados/';
            </script>";
        }
    }

    public function definirUrgenciaSla(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE chamados SET
            urgencia = :urgencia,
            sla = :sla,
            updated_at = :updated_at 
        WHERE id_chamado = :id_chamado
        ");

        $agora = date("Y-m-d H:i:s");

        $id_chamado = $dados['id_chamado'];
        $urgencia = $dados['urgencia'];
        $sla = $dados['sla'];
        $updated_at = $agora; 
        $id_usuario = $dados['id_usuario'];

        $sql->bindParam(':id_chamado',$id_chamado);
        $sql->bindParam(':urgencia',$urgencia);
        $sql->bindParam(':sla',$sla);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Definiu a urgencia e/ou SLA do chamado($id_chamado)";
            $this->addLog('Editar',$descricao,$id_usuario);

            echo "
            <script>
                alert('Chamado Editado com Sucesso!');
                window.location.href = '" . URL . "/chamados';
            </script>";
        } else {
            echo "
            <script>
                alert('Não foi possivel Editar o Chamado!');
                window.location.href = '" . URL . "/chamados';
            </script>";
            exit;
        }
    }

}

