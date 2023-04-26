<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_familia"])) {
	
	//comprobando si la familia existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM familia WHERE habilitada AND nombre_familia = :nombre_familia AND id_familia != :id_familia LIMIT 1");
	$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);
	$query->bindValue(":id_familia", $_POST["id_familia"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la familia existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM familia WHERE habilitada AND nombre_familia = :nombre_familia LIMIT 1");
	$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>