<?php

function configurarMontos(){

	$monto_total = 0;
	$monto_total_puntos = 0;
	$cantidad = 0;

	foreach ($_SESSION["sistema"]["venta"]["carrito"] as $codigo_barras => $producto) {

		$total_producto = $producto["cantidad"] * $producto["precio_venta"];

		$descuento_porcentaje = $producto["descuento_porcentaje"];
		$descuento_dinero = $producto["descuento_dinero"] * $producto["cantidad"];
		
		//total del producto con el descuento aplicado
		$subtotal = round($total_producto - (($total_producto * $descuento_porcentaje) / 100)) - $descuento_dinero;

		$subtotal = $subtotal < 0 ? 0 : $subtotal;

		//monto total es el total de la venta sin el descuento aplicado
	    $monto_total += $subtotal;
		$cantidad += $producto["cantidad"];

		//monto total puntos es el total de los productos que acumulan puntos
		$monto_total_puntos += $producto["acumula_puntos"] ? $subtotal : 0;

		$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["subtotal"] = $subtotal;
		$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["total_descuento"] = $total_producto - $subtotal;

	}

	$descuento_porcentaje = $_SESSION["sistema"]["venta"]["descuento_porcentaje"];
	$descuento_dinero = $_SESSION["sistema"]["venta"]["descuento_dinero"];

	//total a pagar es el total de la venta con el descuento aplicado
	$total_a_pagar = round($monto_total - (($monto_total * $descuento_porcentaje) / 100)) - $descuento_dinero;

	$total_a_pagar = $total_a_pagar < 0 ? 0 : $total_a_pagar;

	//aplicando descuento al monto total puntos
	$monto_total_puntos = round($monto_total_puntos - (($monto_total_puntos * $descuento_porcentaje) / 100)) - $descuento_dinero;

	$monto_total_puntos = $monto_total_puntos < 0 ? 0 : $monto_total_puntos;

	$total_descuento = $monto_total - $total_a_pagar;

	$monto_neto = round($total_a_pagar / 1.19);

	$total_iva = $total_a_pagar - $monto_neto;

	$puntos = round(($monto_total_puntos * 2) / 100);

	$_SESSION["sistema"]["venta"]["total_descuento"] = $total_descuento;
	$_SESSION["sistema"]["venta"]["monto_neto"]      = $monto_neto;
	$_SESSION["sistema"]["venta"]["total_iva"]       = $total_iva;
	$_SESSION["sistema"]["venta"]["total_a_pagar"]   = $total_a_pagar;
	$_SESSION["sistema"]["venta"]["monto_total"]     = $monto_total;
	$_SESSION["sistema"]["venta"]["cantidad"]        = $cantidad;
    $_SESSION["sistema"]["venta"]["puntos"]          = $puntos;

}

?>