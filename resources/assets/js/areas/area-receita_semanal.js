// Declaração global da variável para armazenar o gráfico de Lente de Contato
let graficoLenteContato = null;

// Define a família de fontes e a cor padrão para o Chart.js (imitando o estilo do Bootstrap)
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

/**
 * Formata um número de acordo com os parâmetros informados.
 * @param {number} number - O número a ser formatado.
 * @param {number} decimals - Número de casas decimais.
 * @param {string} dec_point - Separador decimal.
 * @param {string} thousands_sep - Separador de milhares.
 * @returns {string} Número formatado.
 */
function number_format(number, decimals, dec_point, thousands_sep) {
    // Exemplo: number_format(1234.56, 2, ',', ' ') retornará '1 234,56'
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

/**
 * Função para buscar os dados do JSON e atualizar o gráfico para cada dia da semana.
 * Os dados são referentes à Lente de Contato.
 * @param {string} semana - Valor da semana no formato "YYYY-Www".
 */
async function fetchDataAndUpdateChart(semana) {
    // Obtém o contexto do canvas para renderizar o gráfico
    var ctx = document.getElementById("area_receita_semanal");

    // Gera as datas da semana selecionada
    var { daysWithDates: diasComDatas, datesISO: datasISO } = getDatesFromInput(semana);

    // Obtém o ID da empresa selecionada
    var idEmpresa = document.getElementById("empresaSelect").value;

    try {
        // Realiza a requisição passando a data inicial da semana e o id da empresa
        let response = await fetch(`/grnacoes/resources/assets/js/dados/get_captacoes_area.php?data=${datasISO[0]}&id_empresa=${idEmpresa}`);
        let dadosSemana = await response.json();

        // Criação dos arrays para os diferentes conjuntos de dados da Lente de Contato
        let dadosCaptados = [];
        let dadosNaoCaptados = [];
        let dadosLentes = [];
        let dadosGarantia = [];
        let dadosReceitas = [];
        let dadosReceitasValidas = [];

        // Preenche os arrays com os dados retornados pelo backend
        dadosSemana.forEach(dia => {
            dadosCaptados.push(dia.captados);
            dadosNaoCaptados.push(dia.naoCaptados);
            dadosLentes.push(dia.lentes);
            dadosGarantia.push(dia.garantia);
            dadosReceitas.push(dia.receitas);
            dadosReceitasValidas.push(dia.receitasValidas);
        });

        // Se o gráfico já foi criado, destrói-o para criar um novo com os dados atualizados
        if (graficoLenteContato) {
            graficoLenteContato.destroy();
        }

        // Criação do gráfico com os datasets atualizados
        graficoLenteContato = new Chart(ctx, {
            type: 'line',
            data: {
                labels: diasComDatas,
                datasets: [
                    {
                        label: "Receitas Captáveis",
                        lineTension: 0.3,
                        backgroundColor: "rgba(255, 99, 132, 0.05)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        data: dadosReceitasValidas,
                        hidden: true
                    },
                    {
                        label: "Captados",
                        lineTension: 0.3,
                        backgroundColor: "rgba(0, 128, 0, 0.05)",
                        borderColor: "rgba(0, 128, 0, 1)",
                        data: dadosCaptados
                    },
                    {
                        label: "Não Captados",
                        lineTension: 0.3,
                        backgroundColor: "rgba(255, 0, 0, 0.05)",
                        borderColor: "rgba(255, 0, 0, 1)",
                        data: dadosNaoCaptados
                    },
                    {
                        label: "Lentes",
                        lineTension: 0.3,
                        backgroundColor: "rgba(0, 91, 143, 0.05)",
                        borderColor: "rgba(0, 91, 143, 1)",
                        data: dadosLentes
                    },
                    {
                        label: "Garantia",
                        lineTension: 0.3,
                        backgroundColor: "rgba(246, 194, 62, 0.05)",
                        borderColor: "rgba(246, 194, 62, 1)",
                        data: dadosGarantia
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
                    }]
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
        console.error('Erro ao buscar os dados da semana para Lente de Contato:', error);
    }
}

// Atualiza o gráfico ao alterar a empresa selecionada
document.getElementById("empresaSelect").addEventListener("change", function () {
    fetchDataAndUpdateChart(document.getElementById("semanaInput").value);
});

// Atualiza o gráfico ao alterar a semana selecionada
document.getElementById("semanaInput").addEventListener("change", function () {
    fetchDataAndUpdateChart(this.value);
});

// Chama a função ao carregar a página com a semana atual
fetchDataAndUpdateChart(document.getElementById("semanaInput").value);

/**
 * Gera as datas da semana selecionada no formato ISO "YYYY-MM-DD" e uma label com dia e data.
 * @param {string} semana - Valor da semana no formato "YYYY-Www".
 * @returns {object} Objeto contendo o array de labels (diasComDatas) e o array de datas no formato ISO (datasISO).
 */
function getDatesFromInput(semana) {
    const [ano, semanaNum] = semana.split('-W');
    const dataInicio = new Date(ano, 0, 1 + (semanaNum - 1) * 7);

    // Ajusta para a segunda-feira da semana
    while (dataInicio.getDay() !== 1) {
        dataInicio.setDate(dataInicio.getDate() - 1);
    }

    const diasComDatas = [];
    const datasISO = [];
    const weekDays = ["Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"];

    for (let i = 0; i < 7; i++) {
        const dataAtual = new Date(dataInicio);
        dataAtual.setDate(dataInicio.getDate() + i);

        // Formata a data para exibição (ex: "Seg (01/02)")
        const dataFormatada = dataAtual.getDate().toString().padStart(2, '0') + '/' + (dataAtual.getMonth() + 1).toString().padStart(2, '0');
        diasComDatas.push(`${weekDays[i]} (${dataFormatada})`);
        datasISO.push(dataAtual.toISOString().split('T')[0]);
    }

    return { daysWithDates: diasComDatas, datesISO: datasISO };
}
