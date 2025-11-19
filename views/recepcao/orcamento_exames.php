<?php
$Exame = new Exame();
$listaExames = $Exame->listar();
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
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="row mt-4">
                    <div class="col-md-8 offset-md-2">

                        <!-- Seleção: Particular ou Fidelidade -->
                        <div class="card mb-3">
                            <div class="card-header">
                                Tipo de Valor
                            </div>
                            <div class="card-body">
                                <label class="mr-3">
                                    <input type="radio" name="tipo_valor" value="particular" checked> Particular
                                </label>
                                <label>
                                    <input type="radio" name="tipo_valor" value="fidelidade"> Fidelidade
                                </label>
                            </div>
                        </div>

                        <!-- Barra de Pesquisa -->
                        <div class="card mb-3">
                            <div class="card-header">Pesquisar Exames</div>
                            <div class="card-body">
                                <input type="text" id="buscar_exame" class="form-control" placeholder="Digite para filtrar...">
                            </div>
                        </div>


                        <!-- Lista de exames com checkbox -->
                        <div class="card">
                            <div class="card-header">
                                Exames Disponíveis
                            </div>

                            <div class="card-body">
                                <form id="form_exames">

                                    <?php foreach ($listaExames as $exame): ?>
                                        <div class="row align-items-center mb-2 exame-item"
                                            data-particular="<?php echo $exame->valor_particular; ?>"
                                            data-fidelidade="<?php echo $exame->valor_fidelidade; ?>">

                                            <div class="col-1 text-right">
                                                <input type="checkbox" name="exames[]" value="<?php echo $exame->id_exame; ?>">
                                            </div>

                                            <div class="col-7">
                                                <?php echo $exame->exame; ?>
                                            </div>

                                            <div class="col-4 valor-exame text-right font-weight-bold">
                                                R$ <?php echo number_format($exame->valor_particular, 2, ',', '.'); ?>
                                            </div>
                                        </div>

                                        <hr>
                                    <?php endforeach; ?>

                                </form>
                            </div>
                        </div>

                        <!-- Botão imprimir -->
                        <div class="text-center mt-4">
                            <a href="gerar_orcamento_exames" id="btn_imprimir" class="btn btn-primary btn-lg" target="_blank">
                                Imprimir
                            </a>
                        </div>

                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#rece').addClass('active')

            // Troca os valores ao mudar o tipo (já existia)
            function atualizarValores() {
                let tipo = $('input[name="tipo_valor"]:checked').val();

                $('.exame-item').each(function() {
                    let valor = (tipo === 'particular') ?
                        $(this).data('particular') :
                        $(this).data('fidelidade');

                    valor = parseFloat(valor).toFixed(2).replace('.', ',');

                    $(this).find('.valor-exame').text("R$ " + valor);
                });
            }

            $('input[name="tipo_valor"]').on('change', atualizarValores);
            atualizarValores();

            // ✔ Clicar no nome ou linha seleciona o checkbox
            $('.exame-item').on('click', function(e) {

                // evitar que o clique no próprio checkbox duplique o toggle
                if ($(e.target).is('input[type="checkbox"]')) return;

                let checkbox = $(this).find('input[type="checkbox"]');

                checkbox.prop('checked', !checkbox.prop('checked'));
            });

            // Pesquisa dinâmica nos exames
            $('#buscar_exame').on('keyup', function() {

                let termo = $(this).val().toLowerCase().trim();

                $('.exame-item').each(function() {

                    let nomeExame = $(this).find('.col-7').text().toLowerCase();

                    // Exibe se o nome contém o termo
                    if (nomeExame.indexOf(termo) !== -1) {
                        $(this).show().next('hr').show();
                    } else {
                        $(this).hide().next('hr').hide();
                    }
                });
            });

            $("#btn_imprimir").on("click", function() {

                let tipo = $("input[name='tipo_valor']:checked").val();
                let exames = [];

                $("input[name='exames[]']:checked").each(function() {
                    exames.push($(this).val());
                });

                if (exames.length === 0) {
                    alert("Selecione ao menos um exame!");
                    return;
                }

                let params = "?tipo=" + tipo + "&exames[]=" + exames.join("&exames[]=");

                window.open("gerar_orcamento_exames" + params, "_blank");
            });

        });
    </script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/chart-area-demo.js"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/demo/datatables-demo.js"></script>
</body>

</html>