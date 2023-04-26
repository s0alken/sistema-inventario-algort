<?php

require_once "../controller/conexion.php";

if (isset($_POST["id_subcategoria"])) {

	//comprobando si la subcategoría existe
	$query = $pdo->prepare("
		SELECT COUNT(*) FROM subcategoria
		WHERE habilitada AND nombre_subcategoria = :nombre_subcategoria
		AND id_categoria = :id_categoria
		AND id_subcategoria != :id_subcategoria
		LIMIT 1");

	$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
	$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);
	$query->bindValue(":id_subcategoria", $_POST["id_subcategoria"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

} else {

	//comprobando si la subcategoría existe
	$query = $pdo->prepare("SELECT COUNT(*) FROM subcategoria WHERE habilitada AND nombre_subcategoria = :nombre_subcategoria AND id_categoria = :id_categoria LIMIT 1");
	$query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria"], PDO::PARAM_STR);
	$query->bindValue(":id_categoria", $_POST["id_categoria_nueva_subcategoria"], PDO::PARAM_INT);
	$query->execute();

	echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

}

?>