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
            { "width": "10%", "targets": 0 },
            { "width": "10%", "targets": 1 }, 
            { "width": "10%", "targets": 2 },
            { "width": "5%", "targets": 3 },
            { "width": "3%", "targets": 4 }
        ]
    });
});
