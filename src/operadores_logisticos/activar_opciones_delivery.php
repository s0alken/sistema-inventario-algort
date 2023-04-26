<?php

require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
	SELECT nombre_tipo_operador_logistico
	FROM tipo_operador_logistico
	WHERE id_tipo_operador_logistico = :id_tipo_operador_logistico");

$query->bindValue(":id_tipo_operador_logistico", $_POST["id_tipo_operador_logistico"], PDO::PARAM_INT);
$query->execute();

$nombre_tipo_operador_logistico = $query->fetch(PDO::FETCH_COLUMN);

$opciones_delivery_class = $nombre_tipo_operador_logistico === "delivery" ? "d-block" : "d-none";

echo json_encode($opciones_delivery_class);

?>