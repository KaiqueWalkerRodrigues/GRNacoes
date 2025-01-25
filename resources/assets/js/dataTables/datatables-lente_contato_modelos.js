$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ fornecedores",
            "lengthMenu": "Mostrar _MENU_ fornecedores",
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
            { "width": "80%", "targets": 1 },
            { "width": "20%", "targets": 2 }
        ]
    });
});
