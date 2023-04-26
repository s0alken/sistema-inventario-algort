<?php

function cambiarImagen($file, $id_slider) {

	global $pdo;

	//comprobando formatos permitidos
	if(explode("/", mime_content_type($file["tmp_name"]))[0] != "image"){

		throw new Exception("¡La imagen debe ser compatible!");

	}

	//obteniendo nombre imagen
	$query = $pdo->prepare("SELECT imagen FROM slider WHERE id_slider = :id_slider");
	$query->bindValue(":id_slider", $id_slider, PDO::PARAM_INT);
	$query->execute();

	$nombre_imagen = $query->fetch(PDO::FETCH_COLUMN);

	//removiendo imagen original del directorio
	if (!unlink("../img/slider/" . $nombre_imagen)) {

		throw new Exception("¡Error al eliminar imagen!");

	}

	//creando imagen
	$imagen = imagecreatefromstring(file_get_contents($file["tmp_name"]));

	//subiendo imagen al directorio
	if (!imagejpeg($imagen, "../img/slider/" . $nombre_imagen)) {

		throw new Exception("¡Error al subir imagen!");

	}

}

?>