$(document).ready(function () {
    if (!$("#tablaBitacora").length) {
        return;
    }

    $("#tablaBitacora").DataTable({
        dom: "Bfrtip",

        buttons: [
            {
                extend: "excelHtml5",
                text: "Exportar Excel",
                title: "Bitácora Sala 1",
                className: "btn btn-success btn-sm",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                }
            },
            {
                extend: "pdfHtml5",
                text: "Exportar PDF",
                title: "Bitácora Sala 1",
                className: "btn btn-danger btn-sm",
                orientation: "landscape",
                pageSize: "A3",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 7;
                    doc.styles.tableHeader.fontSize = 8;
                    doc.styles.title.fontSize = 14;
                }
            }
        ],

        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "No hay registros disponibles",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });
});