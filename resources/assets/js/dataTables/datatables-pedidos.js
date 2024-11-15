$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ pedidos",
            "lengthMenu": "Mostrar _MENU_ pedidos",
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
            { "width": "0%", "targets": 0 },
            { "width": "1%", "targets": 1 },
            { "width": "7%", "targets": 2 },
            { "width": "41%", "targets": 3 },
            { "width": "10%", "targets": 4 },
            { "width": "15%", "targets": 5 },
            { "width": "8%", "targets": 6 },
            { "width": "8%", "targets": 7 },
            { "width": "18%", "targets": 8 }
        ]
    });
});
