<?php 
    $Usuario = new Usuario();

    if(isset($_POST['btnAlterarSenha'])){
        $Usuario->alterarSenha($_POST);
    }
?>
<style>
    .nav-link-icon{
        margin-top: 3px;
    }
</style>
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <br>
                <a class="nav-link" id="home" href="<?php echo URL ?>">
                    <div class="nav-link-icon"><i class="fa-solid fa-house"></i></div>
                    Home
                </a>
                
                <div class="sidenav-menu-heading">Interfaces</div>
                
                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 13 OR $_SESSION['id_setor'] == 14){ ?>
                <a class="nav-link collapsed" id="dash" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                    <div class="nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>
                    Dashboards
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDashboards" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="<?php echo URL ?>/dashboards/captacao">Captação<span class="badge badge-primary ml-2">Novo!</span></a>
                    </nav>
                </div> 
                <?php } ?>

                <a class="nav-link collapsed" id="cham" href="javascript:void(0);" data-toggle="collapse" data-target="#chamados" aria-expanded="false" aria-controls="chamados">
                    <div class="nav-link-icon"><i class="fa-solid fa-ticket-simple"></i></div>
                    Chamados
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="chamados" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link" id="chamados_meus_chamados" href="<?php echo URL ?>/chamados/meus_chamados">Meus Chamados</a>
                        <a class="nav-link" id="chamados_chamados" href="<?php echo URL ?>/chamados">Chamados</a>
                        <?php if($_SESSION['id_setor'] == 1){ ?>
                            <a class="nav-link" id="chamados_todos" href="<?php echo URL ?>/chamados/todos">Todos os Chamados</a>
                        <?php } ?>
                    </nav>
                </div>

                <a class="nav-link" id="chats" href="<?php echo URL ?>/chats">
                    <div class="nav-link-icon"><i class="fa-solid fa-messages"></i></div>
                    Chats
                </a>

                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 5 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){ ?>

                <a class="nav-link collapsed" id="ciru" href="javascript:void(0);" data-toggle="collapse" data-target="#cirurgias" aria-expanded="false" aria-controls="cirurgias">
                    <div class="nav-link-icon"><i class="fa-solid fa-eye"></i></div>
                    Cirurgias
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse" id="cirurgias" data-parent="#accordionSidenav">

                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" id="cirurgias_catarata" data-target="#cirurgias_catarata" aria-expanded="false" aria-controls="cirurgias_catarata">Catarata<span class="badge badge-primary ml-2">Novo!</span>
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>

                        <div class="collapse" id="cirurgias_catarata" data-parent="#accordionSidenavLayout">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" id="cirurgias_catarata_agendas" href="<?php echo URL ?>/cirurgias/catarata/agendas">Agendas</a>
                                <a class="nav-link" id="cirurgias_catarata_agendamento" href="<?php echo URL ?>/cirurgias/catarata/agendamento">Agendamento</a>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" id="cirurgias_catarata_configuracoes" data-target="#cirurgias_catarata_configuracoes" aria-expanded="false" aria-controls="cirurgias_catarata_configuracoes">Configurações
                                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="cirurgias_catarata_configuracoes" data-parent="#accordionSidenavLayout">
                                    <nav class="sidenav-menu-nested nav">
                                        <a class="nav-link" id="cirurgias_catarata_configuracoes_lentes" href="<?php echo URL ?>/cirurgias/catarata/configuracoes/lentes">Lentes</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>

                    </nav>
                </div>

                <?php } ?>

                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 3 OR $_SESSION['id_setor'] == 12){ ?>

                    <a class="nav-link collapsed" id="comp" href="javascript:void(0);" data-toggle="collapse" data-target="#compras" aria-expanded="false" aria-controls="compras">
                        <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        Compras
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="compras" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="compras_pedidos" href="<?php echo URL ?>/compras/pedidos">Pedidos de Compras</a>
                            <a class="nav-link" id="compras_notas" href="<?php echo URL ?>/compras/notas">Notas Fiscais</a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#compras_relatorios" aria-expanded="false" aria-controls="compras_relatorios">Relatórios
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="compras_relatorios" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" href="javascript:void(0);" data-toggle="modal" data-target="#modalRelatorioPorCategoria">Categorias</a>
                                    <a class="nav-link" href="javascript:void(0);" data-toggle="modal" data-target="#modalRelatorioPorFornecedor">Fornecedores</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" id="compras_configuracoes" data-target="#compras_configuracoes" aria-expanded="false" aria-controls="compras_configuracoes">Configurações<span class="badge badge-primary ml-2">Novo!</span>
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="compras_configuracoes" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" id="compras_configuracoes_categorias" href="<?php echo URL ?>/compras/configuracoes/categorias">Gerenciar Categorias</a>
                                    <a class="nav-link" id="compras_configuracoes_fornecedores" href="<?php echo URL ?>/compras/configuracoes/fornecedores">Gerenciar Fornecedores</a>
                                </nav>
                            </div>
                        </nav>
                    </div>

                <?php } ?>

                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 8 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 13 OR $_SESSION['id_setor'] == 14){ ?>

                    <a class="nav-link collapsed" id="capt" href="javascript:void(0);" data-toggle="collapse" data-target="#captacao" aria-expanded="false" aria-controls="captacao">
                        <div class="nav-link-icon"><i class="fa-solid fa-people-pulling"></i></div>
                        Captação
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="captacao" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="captcao_captar" href="<?php echo URL ?>/captacao/captar">Captar</a>
                            <a class="nav-link" id="captacao_alterar" href="<?php echo URL ?>/captacao/alterar">Alterar Captação</a>
                            <?php if($_SESSION['id_setor'] != 8){ ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#captacao_relatorios" aria-expanded="false" aria-controls="captacao_relatorios">Relatórios
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="captacao_relatorios" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" href="javascript:void(0);" data-toggle="modal" data-target="#modalCaptacaoGeral">Geral</a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>

                <?php } ?>

                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 5 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){ ?>

                <a class="nav-link collapsed" id="fina" href="javascript:void(0);" data-toggle="collapse" data-target="#financeiro" aria-expanded="false" aria-controls="financeiro">
                    <div class="nav-link-icon"><i class="fa-solid fa-money-bill"></i></div>
                    Financeiro
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="financeiro" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link" id="financeiro_campanhas" href="<?php echo URL ?>/financeiro/campanhas">Campanhas</a>
                        <a class="nav-link" id="financeiro_pendencias" href="<?php echo URL ?>/financeiro/pendencias">Pendencias</a>
                    </nav>
                </div>

                <?php } ?>

                <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 2 OR $_SESSION['id_setor'] == 3 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14){ ?>

                    <div class="sidenav-menu-heading">Gerenciamento</div>

                    <a class="nav-link collapsed" id="conf" href="javascript:void(0);" data-toggle="collapse" data-target="#configuracoes" aria-expanded="false" aria-controls="configuracoes">
                        <div class="nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                        Configurações
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="configuracoes" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            
                            <?php if ($_SESSION['id_setor'] != 3) { ?>
                                <a class="nav-link" id="configuracoes_cargos" href="<?php echo URL ?>/configuracoes/cargos">Gerenciar Cargos</a>
                                <a class="nav-link" id="configuracoes_convenios" href="<?php echo URL ?>/configuracoes/convenios">Gerenciar Convênios</a>
                                <a class="nav-link" id="configuracoes_medicos" href="<?php echo URL ?>/configuracoes/medicos">Gerenciar Médicos</a>
                                <a class="nav-link" id="configuracoes_setores" href="<?php echo URL ?>/configuracoes/setores">Gerenciar Setores</a>
                                <a class="nav-link" id="configuracoes_usuarios" href="<?php echo URL ?>/configuracoes/usuarios">Gerenciar Usuários</a>
                            <?php } ?>

                        </nav>
                    </div>

                    <?php if($_SESSION['id_setor'] == 1){ ?>

                        <a class="nav-link" id="logs" href="<?php echo URL ?>/logs">
                            <div class="nav-link-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                            Logs
                        </a>

                    <?php  }; ?>

                <?php }; ?>
            </div>
        </div>

        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Acessado Como:</div>
                <div class="sidenav-footer-title"><?php echo Helper::encurtarNome($_SESSION['nome']) ?></div>
            </div>
        </div>
    </nav>
</div>

<!-- Modais -->

    <!-- Modal Relatório por Categoria -->
    <div class="modal fade" id="modalRelatorioPorCategoria" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorCategoriaLabel" aria-hidden="true">
        <form action="<?php echo URL; ?>/compras/relatorios/categorias" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimir o Relatório por Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <label for="empresa" class="form-label">Empresas:</label>
                                <select name="empresa" class="form-control" id="porcategoria_empresa">
                                    <option value="0">Todas</option>
                                    <option value="1">Clínicas</option>
                                    <option value="2">Óticas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorCategoria">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Relatório por Fornecedor -->
    <div class="modal fade" id="modalRelatorioPorFornecedor" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorFornecedorLabel" aria-hidden="true">
        <form action="<?php echo URL; ?>/compras/relatorios/fornecedores" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimir o Relatório por Fornecedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <label for="empresa" class="form-label">Empresas:</label>
                                <select name="empresa" class="form-control" id="porfornecedor_empresa">
                                    <option value="0">Todas</option>
                                    <option value="1">Clínicas</option>
                                    <option value="2">Óticas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorFornecedor">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Relatório Captação Geral -->
    <div class="modal fade" id="modalCaptacaoGeral" tabindex="1" role="dialog" aria-labelledby="modalCaptacaoGeralLabel" aria-hidden="true">
        <form action="<?php echo URL; ?>/captacao/relatorios/geral" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gerar Relatório Geral Captação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p id="erroDatas" class="text-danger" style="display:none;">A data de fim não pode ser menor que a data de início.</p>
                            </div>
                            <div class="col-4 offset-2">
                                <div class="form-group">
                                    <label for="dataInicio">Data de Início</label>
                                    <input type="date" class="form-control" id="dataInicio" name="inicio" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="dataFim">Data de Fim</label>
                                    <input type="date" class="form-control" id="dataFim" name="fim" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btnGerar" name="btnGerarCaptacaoGeral" disabled>Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Alterar Senha -->
    <div class="modal fade" id="modalAlterarSenha" tabindex="1" role="dialog" aria-labelledby="modalAlterarSenhaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Alterar Senha de Acesso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8 offset-2 text-center d-none mb-3" id="senhasDifentes">
                                <span class="alert alert-danger">As Senhas são diferentes!</span>    
                            </div>
                            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                            <div class="col-8 offset-2">
                                <label for="senha_atual" class="form-label">Senha Atual *</label>
                                <input type="password" name="senha_atual" class="form-control" required>
                            </div>
                            <div class="col-8 offset-2 mt-3">
                                <label for="senha" class="form-label">Nova Senha *</label>
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <div class="col-8 offset-2">
                                <label for="confirma_senha" class="form-label">Confirmar Nova Senha *</label>
                                <input type="password" id="confirma_senha" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnAlterarSenha" id="btnAlterarSenha" class="btn btn-success disabled">Alterar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataInicio = document.getElementById('dataInicio');
        const dataFim = document.getElementById('dataFim');
        const btnGerar = document.getElementById('btnGerar');
        const erroDatas = document.getElementById('erroDatas');

        function validarDatas() {
            const inicio = new Date(dataInicio.value);
            const fim = new Date(dataFim.value);

            if (fim < inicio) {
                erroDatas.style.display = 'block'; 
                btnGerar.disabled = true;
            } else {
                erroDatas.style.display = 'none';
                btnGerar.disabled = false; 
            }
        }

        dataInicio.addEventListener('change', validarDatas);
        dataFim.addEventListener('change', validarDatas);
    });
    $(document).ready(function () {
         // Verificação das senhas no modal de alteração de senha
         $('#senha, #confirma_senha').keyup(function() { 
            let senha = $('#senha').val();
            let confirma_senha = $('#confirma_senha').val();

            if(senha === confirma_senha){
                $('#senhasDifentes').addClass('d-none');
                $('#btnAlterar').removeClass('disabled');
            } else {
                $('#senhasDifentes').removeClass('d-none');
                $('#btnAlterar').addClass('disabled');
            }
        });
    });
</script>