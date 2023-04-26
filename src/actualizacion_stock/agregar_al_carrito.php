<?php

require_once "../controller/conexion.php";

session_start();

foreach ($_POST as $campo) {
	
	if (strlen($campo) === 0) {
		
		echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
  		exit();

	}

}

$codigo_barras = $_GET["codigo_barras"];

$cantidad     = intval($_POST["cantidad_producto"]);
$precio_costo = intval($_POST["precio_costo"]);
$precio_venta = intval($_POST["precio_venta"]);

if (strlen($codigo_barras) === 0) {

  echo json_encode(array("guardado" => false, "mensaje" => "¡Debes especificar un código de barras!"));
  exit();

}

$query = $pdo->prepare("
	SELECT
	p.id_producto,
	CONCAT(p.descripcion, ' ', m.nombre_marca) AS nombre,
	p.precio_costo,
	p.precio_venta
	FROM producto p
	INNER JOIN marca m ON m.id_marca = p.id_marca
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->execute();

$producto = $query->fetch();

if (!$producto) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Producto no encontrado!"));
	exit();

}

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

$_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras] = array("id_producto"  => $producto->id_producto,
																               "nombre"       => $producto->nombre,
																               "precio_costo" => $precio_costo,
																               "precio_venta" => $precio_venta,
																               "cantidad"     => $cantidad);

echo json_encode(array("guardado" => true, "redireccionar" => false));

?>