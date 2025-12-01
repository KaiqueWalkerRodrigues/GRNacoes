$(document).ready(function() {
    $('#dataTableBlocoNotas').DataTable({
        "order": [[ 0, 'asc' ]],
        "autoWidth": false,
        "responsive": true,
        "initComplete": function() { $('#dataTable').css('width','100%'); },
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ blocos de notas",
            "lengthMenu": "Mostrar _MENU_ blocos de notas",
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
            { "width": "60%", "targets": 0 },
            { "width": "40%", "targets": 1 },
            { "width": "20%", "targets": 2 },
        ]
    });
});
