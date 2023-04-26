

//===============DATATABLES====================

//tablas generales
configurarTabla(".datatable", 10, "#myInput");

//tablas específicas
var tabla_modal_compatibilidad       = configurarTabla("#tabla_modal_compatibilidad", 5, "#input_tabla_compatibilidad", true);
var tabla_modal_imagenes             = configurarTabla("#tabla_modal_imagenes", 4, "#input_tabla_imagenes", true);
var tabla_modal_ciudades_compatibles = configurarTabla("#tabla_modal_ciudades_compatibles", 10, "#input_tabla_ciudades_compatibles", true);
var tabla_modal_clientes             = configurarTabla("#tabla_modal_clientes", 5, "#input_tabla_clientes", true);
var tabla_modal_proveedores          = configurarTabla("#tabla_modal_proveedores", 5, "#input_tabla_proveedores", true);
var tabla_modal_productos            = configurarTabla("#tabla_modal_productos", 8, "#input_tabla_productos", true);

//iniciando tabla filtrar para exportar a excel
var tabla_filtrar = configurarTabla("#tabla_filtrar", 10, "#myInput", true);

function configurarTabla(tabla_id, pageLength, input_search = null, retornar_tabla = false) {

    //configuración
    var tabla_config = {
        processing: true,
        dom: "tpr",
        paging: true,
        pageLength: pageLength,
        aaSorting: [],
        language: {
            "emptyTable":     "No hay datos disponibles",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "zeroRecords":    "Nada encontrado",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
        },
        buttons: [
            {
                extend: "excelHtml5",
                filename: "planilla",
                title: "",
                exportOptions: {
                    columns: ":not(.no-exportar)"
                }
            }
        ]
    }

    var tabla = $(tabla_id).DataTable(tabla_config);

    //configurando input para hacer búsquedas en tablas
    if (input_search) {

        $(input_search).on("keyup", function () {

            tabla.search($(this).val()).draw();

        });
    }

    if (retornar_tabla) {

        return tabla;

    }

}

//lightbox
lightbox.option({

  albumLabel: "Imagen %1 de %2"
  
})