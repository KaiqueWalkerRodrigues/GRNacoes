<?php 
    $Usuario = new Usuario();
    $Cargo = new Cargo();
    $Setor = new Setor();

    if (isset($_POST['btnCadastrarSolicitacao'])) {
        $Usuario->cadastrarSolicitacao($_POST);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Solicitação de cadastro" />
    <meta name="author" content="" />
    <title><?php echo $pageTitle ?></title>
    <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body { background-color: #f8f9fa; }
        .page-title { font-size: 1.5rem; font-weight: 600; }
        .required::after { content: " *"; color: #dc3545; }
        .card { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075 ); }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="page-title"><i class="fa-solid fa-user-plus me-2"></i> Solicitação de Cadastro</h1>
            <p class="text-muted mb-0">Preencha os campos abaixo e confirme para enviar sua solicitação.</p>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-user-plus me-2"></i> &nbsp;
                Formulário de Solicitação de Cadastro
            </div>
            <div class="card-body">
                <form action="?" method="post" id="formSolicitacaoCadastro" novalidate>
                    <input type="hidden" name="btnCadastrarSolicitacao" value="1">

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="nome" class="form-label required">Nome Completo do Funcionário</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nome" id="nome" required>
                                <button class="btn btn-success" type="button" id="gerar_usuario" title="Gerar usuário pelo nome"><i class="fa-solid fa-user"></i></button>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="usuario" class="form-label required">Usuário</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="celular" class="form-label required">Celular</label>
                            <input type="text" class="form-control" name="celular" id="celular" placeholder="(99) 99999-9999" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cpf" class="form-label required">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-3">
                            <label for="senha" class="form-label required">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="senha" id="senha" required>
                                <button class="btn btn-outline-secondary" type="button" id="ver_senha" title="Mostrar/ocultar senha">
                                    <i class="fa-solid fa-eye-slash" id="icone_visibilidade"></i>
                                </button>
                            </div>
                            <div class="text-danger">(!) Mínimo de 6 caracteres.</div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="confirmar_senha" class="form-label required">Confirmar Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="confirmar_senha" id="confirmar_senha" required>
                                <button class="btn btn-outline-secondary" type="button" id="confirmar_ver_senha" title="Mostrar/ocultar senha">
                                    <i class="fa-solid fa-eye-slash" id="confirmar_icone_visibilidade"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="data_nascimento" class="form-label required">Data de Nascimento</label>
                            <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="email@exemplo.com">
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-3">
                            <label for="empresa" class="form-label required">Empresa</label>
                            <select class="form-control" name="empresa" id="empresa" required>
                                <option value="">Selecione...</option>
                                <option value="1">Clínica Parque</option>
                                <option value="3">Clínica Mauá</option>
                                <option value="5">Clínica Jardim</option>
                                <option value="2">Ótica Matriz</option>
                                <option value="4">Ótica Prestigio</option>
                                <option value="6">Ótica Daily</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="id_setor" class="form-label required">Setor</label>
                            <select class="form-control" name="id_setor" id="id_setor" required>
                                <option value="">Selecione...</option>
                                <?php foreach($Setor->listar() as $setor){ ?>
                                    <option value="<?php echo $setor->id_setor ?>"><?php echo $setor->setor ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="id_cargo" class="form-label required">Cargo</label>
                            <select class="form-control" name="id_cargo" id="id_cargo" required>
                                <option value="">Selecione...</option>
                                <?php foreach($Cargo->listar() as $cargo){ ?>
                                    <option value="<?php echo $cargo->id_cargo ?>"><?php echo $cargo->cargo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="contrato" class="form-label required">Contrato</label>
                            <select class="form-control" name="contrato" id="contrato" required>
                                <option value="">Selecione...</option>
                                <option value="0">CLT</option>
                                <option value="1">Estagiário</option>
                                <option value="2">MEI</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-3">
                            <label for="n_folha" class="form-label">Número da Folha</label>
                            <input type="text" class="form-control" name="n_folha" id="n_folha" placeholder="Opcional">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="data_admissao" class="form-label required">Data de Admissão</label>
                            <input type="date" class="form-control" name="data_admissao" id="data_admissao" required>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <strong>Atenção:</strong> Todos os campos marcados com (*) são obrigatórios. Verifique as informações antes de enviar.
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-secondary me-2" onclick="limparFormulario()">
                            <i class="fa-solid fa-eraser me-1"></i>&nbsp;Limpar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="confirmarCadastro()">
                            <i class="fa-solid fa-paper-plane me-1"></i>&nbsp;Enviar Solicitação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="modalConfirmacao" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacaoLabel"><i class="fa-solid fa-question-circle me-2"></i>Confirmar Solicitação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tem certeza que deseja enviar esta solicitação de cadastro?</strong></p>
                    <p>Verifique se todas as informações estão corretas antes de confirmar.</p>
                    <div id="dadosConfirmacao" class="small"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-times me-1"></i>Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarSolicitacao()"><i class="fa-solid fa-check me-1"></i>Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script>
        function removerAcentos(str ) {
            return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }
        function validarCPF(cpf) {
            cpf = (cpf || '').replace(/[^\d]+/g,'');
            if (!cpf || cpf.length !== 11 || /(\d)\1{10}/.test(cpf)) return false;
            let add = 0;
            for (let i = 0; i < 9; i++) add += parseInt(cpf.charAt(i)) * (10 - i);
            let rev = 11 - (add % 11); if (rev === 10 || rev === 11) rev = 0;
            if (rev !== parseInt(cpf.charAt(9))) return false;
            add = 0;
            for (let i = 0; i < 10; i++) add += parseInt(cpf.charAt(i)) * (11 - i);
            rev = 11 - (add % 11); if (rev === 10 || rev === 11) rev = 0;
            if (rev !== parseInt(cpf.charAt(10))) return false;
            return true;
        }
        function formatarCPF($campo) {
            let cpf = ($campo.val() || '').replace(/\D/g, '');
            if (cpf.length > 11) cpf = cpf.substring(0, 11);
            if (cpf.length >= 3) cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            if (cpf.length >= 7) cpf = cpf.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            if (cpf.length >= 11) cpf = cpf.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})$/, '$1.$2.$3-$4');
            $campo.val(cpf);
            const valido = validarCPF(cpf);
            $campo.toggleClass('is-invalid', !valido).toggleClass('is-valid', valido);
            if (!valido) {
                if ($campo.next('.invalid-feedback').length === 0) {
                    $('<div class="invalid-feedback">CPF inválido. Verifique e tente novamente.</div>').insertAfter($campo);
                }
            } else {
                $campo.next('.invalid-feedback').remove();
            }
            return valido;
        }
        function formatarCelular($campo) {
            let tel = ($campo.val() || '').replace(/\D/g, ''); // mantém apenas dígitos
            if (tel.length > 11) tel = tel.substring(0, 11);

            // aplica máscara só se tiver DDD + número
            let formatado = tel;
            if (tel.length >= 2) formatado = formatado.replace(/(\d{2})(\d)/, '($1) $2');
            if (tel.length >= 7) formatado = formatado.replace(/(\d{5})(\d{0,4})$/, '$1-$2');

            $campo.val(formatado.trim());

            // valida apenas os dígitos
            const valido = (tel.length === 11);

            $campo.toggleClass('is-invalid', !valido).toggleClass('is-valid', valido);
            if (!valido) {
                if ($campo.next('.invalid-feedback').length === 0) {
                    $('<div class="invalid-feedback">Celular inválido. Formato: (99) 99999-9999.</div>').insertAfter($campo);
                }
            } else {
                $campo.next('.invalid-feedback').remove();
            }
            return valido;
        }


        function validarEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function validarSenha(senha) {
            return senha.length >= 6;
        }

        function validarFormulario() {
            let valido = true;

            // Validação de campos obrigatórios
            $('#formSolicitacaoCadastro [required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">Este campo é obrigatório.</div>').insertAfter($(this));
                    }
                    valido = false;
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Validação de CPF
            if (!formatarCPF($('#cpf'))) valido = false;

            // Validação de Celular
            if (!formatarCelular($('#celular'))) valido = false;

            // Validação de Email
            const $email = $('#email');
            if ($email.val() && !validarEmail($email.val())) {
                $email.addClass('is-invalid');
                if ($email.next('.invalid-feedback').length === 0) {
                    $('<div class="invalid-feedback">E-mail inválido.</div>').insertAfter($email);
                }
                valido = false;
            } else if ($email.val()) {
                $email.removeClass('is-invalid').addClass('is-valid');
                $email.next('.invalid-feedback').remove();
            }

            // Validação de Senha
            const $senha = $('#senha');
            const $confirmarSenha = $('#confirmar_senha');

            if ($senha.val() && !validarSenha($senha.val())) {
                $senha.addClass('is-invalid');
                if ($senha.next('.invalid-feedback').length === 0) {
                    $('<div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>').insertAfter($senha);
                }
                valido = false;
            } else if ($senha.val()) {
                $senha.removeClass('is-invalid').addClass('is-valid');
                $senha.next('.invalid-feedback').remove();
            }

            if ($senha.val() !== $confirmarSenha.val()) {
                $confirmarSenha.addClass('is-invalid');
                if ($confirmarSenha.next('.invalid-feedback').length === 0) {
                    $('<div class="invalid-feedback">As senhas não coincidem.</div>').insertAfter($confirmarSenha);
                }
                valido = false;
            } else if ($confirmarSenha.val()) {
                $confirmarSenha.removeClass('is-invalid').addClass('is-valid');
                $confirmarSenha.next('.invalid-feedback').remove();
            }

            return valido;
        }

        function limparFormulario() {
            if (confirm('Tem certeza que deseja limpar todos os campos do formulário?')) {
                $('#formSolicitacaoCadastro')[0].reset();
                $('#formSolicitacaoCadastro .is-invalid, #formSolicitacaoCadastro .is-valid').removeClass('is-invalid is-valid');
                $('#formSolicitacaoCadastro .invalid-feedback').remove();
                $('#icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                $('#confirmar_icone_visibilidade').removeClass('fa-eye').addClass('fa-eye-slash');
                $('#senha').attr('type', 'password');
                $('#confirmar_senha').attr('type', 'password');
            }
        }

        function confirmarCadastro() {
            if (validarFormulario()) {
                let dados = '';
                $('#formSolicitacaoCadastro input, #formSolicitacaoCadastro select').each(function() {
                    const $this = $(this);
                    const label = $this.prev('label').text().replace('*', '').trim();
                    let valor = $this.val();

                    if ($this.attr('type') === 'password') {
                        valor = '********'; // Não mostrar senha
                    } else if ($this.is('select')) {
                        valor = $this.find('option:selected').text();
                    }

                    if (label && valor) {
                        dados += `<p><strong>${label}:</strong> ${valor}</p>`;
                    }
                });
                $('#dadosConfirmacao').html(dados);
                var myModal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
                myModal.show();
            }
        }

        function enviarSolicitacao() {
            $('#formSolicitacaoCadastro').submit();
        }

        $(document).ready(function() {
            // Gerar usuário
            $('#gerar_usuario').click(function() {
                const nomeCompleto = $('#nome').val();
                if (nomeCompleto) {
                    const partesNome = removerAcentos(nomeCompleto).toLowerCase().split(' ');
                    let usuarioGerado = '';
                    if (partesNome.length > 0) {
                        usuarioGerado = partesNome[0];
                        if (partesNome.length > 1) {
                            usuarioGerado += '.' + partesNome[partesNome.length - 1];
                        }
                    }
                    $('#usuario').val(usuarioGerado);
                    $('#usuario').removeClass('is-invalid').addClass('is-valid');
                    $('#usuario').next('.invalid-feedback').remove();
                }
            });

            // Mostrar/Ocultar Senha
            $('#ver_senha').click(function() {
                const senhaInput = $('#senha');
                const icone = $('#icone_visibilidade');
                if (senhaInput.attr('type') === 'password') {
                    senhaInput.attr('type', 'text');
                    icone.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    senhaInput.attr('type', 'password');
                    icone.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            $('#confirmar_ver_senha').click(function() {
                const senhaInput = $('#confirmar_senha');
                const icone = $('#confirmar_icone_visibilidade');
                if (senhaInput.attr('type') === 'password') {
                    senhaInput.attr('type', 'text');
                    icone.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    senhaInput.attr('type', 'password');
                    icone.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            // Validações em tempo real
            $('#cpf').on('blur', function() { formatarCPF($(this)); });
            $('#celular').on('blur', function() { formatarCelular($(this)); });
            $('#email').on('blur', function() {
                const $email = $(this);
                if ($email.val() && !validarEmail($email.val())) {
                    $email.addClass('is-invalid');
                    if ($email.next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">E-mail inválido.</div>').insertAfter($email);
                    }
                } else if ($email.val()) {
                    $email.removeClass('is-invalid').addClass('is-valid');
                    $email.next('.invalid-feedback').remove();
                }
            });

            $('#senha').on('blur', function() {
                const $senha = $(this);
                if ($senha.val() && !validarSenha($senha.val())) {
                    $senha.addClass('is-invalid');
                    if ($senha.next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>').insertAfter($senha);
                    }
                } else if ($senha.val()) {
                    $senha.removeClass('is-invalid').addClass('is-valid');
                    $senha.next('.invalid-feedback').remove();
                }
                // Revalida a confirmação de senha se a senha for alterada
                $('#confirmar_senha').trigger('blur');
            });

            $('#confirmar_senha').on('blur', function() {
                const $senha = $('#senha');
                const $confirmarSenha = $(this);
                if ($senha.val() !== $confirmarSenha.val()) {
                    $confirmarSenha.addClass('is-invalid');
                    if ($confirmarSenha.next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">As senhas não coincidem.</div>').insertAfter($confirmarSenha);
                    }
                } else if ($confirmarSenha.val()) {
                    $confirmarSenha.removeClass('is-invalid').addClass('is-valid');
                    $confirmarSenha.next('.invalid-feedback').remove();
                }
            });

            // Validação de campos obrigatórios ao sair do campo
            $('#formSolicitacaoCadastro [required]').on('blur', function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.invalid-feedback').length === 0) {
                        $('<div class="invalid-feedback">Este campo é obrigatório.</div>').insertAfter($(this));
                    }
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).next('.invalid-feedback').remove();
                }
            });
        });
    </script>
</body>
</html>
