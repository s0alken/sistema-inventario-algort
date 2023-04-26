<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la procedencia tiene productos asociados
$query = $pdo->prepare("SELECT * FROM producto WHERE habilitado AND id_procedencia = :id_procedencia LIMIT 1");

$query->bindValue(":id_procedencia", $_GET["id_procedencia"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta procedencia porque tiene productos asociados!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE procedencia SET habilitada = false WHERE id_procedencia = :id_procedencia");

	$query->bindValue(":id_procedencia", $_GET["id_procedencia"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Procedencia eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../procedencias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>