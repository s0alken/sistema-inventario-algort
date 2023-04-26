<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "configurar_stock.php";

foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si la sucursal existe
$query = $pdo->prepare("SELECT * FROM sucursal WHERE habilitada AND nombre_sucursal = :nombre_sucursal AND id_ciudad = :id_ciudad LIMIT 1");

$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la sucursal " . $_POST["nombre_sucursal"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		INSERT INTO sucursal(
		nombre_sucursal,
		direccion,
		n_direccion,
		id_ciudad)
		VALUES (
		:nombre_sucursal,
		:direccion,
		:n_direccion,
		:id_ciudad)");

	$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
	$query->bindValue(":direccion", $_POST["direccion"], PDO::PARAM_STR);
	$query->bindValue(":n_direccion", $_POST["n_direccion"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);

	$query->execute();

	$id_sucursal = $pdo->lastInsertId();

	configurarStock($id_sucursal);

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Sucursal creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../sucursales/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>