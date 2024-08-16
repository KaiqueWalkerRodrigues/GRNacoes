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
                                                <button class="btn btn-success" data-toggle="modal" data-target="#modalDocumentos" class="collapse-item"
                                                    data-nome="<?php echo $usuario->nome ?>"
                                                    data-contrato="<?php echo $usuario->contrato_nube ?>"
                                                    data-rg="<?php echo $usuario->rg ?>"
                                                    data-foto3x4="<?php echo $usuario->foto3x4 ?>"
                                                    data-residencia="<?php echo $usuario->residencia ?>"
                                                    data-id_usuario="<?php echo $usuario->id_usuario ?>"
                                                    >
                                                    <i class="fa-regular fa-address-book"></i>
                                                </button>
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
    <div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog" aria-labelledby="modalDocumentosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Documentos de <span class="modalDocumentosLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="?" method="post">
                        <div class="row">
                            <div class="col-3 text-center d-none" id="colrg">
                                <h5>RG</h5>
                                <img id="verrg" style="width: 200px;height:100px" alt="">
                                <br><br>
                                <button id="RGdownloadButton" type="button" class="btn btn-success"><i class="fa-solid fa-download"></i></button>
                            </div>
                            <div class="col-3 text-center d-none" id="formrg">
                                <label for="input_rg" class="form-label">RG</label>
                                <input type="file" name="rg" id="input_rg" class="form-control" accept="image/png, image/jpeg, image/webp, image/jpg">
                            </div>
                            <div class="col-3 text-center d-none" id="colcontrato">
                                <h5>Contrato</h5>
                                <img id="vercontrato" style="width: 100px;height:100px" alt="">
                                <br><br>
                                <button class="btn btn-success"><i class="fa-solid fa-download"></i></button>
                            </div>
                            <div class="col-3 text-center d-none" id="formcontrato">
                                <label for="input_contrato" class="form-label">Contrato</label>
                                <input type="file" name="contrato" id="input_contrato" class="form-control" accept="image/png, image/jpeg, image/webp, image/jpg">
                            </div>
                            <div class="col-3 text-center d-none" id="colfoto">
                                <h5>Foto 3x4</h5>
                                <img id="verfoto" style="width: 100px;height:100px" alt="">
                                <br><br>
                                <button class="btn btn-success"><i class="fa-solid fa-download"></i></button>
                            </div>
                            <div class="col-3 text-center d-none" id="formfoto">
                                <label for="input_foto" class="form-label">Foto 3x4</label>
                                <input type="file" name="foto" id="input_foto" class="form-control" accept="image/png, image/jpeg, image/webp, image/jpg">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="btnSalvar" data-dismiss="modal">Salvar</button>
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
                            <div class="col-3">
                                <label for="cadastrar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="cadastrar_cpf" class="form-control">
                            </div>
                            <div class="col-2">
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
                            <div class="col-3">
                                <label for="editar_cpf" class="form-label">CPF *</label>
                                <input type="text" name="cpf" id="editar_cpf" class="form-control">
                            </div>
                            <div class="col-2">
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
        $(document).ready(function() {
            $('#config').addClass('active');
            $('#gerenciar_usuarios').addClass('active');

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
                let idsetor = button.data('idsetor');
                let idcargo = button.data('idcargo');
                let data_admissao = button.data('data_admissao');

                modal.find('#editar_nome').val(nome);
                modal.find('#editar_id_usuario').val(idusuario);
                modal.find('#editar_usuario').val(usuario);
                modal.find('#editar_contrato').val(contrato);
                modal.find('#editar_celular').val(celular);
                modal.find('#editar_cpf').val(cpf);
                modal.find('#editar_data_nascimento').val(data_nascimento);
                modal.find('#editar_email').val(email);
                modal.find('#editar_empresa').val(empresa);
                modal.find('#editar_id_setor').val(idsetor);
                modal.find('#editar_id_cargo').val(idcargo);
                modal.find('#editar_data_admissao').val(data_admissao);

                // Validar e formatar CPF e Celular assim que o modal de edição for aberto
                formatarCPF(modal.find('#editar_cpf'));
                formatarCelular(modal.find('#editar_celular'));
            });

            $('#modalDesativarUsuario').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let idusuario = button.data('idusuario');
                let nome = button.data('nome');

                $('#desativar_id_usuario').val(idusuario);

                $('.modalDesativarUsuarioLabel').empty();
                $('.modalDesativarUsuarioLabel').append(nome);
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



</body>

</html>