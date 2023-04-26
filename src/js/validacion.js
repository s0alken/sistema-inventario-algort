

//------------- PRODUCTO ----------------//

//previniendo submit on enter
$(".form-item").keypress(function(e){
    if(e.keyCode === 13) {
        e.preventDefault();
    }
})

$("#codigo_barras").keyup(function(){

    validarCodigoBarras($(this).val());

})

$("#precio_costo, #precio_venta").keyup(function(){

    validarPrecio($("#precio_costo").val(), $("#precio_venta").val());

})

$(".form-producto-crear #observaciones, .form-producto-editar #observaciones, #caracteristicas_tecnicas").keyup(function(){

    validarLargo($(this), 1500);

})

$("#observaciones").keyup(function(){

    validarLargo($(this), 300);

})

$("#motivo_rechazo").keyup(function(){

    validarLargo($(this), 500);

})

//======================FAMILIAS==================================

$("#nombre_familia").keyup(function(){

    validarFamilia($(this).val());

})

$("#nombre_categoria").keyup(function(){

    validarCategoria($(this).val(), $("#id_familia_nueva_categoria").val());

})

$("#nombre_subcategoria").keyup(function(){

    validarSubcategoria($(this).val(), $("#id_categoria_nueva_subcategoria").val());

})

$("#nombre_marca").keyup(function(){

    validarMarca($(this).val());

})

$("#nombre_procedencia").keyup(function(){

    validarProcedencia($(this).val());

})

//======================SUCURSALES==================================
$("#nombre_sucursal").keyup(function(){

    validarSucursal($(this).val(), $("#id_ciudad_nueva_sucursal").val());

})

//======================BODEGAS==================================

$("#nombre_bodega").keyup(function(){

    validarBodega($(this).val());

})

$("#nombre_locker").keyup(function(){

    validarLocker($(this).val(), $("#id_bodega_nuevo_locker").val());

})

$("#nombre_seccion").keyup(function(){

    validarSeccion($(this).val(), $("#id_locker_nueva_seccion").val());

})

$("#nombre_nivel").keyup(function(){

    validarNivel($(this).val(), $("#id_seccion_nuevo_nivel").val());

})

$("#nombre_unidad_medida").keyup(function(){

    validarUnidadesMedida($(this).val());

})

//======================OPERADORES LOGÍSTICOS==================================

$("#nombre_operador_logistico").keyup(function(){

    validarOperadorLogistico($(this).val());

})

//======================PERSONA==================================

$("#rut").keyup(function(){

    //formateando rut
    $(this).val($.formatRut($(this).val()));

    //validando rut
    validarRut($(this).val());

})

//======================CLIENTE==================================

$("#rut_cliente, #rut_proveedor, #rut_banco").keyup(function(e){

    var tipo_persona = $(this).data("tipo-persona");

    //formateando rut
    $(this).val($.formatRut($(this).val()));

    //validando rut
    if(e.keyCode !== 13) {
        
        validarRutClienteProveedorBanco($(this).val(), tipo_persona);

    } 

})

//======================CLIENTE==================================

$("#rut_empresa").keyup(function(e){

    //formateando rut
    $(this).val($.formatRut($(this).val()));

    validarRutEmpresa($(this).val());

})

//======================USUARIOS==================================

$("#nombre_usuario").keyup(function(){

    validarUsuario($(this).val());

})

//======================CUENTA==================================

$("#nombre_usuario_cuenta").keyup(function(){

    validarUsuarioCuenta($(this).val());

})

function validarCodigoBarras(codigo_barras){

    var data = {codigo_barras: codigo_barras};

    //al editar hay que pasar el id_producto
    if($(".form-producto-editar").length){

        data.id_producto = valorQueryString("id_producto", $(".form-producto-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../productos/validar_codigo_barras.php",
        data: data,
        dataType: "JSON",
        success: function (codigo_barras) {
            console.log(codigo_barras);
            if (codigo_barras.existe) {
                $("#codigo_barras_alerta").show();
            } else {
                $("#codigo_barras_alerta").hide();
            }

        }
    });

}

function validarFamilia(nombre_familia){

    var data = {nombre_familia: nombre_familia};

    //al editar hay que pasar el id_familia
    if($(".form-familia-editar").length){

        data.id_familia = valorQueryString("id_familia", $(".form-familia-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../familias/validar_familia.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_familia) {

            if (nombre_familia.existe) {
                $("#nombre_familia_alerta").show();
            } else {
                $("#nombre_familia_alerta").hide();
            }

        }
    });

}

function validarCategoria(nombre_categoria, id_familia_nueva_categoria){

    var data = {nombre_categoria: nombre_categoria, id_familia_nueva_categoria: id_familia_nueva_categoria};

    //al editar hay que pasar el id_categoria
    if($(".form-categoria-editar").length){

        data.id_categoria = valorQueryString("id_categoria", $(".form-categoria-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../categorias/validar_categoria.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_categoria) {

            if (nombre_categoria.existe) {
                $("#nombre_categoria_alerta").show();
            } else {
                $("#nombre_categoria_alerta").hide();
            }

        }
    });

}

function validarSubcategoria(nombre_subcategoria, id_categoria_nueva_subcategoria){

    var data = {nombre_subcategoria: nombre_subcategoria, id_categoria_nueva_subcategoria: id_categoria_nueva_subcategoria};

    //al editar hay que pasar el id_subcategoria
    if($(".form-subcategoria-editar").length){

        data.id_subcategoria = valorQueryString("id_subcategoria", $(".form-subcategoria-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../subcategorias/validar_subcategoria.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_subcategoria) {

            if (nombre_subcategoria.existe) {
                $("#nombre_subcategoria_alerta").show();
            } else {
                $("#nombre_subcategoria_alerta").hide();
            }

        }
    });

}

function validarMarca(nombre_marca){

    var data = {nombre_marca: nombre_marca};

    //al editar hay que pasar el id_marca
    if($(".form-marca-editar").length){

        data.id_marca = valorQueryString("id_marca", $(".form-marca-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../marcas/validar_marca.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_marca) {

            if (nombre_marca.existe) {
                $("#nombre_marca_alerta").show();
            } else {
                $("#nombre_marca_alerta").hide();
            }

        }
    });

}

function validarProcedencia(nombre_procedencia){

    var data = {nombre_procedencia: nombre_procedencia};

    //al editar hay que pasar el id_procedencia
    if($(".form-procedencia-editar").length){

        data.id_procedencia = valorQueryString("id_procedencia", $(".form-procedencia-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../procedencias/validar_procedencia.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_procedencia) {

            if (nombre_procedencia.existe) {
                $("#nombre_procedencia_alerta").show();
            } else {
                $("#nombre_procedencia_alerta").hide();
            }

        }
    });

}

function validarSucursal(nombre_sucursal, id_ciudad_nueva_sucursal){

    var data = {nombre_sucursal: nombre_sucursal, id_ciudad_nueva_sucursal: id_ciudad_nueva_sucursal};

    //al editar hay que pasar el id_locker
    if($(".form-sucursal-editar").length){

        data.id_sucursal = valorQueryString("id_sucursal", $(".form-sucursal-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../sucursales/validar_sucursal.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_sucursal) {

            if (nombre_sucursal.existe) {
                $("#nombre_sucursal_alerta").show();
            } else {
                $("#nombre_sucursal_alerta").hide();
            }

        }
    });

}

function validarBodega(nombre_bodega){
    
    var data = {nombre_bodega: nombre_bodega};

    //al editar hay que pasar el id_bodega
    if($(".form-bodega-editar").length){

        data.id_bodega = valorQueryString("id_bodega", $(".form-bodega-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../bodegas/validar_bodega.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_bodega) {

            if (nombre_bodega.existe) {
                $("#nombre_bodega_alerta").show();
            } else {
                $("#nombre_bodega_alerta").hide();
            }

        }
    });

}

function validarLocker(nombre_locker, id_bodega_nuevo_locker){

    var data = {nombre_locker: nombre_locker, id_bodega_nuevo_locker: id_bodega_nuevo_locker};

    //al editar hay que pasar el id_locker
    if($(".form-locker-editar").length){

        data.id_bodega = valorQueryString("id_locker", $(".form-locker-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../lockers/validar_locker.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_locker) {

            if (nombre_locker.existe) {
                $("#nombre_locker_alerta").show();
            } else {
                $("#nombre_locker_alerta").hide();
            }

        }
    });

}

function validarSeccion(nombre_seccion, id_locker_nueva_seccion){

    var data = {nombre_seccion: nombre_seccion, id_locker_nueva_seccion: id_locker_nueva_seccion};

    //al editar hay que pasar el id_locker
    if($(".form-seccion-editar").length){

        data.id_seccion = valorQueryString("id_seccion", $(".form-seccion-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../secciones/validar_seccion.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_seccion) {

            if (nombre_seccion.existe) {
                $("#nombre_seccion_alerta").show();
            } else {
                $("#nombre_seccion_alerta").hide();
            }

        }
    });

}

function validarNivel(nombre_nivel, id_seccion_nuevo_nivel){

    var data = {nombre_nivel: nombre_nivel, id_seccion_nuevo_nivel: id_seccion_nuevo_nivel};

    //al editar hay que pasar el id_locker
    if($(".form-nivel-editar").length){

        data.id_nivel = valorQueryString("id_nivel", $(".form-nivel-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../niveles/validar_nivel.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_nivel) {

            if (nombre_nivel.existe) {
                $("#nombre_nivel_alerta").show();
            } else {
                $("#nombre_nivel_alerta").hide();
            }

        }
    });

}

function validarUnidadesMedida(nombre_unidad_medida){

    var data = {nombre_unidad_medida: nombre_unidad_medida};

    //al editar hay que pasar el id_unidad_medida
    if($(".form-unidad-medida-editar").length){

        data.id_unidad_medida = valorQueryString("id_unidad_medida", $(".form-unidad-medida-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../unidades_medidas/validar_unidad_medida.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_unidad_medida) {

            if (nombre_unidad_medida.existe) {
                $("#nombre_unidad_medida_alerta").show();
            } else {
                $("#nombre_unidad_medida_alerta").hide();
            }

        }
    });

}

function validarRut(rut){

    var data = {rut: rut};

    //al editar hay que pasar el id_persona
    if($(".form-persona-editar").length){

        data.id_persona = valorQueryString("id_persona", $(".form-persona-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../personas/validar_rut.php",
        data: data,
        dataType: "JSON",
        success: function (rut) {

            if (rut.valido) {

                if (rut.existe) {

                    $("#rut_incorrecto_alerta").hide();
                    $("#rut_alerta").show();

                } else {

                    $("#rut_incorrecto_alerta").hide();
                    $("#rut_alerta").hide();

                }

            } else {

                $("#rut_alerta").hide();
                $("#rut_incorrecto_alerta").show();

            }
            
        }
    });

}

function validarRutClienteProveedorBanco(rut, tipo_persona){

    $.ajax({
        type: "POST",
        url: "../personas/validar_rut.php",
        data: {rut: rut},
        dataType: "JSON",
        success: function (rut) {

            if (rut.valido) {

                $("#rut_" + tipo_persona + "_incorrecto_alerta").hide();

            } else {

                $("#rut_" + tipo_persona + "_incorrecto_alerta").show();

            }
            
        }
    });

}

function validarRutEmpresa(rut_empresa){

    $.ajax({
        type: "POST",
        url: "../empresa/validar_rut.php",
        data: {rut_empresa: rut_empresa},
        dataType: "JSON",
        success: function (rut_empresa) {
            console.log(rut_empresa);
            if (rut_empresa.valido) {

                if (rut_empresa.existe) {

                    $("#rut_empresa_incorrecto_alerta").hide();
                    $("#rut_empresa_alerta").show();

                } else {

                    $("#rut_empresa_incorrecto_alerta").hide();
                    $("#rut_empresa_alerta").hide();

                }

            } else {

                $("#rut_empresa_alerta").hide();
                $("#rut_empresa_incorrecto_alerta").show();

            }
            
        }
    });

}

function validarPrecio(precio_costo, precio_venta){

    precio_costo = Number(precio_costo);
    precio_venta = Number(precio_venta);

    if (precio_costo > precio_venta) {

        $("#precio_venta_alerta").show();

    } else {

         $("#precio_venta_alerta").hide();

    }

}

function validarOperadorLogistico(nombre_operador_logistico){

    var data = {nombre_operador_logistico: nombre_operador_logistico};

    //al editar hay que pasar el id_operador_logistico
    if($(".form-operador-logistico-editar").length){

        data.id_operador_logistico = valorQueryString("id_operador_logistico", $(".form-operador-logistico-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../operadores_logisticos/validar_operador_logistico.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_operador_logistico) {

            if (nombre_operador_logistico.existe) {
                $("#nombre_operador_logistico_alerta").show();
            } else {
                $("#nombre_operador_logistico_alerta").hide();
            }

        }
    });

}

function validarUsuario(nombre_usuario){

    var data = {nombre_usuario: nombre_usuario};

    //al editar hay que pasar el id_familia
    if($(".form-usuario-editar").length){

        data.id_usuario = valorQueryString("id_usuario", $(".form-usuario-editar").attr("action"));

    };

    $.ajax({
        type: "POST",
        url: "../usuarios/validar_usuario.php",
        data: data,
        dataType: "JSON",
        success: function (nombre_usuario) {

            if (nombre_usuario.existe) {
                $("#nombre_usuario_alerta").show();
            } else {
                $("#nombre_usuario_alerta").hide();
            }

        }
    });

}

function validarUsuarioCuenta(nombre_usuario_cuenta){

    $.ajax({
        type: "POST",
        url: "../cuenta/validar_usuario.php",
        data: {nombre_usuario_cuenta: nombre_usuario_cuenta},
        dataType: "JSON",
        success: function (nombre_usuario_cuenta) {
            console.log(nombre_usuario_cuenta);
            if (nombre_usuario_cuenta.existe) {
                $("#nombre_usuario_cuenta_alerta").show();
            } else {
                $("#nombre_usuario_cuenta_alerta").hide();
            }

        }
    });

}

function validarLargo(input, largo){

   var texto = input.val();
   var id_alerta = "#" + input.parent().find("small").attr("id");

   if (texto.length > largo) {

    $(id_alerta).show();

   } else {

    $(id_alerta).hide();

   }

}

function valorQueryString(parametro, url){

    var resultados = new RegExp('[\?&]' + parametro + '=([^&#]*)').exec(url);

    return (resultados !== null) ? resultados[1] || 0 : false;

}