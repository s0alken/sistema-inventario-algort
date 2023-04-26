<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (!is_array($item) && strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si el loker existe
$query = $pdo->prepare("
    SELECT * FROM locker
    WHERE habilitado
    AND nombre_locker = :nombre_locker
    AND id_bodega = :id_bodega
    AND id_locker != :id_locker
    LIMIT 1");

$query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
$query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);
$query->bindValue(":id_locker", $_GET["id_locker"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el locker " . $_POST["nombre_locker"] . " dentro de la bodega seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE locker SET nombre_locker = :nombre_locker, id_bodega = :id_bodega WHERE id_locker = :id_locker");

	$query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
	$query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);
    $query->bindValue(":id_locker", $_GET["id_locker"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Locker editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../lockers/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>