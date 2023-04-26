<?php

require_once "../controller/conexion.php";

session_start();

if (isset($_POST["id_bodega"])) {
	
	//comprobando si la bodega existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM bodega
		WHERE habilitada
		AND nombre_bodega = :nombre_bodega
		AND id_sucursal = :id_sucursal
		AND id_bodega != :id_bodega
		LIMIT 1");

	$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
	$query->bindValue(":id_bodega", $_POST["id_bodega"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la bodega existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM bodega
		WHERE habilitada
		AND nombre_bodega = :nombre_bodega
		AND id_sucursal = :id_sucursal
		LIMIT 1");

	$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}



?>