$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ boletos",
            "lengthMenu": "Mostrar _MENU_ boletos",
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
            { "width": "12%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "25%", "targets": 2 },
            { "width": "0%", "targets": 3 },
            { "width": "35%", "targets": 4 },
            { "width": "10%", "targets": 5 },
            { "width": "10%", "targets": 6 },
            { "width": "10%", "targets": 7 },
            { "width": "30%", "targets": 8 }
        ]
    });
});
