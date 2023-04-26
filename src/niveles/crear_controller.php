<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si el nivel existe
$query = $pdo->prepare("SELECT * FROM nivel WHERE habilitado AND nombre_nivel = :nombre_nivel AND id_seccion = :id_seccion LIMIT 1");
$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el nivel " . $_POST["nombre_nivel"] . " dentro de la sección seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("INSERT INTO nivel(nombre_nivel, id_seccion) VALUES (:nombre_nivel, :id_seccion)");

	$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
	$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);

	$query->execute();

	$id_nivel = $pdo->lastInsertId();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Nivel creado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../niveles/";

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {
    	
    	$_SESSION["sistema"]["producto"]["nivel"]["id_bodega"] = $_POST["id_bodega_nuevo_nivel"];
        $_SESSION["sistema"]["producto"]["nivel"]["id_locker"] = $_POST["id_locker_nuevo_nivel"];
        $_SESSION["sistema"]["producto"]["nivel"]["id_seccion"] = $_POST["id_seccion_nuevo_nivel"];
        $_SESSION["sistema"]["producto"]["nivel"]["id_nivel"] = $id_nivel;
    	
    }

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>