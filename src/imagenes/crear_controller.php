<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "../controller/subir_imagen.php";

if ($_FILES["imagenes_nuevas"]["error"][0] != UPLOAD_ERR_OK) {
		
	echo json_encode(array("guardado" => false, "mensaje" => "Debes seleccionar por lo menos una imagen"));
	exit();

}

try {  

	$pdo->beginTransaction();

	subirImagen($_FILES["imagenes_nuevas"]);

	$pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Imagen subida exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../imagenes/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>