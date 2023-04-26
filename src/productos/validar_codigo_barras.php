<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_producto"])) {
	
	//comprobando si el código de barras está en uso
	$query = $pdo->prepare("
		SELECT
		COUNT(*)
		FROM producto
		WHERE habilitado
		AND codigo_barras = :codigo_barras
		AND id_producto != :id_producto
		LIMIT 1");
	
	$query->bindValue(":codigo_barras", $_POST["codigo_barras"], PDO::PARAM_STR);
	$query->bindValue(":id_producto", $_POST["id_producto"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si el código de barras está en uso
	$query = $pdo->prepare("SELECT COUNT(*) FROM producto WHERE habilitado AND codigo_barras = :codigo_barras LIMIT 1");
	$query->bindValue(":codigo_barras", $_POST["codigo_barras"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>