<?php

require_once "cargar_cantidad_ventas_web_procesar.php";

$cantidad_ventas_web_procesar = cargarCantidadVentasWebProcesar();

$cantidad_ventas_web = $cantidad_ventas_web_procesar > 1 ? $cantidad_ventas_web_procesar . " ventas web" : "1 venta web";

$mensaje_ventas_web_procesar = "Tienes " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cantidad_ventas_web) . " por procesar";

echo json_encode(array("cantidad_ventas_web_procesar" => $cantidad_ventas_web_procesar, "mensaje" => $mensaje_ventas_web_procesar));

?>