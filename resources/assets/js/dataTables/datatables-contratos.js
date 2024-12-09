$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ contratos",
            "lengthMenu": "Mostrar _MENU_ contratos",
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
            { "width": "13%", "targets": 0 },
            { "width": "20%", "targets": 1 },
            { "width": "30%", "targets": 2 },
            { "width": "12%", "targets": 3 },
            { "width": "12%", "targets": 4 },
            { "width": "12%", "targets": 5 },
            { "width": "20%", "targets": 6 }
        ]
    });
});
