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

//comprobando si la procedencia existe
$query = $pdo->prepare("SELECT * FROM procedencia WHERE habilitada AND nombre_procedencia = :nombre_procedencia AND id_procedencia != :id_procedencia LIMIT 1");
$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);
$query->bindValue(":id_procedencia", $_GET["id_procedencia"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la procedencia " . $_POST["nombre_procedencia"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE procedencia SET nombre_procedencia = :nombre_procedencia WHERE habilitada AND id_procedencia = :id_procedencia");

	$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);
	$query->bindValue(":id_procedencia", $_GET["id_procedencia"], PDO::PARAM_INT);
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Procedencia editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../procedencias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>