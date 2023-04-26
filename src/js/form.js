var tipo_dato = "JSON";

//------------- PRODUCTO ----------------//

//configurando la variable de sesión producto al setear un campo
$(".form-producto-crear").on("focusout change click", ".campo-producto", function(e){

    configurarProducto($(".form-producto-crear"));

})

//botón seleccionar todo en creación/edición de producto
$(".btn-seleccionar-todo").click(function(){

    var modal = $(this).closest(".modal");

    var tablas = {"modal_compatibilidad"       : tabla_modal_compatibilidad,
                  "modal_imagenes"             : tabla_modal_imagenes,
                  "modal_ciudades_compatibles" : tabla_modal_ciudades_compatibles};

    var tabla = tablas[modal.attr("id")];

    $(tabla.$(":not(:checked)")).prop("checked", true);

})

//botón deseleccionar todo en creación/edición de producto
$(".btn-deseleccionar-todo").click(function(){

    var modal = $(this).closest(".modal");

    var tablas = {"modal_compatibilidad"       : tabla_modal_compatibilidad,
                  "modal_imagenes"             : tabla_modal_imagenes,
                  "modal_ciudades_compatibles" : tabla_modal_ciudades_compatibles};

    var tabla = tablas[modal.attr("id")];

    $(tabla.$(":checked")).prop("checked", false);

})

$("#btn_generar_codigo_barras").click(function(){

    generarCodigoBarras($(this).data("target"));

})

//cargando select dependientes al seleccionar otra opción

//======================FAMILIAS==================================

$("#id_familia").change(function(){

    var cargar_subcategoria = {
        id_categoria: "#id_categoria",
        id_select: "#id_subcategoria",
        cargar_campos_medidas: true
    }

    cargarCategoria($(this).val(), "#id_categoria", true, false, cargar_subcategoria);

})

$("#id_categoria").change(function(){

    cargarSubcategoria($(this).val(), "#id_subcategoria", true);

})

//======================CREANDO CATEGORÍA==================================

$("#id_familia_nueva_categoria").change(function(){

    validarCategoria($("#nombre_categoria").val(), $(this).val());

})

//======================CREANDO SUBCATEGORÍA==================================

$("#id_familia_nueva_subcategoria").change(function(){

    cargarCategoria($(this).val(), "#id_categoria_nueva_subcategoria", false, true);

})

$("#id_categoria_nueva_subcategoria").change(function(){

    validarSubcategoria($("#nombre_subcategoria").val(), $(this).val());

})

//======================BODEGAS==================================

$("#id_bodega").change(function(){

    var cargar_seccion = {
        id_locker: "#id_locker",
        id_select: "#id_seccion",
        modo_nivel: true,
        validar_nivel: false,
        cargar_nivel: {id_seccion: "#id_seccion", id_select: "#id_nivel"}
    }

    cargarLocker($(this).val(), "#id_locker", true, false, false, cargar_seccion);

})

$("#id_locker").change(function(){

    cargarSeccion($(this).val(), "#id_seccion", true, false, {id_seccion: "#id_seccion", id_select: "#id_nivel"});

})

$("#id_seccion").change(function(){

    cargarNivel($(this).val(), "#id_nivel");

})

//======================CREANDO LOCKER==================================

$("#id_bodega_nuevo_locker").change(function(){

    validarLocker($("#nombre_locker").val(), $(this).val());

})

//======================CREANDO SECCIÓN==================================

$("#id_bodega_nueva_seccion").change(function(){

    cargarLocker($(this).val(), "#id_locker_nueva_seccion", false, false, true);

})

$("#id_locker_nueva_seccion").change(function(){

    validarSeccion($("#nombre_seccion").val(), $(this).val());

})

//======================CREANDO NIVEL==================================

$("#id_bodega_nuevo_nivel").change(function(){

    var cargar_seccion = {
        id_locker: "#id_locker_nuevo_nivel",
        id_select: "#id_seccion_nuevo_nivel",
        modo_nivel: false,
        validar_nivel: true,
        cargar_nivel: {}
    }

    cargarLocker($(this).val(), "#id_locker_nuevo_nivel", false, true, false, cargar_seccion);

})

$("#id_locker_nuevo_nivel").change(function(){

    cargarSeccion($(this).val(), "#id_seccion_nuevo_nivel", false, true);

})

$("#id_seccion_nuevo_nivel").change(function(){

    validarNivel($("#nombre_nivel").val(), $(this).val());

})

//======================DIRECCIÓN PERSONA==================================

$("#id_region").change(function(){

    var cargar_ciudad = {
        id_comuna: "#id_comuna",
        id_select: "#id_ciudad",
        validar_sucursal: false
    }

    cargarComuna($(this).val(), "#id_comuna", cargar_ciudad);

})

$("#id_comuna").change(function(){

    cargarCiudad($(this).val(), "#id_ciudad");

})

//======================CREANDO SUCURSAL==================================

$("#id_region_nueva_sucursal").change(function(){

    var cargar_ciudad = {
        id_comuna: "#id_comuna_nueva_sucursal",
        id_select: "#id_ciudad_nueva_sucursal",
        validar_sucursal: true
    }

    cargarComuna($(this).val(), "#id_comuna_nueva_sucursal", cargar_ciudad);

})

$("#id_comuna_nueva_sucursal").change(function(){

    cargarCiudad($(this).val(), "#id_ciudad_nueva_sucursal", true);

})

$("#id_ciudad_nueva_sucursal").change(function(){

    validarSucursal($("#nombre_sucursal").val(), $(this).val());

})

//======================CREANDO OPERADOR LOGÍSTICO==================================

$("#id_tipo_operador_logistico").change(function(){

    activarOpcionesDelivery($(this).val());

})

function cargarCategoria(id_familia, id_select, modo_subcategoria = false, validar_subcategoria = false, cargar_subcategoria = {}) {

    $.ajax({
        url: "../controller/cargar_categoria.php",
        method: "POST",
        data: {id_familia: id_familia, modo_subcategoria: modo_subcategoria},
        dataType: "TEXT",
        success: function(categorias){

            $(id_select).html(categorias);

            if (validar_subcategoria) {

                validarSubcategoria($("#nombre_subcategoria").val(), $("#id_categoria_nueva_subcategoria").val())

            }
            
            if (!jQuery.isEmptyObject(cargar_subcategoria)) {

                cargarSubcategoria($(cargar_subcategoria.id_categoria).val(), cargar_subcategoria.id_select, cargar_subcategoria.cargar_campos_medidas);

            }

        }
    });

}

function cargarSubcategoria(id_categoria, id_select, cargar_campos_medidas = false) {

    $.ajax({
        url: "../controller/cargar_subcategoria.php",
        method: "POST",
        data: {id_categoria: id_categoria},
        dataType: "TEXT",
        success: function(subcategorias){
 
            $(id_select).html(subcategorias);

            if (cargar_campos_medidas) {

                //cargando medidas en caso de que la categoría las tenga
                cargarCamposMedidas(id_categoria);

            }    

        }
    });

}

function cargarLocker(id_bodega, id_select, modo_nivel = false, modo_seccion = false, validar_seccion = false, cargar_seccion = {}) {

    $.ajax({
        url: "../controller/cargar_locker.php",
        method: "POST",
        data: {id_bodega: id_bodega, modo_nivel: modo_nivel, modo_seccion: modo_seccion},
        dataType: "TEXT",
        success: function(lockers){

            $(id_select).html(lockers);

            if (validar_seccion) {

                validarSeccion($("#nombre_seccion").val(), $("#id_locker_nueva_seccion").val());

            }

            if (!jQuery.isEmptyObject(cargar_seccion)) {

                cargarSeccion($(cargar_seccion.id_locker).val(), cargar_seccion.id_select, cargar_seccion.modo_nivel, cargar_seccion.validar_nivel, cargar_seccion.cargar_nivel);

            }

        }
    });

}

function cargarSeccion(id_locker, id_select, modo_nivel = false, validar_nivel = false, cargar_nivel = {}) {

    $.ajax({
        url: "../controller/cargar_seccion.php",
        method: "POST",
        data: {id_locker: id_locker, modo_nivel: modo_nivel},
        dataType: "TEXT",
        success: function(secciones){

            $(id_select).html(secciones);

            if (validar_nivel) {

                validarNivel($("#nombre_nivel").val(), $("#id_seccion_nuevo_nivel").val());

            }

            if (!jQuery.isEmptyObject(cargar_nivel)) {

                cargarNivel($(cargar_nivel.id_seccion).val(), cargar_nivel.id_select);

            }

        }
    });

}

function cargarNivel(id_seccion, id_select) {

    $.ajax({
        url: "../controller/cargar_nivel.php",
        method: "POST",
        data: {id_seccion: id_seccion},
        dataType: "TEXT",
        success: function(niveles){

            $(id_select).html(niveles);

        }
    });

}

function cargarComuna(id_region, id_select, cargar_ciudad) {

    $.ajax({
        url: "../controller/cargar_comuna.php",
        method: "POST",
        data: {id_region: id_region},
        dataType: "TEXT",
        success: function(comunas){

            $(id_select).html(comunas);

            cargarCiudad($(cargar_ciudad.id_comuna).val(), cargar_ciudad.id_select, cargar_ciudad.validar_sucursal);

        }
    });

}

function cargarCiudad(id_comuna, id_select, validar_sucursal = false) {

    $.ajax({
        url: "../controller/cargar_ciudad.php",
        method: "POST",
        data: {id_comuna: id_comuna},
        dataType: "TEXT",
        success: function(ciudades){
 
            $(id_select).html(ciudades);

            if (validar_sucursal) {

                validarSucursal($("#nombre_sucursal").val(), $("#id_ciudad_nueva_sucursal").val());
                
            }

        }
    });

}

//enviando formulario
$(document).on("submit", ".form-item", function(e) {

    e.preventDefault();

    //var form = $(this).closest(".form-item");

    var form = $(this);

    //var btn = $(this);

    var btn = $(this).find("button[type=submit]:focus");

    guardarItem(form, btn);

});

//crear y editar item (productos, categorias, proveedores, etc)
function guardarItem(form, btn) {

    //texto original del botón
    var btn_text = btn.text();

    //height original del botón
    var btn_height = btn.outerHeight();
    
    var spinner = "<div class='d-flex align-items-center justify-content-center'>" +
                  "<div class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></div>" +
                  "</div>";

    btn.html(spinner);

    //al poner el spinner dentro del botón,
    //este pierde su height original, por lo tanto,
    //hay que setearlo
    btn.outerHeight(btn_height);
    

    var url = form.attr("action");

    var form_data = new FormData(form[0]);

    //agregando compatibilidad e imagenes seleccionadas al form_data
    //esto lo hice porque al usar la paginación de datatables
    //los valores de las páginas ocultas no llegan al form
    
    if ($(".form-producto-crear").length || $(".form-producto-editar").length) {

        var compatibilidad = $(tabla_modal_compatibilidad.$(":checked")).map(function() {

            return $(this).val();

        }).toArray();

        var imagenes = $(tabla_modal_imagenes.$(":checked")).map(function() {

            return $(this).val();

        }).toArray();

        if (compatibilidad.length) {

            form_data.set("compatibilidad", JSON.stringify(compatibilidad));

        }

        if (imagenes.length) {

            form_data.set("imagenes", JSON.stringify(imagenes));
            
        }

    }

    //form de crear operador logístico
    if ($(".form-operador-logistico-crear").length || $(".form-operador-logistico-editar").length) {

        var ciudades_compatibles = $(tabla_modal_ciudades_compatibles.$(":checked")).map(function() {

            return $(this).val();

        }).toArray();

        if (ciudades_compatibles.length) {

            form_data.set("ciudades_compatibles", JSON.stringify(ciudades_compatibles));

        }

    }

    $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: tipo_dato,
        contentType: false,
        processData: false,
        success: function (item) {
            console.log(item);
            if(item.guardado) {

                redireccionar(item.redireccionar);

            } else {

                setTimeout(function(){

                    mostrarSnackbar('#snackbar', item.mensaje, item.guardado);
                    btn.html(btn_text);

                }, 500);

            }
        } 
    });
}

function configurarProducto(form) {

    var form_data = new FormData(form[0]);

    //agregando compatibilidad e imagenes seleccionadas al form_data
    //esto lo hice porque al usar la paginación de datatables
    //los valores de las páginas ocultas no llegan al form
    
    var compatibilidad = $(tabla_modal_compatibilidad.$(":checked")).map(function() {

        return $(this).val();

    }).toArray();

    var imagenes = $(tabla_modal_imagenes.$(":checked")).map(function() {

        return $(this).val();

    }).toArray();

    if (compatibilidad.length) {

        form_data.set("compatibilidad", JSON.stringify(compatibilidad));

    }

    if (imagenes.length) {

        form_data.set("imagenes", JSON.stringify(imagenes));
        
    }

    $.ajax({
        type: "POST",
        url: "configurar_producto.php",
        data: form_data,
        dataType: "text",
        contentType: false,
        processData: false,
        success: function (item) {
            console.log(item);
        } 
    });

}

function redireccionar(redireccionar) {

    if (redireccionar) {

        window.location.replace("../exito/");

    } else {

        window.location.reload(true);

    }

}

//seteando puntos
$("#precio_venta").focusout(function(){

    precio_venta = $(this).val();

    $.ajax({
        type: "POST",
        url: "../controller/puntos.php",
        data: {precio_venta: precio_venta},
        dataType: "JSON",
        success: function (puntos) {

            $("#puntos").val(puntos);

        }
    });

})

//------------- CATEGORÍA ----------------//

//agregando campos de medidas a la categoría
$(".btn_agregar_medida").click(function(){

    var form = $(this).parents("form");

    var number = form.find(".medida").length + 1;

    var medida = "<div class='medida'>" +
                 "<div class='form-row justify-content-center text-center'>" +
                 "<div class='form-group col-md-3'>" +
                 "<input type='text' class='form-control form-control-sm rounded-pill' name='medidas[medida_" + number + "][nombre_medida]' placeholder='Nombre medida'>" +
                 "</div>" +
                 "<div class='form-group col-md-3'>" +
                 "<select name='medidas[medida_" + number + "][id_unidad_medida]' class='form-control form-control-sm rounded-pill'></select>" +
                 "</div>" +
                 "<div class='form-group col-md-2'>" +
                 "<button type='button' class='btn btn-primary btn-sm btn-block rounded-pill btn-eliminar-medida'>Eliminar</button>" +
                 "</div>" +
                 "</div>" +
                 "</div>";

    form.find(".medidas").append(medida);
    cargarUnidadMedida(number, form);
})

//eliminando campos de medidas de la categoría
$(".medidas").on("click", ".btn-eliminar-medida", function(){

    var form = $(this).parents("form");

    $(this).parents(".medida").remove();

    form.find(".medida").each(function(index, medida){

        $(medida).find("input").attr("name", "medidas[medida_" + (index + 1) + "][nombre_medida]");
        $(medida).find("select").attr("name", "medidas[medida_" + (index + 1) + "][id_unidad_medida]");

    })
})

function cargarUnidadMedida(number, form){

    $.ajax({
        url: "../controller/cargar_unidad_medida.php",
        dataType: "TEXT",
        success: function (unidades) {
            
            form.find("select[name='medidas[medida_" + number + "][id_unidad_medida]']").html(unidades);

        }
    });

}

function cargarCamposMedidas(id_categoria){

    $.ajax({
        type: "POST",
        url: "../controller/cargar_campos_medidas.php",
        data: {id_categoria: id_categoria, carga_automatica: true, configurar_producto: $(".form-producto-crear").length},
        dataType: "TEXT",
        success: function (campos_medidas) {

            $("#campos_medidas").html(campos_medidas);

        }
    });

}

//indicando la cantidad de archivos seleccionados
//en el texto del botón subir imagen
$("input[type=file]").change(function(){

    var files = $(this)[0].files.length;

    var text = files > 1 ? files + " imágenes seleccionadas" : "1 imagen seleccionada";

    $("span." + $(this).data("btn-imagen")).text(text);

});

//------------- ELIMINAR ITEMS ----------------//

$("#content").on("click", ".btn-eliminar-item", function(){

    //no es necesario hacer esto en ajax pero prefiero
    //traer las url del servidor que hardcodearlas en el js

    $.ajax({
        type: "POST",
        url: "../controller/cargar_modal_eliminar_item.php",
        data: {item: $(this).data("item"), id_item: $(this).val()},
        dataType: "JSON",
        success: function (item) {
            console.log(item);
            var modal = $("#modal_eliminar_item");

            modal.find("form").attr("action", item.url);
            modal.find("form #modal_eliminar_advertencia").text(item.advertencia);

            modal.modal("show");

        }
    });

})

//------------- CAMBIAR IMAGEN ----------------//

$(".table").on("click", ".btn-cambiar-imagen", function(){

    var form = $("#modal_cambiar_imagen").find("form");

    var url = "cambiar_imagen_controller.php?redireccionar=false&id_slider=";

    form.attr("action", url + $(this).val());

})

//------------- CAMBIAR TEXTO IMAGEN ----------------//

$(".table").on("click", ".btn-editar-texto-slider", function(){

    var id_slider = $(this).val();

    var modal = $("#modal_editar_texto_slider");

    var form = modal.find("form");

    var url = "editar_texto_slider_controller.php?redireccionar=false&id_slider=";

    form.attr("action", url + id_slider);

    $.ajax({
        type: "POST",
        url: "cargar_texto_slider.php",
        data: {id_slider: id_slider},
        dataType: "JSON",
        success: function (slider) {
            console.log(slider);
            modal.find("#encabezado_editar").val(slider.encabezado);
            modal.find("#encabezado_color_editar").val(slider.encabezado_color);
            modal.find("#subtitulo_editar").val(slider.subtitulo);
            modal.find("#subtitulo_color_editar").val(slider.subtitulo_color);

            modal.modal("show");

        }
    });

})

//------------- CUENTA USUARIO ----------------//

//habilitar/deshabilitar campos para editar password
$(".form-cuenta-editar #cambiar_password").change(function(){

    var inputs = $(".form-cuenta-editar input[type=password]");
    var checked = $(this).is(":checked");

    inputs.each(function(index, element){
        $(element).prop("disabled", !checked);
    })

});

//------------- OPERADOR LOGÍSTICO ----------------//

//habilitar/deshabilitar campos para agregar monto mínimo para despacho gratis
$("#habilitar_despacho_gratis").change(function(){

    var checked = $(this).is(":checked");

    $("#monto_minimo_despacho_gratis").prop("disabled", !checked);

});

//------------- SNACKBAR ----------------//

function mostrarSnackbar(id_snackbar, mensaje, agregado) {

  var snackbar = $(id_snackbar);

  var estilo = agregado ? "bg-warning text-dark show" : "bg-danger text-white show";

  snackbar.text(mensaje);

  snackbar.addClass(estilo);

  setTimeout(function(){

    snackbar.removeClass(estilo);

  }, 3700);

}

//enviando formulario
$("#form-login").submit(function(e) {

    e.preventDefault();

    loginUsuario($(this));

});

function loginUsuario(form){

    var btn = form.find("button[type=submit]");

    //texto original del botón
    var btn_text = btn.text();

    //height original del botón
    var btn_height = btn.outerHeight();
    
    var spinner = "<div class='d-flex align-items-center justify-content-center'>" +
                  "<div class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></div>" +
                  "</div>";

    btn.html(spinner);

    //al poner el spinner dentro del botón,
    //este pierde su height original, por lo tanto,
    //hay que setearlo
    btn.outerHeight(btn_height);

    var url = form.attr("action");

    var form_data = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function (usuario) {
            console.log(usuario);
            if(usuario.autenticado) {

                window.location.reload(true);

            } else {

                setTimeout(function(){

                    grecaptcha.reset();
                    $(".alert").html(usuario.error);
                    $(".alert").show();
                    btn.html(btn_text);

                }, 500);

            }
        }

    });

}

//configurando botón filtrar
$("#btn_filtrar").click(function(){

    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_termino = $("#fecha_termino").val();

    var filtros = {fecha_inicio: fecha_inicio, fecha_termino: fecha_termino};

    var url = $(this).val();

    cargarTablaFiltrar(filtros, url);

})

//configurando botón exportar a Excel
$("#btn_exportar").click(function() {

  tabla_filtrar.button(".buttons-excel").trigger();

});

function cargarTablaFiltrar(filtros, url){

    $.ajax({
        type: "POST",
        url: "../informes/" + url,
        data: {filtros: filtros, carga_automatica: true},
        dataType: "text",
        success: function (resultados) {

            tabla_filtrar.destroy();

            $("#resultados").html(resultados);

            //reiniciando tabla ventas para exportar a excel
            tabla_filtrar = configurarTabla("#tabla_filtrar", 10, null, true);

        }
    });

}

//confirmando compra por la tienda
$("table").on("click", ".btn-confirmar-compra", function(){

    var id_compra = $(this).val();

    var url = "confirmar_compra.php?redireccionar=true&id_compra=" + id_compra;

    var modal = $("#modal_confirmar_compra");

    modal.find("form").attr("action", url);

    modal.modal("show");

})

function generarCodigoBarras(target) {

    $.ajax({
        url: "../productos/generar_codigo_barras.php",
        method: "POST",
        dataType: "JSON",
        success: function(codigo_barras){

            $(target).val(codigo_barras);
            validarCodigoBarras(codigo_barras);

        }
    });

}

function activarOpcionesDelivery(id_tipo_operador_logistico) {

    $.ajax({
        type: "POST",
        url: "../operadores_logisticos/activar_opciones_delivery.php",
        data: {id_tipo_operador_logistico: id_tipo_operador_logistico},
        dataType: "JSON",
        success: function(opciones_delivery_class){

            $("#opciones_delivery").attr("class", opciones_delivery_class);

        }
    });

}