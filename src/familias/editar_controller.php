<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando que se hayan completado los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si la familia existe
$query = $pdo->prepare("SELECT * FROM familia WHERE habilitada AND nombre_familia = :nombre_familia AND id_familia != :id_familia LIMIT 1");
$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);
$query->bindValue(":id_familia", $_GET["id_familia"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la familia " . $_POST["nombre_familia"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE familia SET nombre_familia = :nombre_familia WHERE id_familia = :id_familia");

	$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);
	$query->bindValue(":id_familia", $_GET["id_familia"], PDO::PARAM_INT);
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Familia editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../familias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>