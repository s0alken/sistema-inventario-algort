<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando si la categoría tiene subcategorías asociadas
$query = $pdo->prepare("SELECT * FROM subcategoria WHERE habilitada AND id_categoria = :id_categoria LIMIT 1");

$query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes eliminar esta categoría porque tiene subcategorías asociadas!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE categoria SET habilitada = false WHERE id_categoria = :id_categoria");

	$query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Categoría eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../categorias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>