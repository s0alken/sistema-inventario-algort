<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_operador_logistico"])) {
	
	//comprobando el operador logístico existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM operador_logistico
		WHERE NOT eliminado
		AND nombre_operador_logistico = :nombre_operador_logistico
		AND id_operador_logistico != :id_operador_logistico
		LIMIT 1");

	$query->bindValue(":nombre_operador_logistico", $_POST["nombre_operador_logistico"], PDO::PARAM_STR);
	$query->bindValue(":id_operador_logistico", $_POST["id_operador_logistico"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando el operador logístico existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM operador_logistico WHERE NOT eliminado AND nombre_operador_logistico = :nombre_operador_logistico LIMIT 1");
	$query->bindValue(":nombre_operador_logistico", $_POST["nombre_operador_logistico"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>