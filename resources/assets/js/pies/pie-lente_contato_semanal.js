// Define a fonte padrão e a cor do texto para imitar o estilo padrão do Bootstrap
Chart.defaults.global.defaultFontFamily = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Variável para armazenar a instância do gráfico de lente contato semanal
let Pie_lenteContato;

// Função para buscar os dados do JSON e atualizar o gráfico de pizza de lente contato
async function fetchLenteContatoDataAndUpdateChart(semana) {
    // Obtém o contexto do canvas e o valor selecionado da empresa
    var ctx = document.getElementById("pie_lente_contato_semanal");
    var empresaSelect = document.getElementById("empresaSelect");
    var empresaId = empresaSelect.value;

    // Obtém a data de início da semana usando a função getDatesFromInput
    var { datesISO } = getDatesFromInput(semana);
    var dataInicioSemana = datesISO[0]; // Primeira data da semana (segunda-feira)

    try {
        // Faz a requisição para o servidor PHP com a data da semana e o ID da empresa
        let response = await fetch(`/GRNacoes/resources/assets/js/dados/get_lente_contato_pie.php?empresaId=${empresaId}&dataInicio=${dataInicioSemana}`);
        let data = await response.json();

        // Verifica se houve algum erro na requisição
        if (data.error) {
            console.error('Erro ao buscar os dados:', data.error);
            return;
        }

        // Se o gráfico já existir, destrói-o para atualizar com os novos dados
        if (Pie_lenteContato) {
            Pie_lenteContato.destroy();
        }

        // Prepara os dados para o gráfico de pizza
        var labels = Object.keys(data);
        var dataPoints = labels.map(label => data[label].total);
        var backgroundColors = labels.map(label => data[label].color);
        var hoverBackgroundColors = labels.map(label => data[label].hoverColor);

        // Cria o gráfico de pizza (doughnut) para os dados de lente contato
        Pie_lenteContato = new Chart(ctx, {
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
                    display: true // Exibe a legenda com os nomes das clínicas ou responsáveis
                },
                cutoutPercentage: 80,
            },
        });
        
    } catch (error) {
        console.error('Erro ao buscar os dados:', error);
    }
}

// Atualiza o gráfico de lente contato ao alterar a semana
document.getElementById("semanaInput").addEventListener("change", function () {
    fetchLenteContatoDataAndUpdateChart(this.value);
});

// Chama a função para buscar os dados e atualizar o gráfico de lente contato ao carregar a página
fetchLenteContatoDataAndUpdateChart(document.getElementById("semanaInput").value);

// Função para gerar as datas da semana selecionada no formato ISO "YYYY-MM-DD"
function getDatesFromInput(semana) {
    const [ano, semanaNum] = semana.split('-W');
    const dataInicio = new Date(ano, 0, 1 + (semanaNum - 1) * 7);

    // Ajusta a data para a segunda-feira
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
