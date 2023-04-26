<?php

require_once "../controller/conexion.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

$query = $pdo->prepare("
	SELECT stock FROM stock_producto sp
	INNER JOIN producto p ON p.id_producto = sp.id_producto
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras
	AND sp.id_sucursal = :id_sucursal");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["traspaso"]["id_sucursal_origen"], PDO::PARAM_INT);
$query->execute();

$max = $query->fetch(PDO::FETCH_COLUMN);

$producto = $_SESSION["sistema"]["traspaso"]["productos"][$codigo_barras];

echo json_encode(array("cantidad" => $producto["cantidad"], "max" => $max));

?>