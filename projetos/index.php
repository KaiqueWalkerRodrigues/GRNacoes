<?php 
    include_once('../const.php'); 
    
    $Projeto = new Projeto();
    $Usuario = new Usuario();

    if (isset($_POST['btnCadastrar'])) {
        $Projeto->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Projeto->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Projeto->desativar($_POST['id_projeto'],$_POST['id_usuario']);
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

    <title>GRNacoes - Gerenciar Projetos</title>

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

                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtroStatus">Filtrar por Status:</label>
                            <select class="form-control" id="filtroStatus">
                                <option value="">Todos</option>
                                <option value="Em Análise">Em Análise</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Recusado">Recusado</option>
                            </select>
                        </div>
                    </div>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Projetos Ativos | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarProjeto" class="collapse-item">Cadastrar Novo Projeto</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Status</th>
                                            <th>Criado por</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Projeto->listar() as $p){ ?>
                                        <tr>
                                            <td><?php echo $p->id_projeto ?></td>
                                            <td><?php echo $p->titulo ?></td>
                                            <td><?php echo Helper::statusProjeto($p->status) ?></td>
                                            <td><?php echo $Usuario->mostrar($p->id_usuario)->nome ?></td>
                                            <td class="text-center">
                                                <form action="projeto" method="post">
                                                    <input type="hidden" value="<?php echo $p->id_projeto ?>" name="id_projeto">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa-solid fa-list-check"></i>
                                                    </button>
                                                    <buton class="btn btn-info">
                                                        <i class="fa-solid fa-user-group"></i>
                                                    </buton>
                                                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarProjeto" class="collapse-item" 
                                                        data-id_projeto="<?php echo $p->id_projeto ?>" 
                                                        data-titulo="<?php echo $p->titulo ?>"
                                                        data-data_conclusao="<?php echo $p->data_conclusao ?>"
                                                        data-descricao="<?php echo $p->descricao ?>"
                                                        >
                                                        <i class="fa-solid fa-gear"></i>
                                                    </button>
                                                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarProjeto" class="collapse-item" 
                                                        data-id_projeto="<?php echo $p->id_projeto ?>" 
                                                        data-titulo="<?php echo $p->titulo ?>"
                                                        >
                                                        <i class="fa-solid fa-power-off"></i>
                                                    </button>
                                                </form>
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


    <!-- Modal Cadastrar Projetos -->
    <div class="modal fade" id="modalCadastrarProjeto" tabindex="1" role="dialog" aria-labelledby="modalCadastrarProjetosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Projeto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-4 mb-2">
                                <label for="titulo" class="form-label">Título do Projeto *</label>
                                <input type="text" name="titulo" class="form-control" required>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="data_conclusao" class="form-label">Data-alvo de conclusão</label>
                                <input type="date" name="data_conclusao" class="form-control">
                            </div>
                            <div class="col-8 offset-2">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea name="descricao" id="cadastrar_descricao" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Projeto -->
    <div class="modal fade" id="modalEditarProjeto" tabindex="1" role="dialog" aria-labelledby="modalEditarProjetoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Projeto: <span class="modalEditarProjetoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="row">
                            <input type="hidden" name="id_projeto" id="editar_id_projeto">
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-4 mb-2">
                                <label for="titulo" class="form-label">Título do Projeto *</label>
                                <input type="text" name="titulo" id="editar_titulo" class="form-control" required>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="editar_status" class="form-label">Status *</label>
                                <select class="form-control" name="status" id="editar_status" required>
                                    <option value="1">Em Análise</option>
                                    <option value="2">Em Andamento</option>
                                    <option value="3">Concluido</option>
                                    <option value="4">Cancelado</option>
                                    <option value="5">Recusado</option>
                                </select>
                            </div>
                            <div class="col-3 mb-2">
                                <label for="data_conclusao" class="form-label">Data-alvo de conclusão</label>
                                <input type="date" name="data_conclusao" id="editar_data_conclusao" class="form-control">
                            </div>
                            <div class="col-8 offset-2">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea name="descricao" id="editar_descricao" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Desativar Usuário-->
    <div class="modal fade" id="modalDesativarProjeto" tabindex="-1" role="dialog" aria-labelledby="modalDesativarProjetosLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar o Projeto: <span class="modalDesativarProjetoLabel"></span>?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_projeto" id="desativar_id_projeto">
                        <div class="row">
                            <p>Deseja desativar a Projeto: <span class="modalDesativarProjetoLabel"></span>?</p>
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesProjetos.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
       $(document).ready(function() {
            $('#proj').addClass('active');
            $('#projetos_index').addClass('active');

            if (!$.fn.DataTable.isDataTable('#dataTable')) {
                var table = $('#dataTable').DataTable({
                    "drawCallback": function() {
                        calcularSomaTotal();
                    }
                });
            } else {
                var table = $('#dataTable').DataTable();
            }

            // Adicione um evento de mudança ao filtro de status
            $('#filtroStatus').change(function() {
                var status = $(this).val();

                table.column(2).search(status);

                table.draw();
            });

            $('#modalEditarProjeto').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_projeto = button.data('id_projeto')
                let titulo = button.data('titulo')
                let data_conclusao = button.data('data_conclusao')
                let descricao = button.data('descricao')

                $('.modalEditarProjetoLabel').empty()
                $('.modalEditarProjetoLabel').append(titulo)
                $('#editar_id_projeto').val(id_projeto)
                $('#editar_titulo').val(titulo)
                $('#editar_data_conclusao').val(data_conclusao)
                $('#editar_descricao').val(descricao)
            })

            $('#modalDesativarProjeto').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_projeto = button.data('id_projeto')
                let titulo = button.data('titulo')
                $('.modalDesativarProjetoLabel').empty()
                $('.modalDesativarProjetoLabel').append(titulo)
                $('#desativar_id_projeto').empty()
                $('#desativar_id_projeto').val(id_projeto)
            })
        });
    </script>

</body>

</html>