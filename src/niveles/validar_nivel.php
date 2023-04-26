<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_nivel"])) {
	
	//comprobando si el nivel existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM nivel
		WHERE habilitado AND nombre_nivel = :nombre_nivel
		AND id_seccion = :id_seccion
		AND id_nivel != :id_nivel
		LIMIT 1");

	$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
	$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);
	$query->bindValue(":id_nivel", $_POST["id_nivel"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la nivel existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM nivel WHERE habilitado AND nombre_nivel = :nombre_nivel AND id_seccion = :id_seccion LIMIT 1");
	$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
	$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>