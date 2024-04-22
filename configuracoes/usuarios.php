<?php 
    include_once('../const.php'); 

    $Usuario = new Usuario();
    $Setor = new Setor();
    $Cargo = new Cargo();

    if (isset($_POST['btnCadastrar'])) {
        $Usuario->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Usuario->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Usuario->desativar($_POST['id_usuario'],$_POST['usuario_logado']);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Gerenciar Usuários</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('../sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('../topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Usuários Ativos | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarUsuario" class="collapse-item">Cadastrar Novo Usuário</button> | <button class="btn btn-secondary">Ver Usuários Desativados</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Cargo</th>
                                            <th>Setor</th>
                                            <th>Empresa</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Usuario->listar() as $usuario){ ?>
                                        <tr>
                                            <td><?php echo $usuario->nome; ?></td>
                                            <td><?php echo $Cargo->nomeCargo($usuario->id_cargo); ?></td>
                                            <td><?php echo $Setor->nomeSetor($usuario->id_setor); ?></td>
                                            <td><?php echo Helper::mostrar_empresa($usuario->empresa); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-primary"><i class="fa-solid fa-comment"></i></button>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#modalDocumentos" class="collapse-item"><i class="fa-regular fa-address-book"></i></button>
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarUsuario" class="collapse-item" 
                                                    data-nome="<?php echo $usuario->nome ?>"
                                                    data-idusuario="<?php echo $usuario->id_usuario ?>"
                                                    data-usuario="<?php echo $usuario->usuario ?>"
                                                    data-senha="<?php echo $usuario->senha ?>"
                                                    data-contrato="<?php echo $usuario->contrato ?>"
                                                    data-celular="<?php echo $usuario->celular ?>"
                                                    data-cpf="<?php echo $usuario->cpf ?>"
                                                    data-data_nascimento="<?php echo $usuario->data_nascimento ?>"
                                                    data-email="<?php echo $usuario->email ?>"
                                                    data-empresa="<?php echo $usuario->empresa ?>"
                                                    data-idsetor="<?php echo $usuario->id_setor ?>"
                                                    data-idcargo="<?php echo $usuario->id_cargo ?>"
                                                    data-data_admissao="<?php echo $usuario->data_admissao ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-danger"  data-toggle="modal" data-target="#modalDesativarUsuario" class="collapse-item"
                                                    data-idusuario="<?php echo $usuario->id_usuario ?>"
                                                    data-nome="<?php echo $usuario->nome ?>"
                                                    >
                                                    <i class="fa-solid fa-power-off"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal Documentos -->
    <div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog" aria-labelledby="abrirchamadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDocumentosLabel">Documentos de Kaique Rodrigues de Souza</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 text-center">
                            <h5>RG</h5>
                            <img style="width: 200px;height:100px" src="<?php echo URL ?>/img/usuarios/teste/rg.jpg" alt="">
                            <br><br>
                            <button class="btn btn-success"><i class="fa-solid fa-download"></i></button>
                        </div>
                        <div class="col-3 text-center">
                            <h5>Contrato</h5>
                            <img style="width: 100px;height:100px" src="<?php echo URL ?>/img/usuarios/teste/contrato.jpg" alt="">
                            <br><br>
                            <button class="btn btn-success"><i class="fa-solid fa-download"></i></button>
                        </div>
                        <div class="col-3 text-center">
                            <h5>Contrato Nube</h5>
                            <span>Não possui/Se Aplica</span>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar Usuário -->
    <div class="modal fade" id="modalCadastrarUsuario" tabindex="1" role="dialog" aria-labelledby="modalCadastrarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="?" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar Novo Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                                <label for="cadastrar_nome" class="form-label">Nome Completo do Funcionário *</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nome" id="cadastrar_nome" required>
                                    <button class="btn btn-success" type="button" id="gerar_usuario"><i class="fa-solid fa-user"></i></button>
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_usuario" class="form-label">Usuário *</label>
                                <input type="text" name="usuario" id="cadastrar_usuario" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_senha" class="form-label">Senha *</label>
                                <div class="input-group mb-3">
                                    <input type="password" name="senha" id="cadastrar_senha" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" id="ver_senha"><i class="fa-solid" id="icone_visibilidade"></i></button>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_senha" class="form-label">Confirmar Senha *</label>
                                <div class="input-group mb-3">
                                    <input type="password" id="cadastrar_confirmar_senha" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" id="confirmar_ver_senha"><i class="fa-solid" id="confirmar_icone_visibilidade"></i></button>
                                </div>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="cadastrar_contrato" class="form-label">Contrato *</label>
                                <select name="contrato" id="cadastrar_contrato" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">CLT</option>
                                    <option value="1">Estagiário</option>
                                    <option value="2">MEI</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_celular" class="form-label">Celular *</label>
                                <input type="text" name="celular" id="cadastrar_celular" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="cadastrar_cpf" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_data_nascimento" class="form-label">Data de Nascimento *</label>
                                <input type="date" name="data_nascimento" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="cadastrar_email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-3 mb-3">
                                <label for="cadastrar_empresa" class="form-label">Empresa *</label>
                                <select name="empresa" id="cadastrar_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_id_setor" class="form-label">Setor *</label>
                                <select name="id_setor" id="cadastrar_id_setor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Setor->listar() as $setor){ ?>
                                        <option value="<?php echo $setor->id_setor ?>"><?php echo $setor->setor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="cadastrar_id_cargo" class="form-label">Cargo *</label>
                                <select name="id_cargo" id="cadastrar_id_cargo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Cargo->listar() as $cargo){ ?>
                                        <option value="<?php echo $cargo->id_cargo ?>"><?php echo $cargo->cargo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="cadastrar_n_folha" class="form-label">N° Folha</label>
                                <input type="text" name="n_folha" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="cadastrar_data_admissao" class="form-label">Data de Admissão *</label>
                                <input type="date" name="data_admissao" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuário -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="?" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuário: <span class="modalEditarLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <div class="col-4">
                                <input type="hidden" name="id_usuario" id="editar_id_usuario">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario']; ?>">
                                <label for="editar_nome" class="form-label">Nome Completo do Funcionário *</label>
                                <input type="text" class="form-control" name="nome" id="editar_nome" required>
                            </div>
                            <div class="col-2">
                                <label for="editar_usuario" class="form-label">Usuário *</label>
                                <input type="text" name="usuario" id="editar_usuario" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <label for="editar_senha" class="form-label">Nova Senha *</label>
                                <div class="input-group mb-3">
                                    <input type="password" name="senha" id="editar_senha" class="form-control" >
                                    <button class="btn btn-outline-secondary" type="button" id="editar_ver_senha"><i class="fa-solid" id="editar_icone_visibilidade"></i></button>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="editar_senha" class="form-label">Nova Confirmar Senha *</label>
                                <div class="input-group mb-3">
                                    <input type="password" id="editar_confirmar_senha" class="form-control">
                                    <button class="btn btn-outline-secondary" type="button" id="editar_confirmar_ver_senha"><i class="fa-solid" id="editar_confirmar_icone_visibilidade"></i></button>
                                </div>
                            </div>
                            <div class="col-2 mb-3">
                                <label for="editar_contrato" class="form-label">Contrato *</label>
                                <select name="contrato" id="editar_contrato" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="0">CLT</option>
                                    <option value="1">Estagiário</option>
                                    <option value="2">MEI</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="editar_celular" class="form-label">Celular *</label>
                                <input type="text" name="celular" id="editar_celular" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="editar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="editar_cpf" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="editar_data_nascimento" class="form-label">Data de Nascimento *</label>
                                <input type="date" name="data_nascimento" id="editar_data_nascimento" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="editar_email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-3 mb-3">
                                <label for="editar_empresa" class="form-label">Empresa *</label>
                                <select name="empresa" id="editar_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="editar_id_setor" class="form-label">Setor *</label>
                                <select name="id_setor" id="editar_id_setor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Setor->listar() as $setor){ ?>
                                        <option value="<?php echo $setor->id_setor ?>"><?php echo $setor->setor ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="editar_id_cargo" class="form-label">Cargo *</label>
                                <select name="id_cargo" id="editar_id_cargo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Cargo->listar() as $cargo){ ?>
                                        <option value="<?php echo $cargo->id_cargo ?>"><?php echo $cargo->cargo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="editar_n_folha" class="form-label">N° Folha</label>
                                <input type="text" name="n_folha" class="form-control">
                            </div>
                            <div class="col-2">
                                <label for="editar_data_admissao" class="form-label">Data de Admissão *</label>
                                <input type="date" name="data_admissao" id="editar_data_admissao" class="form-control" required>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Modal Desativar Usuário-->
    <div class="modal fade" id="modalDesativarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalDesativarLabel" aria-hidden="true">
        <form action="?" method="post">  
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar usuário: <span class="modalDesativarUsuarioLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">>
                        <input type="hidden" name="usuario_logado" id="desativar_usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_usuario" id="desativar_id_usuario">
                        <div class="row">
                            <p>Deseja desativar o usuário: <span class="modalDesativarUsuarioLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDesativar" class="btn btn-danger">Desativar</button>
                    </div>
                </div>
            </div>
        </form>  
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesUsuarios.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $('#gerar_usuario').click(function (e) { 
            // Obter o valor do campo "cadastrar_nome"
            let nomeCompleto = $('#cadastrar_nome').val();

            // Dividir o nome completo em partes usando o espaço como delimitador
            let partesNome = nomeCompleto.split(' ');

            // Extrair o primeiro nome e o último nome
            let primeiroNome = partesNome[0];
            let ultimoNome = partesNome[partesNome.length - 1];

            // Concatenar o primeiro nome e o último nome com um ponto entre eles
            let nomeUsuario = primeiroNome + '.' + ultimoNome;

            // Definir o valor do campo "cadastrar_usuario" como o nome de usuário gerado
            $('#cadastrar_usuario').val(nomeUsuario);
        });
        $('#cadastrar_celular').blur(function() {
            let telefone = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos
            let telefoneFormatado = '';

            // Verifica se o telefone possui 9 caracteres
            if (telefone.length === 9) {
                telefoneFormatado = '(11) ' + telefone.substr(0, 5) + '-' + telefone.substr(5);
            } else {
                // Verifica se o telefone possui 11 caracteres
                if (telefone.length === 11) {
                    telefoneFormatado = '(' + telefone.substr(0, 2) + ') ' + telefone.substr(2, 5) + '-' + telefone.substr(7);
                }
            }

            // Define o valor do campo como o telefone formatado
            $(this).val(telefoneFormatado);
        });
        $('#cadastrar_cpf').blur(function() {
            let cpf = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos
            let cpfFormatado = '';

            // Verifica se o CPF possui 11 caracteres
            if (cpf.length === 11) {
                cpfFormatado = cpf.substr(0, 3) + '.' + cpf.substr(3, 3) + '.' + cpf.substr(6, 3) + '-' + cpf.substr(9);
            }

            // Define o valor do campo como o CPF formatado
            $(this).val(cpfFormatado);
        });
        //Senhas
        {
            //Cadastrar Senha
            {
                $(document).ready(function(){
                    $('#config').addClass('active');
                    $('#gerenciar_usuarios').addClass('active');

                    // Função para alternar o ícone de visibilidade
                    function alternarIconeVisibilidade(tipoInput) {
                        if (tipoInput === 'password') {
                            $('#icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                        } else {
                            $('#icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                        }
                    }
                    
                    // Ao clicar no botão, alternar a visibilidade do input
                    $('#ver_senha').click(function(){
                        var tipoInput = $('#cadastrar_senha').attr('type');
                        
                        // Alternar o tipo de input entre 'password' e 'text'
                        if (tipoInput === 'password') {
                            $('#cadastrar_senha').attr('type', 'text');
                        } else {
                            $('#cadastrar_senha').attr('type', 'password');
                        }
                        
                        // Alternar o ícone de visibilidade com base no novo tipo de input
                        alternarIconeVisibilidade($('#cadastrar_senha').attr('type'));
                    });
                    
                    // Inicializar o ícone de visibilidade com base no tipo de input inicial
                    alternarIconeVisibilidade($('#cadastrar_senha').attr('type'));
                });
                $(document).ready(function(){
                    // Função para alternar o ícone de visibilidade
                    function alternarIconeVisibilidade(tipoInput) {
                        if (tipoInput === 'password') {
                            $('#confirmar_icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                        } else {
                            $('#confirmar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                        }
                    }
                    
                    // Ao clicar no botão, alternar a visibilidade do input
                    $('#confirmar_ver_senha').click(function(){
                        var tipoInput = $('#cadastrar_confirmar_senha').attr('type');
                        
                        // Alternar o tipo de input entre 'password' e 'text'
                        if (tipoInput === 'password') {
                            $('#cadastrar_confirmar_senha').attr('type', 'text');
                        } else {
                            $('#cadastrar_confirmar_senha').attr('type', 'password');
                        }
                        
                        // Alternar o ícone de visibilidade com base no novo tipo de input
                        alternarIconeVisibilidade($('#cadastrar_confirmar_senha').attr('type'));
                    });
                    
                    // Inicializar o ícone de visibilidade com base no tipo de input inicial
                    alternarIconeVisibilidade($('#cadastrar_confirmar_senha').attr('type'));
                });
            }
            //Editar Senha
            {
                $(document).ready(function(){
                    // Função para alternar o ícone de visibilidade
                    function alternarIconeVisibilidade(tipoInput) {
                        if (tipoInput === 'password') {
                            $('#editar_icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                        } else {
                            $('#editar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                        }
                    }
                    
                    // Ao clicar no botão, alternar a visibilidade do input
                    $('#editar_ver_senha').click(function(){
                        var tipoInput = $('#editar_senha').attr('type');
                        
                        // Alternar o tipo de input entre 'password' e 'text'
                        if (tipoInput === 'password') {
                            $('#editar_senha').attr('type', 'text');
                        } else {
                            $('#editar_senha').attr('type', 'password');
                        }
                        
                        // Alternar o ícone de visibilidade com base no novo tipo de input
                        alternarIconeVisibilidade($('#editar_senha').attr('type'));
                    });
                    
                    // Inicializar o ícone de visibilidade com base no tipo de input inicial
                    alternarIconeVisibilidade($('#editar_senha').attr('type'));
                });
                $(document).ready(function(){
                    // Função para alternar o ícone de visibilidade
                    function alternarIconeVisibilidade(tipoInput) {
                        if (tipoInput === 'password') {
                            $('#editar_confirmar_icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                        } else {
                            $('#editar_confirmar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                        }
                    }
                    
                    // Ao clicar no botão, alternar a visibilidade do input
                    $('#editar_confirmar_ver_senha').click(function(){
                        var tipoInput = $('#editar_confirmar_senha').attr('type');
                        
                        // Alternar o tipo de input entre 'password' e 'text'
                        if (tipoInput === 'password') {
                            $('#editar_confirmar_senha').attr('type', 'text');
                        } else {
                            $('#editar_confirmar_senha').attr('type', 'password');
                        }
                        
                        // Alternar o ícone de visibilidade com base no novo tipo de input
                        alternarIconeVisibilidade($('#editar_confirmar_senha').attr('type'));
                    });
                    
                    // Inicializar o ícone de visibilidade com base no tipo de input inicial
                    alternarIconeVisibilidade($('#editar_confirmar_senha').attr('type'));
                });
            }
        }
        $('#modalEditarUsuario').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idusuario = button.data('idusuario')
            let nome = button.data('nome')
            let usuario = button.data('usuario')
            let contrato = button.data('contrato')
            let celular = button.data('celular')
            let cpf = button.data('cpf')
            let data_nascimento = button.data('data_nascimento')
            let email = button.data('email')
            let empresa = button.data('empresa')
            let idsetor = button.data('idsetor')
            let idcargo = button.data('idcargo')
            let data_admissao = button.data('data_admissao')

            $('#modalEditarUsuarioLabel').empty()
            $('#modalEditarUsuarioLabel').append(usuario)
            $('#editar_nome').val(nome)
            $('#editar_id_usuario').val(idusuario)
            $('#editar_usuario').val(usuario)
            $('#editar_contrato').val(contrato)
            $('#editar_celular').val(celular)
            $('#editar_cpf').val(cpf)
            $('#editar_data_nascimento').val(data_nascimento)
            $('#editar_email').val(email)
            $('#editar_empresa').val(empresa)
            $('#editar_id_setor').val(idsetor)
            $('#editar_id_cargo').val(idcargo)
            $('#editar_id_usuario').val(idusuario)
            $('#editar_data_admissao').val(data_admissao)
        })
        $('#modalDesativarUsuario').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget)
            let idusuario = button.data('idusuario')
            let nome = button.data('nome')

            $('#desativar_id_usuario').val(idusuario)

            $('.modalDesativarUsuarioLabel').empty()
            $('.modalDesativarUsuarioLabel').append(nome)
        })
    </script>

</body>

</html>