$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ captações",
            "lengthMenu": "Mostrar _MENU_ captações",
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
            { "width": "10%", "targets": 1 },
            { "width": "20%", "targets": 2 },
            { "width": "15%", "targets": 3 },
            { "width": "10%", "targets": 4 },
            { "width": "10%", "targets": 5 },
            { "width": "15%", "targets": 6 },
            { "width": "5%", "targets": 7 }
        ]
    });
});
