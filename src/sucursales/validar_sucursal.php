<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_sucursal"])) {
	
	//comprobando si la sucursal existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM sucursal
		WHERE habilitada
		AND nombre_sucursal = :nombre_sucursal
		AND id_ciudad = :id_ciudad
		AND id_sucursal != :id_sucursal
		LIMIT 1");

	$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);
	$query->bindValue(":id_sucursal", $_POST["id_sucursal"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la sucursal existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM sucursal
		WHERE habilitada
		AND nombre_sucursal = :nombre_sucursal
		AND id_ciudad = :id_ciudad
		LIMIT 1");

	$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>