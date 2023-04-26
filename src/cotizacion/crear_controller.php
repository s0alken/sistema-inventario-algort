<?php

session_start();

require_once "../controller/conexion.php";
require_once "../controller/validar_largo.php";

date_default_timezone_set("America/Santiago");

$cotizacion = $_SESSION["sistema"]["cotizacion"];
$carrito = $cotizacion["carrito"];

//comprobando si hay productos en el carrito
if (empty($carrito)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes agregar por lo menos un producto!"));
  	exit();

}

//comprobando si la cotización tiene un cliente
if (empty($cotizacion["cliente"])) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cotización requiere un cliente!"));
  	exit();

}

//comprobando que las observaciones no excedan los 300 caracteres
if (largoExcedido($cotizacion["observaciones"], 300)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las observaciones no deben exceder los 300 caracteres!"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	//insertando cotización
	$query = $pdo->prepare("
		INSERT INTO cotizacion(
		id_cliente,
		fecha,
		id_medio_pago,
		descuento_porcentaje,
		descuento_dinero,
		observaciones) VALUES (
		:id_cliente,
		:fecha,
		:id_medio_pago,
		:descuento_porcentaje,
		:descuento_dinero,
		:observaciones)");

	$query->bindValue(":id_cliente", $cotizacion["cliente"]["id_cliente"], PDO::PARAM_INT);
	$query->bindValue(":fecha", date("Y-m-d H:i:s"), PDO::PARAM_STR);
	$query->bindValue(":id_medio_pago", $cotizacion["medio_pago"]["id_medio_pago"], PDO::PARAM_INT);
	$query->bindValue(":descuento_porcentaje", $cotizacion["descuento_porcentaje"], PDO::PARAM_INT);
	$query->bindValue(":descuento_dinero", $cotizacion["descuento_dinero"], PDO::PARAM_INT);
	$query->bindValue(":observaciones", $cotizacion["observaciones"], PDO::PARAM_STR);

	$query->execute();

	$id_cotizacion = $pdo->lastInsertId();

	foreach ($carrito as $codigo_barras => $producto) {
		
		//insertando detalle cotización
		$query = $pdo->prepare("
			INSERT INTO cotizacion_detalle(
			id_cotizacion,
			codigo_barras,
			producto,
			precio_venta,
			cantidad,
			descuento_porcentaje,
			descuento_dinero) VALUES (
			:id_cotizacion,
			:codigo_barras,
			:producto,
			:precio_venta,
			:cantidad,
			:descuento_porcentaje,
			:descuento_dinero)");

		$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":producto", $producto["nombre"], PDO::PARAM_STR);
		$query->bindValue(":precio_venta", $producto["precio_venta"], PDO::PARAM_INT);
		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":descuento_porcentaje", $producto["descuento_porcentaje"], PDO::PARAM_INT);
		$query->bindValue(":descuento_dinero", $producto["descuento_dinero"], PDO::PARAM_INT);

		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Cotización relizada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../informes/cotizaciones.php";

    //eliminando sesión de cotización
    unset($_SESSION["sistema"]["cotizacion"]);

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>