<?php

function cargarMontos($venta, $carrito){

	$monto_total = 0;
	$monto_total_puntos = 0;

	foreach ($carrito as $producto) {

		$total_producto = $producto->cantidad * $producto->precio_venta;

		$descuento_porcentaje = $producto->descuento_porcentaje;
		$descuento_dinero = $producto->descuento_dinero * $producto->cantidad;
		
		//total del producto con el descuento aplicado
		$subtotal = round($total_producto - (($total_producto * $descuento_porcentaje) / 100)) - $descuento_dinero;

		$subtotal = $subtotal < 0 ? 0 : $subtotal;

		//monto total es el total de la venta sin el descuento aplicado
	    $monto_total += $subtotal;

		if ($venta->acumula_puntos) {
	    	
	    	//monto total puntos es el total de los productos que acumulan puntos
		    $monto_total_puntos += $producto->acumula_puntos ? $subtotal : 0;

	    }

		$producto->subtotal = $subtotal;
		$producto->total_descuento = $total_producto - $subtotal;

	}

	$descuento_porcentaje = $venta->descuento_porcentaje;
	$descuento_dinero = $venta->descuento_dinero;

	//total a pagar es el total de la venta con el descuento aplicado
	$total_a_pagar = round($monto_total - (($monto_total * $descuento_porcentaje) / 100))
	                 - $descuento_dinero - $venta->puntos_aplicados + $venta->costo_despacho;

	$total_a_pagar = $total_a_pagar < 0 ? 0 : $total_a_pagar;

	//aplicando descuento al monto total puntos
	$monto_total_puntos = round($monto_total_puntos - (($monto_total_puntos * $descuento_porcentaje) / 100))
	                      - $descuento_dinero - $venta->puntos_aplicados;

	$monto_total_puntos = $monto_total_puntos < 0 ? 0 : $monto_total_puntos;

	$total_descuento = $monto_total - $total_a_pagar;

	$monto_neto = round($total_a_pagar / 1.19);

	$total_iva = $total_a_pagar - $monto_neto;

	$puntos_acumulados = round(($monto_total_puntos * 2) / 100);

	$venta->monto_neto        = $monto_neto;
	$venta->total_iva         = $total_iva;
	$venta->total_a_pagar     = $total_a_pagar;
	$venta->monto_total       = $monto_total;
    $venta->puntos_acumulados = $puntos_acumulados;

}

?>