<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la subcategoría tiene productos asociados
$query = $pdo->prepare("SELECT * FROM producto WHERE habilitado AND id_subcategoria = :id_subcategoria LIMIT 1");

$query->bindValue(":id_subcategoria", $_GET["id_subcategoria"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta subcategoría porque tiene productos asociados!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE subcategoria SET habilitada = false WHERE id_subcategoria = :id_subcategoria");

	$query->bindValue(":id_subcategoria", $_GET["id_subcategoria"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Subcategoría eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../subcategorias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>