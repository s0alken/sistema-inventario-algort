<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "../controller/cambiar_imagen.php";

if ($_FILES["imagen_cambiar"]["error"] != UPLOAD_ERR_OK) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "Debes subir por lo menos una imagen"));
	exit();

}

try {

	cambiarImagen($_FILES["imagen_cambiar"], $_GET["id_slider"]);

    $_SESSION["sistema"]["mensaje"] = "¡Imagen cambiada exitosamente!";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>