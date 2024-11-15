$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ logs",
            "lengthMenu": "Mostrar _MENU_ logs",
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
            { "width": "15%", "targets": 4 },
            { "width": "65%", "targets": 5 },
        ]
    });
});
