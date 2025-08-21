<?php 
    $Compra_Fornecedor = new Compra_Fornecedor();

    $meses = [
        "Janeiro", 
        "Fevereiro", 
        "Março", 
        "Abril", 
        "Maio", 
        "Junho", 
        "Julho", 
        "Agosto", 
        "Setembro", 
        "Outubro", 
        "Novembro", 
        "Dezembro"
    ];
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
                    <div class="page-header pb-10 page-header-dark bg-dark">
                        <div class="container-fluid">
                            <div class="page-header-content">
                                <h1 class="page-header-title mb-4">
                                    <div class="page-header-icon"><i class="fa-light fa-ballot-check"></i></div>
                                    <span>Estoque e Venda</span>
                                </h1>
                                <div class="row">
                                    <div class="col-2">
                                        <label for="id_empresa" class="form-label text-light">Empresa</label>
                                        <select name="id_empresa" id="id_empresa" class="form-control">
                                            <option value="2">Matriz</option>
                                            <option value="4">Prestigio</option>
                                            <option value="6">Daily</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="mes" class="form-label text-light">Mês</label>
                                        <select name="mes" id="mes" class="form-control">
                                            <?php $x = 1; foreach($meses as $mes){ ?>
                                                <option value="<?php echo $x; ?>"><?php echo $mes; ?></option>
                                            <?php $x++; } ?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="ano" class="form-label text-light">Ano</label>
                                        <select name="ano" id="ano" class="form-control">
                                            <?php 
                                                $anoAtual = date("Y");
                                                $anoFinal = $anoAtual + 2;
                                                for ($ano = 2024; $ano <= $anoFinal; $ano++) { 
                                            ?>
                                                <option value="<?php echo $ano; ?>" 
                                                    <?php echo ($ano == $anoAtual) ? 'selected' : ''; ?>>
                                                    <?php echo $ano; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt-n10">
                        <div class="row" id="cardsContainer">
                            <!-- AJAX vai preencher aqui -->
                        </div>
                        <div class="col-1 offset-11 text-center">
                            <button id="btnSalvar" class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </main>
            <?php include_once('resources/footer.php'); ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('#comp').addClass('active')
            $('#compras_relatorios').addClass('active')
            $('#compras_relatorios_estoque_venda').addClass('active')

            function carregarCards() {
                var mes = $('#mes').val();
                var ano = $('#ano').val();
                var id_empresa = $('#id_empresa').val();

                if (mes === "" || id_empresa === "") {
                    $('#cardsContainer').html(""); // não mostra nada
                    return;
                }

                $.ajax({
                    url: "/GRNacoes/views/ajax/get_estoque_venda.php",
                    method: "GET",
                    data: { mes: mes, ano: ano, id_empresa: id_empresa },
                    dataType: "json",
                    success: function(res) {
                        $('#cardsContainer').html(res.html);
                    }
                });
            }

            // recarregar também quando mudar EMPRESA
            $('#mes, #ano, #id_empresa').on('change', carregarCards);

            $("#btnSalvar").click(function () {
                var id_empresa = $("#id_empresa").val(); // <-- PEGAR AQUI
                var mes = $("#mes").val();
                var ano = $("#ano").val();

                // Monta formData com id_empresa incluso
                var formData = {
                    id_empresa: id_empresa,   // <-- INCLUA
                    mes: mes,
                    ano: ano,
                    id_fornecedor: [],
                    estoque: [],
                    venda: []
                };

                // Coletar dados dos cards
                $("#cardsContainer .card").each(function () {
                    var id_fornecedor = $(this).find('input[name="id_fornecedor[]"]').val();
                    var estoque = $(this).find('input[name="estoque[]"]').val();
                    var venda = $(this).find('input[name="venda[]"]').val();

                    formData.id_fornecedor.push(id_fornecedor);
                    formData.estoque.push(estoque);
                    formData.venda.push(venda);
                });

                $.ajax({
                    url: "/GRNacoes/views/ajax/set_estoque_venda.php",
                    method: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (res) {
                        if (res.status === "success") {
                            alert("✅ " + res.message);
                        } else {
                            alert("❌ " + res.message);
                        }
                    }
                });
            });

            carregarCards();

            // dispara sempre que mudar mês ou ano
            $('#mes, #ano').on('change', carregarCards);
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
</body>

</html>