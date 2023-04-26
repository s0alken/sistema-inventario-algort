<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "../controller/validar_largo.php";

date_default_timezone_set("America/Santiago");

$traspaso = $_SESSION["sistema"]["traspaso"];
$productos = $traspaso["productos"];

//comprobando que sucursal de origen y destino sean distintas
if ($traspaso["id_sucursal_origen"] === $traspaso["id_sucursal_destino"]) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las sucursales de origen y destino deben ser diferentes!"));
	exit();

}

//comprobando si hay productos en el traspaso
if (empty($productos)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes agregar por lo menos un producto!"));
  	exit();

}

//comprobando que las observaciones no excedan los 300 caracteres
if (largoExcedido($traspaso["observaciones"], 300)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las observaciones no deben exceder los 300 caracteres!"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	//insertando traspaso
	$query = $pdo->prepare("
		INSERT INTO traspaso(
		fecha,
		id_sucursal_origen,
		id_sucursal_destino,
		observaciones) VALUES (
		:fecha,
		:id_sucursal_origen,
		:id_sucursal_destino,
		:observaciones)");

	$query->bindValue(":fecha", date("Y-m-d H:i:s"), PDO::PARAM_STR);
	$query->bindValue(":id_sucursal_origen", $traspaso["id_sucursal_origen"], PDO::PARAM_INT);
	$query->bindValue(":id_sucursal_destino", $traspaso["id_sucursal_destino"], PDO::PARAM_INT);
	$query->bindValue(":observaciones", $traspaso["observaciones"], PDO::PARAM_STR);

	$query->execute();

	$id_traspaso = $pdo->lastInsertId();

	foreach ($productos as $codigo_barras => $producto) {
		
		//insertando detalle traspaso
		$query = $pdo->prepare("
			INSERT INTO traspaso_detalle(
			id_traspaso,
			codigo_barras,
			producto,
			cantidad) VALUES (
			:id_traspaso,
			:codigo_barras,
			:producto,
			:cantidad)");

		$query->bindValue(":id_traspaso", $id_traspaso, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":producto", $producto["nombre"], PDO::PARAM_STR);
		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);

		$query->execute();

		//actualizando el stock del producto en sucursal origen
		$query = $pdo->prepare("UPDATE stock_producto SET stock = stock - :cantidad WHERE id_producto = :id_producto AND id_sucursal = :id_sucursal");

		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $producto["id_producto"], PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $traspaso["id_sucursal_origen"], PDO::PARAM_INT);

		$query->execute();

		//actualizando el stock del producto en sucursal destino
		$query = $pdo->prepare("UPDATE stock_producto SET stock = stock + :cantidad WHERE id_producto = :id_producto AND id_sucursal = :id_sucursal");

		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $producto["id_producto"], PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $traspaso["id_sucursal_destino"], PDO::PARAM_INT);

		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Traspaso relizado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../informes/traspasos.php";

    //reseteando sesión de traspaso
    unset($_SESSION["sistema"]["traspaso"]);

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>