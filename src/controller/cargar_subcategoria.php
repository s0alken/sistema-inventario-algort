<?php 

require_once "../controller/conexion.php";

$id_categoria = $_POST["id_categoria"];

$query = $pdo->prepare("SELECT * from subcategoria WHERE id_categoria = :id_categoria AND habilitada ORDER BY nombre_subcategoria");
$query->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_subcategoria . ">" . $opcion->nombre_subcategoria . "</option>";
	
}

?>