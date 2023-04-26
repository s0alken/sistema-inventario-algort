<?php

session_start();

$puntos          = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["puntos"]);
$total_descuento = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["total_descuento"]);
$monto_neto      = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["monto_neto"]);
$total_iva       = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["total_iva"]);
$total_a_pagar   = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["total_a_pagar"]);
$monto_total     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["venta"]["monto_total"]);
$cantidad        = $_SESSION["sistema"]["venta"]["cantidad"];

echo json_encode(array("puntos"          => $puntos,
	                   "total_descuento" => $total_descuento,
	                   "monto_neto"      => $monto_neto,
	                   "total_iva"       => $total_iva,
	                   "total_a_pagar"   => $total_a_pagar,
	                   "monto_total"     => $monto_total,
	                   "cantidad"        => $cantidad));


?>