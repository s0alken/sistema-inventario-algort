<?php

require_once "../controller/conexion.php";

session_start();

//obteniendo el medio de pago efectivo
$query = $pdo->query("SELECT * FROM medio_pago WHERE nombre_medio_pago = 'efectivo'");

$medio_pago = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["cotizacion"]["carrito"]              = [];
$_SESSION["sistema"]["cotizacion"]["cliente"]              = [];
$_SESSION["sistema"]["cotizacion"]["medio_pago"]           = $medio_pago;
$_SESSION["sistema"]["cotizacion"]["descuento_porcentaje"] = 0;
$_SESSION["sistema"]["cotizacion"]["descuento_dinero"]     = 0;
$_SESSION["sistema"]["cotizacion"]["monto_neto"]           = 0;
$_SESSION["sistema"]["cotizacion"]["total_iva"]            = 0;
$_SESSION["sistema"]["cotizacion"]["total_a_pagar"]        = 0;
$_SESSION["sistema"]["cotizacion"]["monto_total"]          = 0;
$_SESSION["sistema"]["cotizacion"]["observaciones"]        = "";
$_SESSION["sistema"]["cotizacion"]["puntos"]               = 0;

header("Location: crear.php");

exit();

?>