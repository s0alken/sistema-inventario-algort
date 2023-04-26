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

$fecha_actual = date("Y-m-d");

$promocion = $_SESSION["sistema"]["promocion"];
$carrito = $promocion["carrito"];

//comprobando si hay productos en el carrito
if (empty($carrito)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes agregar por lo menos un producto!"));
  	exit();

}

//comprobando que se haya completado el nombre de la promoción
if (strlen($promocion["nombre_promocion"]) === 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes especificar un nombre para la promoción!"));
	exit();

}

//comprobando que el nombre de la promoción no exceda los 100 caracteres
if (largoExcedido($promocion["nombre_promocion"], 100)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El nombre de la promoción no debe exceder los 100 caracteres!"));
	exit();

}

//comprobando que la fecha de inicio y término no sean iguales
if ($promocion["fecha_inicio"] === $promocion["fecha_termino"]) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "¡Las fechas de inicio y término son iguales!"));
	exit();

}

//comprobando que la fecha de inicio no sea mayor a la fecha de término
if ($promocion["fecha_inicio"] > $promocion["fecha_termino"]) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "¡La fecha de término es menor a la fecha de inicio!"));
	exit();

}

//comprobando que se haya ingresado por lo menos un descuento
if (!$promocion["descuento_porcentaje"] && !$promocion["descuento_dinero"]) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "¡No has ingresado ningún descuento!"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	//insertando cotización
	$query = $pdo->prepare("
		INSERT INTO promocion(
		nombre_promocion,
		fecha_inicio,
		fecha_termino,
		descuento_porcentaje,
		descuento_dinero,
		hasta_agotar_stock,
		id_promocion_estado) VALUES (
		:nombre_promocion,
		:fecha_inicio,
		:fecha_termino,
		:descuento_porcentaje,
		:descuento_dinero,
		:hasta_agotar_stock,
		:id_promocion_estado)");

	$query->bindValue(":nombre_promocion", $promocion["nombre_promocion"], PDO::PARAM_STR);
	$query->bindValue(":fecha_inicio", $promocion["fecha_inicio"], PDO::PARAM_STR);
	$query->bindValue(":fecha_termino", $promocion["fecha_termino"], PDO::PARAM_STR);
	$query->bindValue(":descuento_porcentaje", $promocion["descuento_porcentaje"], PDO::PARAM_INT);
	$query->bindValue(":descuento_dinero", $promocion["descuento_dinero"], PDO::PARAM_INT);
	$query->bindValue(":hasta_agotar_stock", $promocion["hasta_agotar_stock"] === "checked", PDO::PARAM_BOOL);

	$id_promocion_estado = $promocion["fecha_inicio"] === $fecha_actual ? 2 : 1; 

	$query->bindValue(":id_promocion_estado", $id_promocion_estado, PDO::PARAM_INT);

	$query->execute();

	$id_promocion = $pdo->lastInsertId();

	foreach ($carrito as $codigo_barras => $producto) {
		
		//insertando detalle promoción
		$query = $pdo->prepare("
			INSERT INTO promocion_detalle(
			id_promocion,
			codigo_barras,
			precio_venta,
			stock_promocion) VALUES (
			:id_promocion,
			:codigo_barras,
			:precio_venta,
			:stock_promocion)");

		$query->bindValue(":id_promocion", $id_promocion, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":precio_venta", $producto["precio_ahora"], PDO::PARAM_INT);
		$query->bindValue(":stock_promocion", $producto["stock_promocion"], PDO::PARAM_INT);

		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Promoción creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../promociones/";

    //eliminando sesión de promoción
    unset($_SESSION["sistema"]["promocion"]);

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>