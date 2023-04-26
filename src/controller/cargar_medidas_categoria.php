<?php

require_once "conexion.php";

function cargarMedidasCategoria($id_categoria) {

	global $pdo;

	$query = $pdo->prepare("
		SELECT * FROM medida m
		INNER JOIN unidad_medida um ON um.id_unidad_medida = m.id_unidad_medida
		WHERE m.id_categoria = :id_categoria
		ORDER BY m.nombre_medida");
	
	$query->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
    $query->execute();

	return $query->fetchAll();

}

?>