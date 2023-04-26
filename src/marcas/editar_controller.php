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

//comprobando si la marca existe
$query = $pdo->prepare("SELECT * FROM marca WHERE habilitada AND nombre_marca = :nombre_marca AND id_marca != :id_marca LIMIT 1");
$query->bindValue(":nombre_marca", $_POST["nombre_marca"], PDO::PARAM_STR);
$query->bindValue(":id_marca", $_GET["id_marca"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la marca " . $_POST["nombre_marca"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE marca SET nombre_marca = :nombre_marca WHERE habilitada AND id_marca = :id_marca");

	$query->bindValue(":nombre_marca", $_POST["nombre_marca"], PDO::PARAM_STR);
	$query->bindValue(":id_marca", $_GET["id_marca"], PDO::PARAM_INT);
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Marca editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../marcas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>