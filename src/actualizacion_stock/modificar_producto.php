<?php

require_once "../controller/conexion.php";

session_start();

$codigo_barras = $_GET["codigo_barras"];

$producto = $_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras];

$cantidad     = strlen($_POST["cantidad_producto"]) === 0 ? $producto["cantidad"]     : intval($_POST["cantidad_producto"]);
$precio_costo = strlen($_POST["precio_costo"])      === 0 ? $producto["precio_costo"] : intval($_POST["precio_costo"]);
$precio_venta = strlen($_POST["precio_venta"])      === 0 ? $producto["precio_venta"] : intval($_POST["precio_venta"]);

//comprobando que la cantidad no sea menor a cero
if ($cantidad < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cantidad no debe ser menor a cero!"));
	exit();

}

//comprobando que los precios no sean menores a cero
if ($precio_costo < 0 || $precio_venta < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Los precios no deben ser menores a cero!"));
	exit();

}

//comprobando que el precio de venta no sea menor al precio de costo
if ($precio_costo > $precio_venta) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El precio de venta no debe ser menor al precio de costo!"));
	exit();

}

$_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras]["cantidad"]     = $cantidad;
$_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras]["precio_costo"] = $precio_costo;
$_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras]["precio_venta"] = $precio_venta;

echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

?>