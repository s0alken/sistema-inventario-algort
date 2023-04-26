<?php

require_once "conexion.php";

function cargarCantidadVentasWebProcesar(){

	global $pdo;

	$query = $pdo->query("
		SELECT COUNT(*) FROM compra c
		INNER JOIN compra_estado ce ON ce.id_compra_estado = c.id_compra_estado
		WHERE NOT ce.cierra_venta");

	$cantidad_compras_procesar = $query->fetch(PDO::FETCH_COLUMN);

	return $cantidad_compras_procesar;

}

?>