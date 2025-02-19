$(document).ready(function() {
    $('#tabelaOrcamentos').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ orçamentos",
            "lengthMenu": "Mostrar _MENU_ orçamentos",
            "search": "Pesquisar:",
            "paginate": {
                "first": "Primeiro",
                "last": "Último",
                "next": "Próximo",
                "previous": "Anterior"
            },
            "emptyTable": "Nenhum dado disponível na tabela"
        },
        "columnDefs": [
            { "width": "5%", "targets": 0 },
            { "width": "0%", "targets": 1 },
            { "width": "11%", "targets": 2 },
            { "width": "26%", "targets": 3 },
            { "width": "7%", "targets": 4 },
            { "width": "30%", "targets": 5 },
            { "width": "14%", "targets": 6 },
            { "width": "12%", "targets": 7 }
        ]
    });
});
