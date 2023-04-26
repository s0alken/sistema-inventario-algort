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

	$query = $pdo->prepare("UPDATE producto SET habilitado = false WHERE id_producto = :id_producto");

	$query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Producto eliminado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../productos/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>