$(document).ready(function () {
    $("#tablaBitacora").DataTable({
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: "Exportar Excel",
                title: "Bitácora Sala 1",
                className: "btn btn-success btn-sm"
            },
            {
                extend: "pdfHtml5",
                text: "Exportar PDF",
                title: "Bitácora Sala 1",
                className: "btn btn-danger btn-sm"
            }
        ],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });
});