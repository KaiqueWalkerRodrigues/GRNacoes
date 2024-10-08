// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
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

// Função para gerar os dias da semana com a data atual
function getWeekDaysWithDates() {
    var today = new Date();
    var weekDays = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"];
    var daysWithDates = [];

    for (var i = 1; i <= 6; i++) {
        var currentDate = new Date();
        currentDate.setDate(today.getDate() - today.getDay() + i); // Ajusta a data para o dia da semana
        var day = weekDays[i]; // Nome do dia da semana
        var formattedDate = currentDate.getDate().toString().padStart(2, '0') + '/' + (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Formata a data no formato "dd/mm"
        daysWithDates.push(`${day} (${formattedDate})`);
    }
    return daysWithDates;
}

// Função para gerar as datas no formato ISO "YYYY-MM-DD"
function getDates() {
    var today = new Date();
    var weekDays = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"];
    var daysWithDates = [];
    var datesISO = [];

    for (var i = 1; i <= 6; i++) {
        var currentDate = new Date();
        currentDate.setDate(today.getDate() - today.getDay() + i); // Ajusta a data para o dia da semana
        var day = weekDays[i]; // Nome do dia da semana
        var formattedDate = currentDate.getDate().toString().padStart(2, '0') + '/' + (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Formata a data no formato "dd/mm"
        var isoDate = currentDate.toISOString().split('T')[0]; // Formato ISO "YYYY-MM-DD"
        datesISO.push(isoDate);
        daysWithDates.push(`${day} (${formattedDate})`);
    }

    return { daysWithDates, datesISO };
}

// Variável global para armazenar o gráfico
var myLineChart;

// Função para buscar os dados do JSON e atualizar o gráfico
async function fetchDataAndUpdateChart() {
    var ctx = document.getElementById("myAreaChart");
    var { daysWithDates, datesISO } = getDates();
    var dataCaptados = [];
    var dataNaoCaptados = [];
    var dataLentes = [];
    var dataGarantia = [];
    var dataReceitas = []; // Dataset para todas as receitas
    var dataReceitasValidas = []; // Novo dataset para receitas válidas (captados = 0, 1 e 2)

    var idEmpresa = document.getElementById("empresaSelect").value;

    for (var date of datesISO) {
        try {
            // Faz a requisição para o servidor PHP passando a data e o id da empresa como parâmetros
            let response = await fetch(`/GRNacoes/captacao/listarCaptacoes_bar.php?data=${date}&id_empresa=${idEmpresa}`);
            let data = await response.json();

            // Adiciona os valores específicos para cada categoria no dia
            dataCaptados.push(data.captados);
            dataNaoCaptados.push(data.naoCaptados);
            dataLentes.push(data.lentes);
            dataGarantia.push(data.garantia);
            dataReceitas.push(data.receitas); // Adiciona as receitas totais
            dataReceitasValidas.push(data.receitasValidas); // Adiciona as receitas válidas (captados = 0, 1 e 2)
        } catch (error) {
            console.error('Erro ao buscar os dados:', error);
            dataCaptados.push(0);
            dataNaoCaptados.push(0);
            dataLentes.push(0);
            dataGarantia.push(0);
            dataReceitas.push(0);
            dataReceitasValidas.push(0);
        }
    }

    if (myLineChart) {
        myLineChart.destroy();
    }

    // Criação do gráfico com os datasets atualizados
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: daysWithDates,
            datasets: [
                // {
                //     label: "Receitas",
                //     lineTension: 0.3,
                //     backgroundColor: "rgba(0, 0, 0, 0.05)", // Cor de fundo cinza com transparência
                //     borderColor: "rgba(0, 0, 0, 1)", // Cor da borda cinza
                //     data: dataReceitas,
                // },
                {
                    label: "Receitas Captáveis",
                    lineTension: 0.3,
                    backgroundColor: "rgba(255, 99, 132, 0.05)", // Cor de fundo rosa com transparência
                    borderColor: "rgba(255, 99, 132, 1)", // Cor da borda rosa
                    data: dataReceitasValidas,
                },
                {
                    label: "Captados",
                    lineTension: 0.3,
                    backgroundColor: "rgba(0, 128, 0, 0.05)", // Cor de fundo verde com transparência
                    borderColor: "rgba(0, 128, 0, 1)", // Cor da borda verde
                    data: dataCaptados,
                },
                {
                    label: "Não Captados",
                    lineTension: 0.3,
                    backgroundColor: "rgba(255, 0, 0, 0.05)", // Cor de fundo vermelho com transparência
                    borderColor: "rgba(255, 0, 0, 1)", // Cor da borda vermelha
                    data: dataNaoCaptados,
                },
                {
                    label: "Lentes",
                    lineTension: 0.3,
                    backgroundColor: "rgba(0, 91, 143, 0.05)", // Cor de fundo azul com transparência
                    borderColor: "rgba(0, 91, 143, 1)", // Cor da borda azul
                    data: dataLentes,
                },
                {
                    label: "Garantia",
                    lineTension: 0.3,
                    backgroundColor: "rgba(246, 194, 62, 0.05)", // Cor de fundo amarelo com transparência
                    borderColor: "rgba(246, 194, 62, 1)", // Cor da borda amarela
                    data: dataGarantia,
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
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
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
            legend: {
                display: true
            },
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
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
}

// Atualiza o gráfico quando a empresa é selecionada
document.getElementById("empresaSelect").addEventListener("change", fetchDataAndUpdateChart);

// Chama a função para buscar dados e atualizar o gráfico ao carregar a página
fetchDataAndUpdateChart();
