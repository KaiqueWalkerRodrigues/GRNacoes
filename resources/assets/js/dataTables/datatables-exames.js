$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
        "autoWidth": false,
        "responsive": true,
        "initComplete": function() { $('#dataTable').css('width','100%'); },
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ exames",
            "lengthMenu": "Mostrar _MENU_ exames",
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
            { "width": "50%", "targets": 0 },
            { "width": "20%", "targets": 1 },
            { "width": "15%", "targets": 2 },
            { "width": "15%", "targets": 3 }
        ]
    });
    $('#dataTableCentralExames').DataTable({
        "order": [[ 0, 'asc' ]],
        "autoWidth": false,
        "responsive": true,
        "initComplete": function() { $('#dataTableCentralExames').css('width','100%'); },
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ exames",
            "lengthMenu": "Mostrar _MENU_ exames",
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
            { "width": "70%", "targets": 0 },
            { "width": "15%", "targets": 1 },
            { "width": "15%", "targets": 2 }
        ]
    });
    $('#dataTableCentralPacotes').DataTable({
        "order": [[ 0, 'asc' ]],
        "autoWidth": false,
        "responsive": true,
        "initComplete": function() { $('#dataTableCentralPacotes').css('width','100%'); },
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ exames",
            "lengthMenu": "Mostrar _MENU_ exames",
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
            { "width": "10%", "targets": 1 },
            { "width": "50%", "targets": 2 }
        ]
    });
    
});
