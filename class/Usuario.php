<?php 

class Usuario {

    public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL ORDER BY nome ASC');        
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function cadastrar(Array $dados)
    {
        $sql = $this->pdo->prepare('INSERT INTO usuarios 
                                    (nome,usuario,senha,contrato,celular,cpf,
                                    data_nascimento,email,empresa,id_setor,id_cargo,n_folha,
                                    data_admissao,created_at,updated_at)
                                    VALUES
                                    (:nome,:usuario,:senha,:contrato,:celular,:cpf,
                                    :data_nascimento,:email,:empresa,:id_setor,:id_cargo,:n_folha,
                                    :data_admissao,:created_at,:updated_at)
                                ');

        $nome  = ucwords(strtolower(trim($dados['nome'])));
        $usuario  = trim($dados['usuario']);
        $senha  = $dados['senha'];
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

        $salt = 'GRN+'; 
        $senha = crypt($dados['senha'], $salt);
        
        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':usuario',$usuario);              
        $sql->bindParam(':senha',$senha);              
        $sql->bindParam(':contrato',$contrato);              
        $sql->bindParam(':celular',$celular);              
        $sql->bindParam(':cpf',$cpf);              
        $sql->bindParam(':data_nascimento',$data_nascimento);              
        $sql->bindParam(':email',$email);              
        $sql->bindParam(':empresa',$empresa);              
        $sql->bindParam(':id_setor',$id_setor);              
        $sql->bindParam(':id_cargo',$id_cargo);              
        $sql->bindParam(':n_folha',$n_folha);              
        $sql->bindParam(':data_admissao',$data_admissao);              
        $sql->bindParam(':created_at',$created_at);          
        $sql->bindParam(':updated_at',$updated_at);              

        $sql->execute();

        // Obtém o ID do usuário inserido
        $id_usuario = $this->pdo->lastInsertId();

        // Adicionando log com o ID do usuário cadastrado
        $descricao = "Cadastrou o usuário: $nome ($id_usuario)";
        $this->addLog('Cadastrar', $descricao,$usuario_logado);

        return header('Location:/GRNacoes/configuracoes/usuarios');
    }


    public function mostrar(int $id_usuario)
    {
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1');
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function editar(array $dados)
    {
        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $salt = 'GRN+';
            $senha = crypt($dados['senha'], $salt);
            $sql = $this->pdo->prepare("UPDATE usuarios SET
                                        nome = :nome,
                                        usuario = :usuario,
                                        senha = :senha,
                                        contrato = :contrato,
                                        celular = :celular,
                                        cpf = :cpf,
                                        data_nascimento = :data_nascimento,
                                        email = :email,
                                        empresa = :empresa,                                   
                                        id_setor = :id_setor,
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
                                        usuario = :usuario,
                                        contrato = :contrato,
                                        celular = :celular,
                                        cpf = :cpf,
                                        data_nascimento = :data_nascimento,
                                        email = :email,
                                        empresa = :empresa,                                   
                                        id_setor = :id_setor,
                                        id_cargo = :id_cargo,
                                        n_folha = :n_folha,
                                        data_admissao = :data_admissao,
                                        updated_at = :updated_at                              
                                        WHERE id_usuario = :id_usuario
                                    ");
        }

        $nome = ucwords(strtolower(trim($dados['nome'])));
        $usuario = trim($dados['usuario']);
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

        // Se a senha foi preenchida, a bindamos, senão não
        if (isset($senha)) {
            $sql->bindParam(':senha', $senha);   
        }

        $sql->bindParam(':nome',$nome);
        $sql->bindParam(':usuario',$usuario);              
        $sql->bindParam(':contrato',$contrato);              
        $sql->bindParam(':celular',$celular);              
        $sql->bindParam(':cpf',$cpf);              
        $sql->bindParam(':data_nascimento',$data_nascimento);              
        $sql->bindParam(':email', $email);
        $sql->bindParam(':empresa',$empresa);   
        $sql->bindParam(':id_setor',$id_setor);  
        $sql->bindParam(':id_cargo',$id_cargo);  
        $sql->bindParam(':n_folha',$n_folha);  
        $sql->bindParam(':data_admissao',$data_admissao);  
        $sql->bindParam(':updated_at',$updated_at);        
        $sql->bindParam(':id_usuario',$id_usuario);       

        $sql->execute();

        // Adicionando log com o ID do usuário editado
        $descricao = "Editou o usuário: $nome ($id_usuario)";
        $this->addLog('Editar', $descricao,$usuario_logado);

        return header('location:/GRNacoes/configuracoes/usuarios');
    }

    public function desativar(int $id_usuario, $usuario_logado)
    {
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
            $descricao = "Desativou o usuário $nome_usuario ($id_usuario)";
            $sql = $this->pdo->prepare('INSERT INTO logs 
                                        (acao, id_usuario, descricao, data)
                                        VALUES
                                        (:acao, :id_usuario, :descricao, :data)
                                    ');
            $acao = 'Desativar';
            $sql->bindParam(':acao', $acao);
            $sql->bindParam(':id_usuario', $usuario_logado);
            $sql->bindParam(':descricao', $descricao);
            $sql->bindParam(':data', $agora);
            $sql->execute();

            return header('location:/GRNacoes/configuracoes/usuarios');
        } else {
            // Tratar falha na execução da query, se necessário
        }
    }


    // Métodos de login, logout e outras funcionalidades...

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

    public function mostrarUsuario(int $id_usuario)
    {
        $sql = $this->pdo->prepare('SELECT usuario FROM usuarios WHERE id_usuario = :id_usuario');
        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false) ? $resultado['usuario'] : null;
    }

    public function logar($usuario, $senha)
    {
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha');
        $sql->bindParam(':usuario', $usuario);

        $salt = 'GRN+'; 
        $senha = crypt($senha, $salt);

        $sql->bindParam(':senha', $senha);
        $sql->execute();

        $user = $sql->fetch(PDO::FETCH_OBJ);

        if ($user) {
            session_start();
            $_SESSION['logado'] = true;
            $_SESSION['nome'] = $user->nome;
            $_SESSION['id_usuario'] = $user->id_usuario;
            $_SESSION['id_setor'] = $user->id_setor;

            header('Location: ' . URL . '/');
            exit();
        } else {
            header('Location: ' . URL . '/login?falha');
            exit();
        }
    }

    public function contarUsuariosPorSetor($id_setor)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total FROM usuarios WHERE id_setor = :id_setor');
        $sql->bindParam(':id_setor', $id_setor);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false) ? $resultado['total'] : 0;
    }

    public function contarUsuariosPorCargo($id_cargo)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) AS total FROM usuarios WHERE id_cargo = :id_cargo');
        $sql->bindParam(':id_cargo', $id_cargo);
        $sql->execute();
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false) ? $resultado['total'] : 0;
    }

    public function listarVendedores($empresa = '%'){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND empresa LIKE :empresa AND id_cargo = 9 OR id_cargo = 10 OR id_cargo = 11');
        $sql->bindParam(':empresa',$empresa);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

 }
