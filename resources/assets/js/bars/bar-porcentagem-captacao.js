// Configurações do Chart.js
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Variável para armazenar a instância do gráfico
let myBarChart;

// Função para buscar dados do JSON e atualizar o gráfico de barras
async function fetchBarDataAndUpdateChart(semana) {
    var ctx = document.getElementById("bar-porcentagem-captacao");
    var empresaSelect = document.getElementById("empresaSelect");
    var empresaId = empresaSelect.value;

    // Obtém a data de início da semana usando a função getDatesFromInput
    var { datesISO } = getDatesFromInput(semana);
    var dataInicioSemana = datesISO[0]; // Primeira data da semana (segunda-feira)

    try {
        // Faz a requisição para o PHP com o ID da empresa e data da semana como parâmetros
        let response = await fetch(`/GRNacoes/resources/assets/js/dados/get_porcentagem_captacao_bar.php?empresaId=${empresaId}&dataInicio=${dataInicioSemana}`);
        let data = await response.json();

        // Verifica se há erro no retorno
        if (data.error) {
            console.error('Erro ao buscar os dados:', data.error);
            return;
        }

        // Destrói o gráfico anterior, se existir
        if (myBarChart) {
            myBarChart.destroy();
        }

        // Extrai os labels e valores para captação e não captação
        var labels = data.map(item => item.nome);
        var captadoData = data.map(item => parseFloat(item.captado));
        var naoCaptadoData = data.map(item => parseFloat(item.nao_captado));

        // Cria o gráfico de barras
        myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Captado (%)',
                        backgroundColor: 'rgba(28, 200, 138, 0.9)',  // Verde
                        hoverBackgroundColor: 'rgba(28, 200, 138, 1)',
                        borderColor: '#1cc88a',
                        data: captadoData
                    },
                    {
                        label: 'Não Captado (%)',
                        backgroundColor: 'rgba(231, 74, 59, 0.9)',  // Vermelho
                        hoverBackgroundColor: 'rgba(231, 74, 59, 1)',
                        borderColor: '#e74a3b',
                        data: naoCaptadoData
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true,  // Para empilhar as barras
                        maxBarThickness: 50,
                    }],
                    yAxes: [{
                        stacked: true,  // Para que a soma das barras alcance 100%
                        ticks: {
                            beginAtZero: true,
                            max: 100,  // As porcentagens somam 100%
                            callback: function(value) {
                                return value + "%";  // Adiciona o símbolo de porcentagem
                            }
                        }
                    }]
                },
                legend: { display: true }
            }
        });

    } catch (error) {
        console.error('Erro ao buscar os dados:', error);
    }
}

// Atualiza o gráfico de barras ao alterar a semana
document.getElementById("semanaInput").addEventListener("change", function () {
    fetchBarDataAndUpdateChart(this.value);
});

// Atualiza o gráfico de barras ao alterar a empresa
document.getElementById("empresaSelect").addEventListener("change", function () {
    fetchBarDataAndUpdateChart(document.getElementById("semanaInput").value);
});

// Chama a função para buscar dados e atualizar o gráfico de barras ao carregar a página
fetchBarDataAndUpdateChart(document.getElementById("semanaInput").value);

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
