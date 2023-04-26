<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//obteniendo nombre de la imagen
$query = $pdo->prepare("SELECT nombre_imagen FROM imagen WHERE id_imagen = :id_imagen");

$query->bindValue(":id_imagen", $_GET["id_imagen"], PDO::PARAM_INT);
$query->execute();

$nombre_imagen = $query->fetch(PDO::FETCH_COLUMN);

try {  

	$pdo->beginTransaction();

	//eliminando imagen de todos los productos asociados
	$query = $pdo->prepare("DELETE FROM imagen_producto WHERE id_imagen = :id_imagen");
	$query->bindValue(":id_imagen", $_GET["id_imagen"], PDO::PARAM_INT);
	$query->execute();

	//eliminando imagen de base de datos
	$query = $pdo->prepare("DELETE FROM imagen WHERE id_imagen = :id_imagen");
	$query->bindValue(":id_imagen", $_GET["id_imagen"], PDO::PARAM_INT);
	$query->execute();

	//eliminando imagen del directorio
	if (!unlink("../img/productos/" . $nombre_imagen)) {

		throw new Exception("¡Error al eliminar imagen!");

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Imagen eliminada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../imagenes/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>