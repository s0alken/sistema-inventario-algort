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
$query = $pdo->prepare("SELECT * FROM subcategoria WHERE habilitada AND nombre_subcategoria = :nombre_subcategoria AND id_categoria = :id_categoria LIMIT 1");
$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false,
                           "mensaje"  => "¡Ya existe la subcategoría " . $_POST["nombre_subcategoria"] . " dentro de la categoría seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("INSERT INTO subcategoria(nombre_subcategoria, id_categoria) VALUES (:nombre_subcategoria, :id_categoria)");

	$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
	$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);

	$query->execute();

	$id_subcategoria = $pdo->lastInsertId();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Subcategoría creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../subcategorias/";

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {
    	
    	$_SESSION["sistema"]["producto"]["id_familia"] = $_POST["id_familia_nueva_subcategoria"];
        $_SESSION["sistema"]["producto"]["id_categoria"] = $_POST["id_categoria_nueva_subcategoria"];
        $_SESSION["sistema"]["producto"]["id_subcategoria"] = $id_subcategoria;
        $_SESSION["sistema"]["producto"]["medidas"] = [];
    	
    }

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>