<?php

function cargarMontos($compra, $carrito){

	$monto_total = 0;
	$monto_total_puntos = 0;
	$cantidad = 0;

	foreach ($carrito as $producto) {

		//monto total es el total de la venta sin el descuento aplicado
	    $monto_total += $producto->subtotal;

	    if ($compra->acumula_puntos) {
	    	
	    	//monto total puntos es el total de los productos que acumulan puntos
	    	$monto_total_puntos += $producto->acumula_puntos ? $producto->subtotal : 0;

	    }

	}

	//total productos es el total de la venta con el descuento aplicado
	$total_productos = $monto_total - $compra->puntos_aplicados;

	//total a pagar es el total de la venta con el descuento aplicado y costo de despacho
	$total_a_pagar = $total_productos + $compra->costo_despacho;

	//aplicando descuento al monto total puntos
	$monto_total_puntos = $monto_total_puntos === 0 ? 0 : $monto_total_puntos - $compra->puntos_aplicados;

	$monto_neto = round($total_a_pagar / 1.19);

	$total_iva = $total_a_pagar - $monto_neto;

	$puntos_acumulados = round(($monto_total_puntos * 2) / 100);

	$compra->monto_neto        = $monto_neto;
	$compra->total_iva         = $total_iva;
	$compra->total_a_pagar     = $total_a_pagar;
	$compra->monto_total       = $monto_total;
    $compra->puntos_acumulados = $puntos_acumulados;
    $compra->total_productos   = $total_productos;

}

?>