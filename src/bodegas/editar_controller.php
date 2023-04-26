<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si la bodega existe
$query = $pdo->prepare("
	SELECT * FROM bodega
	WHERE habilitada
	AND nombre_bodega = :nombre_bodega
	AND id_sucursal = :id_sucursal
	AND id_bodega != :id_bodega
	LIMIT 1");

$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->bindValue(":id_bodega", $_GET["id_bodega"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la bodega " . $_POST["nombre_bodega"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE bodega SET nombre_bodega = :nombre_bodega WHERE habilitada AND id_bodega = :id_bodega");

	$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
	$query->bindValue(":id_bodega", $_GET["id_bodega"], PDO::PARAM_INT);
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Bodega editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../bodegas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>