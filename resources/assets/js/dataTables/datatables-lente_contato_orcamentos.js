$(document).ready(function() {
    $('#tabelaOrcamentos').DataTable({
        "order": [[ 0, 'asc' ]],
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
            { "width": "30%", "targets": 2 },
            { "width": "25%", "targets": 3 },
            { "width": "15%", "targets": 4 },
            { "width": "15%", "targets": 5 }
        ]
    });
});
