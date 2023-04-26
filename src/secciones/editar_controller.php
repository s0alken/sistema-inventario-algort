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

//comprobando si la sección existe
$query = $pdo->prepare("
	SELECT * FROM seccion
	WHERE habilitada
	AND nombre_seccion = :nombre_seccion
	AND id_locker = :id_locker
	AND id_seccion != :id_seccion
	LIMIT 1");

$query->bindValue(":nombre_seccion", $_POST["nombre_seccion"], PDO::PARAM_STR);
$query->bindValue(":id_locker", $_POST["id_locker_nueva_seccion"], PDO::PARAM_INT);
$query->bindValue(":id_seccion", $_GET["id_seccion"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la sección " . $_POST["nombre_seccion"] . " dentro del locker seleccionado!"));
	exit();

}

try {

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE seccion SET nombre_seccion = :nombre_seccion, id_locker = :id_locker WHERE id_seccion = :id_seccion");

	$query->bindValue(":nombre_seccion", $_POST["nombre_seccion"], PDO::PARAM_STR);
	$query->bindValue(":id_locker", $_POST["id_locker_nueva_seccion"], PDO::PARAM_INT);
	$query->bindValue(":id_seccion", $_GET["id_seccion"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Sección editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../secciones/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>