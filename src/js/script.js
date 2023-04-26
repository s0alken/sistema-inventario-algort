
$(document).ready(function(){

    //reactivando botones de submit.

    //mientras carga una página, los botones de submit
    //se mantienen desactivados, algunos forms presentan
    //inconvenientes al enviarlos sin que la página
    //esté completamente cargada
    $("button[type='submit']").removeAttr("disabled");

    //notificacion
    setInterval(cargarNotificacionVentasWebProcesar, 10000);

})

//cargando el scroll del sidebar
$("#sidebar").mCustomScrollbar({
    theme: "minimal"
});

//configurando botón para ocultar sidebar
$("#sidebarCollapse").on("click", function () {
    $("#sidebar, #content").toggleClass("active");
    $(".collapse.in").toggleClass("in");
    $("a[aria-expanded=true]").attr("aria-expanded", "false");
});

//animando las flechas del sidebar
$("#sidebar a").click(function () {
    $(this).find(".rotate").toggleClass("down");
})

//cargando modal producto
$(".tabla-producto").on("click", "tbody td:not(.td-opciones)", function() {

    var id_producto = $(this).parent().data("id-producto");

    cargarModalProducto(id_producto);

});

//cargando modal detalle (de venta, compra, etc)
$(".tabla-detalle").on("click", "tbody td:not(.td-opciones)", function() {

    var id = $(this).parent().data("id-detalle");
    var instancia = $(this).parent().data("instancia");

    cargarModalDetalle(id, instancia);

});

//iniciando bootstrap tooltips
$(function () {

  $("[data-toggle='tooltip']").tooltip()

})

function cargarModalProducto(id_producto){

    var modal_producto = $("#modal_producto");

    $.ajax({
        url: "../productos/cargar_modal_producto.php",
        method: "POST",
        data: {id_producto: id_producto},
        dataType: "text",
        success: function(producto){

            modal_producto.find(".modal-body").html(producto);
            
            modal_producto.modal("show");

        }
    });

}

function cargarModalDetalle(id, instancia){

    var modal_detalle = $("#modal_detalle");

    $.ajax({
        url: "../" + instancia + "/cargar_modal_detalle.php",
        method: "POST",
        data: {id: id},
        dataType: "text",
        success: function(detalle){

            modal_detalle.find(".modal-body").html(detalle);
            
            modal_detalle.modal("show");

        }
    });

}

function cargarNotificacionVentasWebProcesar(){

    $.ajax({
        url: "../controller/cargar_notificacion_ventas_web_procesar.php",
        method: "POST",
        dataType: "JSON",
        success: function(notificacion){
            
            if (notificacion.cantidad_ventas_web_procesar > 0) {

                $("#notificacion_ventas_web_procesar span").text(notificacion.mensaje);

                $("#notificacion_ventas_web_procesar").removeClass("invisible");
                
            } else {

                $("#notificacion_ventas_web_procesar").addClass("invisible");

            }

        }
    });

}