$(document).ready(function() {
    $('#dataTableRamais').DataTable({
        "order": [[ 0, 'asc' ]],
        "autoWidth": false,
        "responsive": true,
        "initComplete": function() { $('#dataTableRamais').css('width','100%'); },
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ ramais",
            "lengthMenu": "Mostrar _MENU_ ramais",
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
            { "width": "20%", "targets": 0 },
            { "width": "70%", "targets": 1 },
            { "width": "40%", "targets": 2 }
        ]
    });
});
