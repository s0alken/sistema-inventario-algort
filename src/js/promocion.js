

//------------- PROMOCIÓN ----------------//

//la mayoría de funciones están declaradas en venta.js

$(".form-promocion-crear").on("focusout change", ".campo-promocion", function(e){

    configurarPromocion($(".form-promocion-crear"));

})

//vaciando carrito
$(".form-promocion-crear #btn_vaciar_carrito").click(function(){

    vaciarCarrito("promociones");

})

$(".form-promocion-crear #descuento_porcentaje, .form-promocion-crear #descuento_dinero").keypress(function(e){

    if(e.keyCode === 13) {
        
        var descuento = $(this).val(); 

        var tipo_descuento = $(this).attr("id"); 

        aplicarDescuento(descuento, tipo_descuento, "promociones");

    }

})

//aplicando descuento a promoción
$(".form-promocion-crear .btn-aplicar-descuento").click(function(){

    var target = $(this).data("target");

    var descuento = $(target).val();

    var tipo_descuento = $(target).attr("id");

    aplicarDescuento(descuento, tipo_descuento, "promociones");

})

//modificando producto
$(".form-promocion-crear").on("click", ".btn-modificar-producto", function(){

    var codigo_barras = $(this).val();

    modificarProducto(codigo_barras, "promociones");

})

$("#tabla_modal_productos").on("click", ".btn-agregar-promocion", function() {

    agregarAlaPromocion($(this).val());

})

//cargando producto al carrito
$(".form-promocion-crear #codigo_barras_promocion").keypress(function(e){

    if(e.keyCode === 13) {
        
        agregarAlaPromocion($(this).val());

    }

})

//eliminando producto de la promoción
$(".form-promocion-crear").on("click", ".btn-quitar-del-carrito", function(){

    quitarDelCarrito($(this).val(), "promociones");

})

$("input[name='termino_promocion']").change(function(){

    var valor = $(this).val();

    $("#fecha_limite_promocion").attr("class", valor === "hasta_fecha_limite" ? "d-block" : "d-none");

})

function agregarAlaPromocion(codigo_barras){

    $.ajax({
        type: "POST",
        url: "../promociones/agregar_a_la_promocion.php",
        data: {codigo_barras: codigo_barras},
        dataType: "JSON",
        success: function (producto) {

            $("#codigo_barras_promocion").val("");

            mostrarSnackbar("#snackbar", producto.mensaje, producto.agregado);

            if (producto.agregado) {
                
                cargarCarrito("promociones");

            }

        }
    });

}

function configurarPromocion(form) {

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: "../promociones/configurar_promocion.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (promocion) {
            console.log(promocion);
        } 
    });

}