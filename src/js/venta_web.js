

$(".form-venta-procesar #id_compra_estado").change(function(){

    var id_compra_estado = $(this).val();

    var id_compra = $(this).data("id-compra");

    cargarNumeroEnvio(id_compra_estado, id_compra);
    configurarBtnSubmit(id_compra_estado, "ventas_web_procesar", "#btn_guardar_venta");

})

function cargarNumeroEnvio(id_compra_estado, id_compra){

    $.ajax({
        type: "POST",
        url: "../ventas_web_procesar/cargar_numero_envio.php",
        data: {id_compra_estado: id_compra_estado, id_compra, id_compra, carga_automatica: true},
        dataType: "text",
        success: function (campo_n_envio) {

            $(".form-venta-procesar #campo_n_envio").html(campo_n_envio);

        }
    });

}