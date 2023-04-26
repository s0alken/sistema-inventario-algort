<?php

require_once "../controller/conexion.php";
require_once "configurar_montos.php";

session_start();

$codigo_barras = $_GET["codigo_barras"];

$query = $pdo->prepare("
	SELECT sp.stock FROM stock_producto sp
	INNER JOIN producto p ON p.id_producto = sp.id_producto
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras
	AND sp.id_sucursal = :id_sucursal");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$stock = $query->fetch(PDO::FETCH_COLUMN);

$producto = $_SESSION["sistema"]["venta"]["carrito"][$codigo_barras];

$cantidad = strlen($_POST["cantidad_producto"]) === 0 ? $producto["cantidad"] : intval($_POST["cantidad_producto"]);

$descuento_porcentaje = $_POST["descuento_porcentaje_producto"];

$descuento_porcentaje = strlen($descuento_porcentaje) === 0 ? $producto["descuento_porcentaje"] : intval($descuento_porcentaje);

$descuento_dinero = $_POST["descuento_dinero_producto"];

$descuento_dinero = strlen($descuento_dinero) === 0 ? $producto["descuento_dinero"] : intval($descuento_dinero);

if ($cantidad > $stock) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cantidad excede el stock disponible!"));
	exit();

}

if ($descuento_porcentaje > 100 || $descuento_porcentaje < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El porcentaje de descuento debe ser entre 0 y 100!"));
	exit();

}

if ($descuento_dinero < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El descuento en dinero debe ser cero o mayor!"));
	exit();

}

$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["cantidad"] = $cantidad;
$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["descuento_porcentaje"] = $descuento_porcentaje;
$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["descuento_dinero"] = $descuento_dinero;

configurarMontos();

echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

?>