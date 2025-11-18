<?php 
    $Usuario = new Usuario();

    if(isset($_POST['btnAlterarSenha'])){
        $Usuario->alterarSenha($_POST);
    }

    function gerarOpcoesAnos() {
        $anoAtual = date('Y'); // Obtém o ano atual
        $anos = range(2024, $anoAtual); // Cria um intervalo de anos de 2024 até o ano atual
    
        foreach ($anos as $ano) {
            // Adiciona o atributo "selected" se for o ano atual
            $selected = ($ano == $anoAtual) ? 'selected' : '';
            echo "<option value=\"$ano\" $selected>$ano</option>\n";
        }
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
                <?php if(verificarSetor([1,5,12,13,14])){ ?>
                    
                    <a class="nav-link collapsed" id="dash" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                        <div class="nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>
                        Dashboards
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseDashboards" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link" href="<?php echo URL ?>/dashboards/captacao">Captação</a>
                            <!-- <a class="nav-link" href="<?php echo URL ?>/dashboards/catarata">Catarata</a> -->
                            <a class="nav-link" href="<?php echo URL ?>/dashboards/cobranca">Cobrança</a>
                            <a class="nav-link" href="<?php echo URL ?>/dashboards/lente_contato">Lente de Contato</a>
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
                        <?php if(verificarSetor([1])){ ?>
                            <a class="nav-link" id="chamados_todos" href="<?php echo URL ?>/chamados/todos">Todos os Chamados</a>
                        <?php } ?>
                    </nav>
                </div>

                <?php if(verificarSetor([1,5,12,14])){ ?>

                    <a class="nav-link collapsed" id="arqu" href="javascript:void(0);" data-toggle="collapse" data-target="#arquivo" aria-expanded="false" aria-controls="arquivo">
                        <div class="nav-link-icon"><i class="fa-solid fa-box-archive"></i></div>
                        Arquivos
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="arquivo" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="arquivos_arquivo_morto" href="<?php echo URL ?>/arquivos/arquivos_mortos">Arquivos Mortos</a>
                        </nav>
                    </div>
                
                <?php } ?>

                <?php if(verificarSetor([1,8,12,13,14])){ ?>

                    <a class="nav-link collapsed" id="capt" href="javascript:void(0);" data-toggle="collapse" data-target="#captacao" aria-expanded="false" aria-controls="captacao">
                        <div class="nav-link-icon"><i class="fa-solid fa-people-pulling"></i></div>
                        Captação
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="captacao" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="captcao_captar" href="<?php echo URL ?>/captacao/captar">Captar</a>
                            <a class="nav-link" id="captacao_alterar" href="<?php echo URL ?>/captacao/alterar">Alterar Captação</a>
                            <?php if(bloquearSetor([8])){ ?>
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

                <?php if(verificarSetor([1,5,12,14,15])){ ?>

                    <a class="nav-link collapsed" id="ciru" href="javascript:void(0);" data-toggle="collapse" data-target="#cirurgias" aria-expanded="false" aria-controls="cirurgias">
                        <div class="nav-link-icon"><i class="fa-solid fa-eye"></i></div>
                        Cirurgias
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="cirurgias" data-parent="#accordionSidenav">

                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" id="cirurgias_catarata" data-target="#cirurgias_catarata" aria-expanded="false" aria-controls="cirurgias_catarata">Catarata
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="cirurgias_catarata" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" id="cirurgias_catarata_agendas" href="<?php echo URL ?>/cirurgias/catarata/agendas">Agendas</a>
                                    <a class="nav-link" id="cirurgias_catarata_agendamento" href="<?php echo URL ?>/cirurgias/catarata/agendamento">Agendamento</a>
                                    <?php if($_SESSION['id_usuario'] == 35 OR verificarSetor([1,12,14,15])){ ?>
                                    <a class="nav-link" id="cirurgias_catarata_agendamento" href="<?php echo URL ?>/cirurgias/catarata/agendamento_externo">Agendamento Exter</a>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" id="cirurgias_catarata_configuracoes" data-target="#cirurgias_catarata_configuracoes" aria-expanded="false" aria-controls="cirurgias_catarata_configuracoes">Configurações
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="cirurgias_catarata_configuracoes" data-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" id="cirurgias_catarata_configuracoes_lentes" href="<?php echo URL ?>/cirurgias/catarata/configuracoes/lentes">Lentes</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" id="cirurgias_catarata_relatorios" data-target="#cirurgias_catarata_relatorios" aria-expanded="false" aria-controls="cirurgias_catarata_relatorios">Relatórios
                                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="cirurgias_catarata_relatorios" data-parent="#accordionSidenavLayout">
                                        <nav class="sidenav-menu-nested nav">
                                            <a class="nav-link" id="cirurgias_catarata_relatorios_lentes" href="<?php echo URL ?>/cirurgias/catarata/relatorios/lentes">Vendas</a>
                                        </nav>
                                    </div>
                                    <?php } ?>
                                </nav>
                            </div>

                        </nav>
                    </div>

                <?php } ?>

                <?php if(verificarSetor([1,3,12])){ ?>

                    <a class="nav-link collapsed" id="comp" href="javascript:void(0);" data-toggle="collapse" data-target="#compras" aria-expanded="false" aria-controls="compras">
                        <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        Compras
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="compras" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="compras_pedidos" href="<?php echo URL ?>/compras/pedidos">Pedidos de Compras</a>
                            <a class="nav-link" id="compras_notas" href="<?php echo URL ?>/compras/notas">Notas Fiscais</a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" id="compras_configuracoes" data-target="#compras_configuracoes" aria-expanded="false" aria-controls="compras_configuracoes">Configurações
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="compras_configuracoes" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" id="compras_configuracoes_categorias" href="<?php echo URL ?>/compras/configuracoes/categorias">Gerenciar Categorias</a>
                                    <a class="nav-link" id="compras_configuracoes_fornecedores" href="<?php echo URL ?>/compras/configuracoes/fornecedores">Gerenciar Fornecedores</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" id="compras_relatorios" href="#" data-toggle="collapse" data-target="#compras_relatorios" aria-expanded="false" aria-controls="compras_relatorios">Relatórios
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="compras_relatorios" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" id="compras_relatorios_estoque_venda" href="<?php echo URL ?>/compras/relatorios/estoque_venda">Estoque e Venda</a>
                                    <a class="nav-link" href="javascript:void(0);" data-toggle="modal" data-target="#modalRelatorioPorCategoria">Categorias</a>
                                    <a class="nav-link" href="javascript:void(0);" data-toggle="modal" data-target="#modalRelatorioPorFornecedor">Fornecedores</a>
                                </nav>
                            </div>
                        </nav>
                    </div>

                <?php } ?>


                <?php if(verificarSetor([1,5,12,14,18])){ ?>

                    <a class="nav-link collapsed" id="fatu" href="javascript:void(0);" data-toggle="collapse" data-target="#faturamento" aria-expanded="false" aria-controls="faturamento">
                        <div class="nav-link-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                        Faturamento
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="faturamento" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="faturamento_competencias" href="<?php echo URL ?>/faturamento/competencias">Competências</a>
                        </nav>
                    </div>
                
                <?php } ?>

                <?php if(verificarSetor([1,5,12,14])){ ?>

                    <a class="nav-link collapsed" id="fina" href="javascript:void(0);" data-toggle="collapse" data-target="#financeiro" aria-expanded="false" aria-controls="financeiro">
                        <div class="nav-link-icon"><i class="fa-solid fa-money-bill"></i></div>
                        Financeiro
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="financeiro" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="financeiro_campanhas" href="<?php echo URL ?>/financeiro/campanhas">Campanhas</a>
                            <a class="nav-link" id="financeiro_contratos" href="<?php echo URL ?>/financeiro/contratos">Contratos</a>
                            <a class="nav-link" id="financeiro_faturas_atrasadas" href="<?php echo URL ?>/financeiro/faturas_atrasadas">Faturas Atrasadas</a>
                        </nav>
                    </div>
                
                <?php } ?>

                <?php if(verificarSetor([1,3,5,12,17])){ ?>

                    <a class="nav-link collapsed" id="lent" href="javascript:void(0);" data-toggle="collapse" data-target="#lente_contato" aria-expanded="false" aria-controls="financeiro">
                        <div class="nav-link-icon"><i class="fa-solid fa-microscope"></i></div>
                        Lente de Contato
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="lente_contato" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <?php if(verificarSetor([1,3,5,12,17])){ ?>
                            <a class="nav-link" id="lente_contato_orcamentos" href="<?php echo URL ?>/lente_contato/orcamentos">Orçamentos</a>
                            <a class="nav-link" id="lente_contato_testes" href="<?php echo URL ?>/lente_contato/testes">Testes</a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" id="lente_contato_configuracoes" data-target="#lente_contato_configuracoes" aria-expanded="false" aria-controls="compras_configuracoes">Configurações
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="lente_contato_configuracoes" data-parent="#accordionSidenavLayout">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" id="lente_contato_configuracoes_fornecedores" href="<?php echo URL ?>/lente_contato/configuracoes/fornecedores">Gerenciar Fornecedores</a>
                                    <a class="nav-link" id="lente_contato_configuracoes_modelos" href="<?php echo URL ?>/lente_contato/configuracoes/modelos">Gerenciar Modelos</a>
                                </nav>
                            </div>
                            <?php } ?>
                        </nav>
                    </div>
                
                <?php } ?>

                <?php if(verificarSetor([1,3])){ ?>
                
                    <a class="nav-link collapsed" id="tec" href="javascript:void(0);" data-toggle="collapse" data-target="#tecnologia" aria-expanded="false" aria-controls="financeiro">
                        <div class="nav-link-icon"><i class="fa-solid fa-microchip"></i></div>
                        Tecnologia
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="tecnologia" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            <a class="nav-link" id="tecnologia_computadores" href="<?php echo URL ?>/tecnologia/computadores">Computadores</a>
                            <?php if(verificarSetor([1])){ ?>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" id="tecnologia_configuracoes" data-target="#tecnologia_configuracoes" aria-expanded="false" aria-controls="compras_configuracoes">Configurações
                                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="tecnologia_configuracoes" data-parent="#accordionSidenavLayout">
                                    <nav class="sidenav-menu-nested nav">
                                        <a class="nav-link" id="tecnologia_configuracoes_processadores" href="<?php echo URL ?>/tecnologia/configuracoes/processadores">Gerenciar Processadores</a>
                                        <a class="nav-link" id="tecnologia_configuracoes_sockets" href="<?php echo URL ?>/tecnologia/configuracoes/sockets">Gerenciar Sockets</a>
                                    </nav>
                                </div>
                            <?php } ?>
                        </nav>
                    </div>

                <?php } ?>
                <?php if(verificarSetor([1,2,3,12,14,18])){ ?>

                    <div class="sidenav-menu-heading">Gerenciamento</div>

                    <a class="nav-link collapsed" id="conf" href="javascript:void(0);" data-toggle="collapse" data-target="#configuracoes" aria-expanded="false" aria-controls="configuracoes">
                        <div class="nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                        Configurações
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="configuracoes" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                            
                            <?php if (bloquearSetor([3])) { ?>
                                <a class="nav-link" id="configuracoes_cargos" href="<?php echo URL ?>/configuracoes/cargos">Gerenciar Cargos</a>
                                <a class="nav-link" id="configuracoes_convenios" href="<?php echo URL ?>/configuracoes/convenios">Gerenciar Convênios</a>
                                <a class="nav-link" id="configuracoes_medicos" href="<?php echo URL ?>/configuracoes/medicos">Gerenciar Médicos</a>
                                <a class="nav-link" id="configuracoes_setores" href="<?php echo URL ?>/configuracoes/setores">Gerenciar Setores</a>
                                <a class="nav-link" id="configuracoes_usuarios" href="<?php echo URL ?>/configuracoes/usuarios">Gerenciar Usuários</a>
                            <?php } ?>

                        </nav>
                    </div>

                    <?php if(verificarSetor([1])){ ?>

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
                            <div class="col-2 offset-3">
                                <label for="ano" class="form-label">Ano:</label>
                                <select name="ano" class="form-control" id="porcategoria_ano">
                                    <?php gerarOpcoesAnos(); ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="empresa" class="form-label">Empresas:</label>
                                <select name="empresa" class="form-control" id="porcategoria_empresa" required>
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
                            <div class="col-2 offset-3">
                                <label for="ano" class="form-label">Ano:</label>
                                <select name="ano" class="form-control" id="porfornecedor_ano">
                                    <?php gerarOpcoesAnos(); ?>
                                </select>
                            </div>
                            <div class="col-4">
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
</script>