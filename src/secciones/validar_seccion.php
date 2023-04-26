<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_seccion"])) {
	
	//comprobando si el sección existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM seccion
		WHERE habilitada AND nombre_seccion = :nombre_seccion
		AND id_locker = :id_locker
		AND id_seccion != :id_seccion
		LIMIT 1");

	$query->bindValue(":nombre_seccion", $_POST["nombre_seccion"], PDO::PARAM_STR);
	$query->bindValue(":id_locker", $_POST["id_locker_nueva_seccion"], PDO::PARAM_INT);
	$query->bindValue(":id_seccion", $_POST["id_seccion"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la sección existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM seccion WHERE habilitada AND nombre_seccion = :nombre_seccion AND id_locker = :id_locker LIMIT 1");
	$query->bindValue(":nombre_seccion", $_POST["nombre_seccion"], PDO::PARAM_STR);
	$query->bindValue(":id_locker", $_POST["id_locker_nueva_seccion"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>