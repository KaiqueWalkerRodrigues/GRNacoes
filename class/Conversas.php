<?php

class Conversa {

    public $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conexao();
    }

    public function listar() {
        $sql = $this->pdo->prepare('SELECT * FROM conversas WHERE deleted_at IS NULL ORDER BY created_at DESC');
        $sql->execute();

        $dados = $sql->fetchAll(PDO::FETCH_OBJ);

        return $dados;
    }

    public function mostrar(int $id_conversa) {
        $sql = $this->pdo->prepare('SELECT * FROM conversas WHERE id_conversa = :id_conversa AND deleted_at IS NULL');
        $sql->bindParam(':id_conversa', $id_conversa);
        $sql->execute();

        $conversa = $sql->fetch(PDO::FETCH_OBJ);

        return $conversa;
    }

    public function cadastrar(array $dados) {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO conversas 
                                    (nome, tipo, created_at, updated_at)
                                    VALUES
                                    (:nome, :tipo, :created_at, :updated_at)
                                ');

        $sql->bindParam(':nome', $dados['nome']);
        $sql->bindParam(':tipo', $dados['tipo']);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            $id_conversa = $this->pdo->lastInsertId();

            // Log da ação
            $descricao = "Criou uma nova conversa de ID: {$id_conversa}";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Criar Conversa';

            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return $id_conversa;
        }

        return false;
    }

    public function editar(array $dados) {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare("UPDATE conversas SET
            nome = :nome,
            tipo = :tipo,
            updated_at = :updated_at 
        WHERE id_conversa = :id_conversa AND deleted_at IS NULL
        ");

        $sql->bindParam(':id_conversa', $dados['id_conversa']);
        $sql->bindParam(':nome', $dados['nome']);
        $sql->bindParam(':tipo', $dados['tipo']);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            // Log da ação
            $descricao = "Editou a conversa de ID: {$dados['id_conversa']}";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Editar Conversa';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();
        }
    }

    public function desativar(int $id_conversa) {
        $sql = $this->pdo->prepare('UPDATE conversas 
                                    SET 
                                    deleted_at = :deleted_at 
                                    WHERE id_conversa = :id_conversa
                                    ');

        $deleted_at = date("Y-m-d H:i:s");

        $sql->bindParam(':id_conversa', $id_conversa);
        $sql->bindParam(':deleted_at', $deleted_at);

        if ($sql->execute()) {
            // Log da ação
            $descricao = "Desativou a conversa de ID: $id_conversa";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Desativar Conversa';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $deleted_at);
            $sql->execute();
        }
    }

    public function adicionarParticipante(int $id_conversa, int $id_usuario) {
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO participantes 
                                    (id_conversa, id_usuario, created_at, updated_at)
                                    VALUES
                                    (:id_conversa, :id_usuario, :created_at, :updated_at)
                                ');

        $sql->bindParam(':id_conversa', $id_conversa);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':created_at', $agora);
        $sql->bindParam(':updated_at', $agora);

        if ($sql->execute()) {
            // Log da ação
            $descricao = "Adicionou o usuário de ID: $id_usuario à conversa de ID: $id_conversa";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Adicionar Participante';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();
        }
    }

    public function removerParticipante(int $id_conversa, int $id_usuario) {
        $sql = $this->pdo->prepare('UPDATE participantes 
                                    SET 
                                    deleted_at = :deleted_at 
                                    WHERE id_conversa = :id_conversa AND id_usuario = :id_usuario
                                    ');

        $deleted_at = date("Y-m-d H:i:s");

        $sql->bindParam(':id_conversa', $id_conversa);
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':deleted_at', $deleted_at);

        if ($sql->execute()) {
            // Log da ação
            $descricao = "Removeu o usuário de ID: $id_usuario da conversa de ID: $id_conversa";

            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, descricao, data)
                                        VALUES
                                        (:acao, :descricao, :data)
                                    ');

            $acao = 'Remover Participante';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $deleted_at);
            $sql->execute();
        }
    }
}

?>
