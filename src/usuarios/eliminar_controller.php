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

	$query = $pdo->prepare("UPDATE usuario SET habilitado = false WHERE id_usuario = :id_usuario");

	$query->bindValue(":id_usuario", $_GET["id_usuario"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Usuario eliminado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../usuarios/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>