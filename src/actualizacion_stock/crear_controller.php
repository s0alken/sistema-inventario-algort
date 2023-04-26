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

$actualizacion_stock = $_SESSION["sistema"]["actualizacion_stock"];
$carrito = $actualizacion_stock["carrito"];

//comprobando si hay productos en el carrito
if (empty($carrito)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes agregar por lo menos un producto!"));
  	exit();

}

//comprobando si la cotización tiene un proveedor
if (empty($actualizacion_stock["proveedor"])) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La actualización de stock requiere un proveedor!"));
  	exit();

}

//comprobando si se ingresó el número de documento de compra
if (strlen($actualizacion_stock["n_documento_compra"]) === 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Debes ingresar el N° de documento de compra!"));
	exit();

}

//comprobando que las observaciones no excedan los 300 caracteres
if (largoExcedido($actualizacion_stock["observaciones"], 300)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las observaciones no deben exceder los 300 caracteres!"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	//insertando cotización
	$query = $pdo->prepare("
		INSERT INTO actualizacion_stock(
		id_proveedor,
		fecha,
		id_medio_pago,
		id_documento,
		n_documento_compra,
		observaciones) VALUES (
		:id_proveedor,
		:fecha,
		:id_medio_pago,
		:id_documento,
		:n_documento_compra,
		:observaciones)");

	$query->bindValue(":id_proveedor", $actualizacion_stock["proveedor"]["id_proveedor"], PDO::PARAM_INT);
	$query->bindValue(":fecha", date("Y-m-d H:i:s"), PDO::PARAM_STR);
	$query->bindValue(":id_medio_pago", $actualizacion_stock["medio_pago"]["id_medio_pago"], PDO::PARAM_INT);
	$query->bindValue(":id_documento", $actualizacion_stock["documento"]["id_documento"], PDO::PARAM_INT);
	$query->bindValue(":n_documento_compra", $actualizacion_stock["n_documento_compra"], PDO::PARAM_INT);
	$query->bindValue(":observaciones", $actualizacion_stock["observaciones"], PDO::PARAM_STR);

	$query->execute();

	$id_actualizacion_stock = $pdo->lastInsertId();

	foreach ($carrito as $codigo_barras => $producto) {

		//comprobando que la cantidad no sea menor a cero
		if ($producto["cantidad"] < 0) {

			throw new Exception("¡La cantidad del producto " . $producto["nombre"] . " no debe ser menor a cero!");
			

		}

		//comprobando que los precios no sean menores a cero
		if ($producto["precio_costo"] < 0 || $producto["precio_venta"] < 0) {

			throw new Exception("¡Los precios del producto " . $producto["nombre"] . " no deben ser menores a cero!");
			

		}

		//comprobando que el precio de venta no sea menor al precio de costo
		if ($producto["precio_costo"] > $producto["precio_venta"]) {

			throw new Exception("¡El precio de venta del producto " + $producto["nombre"] + " no debe ser menor al precio de costo!");
			

		}

		//insertando detalle
		$query = $pdo->prepare("
			INSERT INTO actualizacion_stock_detalle(
			id_actualizacion_stock,
			codigo_barras,
			producto,
			cantidad) VALUES (
			:id_actualizacion_stock,
			:codigo_barras,
			:producto,
			:cantidad)");

		$query->bindValue(":id_actualizacion_stock", $id_actualizacion_stock, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":producto", $producto["nombre"], PDO::PARAM_STR);
		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);

		$query->execute();
		
		//actualizando precios
		$query = $pdo->prepare("
			UPDATE producto SET
			precio_costo = :precio_costo,
			precio_venta = :precio_venta
			WHERE id_producto = :id_producto");

		$query->bindValue(":precio_costo", $producto["precio_costo"], PDO::PARAM_INT);
		$query->bindValue(":precio_venta", $producto["precio_venta"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $producto["id_producto"], PDO::PARAM_INT);

		$query->execute();

		//actualizando stock
		$query = $pdo->prepare("UPDATE stock_producto SET stock = stock + :cantidad WHERE id_producto = :id_producto AND id_sucursal = :id_sucursal");

		$query->bindValue(":cantidad", $producto["cantidad"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $producto["id_producto"], PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Actualización de stock relizada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../informes/actualizaciones_stock.php";

    //eliminando sesión de actualización de stock
    unset($_SESSION["sistema"]["actualizacion_stock"]);

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>