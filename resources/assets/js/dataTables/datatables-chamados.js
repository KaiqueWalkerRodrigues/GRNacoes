$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, 'asc' ]],
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
            { "width": "0%", "targets": 0 }, 
            { "width": "5%", "targets": 1 }, 
            { "width": "7%", "targets": 2 }, 
            { "width": "35%", "targets": 3 }, 
            { "width": "20%", "targets": 4 },  
            { "width": "5%", "targets": 5 },
            { "width": "14%", "targets": 6 },
            { "width": "14%", "targets": 7 },
        ]
    });
});
