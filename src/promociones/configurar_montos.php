<?php

function configurarMontos(){

	$descuento_porcentaje = $_SESSION["sistema"]["promocion"]["descuento_porcentaje"];
	$descuento_dinero = $_SESSION["sistema"]["promocion"]["descuento_dinero"];

	foreach ($_SESSION["sistema"]["promocion"]["carrito"] as $codigo_barras => $producto) {
		
		$precio_antes = $producto["precio_antes"];

		$precio_ahora = round($precio_antes - (($precio_antes * $descuento_porcentaje) / 100)) - $descuento_dinero;

		$precio_ahora = $precio_ahora < 0 ? 0 : $precio_ahora;

		$_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras]["precio_ahora"] = $precio_ahora;
		$_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras]["total_descontado"] = $precio_antes - $precio_ahora;

	}

}

?>