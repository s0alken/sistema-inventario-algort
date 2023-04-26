<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_categoria"])) {

	//comprobando si la categoría existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM categoria
		WHERE habilitada AND nombre_categoria = :nombre_categoria
		AND id_familia = :id_familia
		AND id_categoria != :id_categoria
		LIMIT 1");

	$query->bindValue(":nombre_categoria", $_POST["nombre_categoria"], PDO::PARAM_STR);
	$query->bindValue(":id_familia", $_POST["id_familia_nueva_categoria"], PDO::PARAM_INT);
	$query->bindValue(":id_categoria", $_POST["id_categoria"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la subcategoría existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM categoria WHERE habilitada AND nombre_categoria = :nombre_categoria AND id_familia = :id_familia LIMIT 1");
	$query->bindValue(":nombre_categoria", $_POST["nombre_categoria"], PDO::PARAM_STR);
	$query->bindValue(":id_familia", $_POST["id_familia_nueva_categoria"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>