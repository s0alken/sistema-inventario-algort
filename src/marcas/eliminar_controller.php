<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la marca tiene productos asociados
$query = $pdo->prepare("SELECT * FROM producto WHERE habilitado AND id_marca = :id_marca LIMIT 1");

$query->bindValue(":id_marca", $_GET["id_marca"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta marca porque tiene productos asociados!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE marca SET habilitada = false WHERE id_marca = :id_marca");

	$query->bindValue(":id_marca", $_GET["id_marca"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Marca eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../marcas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>