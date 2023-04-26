<?php

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

//obteniendo id de la empresa
$query = $pdo->query("SELECT id_persona FROM empresa");

$id_persona = $query->fetch(PDO::FETCH_COLUMN); 

$rut_limpio = preg_replace("/[\.\-]/", "", $_POST["rut_empresa"]);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//si el rut está vacío se valida sólo para ocultar la alerta al usuario
if (strlen($_POST["rut_empresa"]) === 0) {

	echo json_encode(array("valido" => true, "existe" => false));
	exit();

}

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

	echo json_encode(array("valido" => false, "existe" => false));
	exit();

}

//comprobando si el rut existe
$query = $pdo->prepare("SELECT COUNT(*) FROM persona WHERE habilitada AND rut = :rut AND id_persona != :id_persona LIMIT 1");
$query->bindValue(":rut", $_POST["rut_empresa"], PDO::PARAM_STR);
$query->bindValue(":id_persona", $id_persona, PDO::PARAM_STR);
$query->execute();

echo json_encode(array("valido" => true, "existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

?>