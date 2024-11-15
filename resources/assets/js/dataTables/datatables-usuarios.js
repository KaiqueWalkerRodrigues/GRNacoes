$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ usuarios",
            "lengthMenu": "Mostrar _MENU_ usuarios",
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
            { "width": "40%", "targets": 0 },
            { "width": "25%", "targets": 1 }, 
            { "width": "25%", "targets": 2 },
            { "width": "25%", "targets": 3 },
        ]
    });
});
