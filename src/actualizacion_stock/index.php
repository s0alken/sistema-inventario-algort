<?php

require_once "../controller/conexion.php";

session_start();

//obteniendo el medio de pago efectivo
$query = $pdo->query("SELECT * FROM medio_pago WHERE nombre_medio_pago = 'efectivo'");

$medio_pago = $query->fetch(PDO::FETCH_ASSOC);

//obteniendo el documento voucher
$query = $pdo->query("SELECT * FROM documento WHERE nombre_documento = 'voucher'");

$documento = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["actualizacion_stock"]["carrito"] = [];
$_SESSION["sistema"]["actualizacion_stock"]["proveedor"] = [];
$_SESSION["sistema"]["actualizacion_stock"]["medio_pago"] = $medio_pago;
$_SESSION["sistema"]["actualizacion_stock"]["documento"] = $documento;
$_SESSION["sistema"]["actualizacion_stock"]["n_documento_compra"] = "";
$_SESSION["sistema"]["actualizacion_stock"]["observaciones"] = "";

header("Location: crear.php");

exit();

?>