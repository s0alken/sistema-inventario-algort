<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la unidad de medida tiene medidas asociadas
$query = $pdo->prepare("SELECT * FROM medida WHERE id_unidad_medida = :id_unidad_medida LIMIT 1");

$query->bindValue(":id_unidad_medida", $_GET["id_unidad_medida"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta unidad de medida porque tiene medidas asociadas!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE unidad_medida SET habilitada = false WHERE id_unidad_medida = :id_unidad_medida");

	$query->bindValue(":id_unidad_medida", $_GET["id_unidad_medida"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "Unidad de medida eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../unidades_medidas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>