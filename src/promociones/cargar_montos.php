<?php

session_start();

$puntos          = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["puntos"]);
$descuento       = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["descuento"]);
$total_descuento = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_descuento"]);
$monto_neto      = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["monto_neto"]);
$total_iva       = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_iva"]);
$total_a_pagar   = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_a_pagar"]);
$monto_total     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["monto_total"]);

echo json_encode(array("puntos"          => $puntos,
					   "descuento"       => $descuento,
	                   "total_descuento" => $total_descuento,
	                   "monto_neto"      => $monto_neto,
	                   "total_iva"       => $total_iva,
	                   "total_a_pagar"   => $total_a_pagar,
	                   "monto_total"     => $monto_total));


?>