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
$query = $pdo->prepare("SELECT * FROM procedencia WHERE habilitada AND nombre_procedencia = :nombre_procedencia LIMIT 1");
$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la procedencia " . $_POST["nombre_procedencia"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("INSERT INTO procedencia(nombre_procedencia) VALUES (:nombre_procedencia)");

	$query->bindValue(":nombre_procedencia", $_POST["nombre_procedencia"], PDO::PARAM_STR);

	$query->execute();

    $id_procedencia = $pdo->lastInsertId();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Procedencia creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../procedencias/";

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {
    	
    	$_SESSION["sistema"]["producto"]["id_procedencia"] = $id_procedencia;
    	
    }

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>