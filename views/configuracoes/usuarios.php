<?php 
    $Usuario = new Usuario();
    $Cargo = new Cargo();
    $Setor = new Setor();

    if (isset($_POST['btnCadastrar'])) {
        $Usuario->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Usuario->editar($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Usuario->deletar($_POST['id_usuario'],$_POST['usuario_logado']);
    }
    if (isset($_POST['btnReativar'])) {
        $Usuario->reativar($_POST);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $pageTitle ?></title>
        <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="nav-fixed">
         <!-- Tela de Carregamento -->
        <div id="preloader">
            <div class="spinner"></div>
        </div>
        <?php include_once('resources/topbar.php') ?>
        <div id="layoutSidenav">
            <?php include_once('resources/sidebar.php') ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="page-header pb-10 page-header-dark bg-primary">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                    <span>Usuarios</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">Usuarios
                                <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarUsuario">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                |
                                <button class="btn btn-datatable btn-dark ml-2" type="button" data-toggle="modal" data-target="#modalUsuariosDesativados    ">
                                    Desativados
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
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
                                            <?php foreach($Usuario->listarAtivos() as $usuario){ ?>
                                            <tr>
                                                <td><?php echo $usuario->nome ?></td>
                                                <td><?php echo $Cargo->mostrar($usuario->id_cargo)->cargo ?></td>
                                                <td><?php echo $Setor->mostrar($usuario->id_setor)->setor ?></td>
                                                <td><?php echo Helper::mostrar_empresa($usuario->empresa) ?></td>
                                                <td>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" type="button" data-toggle="modal" data-target="#modalEditarUsuario"
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
                                                        data-n_folha="<?php echo $usuario->n_folha ?>"
                                                        data-idsetor="<?php echo $usuario->id_setor ?>"
                                                        data-idcargo="<?php echo $usuario->id_cargo ?>"
                                                        data-data_admissao="<?php echo $usuario->data_admissao ?>"
                                                        data-ativo="<?php echo $usuario->ativo ?>"
                                                        ><i class="fa-solid fa-gear"></i></button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarUsuario"
                                                        data-idusuario="<?php echo $usuario->id_usuario ?>"
                                                        data-nome="<?php echo $usuario->nome ?>"   
                                                        ><i class="fa-solid fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include_once('resources/footer.php') ?>
            </div>
        </div>

        <!-- Modal Usuários Desativados -->
        <div class="modal fade" id="modalUsuariosDesativados" tabindex="1" role="dialog" aria-labelledby="modalUsuariosDesativadosLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Usuários Desativados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="datatable table-responsive">
                            <table class="table table-bordered table-hover" id="dataTableDesativados" width="100%" cellspacing="0">
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
                                    <?php foreach($Usuario->listarDesativados() as $usuarioDesativado){ ?>
                                    <tr>
                                        <td><?php echo $usuarioDesativado->nome; ?></td>
                                        <td><?php echo $Cargo->mostrar($usuarioDesativado->id_cargo)->cargo; ?></td>
                                        <td><?php echo $Setor->mostrar($usuarioDesativado->id_setor)->setor; ?></td>
                                        <td><?php echo Helper::mostrar_empresa($usuarioDesativado->empresa); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" data-toggle="modal" data-target="#modalEditarUsuario"
                                                data-nome="<?php echo $usuarioDesativado->nome ?>"
                                                data-idusuario="<?php echo $usuarioDesativado->id_usuario ?>"
                                                data-usuario="<?php echo $usuarioDesativado->usuario ?>"
                                                data-senha="<?php echo $usuarioDesativado->senha ?>"
                                                data-contrato="<?php echo $usuarioDesativado->contrato ?>"
                                                data-celular="<?php echo $usuarioDesativado->celular ?>"
                                                data-cpf="<?php echo $usuarioDesativado->cpf ?>"
                                                data-data_nascimento="<?php echo $usuarioDesativado->data_nascimento ?>"
                                                data-email="<?php echo $usuarioDesativado->email ?>"
                                                data-empresa="<?php echo $usuarioDesativado->empresa ?>"
                                                data-n_folha="<?php echo $usuarioDesativado->n_folha ?>"
                                                data-idsetor="<?php echo $usuarioDesativado->id_setor ?>"
                                                data-idcargo="<?php echo $usuarioDesativado->id_cargo ?>"
                                                data-data_admissao="<?php echo $usuarioDesativado->data_admissao ?>"
                                                data-ativo="<?php echo $usuarioDesativado->ativo ?>"
                                            >
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <form action="?" method="post" style="display:inline;">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuarioDesativado->id_usuario ?>">
                                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                                <button class="btn btn-datatable btn-transparent-dark" type="submit" name="btnReativar">
                                                    <i class="fa-solid fa-power-on"></i> Reativar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
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
                                <div class="col-3">
                                    <label for="cadastrar_usuario" class="form-label">Usuário *</label>
                                    <input type="text" name="usuario" id="cadastrar_usuario" class="form-control" required>
                                </div>
                                <div class="col-2">
                                    <label for="cadastrar_celular" class="form-label">Celular *</label>
                                    <input type="text" name="celular" id="cadastrar_celular" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label for="cadastrar_cpf" class="form-label">CPF *</label>
                                    <input type="text" name="cpf" id="cadastrar_cpf" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label for="cadastrar_senha" class="form-label">Senha *</label>
                                    <div class="input-group mb-3">
                                        <input type="password" name="senha" id="cadastrar_senha" class="form-control" required>
                                        <button class="btn btn-outline-dark" type="button" id="ver_senha"><i class="fa-solid" id="icone_visibilidade"></i></button>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="cadastrar_senha" class="form-label">Confirmar Senha *</label>
                                    <div class="input-group mb-3">
                                        <input type="password" id="cadastrar_confirmar_senha" class="form-control" required>
                                        <button class="btn btn-outline-dark" type="button" id="confirmar_ver_senha"><i class="fa-solid" id="confirmar_icone_visibilidade"></i></button>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="cadastrar_data_nascimento" class="form-label">Data de Nascimento *</label>
                                    <input type="date" name="data_nascimento" class="form-control" required>
                                </div>
                                <div class="col-3">
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
                                <div class="col-2 mb-3">
                                    <label for="cadastrar_contrato" class="form-label">Contrato *</label>
                                    <select name="contrato" id="cadastrar_contrato" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <option value="0">CLT</option>
                                        <option value="1">Estagiário</option>
                                        <option value="2">MEI</option>
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
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
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
                                <div class="col-3">
                                    <label for="editar_usuario" class="form-label">Usuário *</label>
                                    <input type="text" name="usuario" id="editar_usuario" class="form-control" required>
                                </div>
                                <div class="col-2">
                                    <label for="editar_celular" class="form-label">Celular *</label>
                                    <input type="text" name="celular" id="editar_celular" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label for="editar_cpf" class="form-labe">CPF *</label>
                                    <input type="text" name="cpf" id="editar_cpf" class="form-control mb-3">
                                </div>
                                <div class="col-3">
                                    <label for="editar_senha" class="form-label">Nova Senha *</label>
                                    <div class="input-group mb-3">
                                        <input type="password" name="senha" id="editar_senha" class="form-control" >
                                        <button class="btn btn-outline-dark" type="button" id="editar_ver_senha"><i class="fa-solid" id="editar_icone_visibilidade"></i></button>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="editar_senha" class="form-label">Nova Confirmar Senha *</label>
                                    <div class="input-group mb-3">
                                        <input type="password" id="editar_confirmar_senha" class="form-control">
                                        <button class="btn btn-outline-dark" type="button" id="editar_confirmar_ver_senha"><i class="fa-solid" id="editar_confirmar_icone_visibilidade"></i></button>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="editar_data_nascimento" class="form-label">Data de Nascimento *</label>
                                    <input type="date" name="data_nascimento" id="editar_data_nascimento" class="form-control" required>
                                </div>
                                <div class="col-3">
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
                                <div class="col-2 mb-3">
                                    <label for="editar_contrato" class="form-label">Contrato *</label>
                                    <select name="contrato" id="editar_contrato" class="form-control" required>
                                        <option value="">Selecione...</option>
                                        <option value="0">CLT</option>
                                        <option value="1">Estagiário</option>
                                        <option value="2">MEI</option>
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
                                    <input type="text" name="n_folha" id="editar_n_folha" class="form-control">
                                </div>
                                <div class="col-2">
                                    <label for="editar_data_admissao" class="form-label">Data de Admissão *</label>
                                    <input type="date" name="data_admissao" id="editar_data_admissao" class="form-control" required>
                                </div>
                                <div class="col-2">
                                    <label for="editar_ativo" class="form-label">Ativo *</label>
                                    <select name="ativo" id="editar_ativo" class="form-control">
                                        <option value="1">Ativo</option>
                                        <option value="0">Desativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Modal Deletar Usuário-->
        <div class="modal fade" id="modalDeletarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalDeletarLabel" aria-hidden="true">
            <form action="?" method="post">  
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar Usuário: <span class="modalDeletarUsuarioLabel"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="usuario_logado" id="deletar_usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <input type="hidden" name="id_usuario" id="deletar_id_usuario">
                            <div class="row">
                                <p>Você tem certeza que deseja Deletar o Usuário <br> <b class="modalDeletarUsuarioLabel"></b>? <br> Essa ação é Irreversível.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="btnDeletar" class="btn btn-danger">Deletar</button>
                        </div>
                    </div>
                </div>
            </form>  
        </div>

        <!-- Modal Usuários do Cargo -->
        <div class="modal fade" id="modalUsuariosCargo" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosCargoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUsuariosCargoLabel">Usuários do Cargo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body" id="usuariosDoCargo">
                        <!-- Lista de usuários -->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script>
            $(window).on('load', function () {
                $('#preloader').fadeOut('slow', function() { $(this).remove(); });
            });

            $(document).ready(function () {
                $('#conf').addClass('active')
                $('#configuracoes_usuarios').addClass('active')

                $('#preloader').fadeOut('slow', function() { $(this).remove(); });

                function validarCPF(cpf) {
                    cpf = cpf.replace(/[^\d]+/g,''); // Remove caracteres não numéricos
                    if (cpf == '') return false;
                    // Elimina CPFs conhecidos que são inválidos
                    if (cpf.length != 11 || 
                        cpf == "00000000000" || 
                        cpf == "11111111111" || 
                        cpf == "22222222222" || 
                        cpf == "33333333333" || 
                        cpf == "44444444444" || 
                        cpf == "55555555555" || 
                        cpf == "66666666666" || 
                        cpf == "77777777777" || 
                        cpf == "88888888888" || 
                        cpf == "99999999999")
                        return false;
                    // Validação do primeiro dígito
                    let add = 0;
                    for (let i=0; i < 9; i ++)
                        add += parseInt(cpf.charAt(i)) * (10 - i);
                    let rev = 11 - (add % 11);
                    if (rev == 10 || rev == 11) 
                        rev = 0;
                    if (rev != parseInt(cpf.charAt(9))) 
                        return false;
                    // Validação do segundo dígito
                    add = 0;
                    for (let i = 0; i < 10; i ++)
                        add += parseInt(cpf.charAt(i)) * (11 - i);
                    rev = 11 - (add % 11);
                    if (rev == 10 || rev == 11) 
                        rev = 0;
                    if (rev != parseInt(cpf.charAt(10)))
                        return false;
                    return true;
                }

                function formatarCPF(campo) {
                    let cpf = campo.val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos

                    if (cpf.length === 11) {
                        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                        campo.val(cpf);
                    }

                    if (!validarCPF(cpf)) {
                        campo.addClass('is-invalid');
                        if (campo.next('.invalid-feedback').length === 0) {
                            $('<div class="invalid-feedback">CPF inválido. Verifique e tente novamente.</div>').insertAfter(campo);
                        }
                    } else {
                        campo.removeClass('is-invalid').addClass('is-valid');
                        campo.next('.invalid-feedback').remove(); // Remove a mensagem de erro, se existir
                    }
                }

                function formatarCelular(campo) {
                    let telefone = campo.val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos

                    if (telefone.length === 11) {
                        telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
                        telefone = telefone.replace(/(\d{5})(\d{4})$/, '$1-$2');
                        campo.val(telefone);
                    }
                }

                $('#gerar_usuario').click(function () { 
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

                $('#cadastrar_celular, #editar_celular').on('input', function() {
                    formatarCelular($(this));
                });

                $('#cadastrar_cpf, #editar_cpf').on('input', function() {
                    formatarCPF($(this));
                });

                //Senhas
                {
                    //Cadastrar Senha
                    {
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

                        // Função para alternar o ícone de visibilidade para confirmar senha
                        function alternarIconeVisibilidadeConfirmar(tipoInput) {
                            if (tipoInput === 'password') {
                                $('#confirmar_icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                            } else {
                                $('#confirmar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                            }
                        }

                        $('#confirmar_ver_senha').click(function(){
                            var tipoInput = $('#cadastrar_confirmar_senha').attr('type');
                            
                            // Alternar o tipo de input entre 'password' e 'text'
                            if (tipoInput === 'password') {
                                $('#cadastrar_confirmar_senha').attr('type', 'text');
                            } else {
                                $('#cadastrar_confirmar_senha').attr('type', 'password');
                            }
                            
                            // Alternar o ícone de visibilidade com base no novo tipo de input
                            alternarIconeVisibilidadeConfirmar($('#cadastrar_confirmar_senha').attr('type'));
                        });
                        
                        // Inicializar o ícone de visibilidade com base no tipo de input inicial
                        alternarIconeVisibilidadeConfirmar($('#cadastrar_confirmar_senha').attr('type'));
                    }
                    //Editar Senha
                    {
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

                        // Função para alternar o ícone de visibilidade para confirmar senha
                        function alternarIconeVisibilidadeConfirmar(tipoInput) {
                            if (tipoInput === 'password') {
                                $('#editar_confirmar_icone_visibilidade').removeClass('fa-eye-slash').addClass('fa-eye');
                            } else {
                                $('#editar_confirmar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                            }
                        }

                        $('#editar_confirmar_ver_senha').click(function(){
                            var tipoInput = $('#editar_confirmar_senha').attr('type');
                            
                            // Alternar o tipo de input entre 'password' e 'text'
                            if (tipoInput === 'password') {
                                $('#editar_confirmar_senha').attr('type', 'text');
                            } else {
                                $('#editar_confirmar_senha').attr('type', 'password');
                            }
                            
                            // Alternar o ícone de visibilidade com base no novo tipo de input
                            alternarIconeVisibilidadeConfirmar($('#editar_confirmar_senha').attr('type'));
                        });
                        
                        // Inicializar o ícone de visibilidade com base no tipo de input inicial
                        alternarIconeVisibilidadeConfirmar($('#editar_confirmar_senha').attr('type'));
                    }
                }

                $('#modalEditarUsuario').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let modal = $(this);

                    // Obtendo dados do usuário para o modal de edição
                    let idusuario = button.data('idusuario');
                    let nome = button.data('nome');
                    let usuario = button.data('usuario');
                    let contrato = button.data('contrato');
                    let celular = button.data('celular');
                    let cpf = button.data('cpf');
                    let data_nascimento = button.data('data_nascimento');
                    let email = button.data('email');
                    let empresa = button.data('empresa');
                    let n_folha = button.data('n_folha');
                    let idsetor = button.data('idsetor');
                    let idcargo = button.data('idcargo');
                    let ativo = button.data('ativo');
                    let data_admissao = button.data('data_admissao');

                    modal.find('.modalEditarLabel').text(nome);
                    modal.find('#editar_nome').val(nome);
                    modal.find('#editar_id_usuario').val(idusuario);
                    modal.find('#editar_usuario').val(usuario);
                    modal.find('#editar_contrato').val(contrato);
                    modal.find('#editar_celular').val(celular);
                    modal.find('#editar_cpf').val(cpf);
                    modal.find('#editar_data_nascimento').val(data_nascimento);
                    modal.find('#editar_email').val(email);
                    modal.find('#editar_empresa').val(empresa);
                    modal.find('#editar_n_folha').val(n_folha);
                    modal.find('#editar_id_setor').val(idsetor);
                    modal.find('#editar_id_cargo').val(idcargo);
                    modal.find('#editar_ativo').val(ativo);
                    modal.find('#editar_data_admissao').val(data_admissao);

                    // Validar e formatar CPF e Celular assim que o modal de edição for aberto
                    formatarCPF(modal.find('#editar_cpf'));
                    formatarCelular(modal.find('#editar_celular'));
                });

                $('#modalDeletarUsuario').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let idusuario = button.data('idusuario');
                    let nome = button.data('nome');

                    $('#deletar_id_usuario').val(idusuario);

                    $('.modalDeletarUsuarioLabel').empty();
                    $('.modalDeletarUsuarioLabel').append(nome);
                });

                $('#modalDocumentos').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    $('#verrg').attr('src', '');
                    $('#vercontrato').attr('src', '');
                    $('#verfoto').attr('src', '');

                    let id_usuario = button.data('id_usuario');
                    let nome = button.data('nome');
                    let rg = button.data('rg');
                    let contrato = button.data('contrato');
                    let foto3x4 = button.data('foto3x4');
                    let residencia = button.data('residencia');

                    $('.modalDocumentosLabel').text(nome);

                    // Construa o caminho para a imagem do RG
                    if(rg != ''){
                        let caminho_rg = '/GRNacoes/configuracoes/usuario/'+id_usuario+'/'+rg;
                        $('#verrg').attr('src', caminho_rg);
                        $('#colrg').removeClass('d-none');
                        $('#formrg').addClass('d-none');
                    }else{
                        $('#colrg').addClass('d-none');
                        $('#formrg').removeClass('d-none');
                    }

                    $('#RGdownloadButton').click(function(){
                        var directory = '/GRNacoes/configuracoes/usuario/' + id_usuario + '/'+rg;
                        
                        var link = document.createElement('a');
                        link.href = directory;
                        link.download = rg;
                        link.click();
                    });

                    if(contrato != ''){
                        let caminho_contrato = '/GRNacoes/configuracoes/usuario/'+id_usuario+'/'+contrato;
                        $('#vercontrato').attr('src', caminho_contrato);
                        $('#colcontrato').removeClass('d-none');
                        $('#formcontrato').addClass('d-none');
                    }else{
                        $('#colcontrato').addClass('d-none');
                        $('#formcontrato').removeClass('d-none');
                    }

                    if(foto3x4 != ''){
                        let caminho_foto = '/GRNacoes/configuracoes/usuario/'+id_usuario+'/'+foto3x4;
                        $('#verfoto').attr('src', caminho_foto);
                        $('#colfoto').removeClass('d-none');
                        $('#formfoto').addClass('d-none');
                    }else{
                        $('#colfoto').addClass('d-none');
                        $('#formfoto').removeClass('d-none');
                    }
                });
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-usuarios.js"></script>
    </body>
</html>
