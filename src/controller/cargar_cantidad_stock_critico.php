<?php

require_once "conexion.php";

function cargarCantidadStockCritico(){

	global $pdo;

	$query = $pdo->prepare("
		SELECT COUNT(*) FROM producto p
		INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
		WHERE p.habilitado
		AND sp.stock <= p.stock_critico
		AND sp.id_sucursal = :id_sucursal");

	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
	$query->execute();

	$cantidad_stock_critico = $query->fetch(PDO::FETCH_COLUMN);

	return $cantidad_stock_critico;

}

?>