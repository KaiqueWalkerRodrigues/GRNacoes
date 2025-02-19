// Declaração global da variável para armazenar o gráfico
let Area_Receitas = null;

// Define a fonte e a cor padrão para o Chart.js (imitando o estilo do Bootstrap)
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     Exemplo: number_format(1234.56, 2, ',', ' ');
    // *     Retorna: '1 234,56'
    number = (number + '').replace(',', '').replace(' ');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Função para buscar os dados do JSON e atualizar o gráfico para cada dia da semana
async function fetchDataAndUpdateChart(semana) {
    var ctx = document.getElementById("area_lente_contato_semanal");
    var { daysWithDates, datesISO } = getDatesFromInput(semana); // Obtém as datas da semana selecionada

    var idEmpresa = document.getElementById("empresaSelect").value;

    try {
        // Realiza a requisição passando a data inicial da semana e o id da empresa
        let response = await fetch(`/grnacoes/resources/assets/js/dados/get_lente_contato_area.php?data=${datesISO[0]}&id_empresa=${idEmpresa}`);
        let dadosSemana = await response.json();

        // Criação dos arrays para os diferentes conjuntos de dados
        let dataPedidos = [];
        let dataPagos = [];
        let dataEntregues = [];
        let dataTestes = [];
        let dataOrcamentos = [];
        let dataLentes = [];

        // Preenche os arrays com os dados retornados do backend
        dadosSemana.forEach(dia => {
            dataPedidos.push(dia.pedidos);
            dataPagos.push(dia.pagos);
            dataEntregues.push(dia.entregues);
            dataTestes.push(dia.testes);
            dataOrcamentos.push(dia.total_orcamentos);
            dataLentes.push(dia.total_lentes);
        });

        // Se o gráfico já existir, destrói-o para criar um novo
        if (Area_Receitas) {
            Area_Receitas.destroy();
        }

        // Criação do gráfico com os novos datasets
        Area_Receitas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: daysWithDates,
                datasets: [
                    {
                        label: "Pedidos",
                        lineTension: 0.3,
                        backgroundColor: "rgba(255, 159, 64, 0.05)",
                        borderColor: "rgba(255, 159, 64, 1)",
                        data: dataPedidos
                    },
                    {
                        label: "Pagos",
                        lineTension: 0.3,
                        backgroundColor: "rgba(75, 192, 192, 0.05)",
                        borderColor: "rgba(153, 102, 255, 1)",
                        data: dataPagos
                    },
                    {
                        label: "Entregues",
                        lineTension: 0.3,
                        backgroundColor: "rgba(153, 102, 255, 0.05)",
                        borderColor: "rgb(21, 241, 68)",
                        data: dataEntregues
                    },
                    {
                        label: "Testes",
                        lineTension: 0.3,
                        backgroundColor: "rgba(255, 205, 86, 0.05)",
                        borderColor: "rgb(233, 84, 225)",
                        data: dataTestes
                    },
                    {
                        label: "Total Orçamentos",
                        lineTension: 0.3,
                        backgroundColor: "rgba(54, 162, 235, 0.05)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        data: dataOrcamentos
                    },
                    {
                        label: "Total Lentes",
                        lineTension: 0.3,
                        backgroundColor: "rgba(201, 203, 207, 0.05)",
                        borderColor: "rgba(201, 203, 207, 1)",
                        data: dataLentes,
                        hidden: true
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'date' },
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 7 }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function (value) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: { display: true },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: true,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Erro ao buscar os dados da semana:', error);
    }
}

// Atualiza o gráfico ao alterar a empresa
document.getElementById("empresaSelect").addEventListener("change", function () {
    fetchDataAndUpdateChart(document.getElementById("semanaInput").value);
});

// Atualiza o gráfico ao alterar a semana
document.getElementById("semanaInput").addEventListener("change", function () {
    fetchDataAndUpdateChart(this.value);
});

// Chama a função ao carregar a página com a semana atual
fetchDataAndUpdateChart(document.getElementById("semanaInput").value);

// Função para gerar as datas da semana selecionada no formato ISO "YYYY-MM-DD"
function getDatesFromInput(semana) {
    const [ano, semanaNum] = semana.split('-W');
    const dataInicio = new Date(ano, 0, 1 + (semanaNum - 1) * 7);

    while (dataInicio.getDay() !== 1) {
        dataInicio.setDate(dataInicio.getDate() - 1);
    }

    const daysWithDates = [];
    const datesISO = [];
    const weekDays = ["Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"];

    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(dataInicio);
        currentDate.setDate(dataInicio.getDate() + i);

        const formattedDate = currentDate.getDate().toString().padStart(2, '0') + '/' + (currentDate.getMonth() + 1).toString().padStart(2, '0');
        daysWithDates.push(`${weekDays[i]} (${formattedDate})`);
        datesISO.push(currentDate.toISOString().split('T')[0]);
    }

    return { daysWithDates, datesISO };
}
