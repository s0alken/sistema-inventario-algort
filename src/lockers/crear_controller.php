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

//comprobando si el locker existe
$query = $pdo->prepare("SELECT * FROM locker WHERE habilitado AND nombre_locker = :nombre_locker AND id_bodega = :id_bodega LIMIT 1");
$query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
$query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el locker " . $_POST["nombre_locker"] . " dentro de la bodega seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

    //insertando locker
    $query = $pdo->prepare("INSERT INTO locker(nombre_locker, id_bodega) VALUES (:nombre_locker, :id_bodega)");

    $query->bindValue(":nombre_locker", $_POST["nombre_locker"], PDO::PARAM_STR);
    $query->bindValue(":id_bodega", $_POST["id_bodega_nuevo_locker"], PDO::PARAM_INT);

    $query->execute();

    $id_locker = $pdo->lastInsertId();

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {

        //insertando sección
        $query = $pdo->prepare("INSERT INTO seccion(nombre_seccion, id_locker) VALUES (:nombre_seccion, :id_locker)");

        $query->bindValue(":nombre_seccion", $_POST["nombre_seccion_nuevo_locker"], PDO::PARAM_STR);
        $query->bindValue(":id_locker", $id_locker, PDO::PARAM_INT);

        $query->execute();

        $id_seccion = $pdo->lastInsertId();

        //insertando nivel
        $query = $pdo->prepare("INSERT INTO nivel(nombre_nivel, id_seccion) VALUES (:nombre_nivel, :id_seccion)");

        $query->bindValue(":nombre_nivel", $_POST["nombre_nivel_nuevo_locker"], PDO::PARAM_STR);
        $query->bindValue(":id_seccion", $id_seccion, PDO::PARAM_INT);

        $query->execute();

        $id_nivel = $pdo->lastInsertId();
        
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_bodega"] = $_POST["id_bodega_nuevo_locker"];
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_locker"] = $id_locker;
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_seccion"] = $id_seccion;
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_nivel"] = $id_nivel;
        
    }

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Locker creado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../lockers/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>