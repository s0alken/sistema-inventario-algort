<?php

$arr = array("medida"                     => array("url"   => "eliminar_medida_controller.php?redireccionar=false&id_medida=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta medida? Todos los productos perderán este valor"),

			"subcategoria"                => array("url"   => "eliminar_controller.php?redireccionar=true&id_subcategoria=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta subcategoría?"),

			"categoria"                   => array("url"   => "eliminar_controller.php?redireccionar=true&id_categoria=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta categoría?"),

			"familia"                     => array("url"   => "eliminar_controller.php?redireccionar=true&id_familia=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta familia?"),

			"marca"                       => array("url"   => "eliminar_controller.php?redireccionar=true&id_marca=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta marca?"),

			"procedencia"                 => array("url"   => "eliminar_controller.php?redireccionar=true&id_procedencia=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta procedencia?"),

			"unidad_medida"               => array("url"   => "eliminar_controller.php?redireccionar=true&id_unidad_medida=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta unidad de medida?"),

			"producto"                    => array("url"   => "eliminar_controller.php?redireccionar=true&id_producto=",
                                             "advertencia" => "¿Seguro que quieres eliminar este producto?"),
			
			"operador_logistico"          => array("url"   => "eliminar_controller.php?redireccionar=true&id_operador_logistico=",
                                             "advertencia" => "¿Seguro que quieres eliminar este operador logístico?"),

			"carrito_venta"               => array("url"   => "quitar_del_carrito.php?redireccionar=false&codigo_barras=",
                                             "advertencia" => "¿Seguro que quieres eliminar este producto de la venta?"),

			"carrito_cotizacion"          => array("url"   => "quitar_del_carrito.php?redireccionar=false&codigo_barras=",
		                                     "advertencia" => "¿Seguro que quieres eliminar este producto de la cotización?"),

			"carrito_actualizacion_stock" => array("url"   => "quitar_del_carrito.php?redireccionar=false&codigo_barras=",
                                    	     "advertencia" => "¿Seguro que quieres eliminar este producto de la actualización de stock?"),

			"productos_traspaso"          => array("url"   => "quitar_del_traspaso.php?redireccionar=false&codigo_barras=",
		                                     "advertencia" => "¿Seguro que quieres eliminar este producto del traspaso?"),

			"sucursal"                    => array("url"   => "eliminar_controller.php?redireccionar=true&id_sucursal=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta sucursal? Se perderán todos los registros relacionados"),

			"imagen"                      => array("url"   => "eliminar_controller.php?redireccionar=true&id_imagen=",
                                             "advertencia" => "¿Seguro que quieres eliminar esta imagen?"),

			"usuario"                      => array("url"   => "eliminar_controller.php?redireccionar=true&id_usuario=",
                                             "advertencia" => "¿Seguro que quieres eliminar este usuario?")
			);

$item = $arr[$_POST["item"]];

$url = $item["url"] . $_POST["id_item"];
$advertencia = $item["advertencia"];

echo json_encode(array("url" => $url, "advertencia" => $advertencia));

?>