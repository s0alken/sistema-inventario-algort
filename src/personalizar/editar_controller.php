<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

if (strlen($_POST["color_sistema"]) === 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Selecciona un color!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE personalizacion SET color_sistema = :color_sistema");

	$query->bindValue(":color_sistema", $_POST["color_sistema"], PDO::PARAM_STR);
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Sistema editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>