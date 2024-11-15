// Configurações do Chart.js
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Variável para armazenar a instância do gráfico
let BarMotivos;

// Função para buscar dados do JSON e atualizar o gráfico de barras
async function fetchBarDataAndUpdateChart() {
    var ctx = document.getElementById("bar-motivos");
    var empresaSelect = document.getElementById("empresaSelect");
    var empresaId = empresaSelect.value;

    try {
        // Faz a requisição para o PHP com o ID da empresa como parâmetro
        let response = await fetch(`/GRNacoes/resources/assets/js/dados/get_motivos_bar.php?id_empresa=${empresaId}`);
        let data = await response.json();

        if (data.error) {
            console.error('Erro ao buscar os dados:', data.error);
            return;
        }

        // Verifica se o gráfico já existe e o destrói
        if (BarMotivos) {
            BarMotivos.destroy();
        }

        // Extrai os rótulos e os valores para o gráfico
        var labels = data.map(motivo => motivo.label);
        var dataPoints = data.map(motivo => motivo.total);

        // Cria o gráfico de barras
        BarMotivos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total',
                    backgroundColor: 'rgba(0, 97, 242, 1)',
                    hoverBackgroundColor: 'rgba(0, 97, 242, 0.9)',
                    borderColor: '#4e73df',
                    data: dataPoints
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },
                legend: { display: false }
            }
        });

    } catch (error) {
        console.error('Erro ao buscar os dados:', error);
    }
}

// Chama a função para buscar dados e atualizar o gráfico ao carregar a página
fetchBarDataAndUpdateChart();

// Atualiza o gráfico quando o valor do select mudar
document.getElementById("empresaSelect").addEventListener("change", fetchBarDataAndUpdateChart);
