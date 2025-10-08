$(document).ready(function() {
    $('#dataTable-competencia').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ notas de serviço",
            "lengthMenu": "Mostrar _MENU_ notas de servico",
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
            { "width": "14%", "targets": 0 },
            { "width": "14%", "targets": 1 },
            { "width": "8%", "targets": 2 },
            { "width": "11%", "targets": 3 },
            { "width": "12%", "targets": 4 },
            { "width": "12%", "targets": 5 },
            { "width": "11%", "targets": 6 },
            { "width": "11%", "targets": 7 },
            { "width": "12%", "targets": 8 }
        ]
    });
});
