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

$producto = $_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras];

$stock_promocion = strlen($_POST["stock_promocion"]) === 0 ? $producto["stock_promocion"] : intval($_POST["stock_promocion"]);

if ($stock_promocion > $stock) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cantidad excede el stock disponible!"));
	exit();

}

$_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras]["stock_promocion"] = $stock_promocion;

configurarMontos();

echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

?>