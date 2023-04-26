<?php 

require_once "../controller/conexion.php";

$id_documento = $_POST["id_documento"];

$query = $pdo->prepare("
	SELECT
	dmp.id_medio_pago,
	mp.nombre_medio_pago
	FROM documento_medio_pago dmp
	INNER JOIN medio_pago mp ON mp.id_medio_pago = dmp.id_medio_pago
	WHERE dmp.id_documento = :id_documento ORDER BY mp.nombre_medio_pago");

$query->bindParam(":id_documento", $id_documento, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	$selected = $opcion->nombre_medio_pago === "efectivo" ? "selected" : "";

	echo "<option " . $selected . " value=" . $opcion->id_medio_pago . ">" . $opcion->nombre_medio_pago . "</option>";
	
}

?>