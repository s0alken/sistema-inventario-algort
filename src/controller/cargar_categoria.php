<?php 

require_once "../controller/conexion.php";

$id_familia = $_POST["id_familia"];

//el modo subcategoríaa solo carga las categorías con subcategorías
$modo_subcategoria = filter_var($_POST["modo_subcategoria"], FILTER_VALIDATE_BOOLEAN);

$query1 = "SELECT * from categoria WHERE id_familia = :id_familia AND habilitada ORDER BY nombre_categoria";

$query2 = "SELECT * from categoria c
           WHERE c.id_familia = :id_familia AND c.habilitada AND EXISTS
           (SELECT 1 FROM subcategoria sc
           WHERE sc.id_categoria = c.id_categoria AND sc.habilitada)
           ORDER BY c.nombre_categoria";

$str = $modo_subcategoria ? $query2 : $query1;

$query = $pdo->prepare($str);
$query->bindParam(':id_familia', $id_familia, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_categoria . ">" . $opcion->nombre_categoria . "</option>";
	
}

?>