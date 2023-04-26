<?php

function cargarMontos($cotizacion, $carrito){

	$monto_total = 0;

	foreach ($carrito as $producto) {

		$total_producto = $producto->cantidad * $producto->precio_venta;

		$descuento_porcentaje = $producto->descuento_porcentaje;
		$descuento_dinero = $producto->descuento_dinero * $producto->cantidad;
		
		//total del producto con el descuento aplicado
		$subtotal = round($total_producto - (($total_producto * $descuento_porcentaje) / 100)) - $descuento_dinero;

		$subtotal = $subtotal < 0 ? 0 : $subtotal;

		//monto total es el total de la cotizacion sin el descuento aplicado
	    $monto_total += $subtotal;

		$producto->subtotal = $subtotal;
		$producto->total_descuento = $total_producto - $subtotal;

	}

	$descuento_porcentaje = $cotizacion->descuento_porcentaje;
	$descuento_dinero = $cotizacion->descuento_dinero;

	//total a pagar es el total de la cotizacion con el descuento aplicado
	$total_a_pagar = round($monto_total - (($monto_total * $descuento_porcentaje) / 100)) - $descuento_dinero;

	$total_a_pagar = $total_a_pagar < 0 ? 0 : $total_a_pagar;

	$total_descuento = $monto_total - $total_a_pagar;

	$monto_neto = round($total_a_pagar / 1.19);

	$total_iva = $total_a_pagar - $monto_neto;

	$cotizacion->monto_neto        = $monto_neto;
	$cotizacion->total_iva         = $total_iva;
	$cotizacion->total_a_pagar     = $total_a_pagar;
	$cotizacion->monto_total       = $monto_total;

}

?>