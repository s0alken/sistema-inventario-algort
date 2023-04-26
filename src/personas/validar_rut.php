<?php

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

$rut_limpio = preg_replace("/[\.\-]/", "", $_POST["rut"]);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//si el rut está vacío se valida sólo para ocultar la alerta al usuario
if (strlen($_POST["rut"]) === 0) {

	echo json_encode(array("valido" => true, "existe" => false));
	exit();

}

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

	echo json_encode(array("valido" => false, "existe" => false));
	exit();

}

if (isset($_POST["id_persona"])) {

	//comprobando si el rut existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM persona WHERE habilitada AND rut = :rut AND id_persona != :id_persona LIMIT 1");
	$query->bindValue(":rut", $_POST["rut"], PDO::PARAM_STR);
	$query->bindValue(":id_persona", $_POST["id_persona"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("valido" => true, "existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si el rut existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM persona WHERE habilitada AND rut = :rut LIMIT 1");
	$query->bindValue(":rut", $_POST["rut"], PDO::PARAM_STR);
	$query->execute();

	echo json_encode(array("valido" => true, "existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>