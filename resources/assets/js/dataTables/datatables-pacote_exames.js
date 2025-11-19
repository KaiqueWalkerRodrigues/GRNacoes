$(document).ready(function () {

    let tabelaPacote = $('#dataTablePacote').DataTable({
        "order": [[0, 'asc']],
        "language": {
            "info": "Exibindo _START_ a _END_ de _TOTAL_ pacotes",
            "lengthMenu": "Mostrar _MENU_ pacotes",
            "search": "Pesquisar:",
            "paginate": {
                "first": "Primeiro",
                "last": "Último",
                "next": "Próximo",
                "previous": "Anterior"
            },
            "emptyTable": "Nenhum pacote cadastrado"
        },
        "columnDefs": [
            { "width": "80%", "targets": 0 }, // Pacote
            { "width": "20%", "targets": 1 }, // Valor Fidelidade
            { "width": "30%", "targets": 2 }  // Ações
        ]
    });

    // Corrige o problema de largura dentro de modal
    $('#modalPacotes').on('shown.bs.modal', function () {
        tabelaPacote.columns.adjust().draw();
    });

});
