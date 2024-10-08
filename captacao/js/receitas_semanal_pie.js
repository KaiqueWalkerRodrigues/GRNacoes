// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Variável para armazenar a instância do gráfico
let myPieChart;

// Função para buscar os dados do JSON e atualizar o gráfico de pizza
async function fetchPieDataAndUpdateChart() {
    var ctx = document.getElementById("myPieChart");
    var empresaSelect = document.getElementById("empresaSelect");
    var empresaId = empresaSelect.value;

    try {
        // Faz a requisição para o servidor PHP com o ID da empresa como parâmetro
        let response = await fetch(`/GRNacoes/captacao/listarCaptacoes_pie.php?empresaId=${empresaId}`);
        let data = await response.json();
        
        // Verifica se houve algum erro
        if (data.error) {
            console.error('Erro ao buscar os dados:', data.error);
            return;
        }

        // Verifica se o gráfico já existe e o destrói
        if (myPieChart) {
            myPieChart.destroy();
        }

        // Prepara os dados para o gráfico de pizza
        var labels = Object.keys(data);
        var dataPoints = labels.map(label => data[label].total);
        var backgroundColors = labels.map(label => data[label].color);
        var hoverBackgroundColors = labels.map(label => data[label].hoverColor);

        // Cria o gráfico de pizza
        myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataPoints,
                    backgroundColor: backgroundColors,
                    hoverBackgroundColor: hoverBackgroundColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true // Mostra a legenda com os nomes das clínicas
                },
                cutoutPercentage: 80,
            },
        });
        
    } catch (error) {
        console.error('Erro ao buscar os dados:', error);
    }
}

// Chama a função para buscar dados e atualizar o gráfico de pizza ao carregar a página
fetchPieDataAndUpdateChart();

// Atualiza o gráfico quando o valor do select mudar
document.getElementById("empresaSelect").addEventListener("change", fetchPieDataAndUpdateChart);
