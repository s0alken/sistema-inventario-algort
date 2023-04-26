

//------------- VENTA ----------------//

$(".form-venta-crear").on("focusout change", ".campo-venta", function(e){

    configurarVenta($(".form-venta-crear"));

})

//cargando cliente a la venta
$(".form-venta-crear #rut_cliente").keypress(function(e){

    var rut_cliente = $(this).val(); 

    if(e.keyCode === 13) {
        
        agregarCliente(rut_cliente, false, "venta");

    }

})

//cargando cliente a la venta mediante la búsqueda
$("#modal_clientes").on("click", ".btn-agregar-cliente-venta", function(e){

    var rut_cliente = $(this).val(); 

    agregarCliente(rut_cliente, true, "venta");

})

//vaciando carrito
$(".form-venta-crear #btn_vaciar_carrito").click(function(){

    vaciarCarrito("venta");

})

//quitando cliente
$(".form-venta-crear #btn_quitar_cliente").click(function(){

    quitarCliente("venta");

})

//aplicando descuento a venta
$(".form-venta-crear #descuento_porcentaje, .form-venta-crear #descuento_dinero").keypress(function(e){

    if(e.keyCode === 13) {
        
        var descuento = $(this).val(); 

        var tipo_descuento = $(this).attr("id"); 

        aplicarDescuento(descuento, tipo_descuento, "venta");

    }

})

//aplicando descuento a venta
$(".form-venta-crear .btn-aplicar-descuento").click(function(){

    var target = $(this).data("target");

    var descuento = $(target).val();

    var tipo_descuento = $(target).attr("id");

    aplicarDescuento(descuento, tipo_descuento, "venta");

})

//modificando producto
$(".form-venta-crear").on("click", ".btn-modificar-producto", function(){

    var codigo_barras = $(this).val();

    modificarProducto(codigo_barras, "venta");

})

$(".tabla-producto, #tabla_modal_productos").on("click", ".btn-agregar-venta", function() {

    agregarAlCarrito($(this).val(), "venta");

})

//cargando producto al carrito
$(".form-venta-crear #codigo_barras_venta").keypress(function(e){

    if(e.keyCode === 13) {
        
        agregarAlCarrito($(this).val(), "venta");

    }

})

$(".form-venta-crear #id_documento").change(function(){

    cargarDocumentoMedioPago($(this).val());
    configurarBtnSubmit($(this).val(), "venta", "#btn_efectuar_venta");

})

//activando modal para ingresar n° de factura y redcompra
//en caso de que se requira
$(".form-venta-crear #btn_efectuar_venta").on("click", "button[type=button]", function(){

    cargarModalNumeroDocumento($(this));

})

//eliminando producto de la venta
$(".form-venta-crear").on("click", ".btn-quitar-del-carrito", function(){

    quitarDelCarrito($(this).val(), "venta");

})

//configurando producto al ingresarlo
$("#tabla_modal_productos").on("click", ".btn-configurar-producto", function() {

    var codigo_barras = $(this).val();

    var instancia = $(this).data("target");

    validarExistenciaProducto(codigo_barras, instancia);

})

//el ingreso de los n° de boleta, factura etc son obligatorios
//dependiendo del botón con el que se haga submit,
//cada botón tiene establecido el parametro ingresar_n_documento
//en su value, con este value se setea el action del fomulario de ventas
$(".form-venta-crear .btn_efectuar_venta_modal").click(function(){

    var action = $(this).val();

    $(".form-venta-crear").attr("action", action);

    guardarItem($(".form-venta-crear"), $(this));

})

function cargarModalNumeroDocumento(btn){

    var btn_text = btn.text();

    var spinner = "<div class='spinner-border spinner-border-sm' role='status'>" +
                  "<span class='sr-only'>Loading...</span>" +
                  "</div>";

    btn.html(spinner);

    var modal = $("#modal_numero_documento");

    $.ajax({
        type: "POST",
        url: "../venta/cargar_modal_numero_documento.php",
        dataType: "text",
        success: function (campos_numero_documento) {

            setTimeout(function(){

                modal.find("#campos_numero_documento").html(campos_numero_documento);
                modal.modal("show");
                btn.html(btn_text);

            }, 500);

        }
    });

}

function agregarAlCarrito(codigo_barras, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/agregar_al_carrito.php",
        data: {codigo_barras: codigo_barras},
        dataType: "JSON",
        success: function (producto) {

            $("#codigo_barras_" + instancia).val("");

            mostrarSnackbar("#snackbar", producto.mensaje, producto.agregado);

            if (producto.agregado) {
                
                cargarCarrito(instancia);
                cargarMontos(instancia);

            }

        }
    });

}

function cargarCarrito(instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/cargar_carrito.php",
        data: {carga_automatica: true},
        dataType: "text",
        success: function (carrito) {

            $("#carrito tbody").html(carrito);

        }
    });

}

function cargarMontos(instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/cargar_montos.php",
        dataType: "JSON",
        success: function (montos) {
            console.log(montos);

            $("#puntos").val(montos.puntos);
            $("#total_descuento").val(montos.monto_total);
            $("#monto_neto").val(montos.monto_neto);
            $("#total_iva").val(montos.total_iva);
            $("#total_a_pagar").val(montos.total_a_pagar);

            if (instancia === "venta") {

                $("#nav-top #cantidad").text(montos.cantidad);

            }

        }
    });

}

function configurarVenta(form) {

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: "../venta/configurar_venta.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (venta) {
            console.log(venta);
        } 
    });

}

function aplicarDescuentoGlobal(descuento_global, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/aplicar_descuento_global.php",
        data: {descuento_global: descuento_global},
        dataType: "JSON",
        success: function (descuento) {

            mostrarSnackbar("#snackbar", descuento.mensaje, descuento.aplicado);

            if (descuento.aplicado) {

                cargarMontos(instancia);

            }

        }
    });

}

function aplicarDescuento(descuento, tipo_descuento, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/aplicar_descuento.php",
        data: {descuento: descuento, tipo_descuento, tipo_descuento},
        dataType: "JSON",
        success: function (descuento) {

            mostrarSnackbar("#snackbar", descuento.mensaje, descuento.aplicado);

            if (descuento.aplicado) {

                if (instancia === "promociones") {

                    cargarCarrito("promociones");

                } else {

                    cargarMontos(instancia);

                }

            }

        }
    });

}

function agregarCliente(rut_cliente, redireccionar, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/configurar_cliente.php",
        data: {rut_cliente: rut_cliente},
        dataType: "JSON",
        success: function (cliente) {
            console.log(cliente);

            if (cliente.agregado && redireccionar) {

                window.location.reload(true);

            } else {

                mostrarSnackbar("#snackbar", cliente.mensaje, cliente.agregado);

                if (cliente.agregado) {

                    $("#nombre_cliente").val(cliente.nombre_cliente);
                    $("#giro_cliente").val(cliente.giro_cliente);
                    $("#direccion_cliente").val(cliente.direccion_cliente);
                    $("#telefono_cliente").val(cliente.telefono_cliente);
                    $("#correo_cliente").val(cliente.correo_cliente);

                }

            }

        }
    });

}

function modificarProducto(codigo_barras, instancia){

    var modal = $("#modal_modificar_producto");

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/cargar_modal_modificar_producto.php",
        data: {codigo_barras: codigo_barras},
        dataType: "text",
        success: function (modal_body) {
            console.log(modal_body);

            modal.find(".modal-body").html(modal_body);

            modal.modal("show");

        }
    });

}

function vaciarCarrito(instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/vaciar_carrito.php",
        success: function () {

            mostrarSnackbar("#snackbar", "¡Carrito vaciado exitosamente!", true);
            cargarCarrito(instancia);
            cargarMontos(instancia);

        }
    });

}

function quitarCliente(instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/quitar_cliente.php",
        success: function () {

            mostrarSnackbar("#snackbar", "¡Cliente eliminado de la " + instancia + " exitosamente!", true);

            $("#nombre_cliente").val("");
            $("#giro_cliente").val("");
            $("#direccion_cliente").val("");
            $("#telefono_cliente").val("");
            $("#correo_cliente").val("");

        }
    });

}

function configurarBtnSubmit(id_elemento, instancia, target){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/configurar_btn_submit.php",
        data: {id_elemento: id_elemento},
        dataType: "text",
        success: function (btn) {

            $(target).html(btn);

        }
    });

}

function quitarDelCarrito(codigo_barras, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/quitar_del_carrito.php",
        data: {codigo_barras: codigo_barras},
        dataType: "text",
        success: function () {

            cargarCarrito(instancia);
            
            if (instancia === "venta" || instancia === "cotizacion") {

                cargarMontos(instancia);

            }

        }
    });

}

function cargarDocumentoMedioPago(id_documento){

    $.ajax({
        type: "POST",
        url: "../controller/cargar_documento_medio_pago.php",
        data: {id_documento: id_documento},
        dataType: "TEXT",
        success: function (medio_pago) {
            
            $("#id_medio_pago").html(medio_pago);
        }
    });

}

function validarExistenciaProducto(codigo_barras, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/validar_existencia_producto.php",
        data: {codigo_barras: codigo_barras},
        dataType: "JSON",
        success: function (producto) {
            console.log(producto);

            if (producto.existe) {

                mostrarSnackbar("#snackbar", producto.mensaje, false);

            } else {

                modalConfigurarProducto(codigo_barras, instancia);

            }

        }

    });

}

function modalConfigurarProducto(codigo_barras, instancia){

    var modal = $("#modal_configurar_producto");

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/cargar_modal_configurar_producto.php",
        data: {codigo_barras: codigo_barras},
        dataType: "text",
        success: function (modal_body) {
            
            modal.find(".modal-body").html(modal_body);

            modal.modal("show");

        }
    });

}