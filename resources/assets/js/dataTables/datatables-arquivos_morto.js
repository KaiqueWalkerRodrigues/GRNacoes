$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ caixas",
            "lengthMenu": "Mostrar _MENU_ caixas",
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
            { "width": "7%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "55%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "12%", "targets": 4 },
            { "width": "25%", "targets": 5 }
        ]
    });
});
