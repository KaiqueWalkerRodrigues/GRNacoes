$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ chamados",
            "lengthMenu": "Mostrar _MENU_ chamados",
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
            { "width": "0%", "targets": 0, "visible": false },
            { "width": "5%", "targets": 1, "className": "text-center" },
            { "width": "7%", "targets": 2, "className": "text-center" },
            { "width": "35%", "targets": 3, "className": "text-center" },
            { "width": "20%", "targets": 4 },
            { "width": "5%", "targets": 5 },
            { "width": "8%", "targets": 6 },
            { "width": "14%", "targets": 7 },
            { "width": "10%", "targets": 8 }
        ]
    });
});