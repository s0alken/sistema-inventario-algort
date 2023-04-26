<?php 

$precio_venta = $_POST["precio_venta"];

$puntos = strlen($precio_venta) === 0 ? 0 : round(($precio_venta * 2) / 100);

echo json_encode($puntos);

?>