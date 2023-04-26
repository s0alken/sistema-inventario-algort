<?php 

require_once "../controller/conexion.php";

$id_locker = $_POST["id_locker"];

//el modo nivel solo carga los lockers con niveles
$modo_nivel = filter_var($_POST["modo_nivel"], FILTER_VALIDATE_BOOLEAN);

$query1 = "SELECT * FROM seccion WHERE id_locker = :id_locker AND habilitada ORDER BY nombre_seccion";

//si modo_nivel es true se cargan solo las secciones que tienen niveles
$query2 = "SELECT * FROM seccion s
          WHERE s.id_locker = :id_locker AND s.habilitada AND EXISTS
          (SELECT 1 FROM nivel n
          WHERE s.id_seccion = n.id_seccion
          AND n.habilitado)
          ORDER BY s.nombre_seccion";

$str = $modo_nivel ? $query2 : $query1;

$query = $pdo->prepare($str);
$query->bindParam(":id_locker", $id_locker, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_seccion . ">" . $opcion->nombre_seccion . "</option>";
	
}

?>