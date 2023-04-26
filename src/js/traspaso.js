
//validando sucursal de origen y destino
$("#form_elegir_sucursal").submit(function(e){

    e.preventDefault();

    validarOrigenDestino($(this));
})

$(".form-traspaso-crear").on("focusout change", ".campo-traspaso", function(e){

    configurarTraspaso($(".form-traspaso-crear"));

})

//cargando producto al traspaso
$("#codigo_barras_traspaso").keypress(function(e){

    if(e.keyCode === 13) {
        
        agregarAlTraspaso($(this).val());

    }

})

$("#tabla_modal_productos").on("click", ".btn-agregar-traspaso", function() {

    agregarAlTraspaso($(this).val());

})

//modificando producto
$(".form-traspaso-crear").on("click", ".btn-modificar-producto", function(){

    var codigo_barras = $(this).val();

    modificarProductoTraspaso(codigo_barras);

})

//vaciando traspaso
$("#btn_vaciar_traspaso").click(function(){

    vaciarTraspaso();

})

//eliminando producto del traspaso
$(".form-traspaso-crear").on("click", ".btn-quitar-del-traspaso", function(){

    quitarDelTraspaso($(this).val());

})

function agregarAlTraspaso(codigo_barras){

    $.ajax({
        type: "POST",
        url: "../traspaso/agregar_al_traspaso.php",
        data: {codigo_barras: codigo_barras},
        dataType: "JSON",
        success: function (producto) {
            
            $(".form-traspaso-crear #codigo_barras_traspaso").val("");

            mostrarSnackbar("#snackbar", producto.mensaje, producto.agregado);

            if (producto.agregado) {

                cargarProductosTraspaso();

            }

        }
    });

}

function cargarProductosTraspaso(){

    $.ajax({
        type: "POST",
        url: "../traspaso/cargar_productos_traspaso.php",
        data: {carga_automatica: true},
        dataType: "text",
        success: function (productos_traspaso) {

            $(".form-traspaso-crear #productos_traspaso tbody").html(productos_traspaso);

        }
    });

}

function validarOrigenDestino(form){

    var btn = form.find("button[type=submit]");

    var btn_text = btn.text();

    var spinner = "<div class='spinner-border spinner-border-sm' role='status'>" +
                  "<span class='sr-only'>Loading...</span>" +
                  "</div>";

    btn.html(spinner);

    var url = form.attr("action");

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function (origen_destino) {
            console.log(origen_destino);
            if(origen_destino.valido) {

                window.location.replace("../traspaso/crear.php");

            } else {

                setTimeout(function(){

                    mostrarSnackbar("#snackbar", origen_destino.mensaje, origen_destino.valido);
                    btn.html(btn_text);

                }, 500);

            }
        }

    });

}

function modificarProductoTraspaso(codigo_barras){

    var modal = $("#modal_modificar_producto");
    var form = modal.find("form");
    var url = "modificar_producto.php?redireccionar=false&codigo_barras=" + codigo_barras;

    form.attr("action", url);

    $.ajax({
        type: "POST",
        url: "../traspaso/cargar_modal_modificar_producto.php",
        data: {codigo_barras: codigo_barras},
        dataType: "JSON",
        success: function (producto) {

            modal.find("#cantidad_producto").val(producto.cantidad);
            modal.find("#cantidad_producto").attr("max", producto.max);

            modal.modal("show");

        }
    });

}

function vaciarTraspaso(){

    $.ajax({
        type: "POST",
        url: "../traspaso/vaciar_traspaso.php",
        success: function () {

            mostrarSnackbar("#snackbar", "¡Traspaso vaciado exitosamente!", true);
            cargarProductosTraspaso();

        }
    });

}

function configurarTraspaso(form) {

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: "../traspaso/configurar_traspaso.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (traspaso) {
            console.log(traspaso);
        } 
    });

}

function quitarDelTraspaso(codigo_barras, instancia){

    $.ajax({
        type: "POST",
        url: "../traspaso/quitar_del_traspaso.php",
        data: {codigo_barras: codigo_barras},
        dataType: "text",
        success: function () {

            cargarProductosTraspaso();

        }
    });

}