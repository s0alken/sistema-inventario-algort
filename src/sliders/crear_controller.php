<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando que se hayan completado los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

$file = $_FILES["imagen_nueva"];

if ($file["error"] != UPLOAD_ERR_OK) {
		
	echo json_encode(array("guardado" => false, "mensaje" => "Debes seleccionar una imagen"));
	exit();

}

try {

	$pdo->beginTransaction();

	//comprobando formatos permitidos
	if(explode("/", mime_content_type($file["tmp_name"]))[0] != "image"){

		throw new Exception("¡La imagen debe ser compatible!");

	}

	//la imagen va a ser jpg
	$nombre_imagen = uniqid("slider_") . ".jpg";

	//comprobando si ya existe la imagen en la bd
	$query = $pdo->prepare("SELECT * FROM slider WHERE imagen = :imagen LIMIT 1");
	$query->bindValue(":imagen", $nombre_imagen, PDO::PARAM_STR);
	$query->execute();

	if ($query->fetch() || $nombre_imagen === "slider_default.jpg") {

		throw new Exception("¡Ya existe la imagen " . $nombre_imagen . "!");

	}

	//creando imagen
	$imagen = imagecreatefromstring(file_get_contents($file["tmp_name"]));

	//insertando slider
	$query = $pdo->prepare("
		INSERT INTO slider(
		imagen,
		encabezado,
		encabezado_color,
		subtitulo,
		subtitulo_color) VALUES (
		:imagen,
		:encabezado,
		:encabezado_color,
		:subtitulo,
		:subtitulo_color)");

	$query->bindValue(":imagen", $nombre_imagen, PDO::PARAM_STR);
	$query->bindValue(":encabezado", $_POST["encabezado"], PDO::PARAM_STR);
	$query->bindValue(":encabezado_color", $_POST["encabezado_color"], PDO::PARAM_STR);
	$query->bindValue(":subtitulo", $_POST["subtitulo"], PDO::PARAM_STR);
	$query->bindValue(":subtitulo_color", $_POST["subtitulo_color"], PDO::PARAM_STR);
	$query->execute();

	//subiendo imagen al directorio
	if (!imagejpeg($imagen, "../img/slider/" . $nombre_imagen)) {

		throw new Exception("¡Error al subir imagen!");

	}

	$pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Slider creado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../sliders/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>