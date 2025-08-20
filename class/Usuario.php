<?php 

class Usuario {

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

    private function adicionarSetor($id_usuario,$id_setor,$principal = 0){
        $sql=$this->pdo->prepare('INSERT INTO usuarios_setores (id_usuario,id_setor,principal,created_at,updated_at) 
                                                        VALUES (:id_usuario,:id_setor,:principal,:created_at,:updated_at)');
        $agora = date("Y-m-d H:i:s");

        $sql->bindParam(':id_usuario',$id_usuario);
        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam('principal',$principal);
        $sql->bindParam('created_at',$agora);
        $sql->bindParam('updated_at',$agora);
        $sql->execute();
    }

    private function EditarSetorPrincipal($id_usuario,$id_setor){
        $sql=$this->pdo->prepare('UPDATE usuarios_setores SET id_setor = :id_setor,
                                                        updated_at = :updated_at
                                                        WHERE id_usuario = :id_usuario
                                                        AND principal = 1
                                                        AND deleted_at IS NULL');
        $agora = date("Y-m-d H:i:s");

        $sql->bindParam(':id_usuario',$id_usuario);
        $sql->bindParam(':id_setor',$id_setor);
        $sql->bindParam('updated_at',$agora);
        $sql->execute();
    }

    //Listar todos os Usuários Não Deletados e Ordenados pelo Nome.
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL ORDER BY nome ASC');        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar todos as Solicitacões de Cadastro
    public function listarSolicitacoes(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND ativo = 2 ORDER BY nome ASC');        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar todos os Usuários Ativos, Não Deletados e Ordenados pelo Nome.
    public function listarAtivos(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND ativo = 1 ORDER BY nome ASC');        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar todos os Usuários Ativos, Não Deletados e Ordenados pelo Nome.
    public function listarAtivosDoCargo($id_cargo){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE id_cargo = :id_cargo AND deleted_at IS NULL AND ativo = 1 ORDER BY nome ASC');        
        $sql->bindParam(':id_cargo',$id_cargo);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar todos os Usuários Ativos, Não Deletados menos si mesmo e Ordenados pelo nome.
    public function listarAtivosMenosEu($id_usuario){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND id_usuario != :id_usuario AND ativo = 1 ORDER BY nome ASC');        
        $sql->bindParam(':id_usuario',$id_usuario);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar todos os Usuários Ativos de uma Determinado Setor/Empresa, Não Deletados e Ordenados pelo Nome.
    public function listarAtivosDoSetorDaEmpresa($id_setor, $id_empresa = null){
        // Construir a base da consulta
        $query = 'SELECT * FROM usuarios u INNER JOIN usuarios_setores us ON u.id_usuario = us.id_usuario WHERE us.deleted_at IS NULL AND ativo = 1 AND us.id_setor = :id_setor';
    
        // Verificar se o id_empresa foi enviado
        if ($id_empresa !== null) {
            // Adicionar a condição para a empresa
            $query .= ' AND empresa = :empresa';
        }
    
        $query .= ' ORDER BY nome ASC';
    
        // Preparar a consulta
        $sql = $this->pdo->prepare($query);
    
        // Vincular o parâmetro id_setor
        $sql->bindParam(':id_setor', $id_setor);
    
        // Vincular o parâmetro empresa se foi fornecido
        if ($id_empresa !== null) {
            $sql->bindParam(':empresa', $id_empresa);
        }
    
        // Executar a consulta
        $sql->execute();
    
        // Retornar os resultados
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
    
    //Listar todos os Usuários Desativos, Não Deletados e Ordenados pelo Nome.
    public function listarDesativados(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND ativo = 0 ORDER BY nome ASC');        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar Usuários Vendedores (Se houver) uma empresa em Especifico.
    public function listarVendedores($empresa = '%'){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND empresa LIKE :empresa AND id_cargo = 9 OR id_cargo = 10 OR id_cargo = 11 AND deleted_at IS NULL AND ativo = 1 ORDER BY nome');
        $sql->bindParam(':empresa',$empresa);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Listar Usuários Vendedores (Se houver) uma empresa em Especifico.
    public function listarVendedoresAtivos($empresa = '%'){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND ativo = 1 AND empresa LIKE :empresa AND id_cargo = 9 OR id_cargo = 10 OR id_cargo = 11 AND deleted_at IS NULL AND ativo = 1 ORDER BY nome');
        $sql->bindParam(':empresa',$empresa);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function cadastrar(Array $dados){
        $sql = $this->pdo->prepare('INSERT INTO usuarios 
                                    (nome,ativo,usuario,senha,contrato,celular,cpf,
                                    data_nascimento,email,empresa,id_cargo,n_folha,
                                    data_admissao,created_at,updated_at)
                                    VALUES
                                    (:nome,:ativo,:usuario,:senha,:contrato,:celular,:cpf,
                                    :data_nascimento,:email,:empresa,:id_cargo,:n_folha,
                                    :data_admissao,:created_at,:updated_at)
                                ');

        $nome  = ucwords(strtolower(trim($dados['nome'])));
        $ativo  = 1;
        $usuario  = trim($dados['usuario']);
        $senha  = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $contrato  = $dados['contrato'];
        $celular  = preg_replace('/[^0-9]/', '', $dados['celular']); 
        $cpf  = preg_replace('/[^0-9]/', '', $dados['cpf']);
        $data_nascimento  = $dados['data_nascimento'];
        $email  = $dados['email'];
        $empresa  = ucwords(strtolower(trim($dados['empresa'])));
        $id_setor  = $dados['id_setor'];
        $id_cargo  = $dados['id_cargo'];
        $n_folha  = $dados['n_folha'];
        $data_admissao  = $dados['data_admissao'];
        $usuario_logado  = $dados['usuario_logado'];

        $agora = date("Y-m-d H:i:s");

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':ativo',$ativo);              
        $sql->bindParam(':usuario',$usuario);              
        $sql->bindParam(':senha',$senha);              
        $sql->bindParam(':contrato',$contrato);              
        $sql->bindParam(':celular',$celular);              
        $sql->bindParam(':cpf',$cpf);              
        $sql->bindParam(':data_nascimento',$data_nascimento);              
        $sql->bindParam(':email',$email);              
        $sql->bindParam(':empresa',$empresa);                            
        $sql->bindParam(':id_cargo',$id_cargo);              
        $sql->bindParam(':n_folha',$n_folha);              
        $sql->bindParam(':data_admissao',$data_admissao);              
        $sql->bindParam(':created_at',$created_at);          
        $sql->bindParam(':updated_at',$updated_at);       

        if ($sql->execute()) {
            $id_usuario = $this->pdo->lastInsertId();
            $descricao = "Cadastrou o usuário: $nome ($id_usuario)";
            $this->addLog('Cadastrar', $descricao, $usuario_logado);

            $this->adicionarSetor($id_usuario,$id_setor,1);

            echo "
            <script>
                alert('Usuário Cadastrado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Cadastrar o Usuário!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }
    }

    public function cadastrarSolicitacao(Array $dados){
        $sql = $this->pdo->prepare('INSERT INTO usuarios 
                                    (nome,ativo,usuario,senha,contrato,celular,cpf,
                                    data_nascimento,email,empresa,id_cargo,n_folha,
                                    data_admissao,created_at,updated_at)
                                    VALUES
                                    (:nome,:ativo,:usuario,:senha,:contrato,:celular,:cpf,
                                    :data_nascimento,:email,:empresa,:id_cargo,:n_folha,
                                    :data_admissao,:created_at,:updated_at)
                                ');

        $nome  = ucwords(strtolower(trim($dados['nome'])));
        $ativo  = 2;
        $usuario  = trim($dados['usuario']);
        $senha  = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $contrato  = $dados['contrato'];
        $celular  = preg_replace('/[^0-9]/', '', $dados['celular']); 
        $cpf  = preg_replace('/[^0-9]/', '', $dados['cpf']);
        $data_nascimento  = $dados['data_nascimento'];
        $email  = $dados['email'];
        $empresa  = ucwords(strtolower(trim($dados['empresa'])));
        $id_setor  = $dados['id_setor'];
        $id_cargo  = $dados['id_cargo'];
        $n_folha  = $dados['n_folha'];
        $data_admissao  = $dados['data_admissao'];

        $agora = date("Y-m-d H:i:s");

        $created_at = $agora;
        $updated_at = $agora;

        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':ativo',$ativo);              
        $sql->bindParam(':usuario',$usuario);              
        $sql->bindParam(':senha',$senha);              
        $sql->bindParam(':contrato',$contrato);              
        $sql->bindParam(':celular',$celular);              
        $sql->bindParam(':cpf',$cpf);              
        $sql->bindParam(':data_nascimento',$data_nascimento);              
        $sql->bindParam(':email',$email);              
        $sql->bindParam(':empresa',$empresa);                            
        $sql->bindParam(':id_cargo',$id_cargo);              
        $sql->bindParam(':n_folha',$n_folha);              
        $sql->bindParam(':data_admissao',$data_admissao);              
        $sql->bindParam(':created_at',$created_at);          
        $sql->bindParam(':updated_at',$updated_at);       

        if ($sql->execute()) {
            $id_usuario = $this->pdo->lastInsertId();

            $this->adicionarSetor($id_usuario,$id_setor,1);

            echo "
            <script>
                window.location.href = '" . URL . "/';
            </script>";
            exit;
        }
    }

    //Mostrar informações de um Usuário.
    public function mostrar(int $id_usuario){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1');
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    //Função para editar usuarios
    public function editar(array $dados){
        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $senha = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $sql = $this->pdo->prepare("UPDATE usuarios SET
                                        nome = :nome,
                                        ativo = :ativo,
                                        usuario = :usuario,
                                        senha = :senha,
                                        contrato = :contrato,
                                        celular = :celular,
                                        cpf = :cpf,
                                        data_nascimento = :data_nascimento,
                                        email = :email,
                                        empresa = :empresa,                                   
                                        id_cargo = :id_cargo,
                                        n_folha = :n_folha,
                                        data_admissao = :data_admissao,
                                        updated_at = :updated_at                              
                                        WHERE id_usuario = :id_usuario
                                    ");
        } else {
            // Caso a senha não tenha sido preenchida, não a atualiza
            $sql = $this->pdo->prepare("UPDATE usuarios SET
                                        nome = :nome,
                                        ativo = :ativo,
                                        usuario = :usuario,
                                        contrato = :contrato,
                                        celular = :celular,
                                        cpf = :cpf,
                                        data_nascimento = :data_nascimento,
                                        email = :email,
                                        empresa = :empresa,                                   
                                        id_cargo = :id_cargo,
                                        n_folha = :n_folha,
                                        data_admissao = :data_admissao,
                                        updated_at = :updated_at                              
                                        WHERE id_usuario = :id_usuario
                                    ");
        }
    
        $nome = ucwords(strtolower(trim($dados['nome'])));
        $usuario = trim($dados['usuario']);
        $ativo = $dados['ativo'];
        $contrato = $dados['contrato'];        
        $celular  = preg_replace('/[^0-9]/', '', $dados['celular']); 
        $cpf  = preg_replace('/[^0-9]/', '', $dados['cpf']);
        $data_nascimento = $dados['data_nascimento'];
        $email = ucwords(strtolower(trim($dados['email'])));
        $empresa = $dados['empresa'];
        $id_setor = $dados['id_setor'];
        $id_cargo = $dados['id_cargo'];
        $n_folha = $dados['n_folha'];
        $data_admissao = $dados['data_admissao'];
        $updated_at = date('Y-m-d H:i');
        $id_usuario = $dados['id_usuario']; 
        $usuario_logado = $dados['usuario_logado'];
    
        if (isset($senha)) {
            $sql->bindParam(':senha', $senha);   
        }
    
        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':ativo',$ativo);              
        $sql->bindParam(':usuario',$usuario);              
        $sql->bindParam(':contrato',$contrato);              
        $sql->bindParam(':celular',$celular);              
        $sql->bindParam(':cpf',$cpf);              
        $sql->bindParam(':data_nascimento',$data_nascimento);              
        $sql->bindParam(':email', $email);
        $sql->bindParam(':empresa',$empresa);   
        $sql->bindParam(':id_cargo',$id_cargo);  
        $sql->bindParam(':n_folha',$n_folha);  
        $sql->bindParam(':data_admissao',$data_admissao);  
        $sql->bindParam(':updated_at',$updated_at);        
        $sql->bindParam(':id_usuario',$id_usuario);       
    
        if ($sql->execute()) {
            $descricao = "Editou o usuário: $nome ($id_usuario)";
            $this->addLog('Editar', $descricao, $usuario_logado);

            $this->EditarSetorPrincipal($id_usuario,$id_setor);
    
            echo "
            <script>
                alert('Usuário Editado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Editar o Usuário!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }
    }
    
    //Editar Avatar do Usuário.
    public function editarAvatar($id_usuario,$id_avatar){
        $sql = $this->pdo->prepare("UPDATE usuarios SET id_avatar = :id_avatar WHERE id_usuario = :id_usuario");
        $sql->bindParam(':id_avatar',$id_avatar);
        $sql->bindParam(':id_usuario',$id_usuario);
        
        $agora = date('Y-m-d H:i:s');

        if ($sql->execute()) {
            $sql = $this->pdo->prepare("SELECT nome FROM usuarios WHERE id_usuario = :id_usuario");
            $sql->bindParam(':id_usuario',$id_usuario);
            $sql->execute();
            $nome = $sql->fetch(PDO::FETCH_OBJ)->nome;

            $descricao = "$nome Alterou seu própio avatar para ($id_avatar)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Editar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $id_usuario);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            echo "
                <script>
                    alert('Avatar Alterado com Sucesso Re-Acesse para alterar!!');
                    window.location.href = '" . URL . "/perfil';
                </script>";
                exit;
        } else {
            echo "
                <script>
                    alert('Não foi possível Alterar o Avatar!');
                    window.location.href = '" . URL . "/perfil';
                </script>";
                exit;
        }
    }

    public function editarSetores(array $id_setores, int $id_usuario){
        try {
            // Iniciar transação para garantir a atomicidade das operações
            $this->pdo->beginTransaction();
    
            // Obter setores atuais do usuário (ativos)
            $stmt = $this->pdo->prepare("SELECT id_setor FROM usuarios_setores WHERE id_usuario = :id_usuario AND principal = 0 AND deleted_at IS NULL");
            $stmt->execute([':id_usuario' => $id_usuario]);
            $setores_atuais = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
            // Processar setores a serem adicionados ou reativados
            foreach($id_setores as $id_setor){
                if(!in_array($id_setor, $setores_atuais)){
                    // Verificar se o setor foi previamente removido
                    $stmt = $this->pdo->prepare("SELECT id_usuario_setor FROM usuarios_setores WHERE id_usuario = :id_usuario AND id_setor = :id_setor AND deleted_at IS NOT NULL LIMIT 1");
                    $stmt->execute([
                        ':id_usuario' => $id_usuario,
                        ':id_setor' => $id_setor
                    ]);
                    $registro = $stmt->fetch(PDO::FETCH_ASSOC);
    
                    if($registro){
                        // Reativar o setor removido anteriormente
                        $stmt = $this->pdo->prepare("UPDATE usuarios_setores SET deleted_at = NULL, updated_at = :updated_at WHERE id_usuario_setor = :id_usuario_setor");
                        $stmt->execute([
                            ':updated_at' => date("Y-m-d H:i:s"),
                            ':id_usuario_setor' => $registro['id_usuario_setor']
                        ]);
                    } else {
                        // Inserir um novo registro de setor para o usuário
                        $agora = date("Y-m-d H:i:s");
                        $stmt = $this->pdo->prepare("INSERT INTO usuarios_setores (id_usuario, id_setor, created_at, updated_at) VALUES (:id_usuario, :id_setor, :created_at, :updated_at)");
                        $stmt->execute([
                            ':id_usuario' => $id_usuario,
                            ':id_setor' => $id_setor,
                            ':created_at' => $agora,
                            ':updated_at' => $agora
                        ]);
                    }
                }
            }
    
            // Determinar quais setores precisam ser removidos (estão nos atuais, mas não nos novos)
            $setores_para_remover = array_diff($setores_atuais, $id_setores);
            if(!empty($setores_para_remover)){
                $agora = date("Y-m-d H:i:s");
                // Atualizar o campo deleted_at para os setores que foram removidos
                $stmt = $this->pdo->prepare("UPDATE usuarios_setores SET deleted_at = :deleted_at, updated_at = :updated_at WHERE id_usuario = :id_usuario AND id_setor = :id_setor AND deleted_at IS NULL");
                
                foreach($setores_para_remover as $setor_para_remover){
                    $stmt->execute([
                        ':deleted_at' => $agora,
                        ':updated_at' => $agora,
                        ':id_usuario' => $id_usuario,
                        ':id_setor' => $setor_para_remover
                    ]);
                }
            }
    
            // Confirmar transação
            $this->pdo->commit();
        } catch (Exception $e) {
            // Reverter transação em caso de erro
            $this->pdo->rollBack();
            // Opcional: lançar a exceção ou lidar com o erro conforme necessário
            throw $e;
        }
    }
    

    public function alterarSenha(array $dados){   
        $sqlVerificaSenha = $this->pdo->prepare("SELECT senha FROM usuarios WHERE id_usuario = :id_usuario");
        $sqlVerificaSenha->bindParam(':id_usuario', $dados['id_usuario']);
        $sqlVerificaSenha->execute();
        
        $usuario = $sqlVerificaSenha->fetch(PDO::FETCH_ASSOC);

        $url = "Location: ".URL."/sair";
        
        if (!password_verify($dados['senha_atual'], $usuario['senha'])) {
            echo "<script>alert('A senha atual está incorreta!');</script>";
            return false;
        }

        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $novaSenha = password_hash($dados['senha'], PASSWORD_DEFAULT);
            
            $sql = $this->pdo->prepare("UPDATE usuarios SET senha = :senha, updated_at = :updated_at WHERE id_usuario = :id_usuario");
            $updated_at = date('Y-m-d H:i');
            
            $sql->bindParam(':senha', $novaSenha);
            $sql->bindParam(':updated_at', $updated_at);
            $sql->bindParam(':id_usuario', $dados['id_usuario']);

            if ($sql->execute()) {
                $descricao = "Alterou a senha do usuário: " . $dados['id_usuario'];
                $this->addLog('Alterar Senha', $descricao, $dados['id_usuario']);

                echo "
                <script>
                    alert('Senha Alterada com Sucesso!');
                    window.location.href = '" . URL . "/sair.php';
                </script>";
                exit;
            } else {
                echo "
                <script>
                    alert('Não foi possível Alterar a Senha!');
                    window.location.href = '" . URL . "/sair.php';
                </script>";
                exit;
            }
        }
    }

    //Reativar um Usuário Desativo.
    public function reativar(array $dados){
        // Reativar o usuário
        $sql = $this->pdo->prepare("UPDATE usuarios SET
                                        ativo = 1                          
                                        WHERE id_usuario = :id_usuario
                                    ");
        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->execute();

        // Seleciona o usuário reativado
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id_usuario AND ativo = 1 AND deleted_at IS NULL");
        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->execute();

        $usuario_reativado = $sql->fetch(PDO::FETCH_OBJ);

        // Seleciona o nome do usuário logado que está reativando
        $sql = $this->pdo->prepare("SELECT nome FROM usuarios WHERE id_usuario = :usuario_logado");
        $sql->bindParam(':usuario_logado', $dados['usuario_logado']);
        $sql->execute();

        $usuario_logado = $sql->fetch(PDO::FETCH_OBJ);

        // Verificar se os usuários foram encontrados
        if ($usuario_reativado && $usuario_logado) {
            // Adicionando log com o nome do usuário que reativou e o usuário reativado
            $descricao = "Reativou o usuário: {$usuario_reativado->nome} (ID: {$usuario_reativado->id_usuario})";
            $this->addLog('Reativar', $descricao, $dados['usuario_logado']);
            echo "
            <script>
                alert('Usuário Reativado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }else{
            echo "
            <script>
                alert('Não foi possível Reativar o Usuário!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }
    }

    //Deletar um Usuário Ativo.
    public function deletar(int $id_usuario, $usuario_logado){
        $consulta_usuario = $this->pdo->prepare('SELECT nome FROM usuarios WHERE id_usuario = :id_usuario');
        $consulta_usuario->bindParam(':id_usuario', $id_usuario);
        $consulta_usuario->execute();
        $resultado_usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);

        if ($resultado_usuario) {
            $nome_usuario = $resultado_usuario['nome'];
        } else {
            $nome_usuario = "Usuário Desconhecido";
        }

        $sql = $this->pdo->prepare('UPDATE usuarios SET deleted_at = :deleted_at WHERE id_usuario = :id_usuario');
        $agora = date("Y-m-d H:i:s");
        $sql->bindParam(':deleted_at', $agora);
        $sql->bindParam(':id_usuario', $id_usuario);

        if ($sql->execute()) {
            $descricao = "Deletou o usuário $nome_usuario ($id_usuario)";
            $this->addLog("Deletar",$descricao,$usuario_logado);

            echo "
            <script>
                alert('Usuário Deletado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Deletar o Usuário!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }
    }

    public function logar($usuario, $senha)
    {
        try {
            // 1) Buscar o usuário ativo pelo "usuario"
            $stmt = $this->pdo->prepare(
                'SELECT id_usuario, nome, senha, id_avatar, empresa
                FROM usuarios
                WHERE usuario = :usuario
                AND deleted_at IS NULL
                AND ativo = 1
                LIMIT 1'
            );
            $stmt->bindValue(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_OBJ);

            // 2) Se não achou usuário OU senha incorreta => falha sem warnings
            if (!$user || !password_verify($senha, $user->senha)) {
                echo '<script>alert("Usuário ou Senha Incorreta")</script>';
                return false;
            }

            // (Opcional) Rehash caso o custo do hash tenha mudado
            if (password_needs_rehash($user->senha, PASSWORD_DEFAULT)) {
                $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                $upd = $this->pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id_usuario = :id');
                $upd->bindValue(':senha', $novoHash, PDO::PARAM_STR);
                $upd->bindValue(':id', $user->id_usuario, PDO::PARAM_INT);
                $upd->execute();
            }

            // 3) Sessão
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            session_regenerate_id(true);

            $_SESSION['logado']     = true;
            $_SESSION['nome']       = $user->nome;
            $_SESSION['id_usuario'] = $user->id_usuario;
            $_SESSION['id_avatar']  = $user->id_avatar;
            $_SESSION['id_empresa'] = $user->empresa;

            // 4) Setor principal
            $stmt = $this->pdo->prepare(
                'SELECT id_setor
                FROM usuarios_setores
                WHERE id_usuario = :id_usuario
                    AND deleted_at IS NULL
                ORDER BY principal DESC
                LIMIT 1'
            );
            $stmt->bindValue(':id_usuario', $user->id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $principal = $stmt->fetch(PDO::FETCH_OBJ);
            $_SESSION['id_setor'] = $principal ? $principal->id_setor : null;

            // 5) Todos os setores
            $stmt = $this->pdo->prepare(
                'SELECT id_setor
                FROM usuarios_setores
                WHERE id_usuario = :id_usuario
                    AND deleted_at IS NULL
                ORDER BY principal DESC'
            );
            $stmt->bindValue(':id_usuario', $user->id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['id_setores'] = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

            // 6) Redirecionar
            header('Location: ' . URL . '/');
            exit;
        } catch (Throwable $e) {
            // Logue o erro no servidor (não exibir detalhes ao usuário)
            error_log('Erro no login: ' . $e->getMessage());
            echo '<script>alert("Falha ao processar o login. Tente novamente.")</script>';
            return false;
        }
    }

    
    public function mostrarSetorPrincipal($id_usuario){
        $sql=$this->pdo->prepare("SELECT * FROM usuarios_setores WHERE id_usuario = :id_usuario AND deleted_at IS NULL AND principal = 1 LIMIT 1");
        $sql->bindParam(':id_usuario',$id_usuario);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);

        return $resultado;
    }

    //Contar Usuários de Acordo com o Setor.
    public function contarUsuariosPorSetor($id_setor){
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total FROM usuarios_setores WHERE id_setor = :id_setor AND deleted_at IS NULL');
        $sql->bindParam(':id_setor', $id_setor);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false) ? $resultado['total'] : 0;
    }

    //Contar Usuários de Acordo com o Cargo.
    public function contarUsuariosPorCargo($id_cargo){
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total FROM usuarios WHERE id_cargo = :id_cargo AND deleted_at IS NULL AND ativo = 1');
        $sql->bindParam(':id_cargo', $id_cargo);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false) ? $resultado['total'] : 0;
    }

    public function listarUsuariosDoCargo($id_cargo) {
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE id_cargo = :id_cargo AND deleted_at IS NULL ORDER BY nome ASC');
        $sql->bindParam(':id_cargo', $id_cargo);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function Aprovar($id_usuario,$usuario_logado,$nome){
        $sql = $this->pdo->prepare('UPDATE usuarios SET ativo = 1 WHERE id_usuario = :id_usuario');
        $sql->bindParam(':id_usuario',$id_usuario);

        if ($sql->execute()) {
            $id_usuario = $this->pdo->lastInsertId();
            $descricao = "Aprovou o usuário: $nome ($id_usuario)";
            $this->addLog('Cadastrar', $descricao, $usuario_logado);

            echo "
            <script>
                alert('Usuário Aprovado com Sucesso!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        } else {
            echo "
            <script>
                alert('Não foi possível Aprovar o Usuário!');
                window.location.href = '" . URL . "/configuracoes/usuarios';
            </script>";
            exit;
        }
    }

    public function listarOnlines(int $minutos = 1)
    {
        // Garante intervalo mínimo de 1 minuto
        $minutos = max(1, $minutos);

        // Calcula o timestamp-limite (agora - X minutos)
        $limite = date('Y-m-d H:i:s', time() - ($minutos * 60));

        $sql = $this->pdo->prepare("
            SELECT id_usuario, nome, usuario, id_avatar, empresa, online_at
            FROM usuarios
            WHERE deleted_at IS NULL
            AND ativo = 1
            AND online_at IS NOT NULL
            AND online_at >= :limite
            ORDER BY online_at DESC
        ");
        $sql->bindValue(':limite', $limite, PDO::PARAM_STR);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
 }