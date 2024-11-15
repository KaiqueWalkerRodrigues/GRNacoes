$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ notas",
            "lengthMenu": "Mostrar _MENU_ notas",
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
            { "width": "7%", "targets": 1 },
            { "width": "10%", "targets": 2 },
            { "width": "7%", "targets": 3 },
            { "width": "12%", "targets": 4 },
            { "width": "15%", "targets": 5 },
            { "width": "15%", "targets": 6 },
            { "width": "30%", "targets": 7 },
            { "width": "20%", "targets": 8 }
        ]
    });
});
