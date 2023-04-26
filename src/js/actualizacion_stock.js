

//------------- ACTUALIZACIÓN STOCK ----------------//

//la mayoría de funciones están declaradas en venta.js

$(".form-actualizacion-stock-crear").on("focusout change", ".campo-actualizacion-stock", function(e){

    configurarActualizacionStock($(".form-actualizacion-stock-crear"));

})

//cargando proveedor a la cotización
$(".form-actualizacion-stock-crear #rut_proveedor").keypress(function(e){

    var rut_proveedor = $(this).val(); 

    if(e.keyCode === 13) {
        
        agregarProveedor(rut_proveedor, false, "actualizacion_stock");

    }

})

//cargando proveedor a la cotización mediante la búsqueda
$("#modal_proveedores").on("click", ".btn-agregar-proveedor-actualizacion-stock", function(){

    var rut_proveedor = $(this).val(); 

    agregarProveedor(rut_proveedor, true, "actualizacion_stock");

})

//modificando producto
$(".form-actualizacion-stock-crear").on("click", ".btn-modificar-producto", function(){

    var codigo_barras = $(this).val();

    modificarProducto(codigo_barras, "actualizacion_stock");

})

//cargando producto al carrito
$(".form-actualizacion-stock-crear #codigo_barras_actualizacion_stock").keypress(function(e){

    if(e.keyCode === 13) {
        
        validarExistenciaProducto($(this).val(), "actualizacion_stock");

    }

})

//eliminando producto de la actualización de stock
$(".form-actualizacion-stock-crear").on("click", ".btn-quitar-del-carrito", function(){

    quitarDelCarrito($(this).val(), "actualizacion_stock");

})

function configurarActualizacionStock(form) {

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: "../actualizacion_stock/configurar_actualizacion_stock.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (actualizacion_stock) {
            console.log(actualizacion_stock);
        } 
    });

}

function agregarProveedor(rut_proveedor, redireccionar, instancia){

    $.ajax({
        type: "POST",
        url: "../" + instancia + "/configurar_proveedor.php",
        data: {rut_proveedor: rut_proveedor},
        dataType: "JSON",
        success: function (proveedor) {
            console.log(proveedor);

            if (proveedor.agregado && redireccionar) {

                window.location.reload(true);

            } else {

                mostrarSnackbar("#snackbar", proveedor.mensaje, proveedor.agregado);

                if (proveedor.agregado) {

                    $("#nombre_proveedor").val(proveedor.nombre_proveedor);
                    $("#giro_proveedor").val(proveedor.giro_proveedor);
                    $("#direccion_proveedor").val(proveedor.direccion_proveedor);
                    $("#telefono_proveedor").val(proveedor.telefono_proveedor);
                    $("#correo_proveedor").val(proveedor.correo_proveedor);

                }

            }

        }
    });

}