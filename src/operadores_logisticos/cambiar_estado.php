<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

$id_operador_logistico = $_GET["id_operador_logistico"];

$query = $pdo->prepare("SELECT habilitado FROM operador_logistico WHERE id_operador_logistico = :id_operador_logistico");

$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);

$query->execute();

$estado = !$query->fetch(PDO::FETCH_COLUMN);

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE operador_logistico SET habilitado = :estado WHERE id_operador_logistico = :id_operador_logistico");

	$query->bindValue(":estado", $estado, PDO::PARAM_INT);
	$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Operador logístico editado exitosamente!";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>