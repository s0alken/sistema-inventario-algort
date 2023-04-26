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

//comprobando si la unidad de medida existe
$query = $pdo->prepare("SELECT * FROM unidad_medida WHERE habilitada AND nombre_unidad_medida = :nombre_unidad_medida LIMIT 1");
$query->bindValue(":nombre_unidad_medida", $_POST["nombre_unidad_medida"], PDO::PARAM_STR);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la unidad de medida " . $_POST["nombre_unidad_medida"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		INSERT INTO unidad_medida(
		nombre_unidad_medida,
		abreviacion_unidad_medida) VALUES (
		:nombre_unidad_medida,
		:abreviacion_unidad_medida)");

	$query->bindValue(":nombre_unidad_medida", $_POST["nombre_unidad_medida"], PDO::PARAM_STR);
	$query->bindValue(":abreviacion_unidad_medida", $_POST["abreviacion_unidad_medida"], PDO::PARAM_STR);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Unidad de medida creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../unidades_medidas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>