<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

if (intval($_GET["id_sucursal"]) === $_SESSION["sistema"]["sucursal"]->id_sucursal) {

	echo json_encode(array("guardado" => false, "mensaje" => "Para eliminar esta sucursal inicia sesión en una sucursal diferente"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE sucursal SET habilitada = false WHERE id_sucursal = :id_sucursal");

	$query->bindValue(":id_sucursal", $_GET["id_sucursal"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Sucursal eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../sucursales/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "error" => $e->getMessage()));

}

?>