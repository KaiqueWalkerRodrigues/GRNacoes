$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ pendencias",
            "lengthMenu": "Mostrar _MENU_ pendencias",
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
            { "width": "10%", "targets": 2 },
            { "width": "25%", "targets": 3 },
            { "width": "0%", "targets": 4 },
            { "width": "20%", "targets": 5 },
            { "width": "10%", "targets": 6 },
            { "width": "13%", "targets": 7 },
            { "width": "5%", "targets": 8 }
        ]
    });
});
