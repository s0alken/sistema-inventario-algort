

//------------- COTIZACIÓN ----------------//

//la mayoría de funciones están declaradas en venta.js

$(".form-cotizacion-crear").on("focusout change", ".campo-cotizacion", function(e){

    configurarCotizacion($(".form-cotizacion-crear"));

})

//cargando cliente a la cotización
$(".form-cotizacion-crear #rut_cliente").keypress(function(e){

    var rut_cliente = $(this).val(); 

    if(e.keyCode === 13) {
        
        agregarCliente(rut_cliente, false, "cotizacion");

    }

})

//cargando cliente a la cotización mediante la búsqueda
$("#modal_clientes").on("click", ".btn-agregar-cliente-cotizacion", function(e){

    var rut_cliente = $(this).val(); 

    agregarCliente(rut_cliente, true, "cotizacion");

})

//vaciando carrito
$(".form-cotizacion-crear #btn_vaciar_carrito").click(function(){

    vaciarCarrito("cotizacion");

})

//quitando cliente
$(".form-cotizacion-crear #btn_quitar_cliente").click(function(){

    quitarCliente("cotizacion");

})

//aplicando descuento a venta
$(".form-cotizacion-crear #descuento_porcentaje, .form-cotizacion-crear #descuento_dinero").keypress(function(e){

    if(e.keyCode === 13) {
        
        var descuento = $(this).val(); 

        var tipo_descuento = $(this).attr("id"); 

        aplicarDescuento(descuento, tipo_descuento, "cotizacion");

    }

})

//aplicando descuento a venta
$(".form-cotizacion-crear .btn-aplicar-descuento").click(function(){

    var target = $(this).data("target");

    var descuento = $(target).val();

    var tipo_descuento = $(target).attr("id");

    aplicarDescuento(descuento, tipo_descuento, "cotizacion");

})

//modificando producto
$(".form-cotizacion-crear").on("click", ".btn-modificar-producto", function(){

    var codigo_barras = $(this).val();

    modificarProducto(codigo_barras, "cotizacion");

})

//cargando producto al carrito
$(".form-cotizacion-crear #codigo_barras_cotizacion").keypress(function(e){

    if(e.keyCode === 13) {
        
        validarExistenciaProducto($(this).val(), "cotizacion");

    }

})

//eliminando producto de la cotización
$(".form-cotizacion-crear").on("click", ".btn-quitar-del-carrito", function(){

    quitarDelCarrito($(this).val(), "cotizacion");

})

function configurarCotizacion(form) {

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: "../cotizacion/configurar_cotizacion.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (cotizacion) {
            console.log(cotizacion);
        } 
    });

}