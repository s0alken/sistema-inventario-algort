<?php

require_once "../controller/conexion.php";
require_once "../controller/validar_largo.php";
require_once "iniciar_venta.php";

session_start();

date_default_timezone_set("America/Santiago");

$venta = $_SESSION["sistema"]["venta"];
$carrito = $venta["carrito"];

$documento = $venta["documento"];
$medio_pago = $venta["medio_pago"];

$ingresar_n_documento = filter_var($_GET["ingresar_n_documento"], FILTER_VALIDATE_BOOLEAN);

//comprobando si hay productos en el carrito
if (empty($carrito)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes agregar por lo menos un producto!"));
  	exit();

}

//comprobando si hay un cliente para los tipos de documento que lo requieran
if ($documento["cliente_requerido"] && empty($venta["cliente"])) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La " . $documento["nombre_documento"] . " requiere un cliente!"));
  	exit();

}

//comprobando que las observaciones no excedan los 300 caracteres
if (largoExcedido($venta["observaciones"], 300)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las observaciones no deben exceder los 300 caracteres!"));
	exit();

}

//comprobando si se ingresó el número de boleta
if ($documento["nombre_documento"] === "boleta" && strlen($venta["n_boleta"]) === 0 && $ingresar_n_documento) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Tienes que ingresar el número de boleta!"));
  	exit();

}

//comprobando si se ingresó el número de factura
if ($documento["nombre_documento"] === "factura" && strlen($venta["n_factura"]) === 0 && $ingresar_n_documento) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Tienes que ingresar el número de factura!"));
  	exit();

}

//comprobando si se ingresó el número de guía de despacho
if ($documento["nombre_documento"] === "guía de despacho" && strlen($venta["n_guia_despacho"]) === 0 && $ingresar_n_documento) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Tienes que ingresar el número de guía de despacho!"));
  	exit();

}

//comprobando si se ingresó el número de redcompra
if ($medio_pago["nombre_medio_pago"] === "redcompra" && strlen($venta["n_redcompra"]) === 0 && $ingresar_n_documento) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Tienes que ingresar el número de redcompra!"));
  	exit();

}

try {  

	$pdo->beginTransaction();

  	//insertando venta
	$query = $pdo->prepare("
		INSERT INTO venta(
		fecha,
		id_documento,
		id_medio_pago,
		descuento_porcentaje,
		descuento_dinero,
		observaciones,
		id_vendedor,
		id_sucursal) VALUES (
		:fecha,
		:id_documento,
		:id_medio_pago,
		:descuento_porcentaje,
		:descuento_dinero,
		:observaciones,
		:id_vendedor,
		:id_sucursal)");

	$query->bindValue(":fecha", date("Y-m-d H:i:s"), PDO::PARAM_STR);
	$query->bindValue(":id_documento", $venta["documento"]["id_documento"], PDO::PARAM_INT);
	$query->bindValue(":id_medio_pago", $venta["medio_pago"]["id_medio_pago"], PDO::PARAM_INT);
	$query->bindValue(":descuento_porcentaje", $venta["descuento_porcentaje"], PDO::PARAM_INT);
	$query->bindValue(":descuento_dinero", $venta["descuento_dinero"], PDO::PARAM_INT);
	$query->bindValue(":observaciones", $venta["observaciones"], PDO::PARAM_STR);
	$query->bindValue(":id_vendedor", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

	$query->execute();

	$id_venta = $pdo->lastInsertId();

	foreach ($carrito as $codigo_barras => $producto) {
		
		//insertando detalle venta
		$query = $pdo->prepare("
			INSERT INTO venta_detalle(
			id_venta,
			codigo_barras,
			producto,
			precio_costo,
			precio_venta,
			cantidad,
			acumula_puntos,
			descuento_porcentaje,
			descuento_dinero) VALUES (
			:id_venta,
			:codigo_barras,
			:producto,
			:precio_costo,
			:precio_venta,
			:cantidad,
			:acumula_puntos,
			:descuento_porcentaje,
			:descuento_dinero)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":producto", $producto["nombre"], PDO::PARAM_STR);
		$query->bindValue(":precio_costo", $producto["precio_costo"], PDO::PARAM_INT);
		$query->bindValue(":precio_venta", $producto["precio_venta"], PDO::PARAM_INT);
		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":acumula_puntos", $producto["acumula_puntos"], PDO::PARAM_BOOL);
		$query->bindValue(":descuento_porcentaje", $producto["descuento_porcentaje"], PDO::PARAM_INT);
		$query->bindValue(":descuento_dinero", $producto["descuento_dinero"], PDO::PARAM_INT);

		$query->execute();

		//actualizando el stock del producto
		$query = $pdo->prepare("UPDATE stock_producto SET stock = stock - :cantidad WHERE id_producto = :id_producto AND id_sucursal = :id_sucursal");

		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $producto["id_producto"], PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

		$query->execute();

	}

	//insertando cliente a la venta
	if ($venta["cliente"]) {

		$query = $pdo->prepare("INSERT INTO venta_cliente(id_venta, id_cliente) VALUES (:id_venta, :id_cliente)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":id_cliente", $venta["cliente"]["id_cliente"], PDO::PARAM_INT);

		$query->execute();

		//actualizando puntos del cliente
		$query = $pdo->prepare("UPDATE persona SET puntos = puntos + :puntos WHERE id_persona = :id_persona");

		$query->bindValue(":puntos", $venta["puntos"], PDO::PARAM_INT);
		$query->bindValue(":id_persona", $venta["cliente"]["id_cliente"], PDO::PARAM_INT);

		$query->execute();

	}

	//insertándo número de boleta
	if ($documento["nombre_documento"] === "boleta" && $ingresar_n_documento) {

		$query = $pdo->prepare("INSERT INTO venta_boleta(id_venta, n_boleta) VALUES (:id_venta, :n_boleta)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":n_boleta", $venta["n_boleta"], PDO::PARAM_INT);

		$query->execute();

	}

	//insertándo número de factura
	if ($documento["nombre_documento"] === "factura" && $ingresar_n_documento) {

		$query = $pdo->prepare("INSERT INTO venta_factura(id_venta, n_factura) VALUES (:id_venta, :n_factura)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":n_factura", $venta["n_factura"], PDO::PARAM_INT);

		$query->execute();

	}

	//insertándo número de guía de despacho
	if ($documento["nombre_documento"] === "guía de despacho" && $ingresar_n_documento) {

		$query = $pdo->prepare("INSERT INTO venta_guia_despacho(id_venta, n_guia_despacho) VALUES (:id_venta, :n_guia_despacho)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":n_guia_despacho", $venta["n_guia_despacho"], PDO::PARAM_INT);

		$query->execute();

	}

	//insertándo número de redcompra
	if ($medio_pago["nombre_medio_pago"] === "redcompra" && $ingresar_n_documento) {

		$query = $pdo->prepare("INSERT INTO venta_redcompra(id_venta, n_redcompra) VALUES (:id_venta, :n_redcompra)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":n_redcompra", $venta["n_redcompra"], PDO::PARAM_INT);

		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Venta relizada exitosamente! <br> Total: $ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta["total_a_pagar"]);
    $_SESSION["sistema"]["redireccion"] = "../ventas_sistema/";

    //reseteando sesión de venta
    iniciarVenta();

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>