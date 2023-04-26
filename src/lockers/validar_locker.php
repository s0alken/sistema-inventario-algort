<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_locker"])) {
	
	//comprobando si el locker existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM locker
		WHERE habilitado AND nombre_locker = :nombre_locker
		AND id_bodega = :id_bodega
		AND id_locker != :id_locker
		LIMIT 1");

	$query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
	$query->bindValue(":id_locker", $_POST["id_locker"], PDO::PARAM_INT);
	$query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la locker existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM locker WHERE habilitado AND nombre_locker = :nombre_locker AND id_bodega = :id_bodega LIMIT 1");
	$query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
	$query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>