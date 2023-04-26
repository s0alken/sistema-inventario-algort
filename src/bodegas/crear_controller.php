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

//comprobando si la bodega existe
$query = $pdo->prepare("
    SELECT * FROM bodega
    WHERE habilitada
    AND nombre_bodega = :nombre_bodega
    AND id_sucursal = :id_sucursal
    LIMIT 1");

$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la bodega " . $_POST["nombre_bodega"] . " en esta sucursal!"));
	exit();

}

try {  

	$pdo->beginTransaction();

    //insertando bodega
	$query = $pdo->prepare("INSERT INTO bodega(nombre_bodega, id_sucursal) VALUES (:nombre_bodega, :id_sucursal)");

	$query->bindValue(":nombre_bodega", $_POST["nombre_bodega"], PDO::PARAM_STR);
    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

	$query->execute();

	$id_bodega = $pdo->lastInsertId();

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {

        //insertando locker
        $query = $pdo->prepare("INSERT INTO locker(nombre_locker, id_bodega) VALUES (:nombre_locker, :id_bodega)");

        $query->bindValue(":nombre_locker", $_POST["nombre_locker_nueva_bodega"], PDO::PARAM_STR);
        $query->bindValue(":id_bodega", $id_bodega, PDO::PARAM_INT);

        $query->execute();

        $id_locker = $pdo->lastInsertId();

        //insertando sección
        $query = $pdo->prepare("INSERT INTO seccion(nombre_seccion, id_locker) VALUES (:nombre_seccion, :id_locker)");

        $query->bindValue(":nombre_seccion", $_POST["nombre_seccion_nueva_bodega"], PDO::PARAM_STR);
        $query->bindValue(":id_locker", $id_locker, PDO::PARAM_INT);

        $query->execute();

        $id_seccion = $pdo->lastInsertId();

        //insertando nivel
        $query = $pdo->prepare("INSERT INTO nivel(nombre_nivel, id_seccion) VALUES (:nombre_nivel, :id_seccion)");

        $query->bindValue(":nombre_nivel", $_POST["nombre_nivel_nueva_bodega"], PDO::PARAM_STR);
        $query->bindValue(":id_seccion", $id_seccion, PDO::PARAM_INT);

        $query->execute();

        $id_nivel = $pdo->lastInsertId();
        
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_bodega"] = $id_bodega;
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_locker"] = $id_locker;
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_seccion"] = $id_seccion;
        $_SESSION["sistema"]["producto"]["ubicacion"]["id_nivel"] = $id_nivel;
        
    }

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Bodega creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../bodegas/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>