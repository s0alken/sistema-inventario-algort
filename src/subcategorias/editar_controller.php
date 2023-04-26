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

//comprobando si la subcategoría existe
$query = $pdo->prepare("
	SELECT * FROM subcategoria
	WHERE habilitada
	AND nombre_subcategoria = :nombre_subcategoria
	AND id_categoria = :id_categoria
	AND id_subcategoria != :id_subcategoria
	LIMIT 1");

$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);
$query->bindValue(":id_subcategoria", $_GET["id_subcategoria"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false,
		                   "mensaje"  => "¡Ya existe la subcategoría " . $_POST["nombre_subcategoria"] . " dentro de la categoría seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		UPDATE subcategoria SET
		nombre_subcategoria = :nombre_subcategoria,
		id_categoria = :id_categoria
		WHERE id_subcategoria = :id_subcategoria");

	$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
	$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);
	$query->bindValue(":id_subcategoria", $_GET["id_subcategoria"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Subcategoría editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../subcategorias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>