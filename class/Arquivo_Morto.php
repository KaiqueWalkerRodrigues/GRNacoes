<?php

class Arquivo_Morto {

    # ATRIBUTOS	
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

    /**
     * listar todas as caixas
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT c.*, l.nome_local 
                                   FROM arquivo_morto_caixas c 
                                   LEFT JOIN arquivo_morto_locais l ON c.id_local = l.id_local 
                                   WHERE c.deleted_at IS NULL 
                                   ORDER BY c.numero_caixa');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }

    /**
     * listar todos os locais
     * @return array
     * @example $variavel = $Obj->listarLocais()
     */
    public function listarLocais(){
        $sql = $this->pdo->prepare('SELECT * FROM arquivo_morto_locais WHERE deleted_at IS NULL ORDER BY nome_local');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }

    /**
     * contar total de caixas
     * @return int
     */
    public function contarCaixas(){
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM arquivo_morto_caixas WHERE deleted_at IS NULL');        
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->total;
    }

    /**
     * contar total de locais
     * @return int
     */
    public function contarLocais(){
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM arquivo_morto_locais WHERE deleted_at IS NULL');        
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->total;
    }

    /**
     * contar total de itens
     * @return int
     */
    public function contarItens(){
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM arquivo_morto_itens WHERE deleted_at IS NULL');        
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->total;
    }

    /**
     * obter última caixa cadastrada
     * @return string
     */
    public function ultimaCaixa(){
        $sql = $this->pdo->prepare('SELECT numero_caixa FROM arquivo_morto_caixas WHERE deleted_at IS NULL ORDER BY id_caixa DESC LIMIT 1');        
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado ? $resultado->numero_caixa : '-';
    }

    /**
     * cadastra uma nova caixa
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados){
        $numero_caixa = trim($dados['numero_caixa']);
        $id_local = $dados['id_local'];
        $observacoes = trim($dados['observacoes']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        // Verificar se já existe uma caixa com este número
        $verificar = $this->pdo->prepare('SELECT COUNT(*) as total FROM arquivo_morto_caixas WHERE numero_caixa = :numero_caixa AND deleted_at IS NULL');
        $verificar->bindParam(':numero_caixa', $numero_caixa);
        $verificar->execute();
        $existe = $verificar->fetch(PDO::FETCH_OBJ);

        if($existe->total > 0){
            echo "
            <script>
                alert('Já existe uma caixa com este número!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }

        $sql = $this->pdo->prepare('INSERT INTO arquivo_morto_caixas 
                                    (numero_caixa, id_local, observacoes, created_at, updated_at)
                                    VALUES
                                    (:numero_caixa, :id_local, :observacoes, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':numero_caixa', $numero_caixa);
        $sql->bindParam(':id_local', $id_local);
        $sql->bindParam(':observacoes', $observacoes);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $caixa_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou a Caixa: $numero_caixa ($caixa_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Caixa Cadastrada com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar a Caixa!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    /**
     * cadastra um novo local
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrarLocal($_POST);
     * 
     */
    public function cadastrarLocal(Array $dados){
        $nome_local = ucwords(strtolower(trim($dados['nome_local'])));
        $descricao = trim($dados['descricao_local']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        // Verificar se já existe um local com este nome
        $verificar = $this->pdo->prepare('SELECT COUNT(*) as total FROM arquivo_morto_locais WHERE nome_local = :nome_local AND deleted_at IS NULL');
        $verificar->bindParam(':nome_local', $nome_local);
        $verificar->execute();
        $existe = $verificar->fetch(PDO::FETCH_OBJ);

        if($existe->total > 0){
            echo "
            <script>
                alert('Já existe um local com este nome!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }

        $sql = $this->pdo->prepare('INSERT INTO arquivo_morto_locais 
                                    (nome_local, descricao, created_at, updated_at)
                                    VALUES
                                    (:nome_local, :descricao, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':nome_local', $nome_local);
        $sql->bindParam(':descricao', $descricao);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $local_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Local: $nome_local ($local_id)";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Local Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Local!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de uma caixa
     * @param int $id_caixa
     * @return object
     * @example $variavel = $Obj->mostrar($id_caixa);
     */
    public function mostrar(int $id_caixa){
    	$sql = $this->pdo->prepare('SELECT c.*, l.nome_local 
                                   FROM arquivo_morto_caixas c 
                                   LEFT JOIN arquivo_morto_locais l ON c.id_local = l.id_local 
                                   WHERE c.id_caixa = :id_caixa LIMIT 1');
        $sql->bindParam(':id_caixa', $id_caixa);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza uma determinada caixa
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados){
        $sql = $this->pdo->prepare("UPDATE arquivo_morto_caixas SET
            numero_caixa = :numero_caixa,
            id_local = :id_local,
            observacoes = :observacoes,
            updated_at = :updated_at 
        WHERE id_caixa = :id_caixa
        ");

        $agora = date("Y-m-d H:i:s");

        $id_caixa = $dados['id_caixa'];
        $numero_caixa = trim($dados['numero_caixa']);
        $id_local = $dados['id_local'];
        $observacoes = trim($dados['observacoes']);
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_caixa',$id_caixa);
        $sql->bindParam(':numero_caixa',$numero_caixa);
        $sql->bindParam(':id_local',$id_local);
        $sql->bindParam(':observacoes',$observacoes);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou a Caixa: $numero_caixa ($id_caixa)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Caixa Editada com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar a Caixa!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    /**
     * Deleta uma caixa
     *
     * @param integer $id_caixa
     * @return void (esse metodo não retorna nada)
     */
    public function deletar(int $id_caixa, $usuario_logado){
        // Consulta para obter o número da caixa
        $consulta_caixa = $this->pdo->prepare('SELECT numero_caixa FROM arquivo_morto_caixas WHERE id_caixa = :id_caixa');
        $consulta_caixa->bindParam(':id_caixa', $id_caixa);
        $consulta_caixa->execute();
        $resultado_caixa = $consulta_caixa->fetch(PDO::FETCH_ASSOC);

        // Verifica se a caixa foi encontrada
        if ($resultado_caixa) {
            $numero_caixa = $resultado_caixa['numero_caixa'];
        } else {
            $numero_caixa = "Caixa Desconhecida";
        }

        // Atualiza o registro da caixa com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE arquivo_morto_caixas SET deleted_at = :deleted_at WHERE id_caixa = :id_caixa');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_caixa', $id_caixa);

        if ($sql->execute()) {
            $descricao = "Deletou a caixa $numero_caixa ($id_caixa)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Caixa Deletada com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar a Caixa!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    // ========== MÉTODOS PARA ITENS ==========

    /**
     * listar todos os itens de uma caixa
     * @param int $id_caixa
     * @return array
     * @example $variavel = $Obj->listarItens($id_caixa)
     */
    public function listarItens(int $id_caixa){
        $sql = $this->pdo->prepare('SELECT * FROM arquivo_morto_itens WHERE id_caixa = :id_caixa AND deleted_at IS NULL ORDER BY created_at DESC');
        $sql->bindParam(':id_caixa', $id_caixa);        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        return $dados;
    }

    /**
     * cadastra um novo item
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrarItem($_POST);
     * 
     */
    public function cadastrarItem(Array $dados){
        $id_caixa = $dados['id_caixa'];
        $tipo_documento = trim($dados['tipo_documento']);
        $nome_documento = trim($dados['nome_documento']);
        $departamento = trim($dados['departamento']);
        $data_documento = $dados['data_documento'] ? $dados['data_documento'] : null;
        $data_arquivamento = $dados['data_arquivamento'] ? $dados['data_arquivamento'] : null;
        $observacoes_item = trim($dados['observacoes_item']);
        $usuario_logado = $dados['usuario_logado'];
        $agora = date("Y-m-d H:i:s");

        $sql = $this->pdo->prepare('INSERT INTO arquivo_morto_itens 
                                    (id_caixa, tipo_documento, nome_documento, departamento, data_documento, data_arquivamento, observacoes_item, created_at, updated_at)
                                    VALUES
                                    (:id_caixa, :tipo_documento, :nome_documento, :departamento, :data_documento, :data_arquivamento, :observacoes_item, :created_at, :updated_at)
                                ');

        $created_at  = $agora;
        $updated_at  = $agora;

        $sql->bindParam(':id_caixa', $id_caixa);
        $sql->bindParam(':tipo_documento', $tipo_documento);
        $sql->bindParam(':nome_documento', $nome_documento);
        $sql->bindParam(':departamento', $departamento);
        $sql->bindParam(':data_documento', $data_documento);
        $sql->bindParam(':data_arquivamento', $data_arquivamento);
        $sql->bindParam(':observacoes_item', $observacoes_item);          
        $sql->bindParam(':created_at', $created_at);          
        $sql->bindParam(':updated_at', $updated_at);          

        if ($sql->execute()) {
            $item_id = $this->pdo->lastInsertId();
            
            $descricao = "Cadastrou o Item: $nome_documento ($item_id) na Caixa ID: $id_caixa";
            $this->addLog("Cadastrar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Item Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Item!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    /**
     * Retorna os dados de um item
     * @param int $id_item
     * @return object
     * @example $variavel = $Obj->mostrarItem($id_item);
     */
    public function mostrarItem(int $id_item){
    	$sql = $this->pdo->prepare('SELECT i.*, c.numero_caixa 
                                   FROM arquivo_morto_itens i 
                                   LEFT JOIN arquivo_morto_caixas c ON i.id_caixa = c.id_caixa 
                                   WHERE i.id_item = :id_item LIMIT 1');
        $sql->bindParam(':id_item', $id_item);
    	$sql->execute();
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza um determinado item
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editarItem($_POST);
     */
    public function editarItem(array $dados){
        $sql = $this->pdo->prepare("UPDATE arquivo_morto_itens SET
            tipo_documento = :tipo_documento,
            nome_documento = :nome_documento,
            departamento = :departamento,
            data_documento = :data_documento,
            data_arquivamento = :data_arquivamento,
            observacoes_item = :observacoes_item,
            updated_at = :updated_at 
        WHERE id_item = :id_item
        ");

        $agora = date("Y-m-d H:i:s");

        $id_item = $dados['id_item'];
        $tipo_documento = trim($dados['tipo_documento']);
        $nome_documento = trim($dados['nome_documento']);
        $departamento = trim($dados['departamento']);
        $data_documento = $dados['data_documento'] ? $dados['data_documento'] : null;
        $data_arquivamento = $dados['data_arquivamento'] ? $dados['data_arquivamento'] : null;
        $observacoes_item = trim($dados['observacoes_item']);
        $updated_at = $agora; 
        $usuario_logado = $dados['usuario_logado'];

        $sql->bindParam(':id_item',$id_item);
        $sql->bindParam(':tipo_documento',$tipo_documento);
        $sql->bindParam(':nome_documento',$nome_documento);
        $sql->bindParam(':departamento',$departamento);
        $sql->bindParam(':data_documento',$data_documento);
        $sql->bindParam(':data_arquivamento',$data_arquivamento);
        $sql->bindParam(':observacoes_item',$observacoes_item);
        $sql->bindParam(':updated_at', $updated_at);       

        if ($sql->execute()) {
            $descricao = "Editou o Item: $nome_documento ($id_item)";
            $this->addLog("Editar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Item Editado com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Item!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

    /**
     * Deleta um item
     *
     * @param integer $id_item
     * @return void (esse metodo não retorna nada)
     */
    public function deletarItem(int $id_item, $usuario_logado){
        // Consulta para obter o nome do item
        $consulta_item = $this->pdo->prepare('SELECT nome_documento FROM arquivo_morto_itens WHERE id_item = :id_item');
        $consulta_item->bindParam(':id_item', $id_item);
        $consulta_item->execute();
        $resultado_item = $consulta_item->fetch(PDO::FETCH_ASSOC);

        // Verifica se o item foi encontrado
        if ($resultado_item) {
            $nome_documento = $resultado_item['nome_documento'];
        } else {
            $nome_documento = "Item Desconhecido";
        }

        // Atualiza o registro do item com a data de exclusão
        $sql = $this->pdo->prepare('UPDATE arquivo_morto_itens SET deleted_at = :deleted_at WHERE id_item = :id_item');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_item', $id_item);

        if ($sql->execute()) {
            $descricao = "Deletou o item $nome_documento ($id_item)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Item Deletado com Sucesso!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Item!');
                window.location.href = '" . URL . "/arquivos/arquivos_mortos';
            </script>";
            exit;
        }
    }

 }

?>

