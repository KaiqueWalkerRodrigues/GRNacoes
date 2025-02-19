$(document).ready(function() {
    $('#tabelaTestes').DataTable({
        "order": [[ 0, 'desc' ]],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ testes",
            "lengthMenu": "Mostrar _MENU_ testes",
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
            { "width": "5%", "targets": 0 },
            { "width": "0%", "targets": 1 },
            { "width": "12%", "targets": 2 },
            { "width": "26%", "targets": 3 },
            { "width": "7%", "targets": 4 },
            { "width": "25%", "targets": 5 },
            { "width": "15%", "targets": 6 },
            { "width": "15%", "targets": 7 }
        ]
    });
});
