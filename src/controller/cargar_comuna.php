<?php 

require_once "../controller/conexion.php";

$id_region = $_POST["id_region"];

$query = $pdo->prepare("SELECT * from comuna WHERE id_region = :id_region ORDER BY nombre_comuna");
$query->bindParam(':id_region', $id_region, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_comuna . ">" . $opcion->nombre_comuna . "</option>";
	
}

?>