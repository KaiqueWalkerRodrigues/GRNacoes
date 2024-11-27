$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ agendamentos",
            "lengthMenu": "Mostrar _MENU_ agendamentos",
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
            { "width": "17%", "targets": 1 },
            { "width": "17%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "10%", "targets": 4 },
            { "width": "7%", "targets": 5 },
            { "width": "5%", "targets": 6 }
        ]
    });
});
