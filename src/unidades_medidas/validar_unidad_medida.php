<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_unidad_medida"])) {
	
	//comprobando si la unidad de medida existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM unidad_medida
		WHERE habilitada
		AND nombre_unidad_medida = :nombre_unidad_medida
		AND id_unidad_medida != :id_unidad_medida
		LIMIT 1");

	$query->bindValue(":nombre_unidad_medida", $_POST["nombre_unidad_medida"], PDO::PARAM_STR);
	$query->bindValue(":id_unidad_medida", $_POST["id_unidad_medida"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la unidad de medida existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM unidad_medida WHERE habilitada AND nombre_unidad_medida = :nombre_unidad_medida LIMIT 1");
	$query->bindValue(":nombre_unidad_medida", $_POST["nombre_unidad_medida"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>