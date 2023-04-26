<?php 

require_once "../controller/conexion.php";

$id_comuna = $_POST["id_comuna"];

$query = $pdo->prepare("SELECT * from ciudad WHERE id_comuna = :id_comuna ORDER BY nombre_ciudad");
$query->bindParam(':id_comuna', $id_comuna, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_ciudad . ">" . $opcion->nombre_ciudad . "</option>";
	
}

?>