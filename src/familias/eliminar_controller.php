<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la familia tiene categorías asociadas
$query = $pdo->prepare("SELECT * FROM categoria WHERE habilitada AND id_familia = :id_familia LIMIT 1");

$query->bindValue(":id_familia", $_GET["id_familia"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta familia porque tiene categorías asociadas!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE familia SET habilitada = false WHERE id_familia = :id_familia");

	$query->bindValue(":id_familia", $_GET["id_familia"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Familia eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../familias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>