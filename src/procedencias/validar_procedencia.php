<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_procedencia"])) {
	
	//comprobando si la procedencia existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM procedencia WHERE habilitada AND nombre_procedencia = :nombre_procedencia AND id_procedencia != :id_procedencia LIMIT 1");
	$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);
	$query->bindValue(":id_procedencia", $_POST["id_procedencia"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la procedencia existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM procedencia WHERE habilitada AND nombre_procedencia = :nombre_procedencia LIMIT 1");
	$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>