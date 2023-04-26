<?php

require_once "../controller/conexion.php";
//require_once "configurar_montos.php";

session_start();

$codigo_barras = $_GET["codigo_barras"];

$query = $pdo->prepare("
	SELECT stock FROM stock_producto sp
	INNER JOIN producto p ON p.id_producto = sp.id_producto
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras
	AND sp.id_sucursal = :id_sucursal");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["traspaso"]["id_sucursal_origen"], PDO::PARAM_INT);
$query->execute();

$stock = $query->fetch(PDO::FETCH_COLUMN);

$cantidad = strlen($_POST["cantidad_producto"]) === 0 ? $_SESSION["sistema"]["traspaso"]["productos"][$codigo_barras]["cantidad"] : intval($_POST["cantidad_producto"]);

if ($cantidad > $stock) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cantidad excede el stock disponible!"));
	exit();

}

$_SESSION["sistema"]["traspaso"]["productos"][$codigo_barras]["cantidad"] = $cantidad;

echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

?>