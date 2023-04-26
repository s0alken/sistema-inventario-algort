<?php 

require_once "../controller/conexion.php";

$id_bodega = $_POST["id_bodega"];

//el modo nivel solo carga los lockers con niveles
$modo_nivel = filter_var($_POST["modo_nivel"], FILTER_VALIDATE_BOOLEAN);
//el modo sección solo carga los lockers con secciones
$modo_seccion = filter_var($_POST["modo_seccion"], FILTER_VALIDATE_BOOLEAN);

$query1 = "SELECT * FROM locker WHERE id_bodega = :id_bodega AND habilitado ORDER BY nombre_locker";

//si modo_nivel es true se cargan solo los lockers que tienen niveles
$query2 = "SELECT * FROM locker l
          WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
          (SELECT 1 FROM nivel n
          INNER JOIN seccion s ON s.id_seccion = n.id_seccion
          WHERE l.id_locker = s.id_locker
          AND n.habilitado)
          ORDER BY l.nombre_locker";

//si modo_seccion es true se cargan solo los locker que tienen seccion
$query3 = "SELECT * FROM locker l
          WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
          (SELECT 1 FROM seccion s
          WHERE l.id_locker = s.id_locker
          AND s.habilitada)
          ORDER BY l.nombre_locker";

$str = $modo_nivel ? $query2 : ($modo_seccion ? $query3 : $query1);

$query = $pdo->prepare($str);
$query->bindParam(":id_bodega", $id_bodega, PDO::PARAM_INT);
$query->execute();

$opciones = $query->fetchAll();

foreach($opciones as $opcion){

	echo "<option value=" . $opcion->id_locker . ">" . $opcion->nombre_locker . "</option>";
	
}

?>