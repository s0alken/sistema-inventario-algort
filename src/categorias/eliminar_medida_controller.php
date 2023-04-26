<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

try {  

	$pdo->beginTransaction();

	//eliminando los registros de la medida asociada al producto
	$query = $pdo->prepare("DELETE FROM medida_producto WHERE id_medida = :id_medida");
	$query->bindValue(":id_medida", $_GET["id_medida"], PDO::PARAM_INT);
	$query->execute();

	//eliminando la medida
    $query = $pdo->prepare("DELETE FROM medida WHERE id_medida = :id_medida");
    $query->bindValue(":id_medida", $_GET["id_medida"], PDO::PARAM_INT);
    $query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Medida eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../categorias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>