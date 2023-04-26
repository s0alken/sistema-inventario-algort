<?php 

require_once "../controller/conexion.php";

$id_seccion = $_POST["id_seccion"];

$query = $pdo->prepare("SELECT * FROM nivel WHERE id_seccion = :id_seccion AND habilitado ORDER BY nombre_nivel");
$query->bindParam(":id_seccion", $id_seccion, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_nivel . ">" . $opcion->nombre_nivel . "</option>";
	
}

?>