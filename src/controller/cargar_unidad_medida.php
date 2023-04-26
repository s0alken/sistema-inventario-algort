<?php 

require_once "../controller/conexion.php";

$query = $pdo->query("SELECT * from unidad_medida WHERE habilitada ORDER BY nombre_unidad_medida");

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_unidad_medida . ">" . $opcion->nombre_unidad_medida . "</option>";
	
}

?>