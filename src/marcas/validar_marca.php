<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_marca"])) {
	
	//comprobando si la marca existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM marca WHERE habilitada AND nombre_marca = :nombre_marca AND id_marca != :id_marca LIMIT 1");
	$query->bindValue(":nombre_marca", $_POST["nombre_marca"], PDO::PARAM_STR);
	$query->bindValue(":id_marca", $_POST["id_marca"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la marca existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM marca WHERE habilitada AND nombre_marca = :nombre_marca LIMIT 1");
	$query->bindValue(":nombre_marca", $_POST["nombre_marca"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>