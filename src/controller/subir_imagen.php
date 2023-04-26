<?php

function subirImagen($files, $id_producto = null) {

	global $pdo;

	//comprobando formatos permitidos
	for ($i = 0; $i < count($files["tmp_name"]); $i++) {

		$file = $files["tmp_name"][$i];

		if(explode("/", mime_content_type($file))[0] != "image"){

			throw new Exception("¡Las imágenes deben ser compatibles!");

		}

	}

	for ($i = 0; $i < count($files["tmp_name"]); $i++) {

		$file = $files["tmp_name"][$i];

		//todas las imágenes van a ser jpg
		$nombre_imagen = pathinfo($files["name"][$i], PATHINFO_FILENAME) . ".jpg";

		//comprobando si ya existe la imagen en la bd
		$query = $pdo->prepare("SELECT * FROM imagen WHERE nombre_imagen = :nombre_imagen LIMIT 1");
		$query->bindValue(":nombre_imagen", $nombre_imagen, PDO::PARAM_STR);
		$query->execute();

		if ($query->fetch() || $nombre_imagen === "default.jpg") {

			throw new Exception("¡Ya existe la imagen " . $nombre_imagen . "!");

		}

		//creando imagen
		$imagen = imagecreatefromstring(file_get_contents($file));

		//subiendo imagen al directorio
		if (!imagejpeg($imagen, "../img/productos/" . $nombre_imagen)) {

			throw new Exception("¡Error al subir imagen!");

		}

		//insertando imagen
		$query = $pdo->prepare("INSERT INTO imagen(nombre_imagen) VALUES (:nombre_imagen)");
		$query->bindValue(":nombre_imagen", $nombre_imagen, PDO::PARAM_STR);
		$query->execute();

		$id_imagen = $pdo->lastInsertId();

		//enlazando imagen a producto
		if ($id_producto) {

			$query = $pdo->prepare("INSERT INTO imagen_producto(id_imagen, id_producto) VALUES (:id_imagen, :id_producto)");

			$query->bindValue(":id_imagen", $id_imagen, PDO::PARAM_INT);
			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->execute();

		}

	}

}

?>