<?php

require_once "../controller/conexion.php";
require_once "../controller/validar_largo.php";

function cerrarVentaWeb($id_compra){

	global $pdo;

	$query = $pdo->prepare("SELECT * FROM compra WHERE id_compra = :id_compra");
	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
	$query->execute();

	$compra = $query->fetch();

	$query = $pdo->prepare("SELECT * FROM compra_detalle WHERE id_compra = :id_compra");
	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
	$query->execute();

	$carrito = $query->fetchAll();

	//comprobando que las observaciones no excedan los 300 caracteres
	if (largoExcedido($_POST["observaciones"], 300)) {

		throw new Exception("¡Las observaciones no deben exceder los 300 caracteres!");
		
	}

	//insertando venta
	$query = $pdo->prepare("
		INSERT INTO venta(
		fecha,
		id_documento,
		id_medio_pago,
		observaciones,
		puntos_aplicados,
		acumula_puntos,
		costo_despacho,
		id_vendedor,
		id_sucursal) VALUES (
		:fecha,
		:id_documento,
		:id_medio_pago,
		:observaciones,
		:puntos_aplicados,
		:acumula_puntos,
		:costo_despacho,
		:id_vendedor,
		:id_sucursal)");

	$query->bindValue(":fecha", $compra->fecha, PDO::PARAM_STR);
	$query->bindValue(":id_documento", $compra->id_documento, PDO::PARAM_INT);
	$query->bindValue(":id_medio_pago", $compra->id_medio_pago, PDO::PARAM_INT);
	$query->bindValue(":observaciones", $_POST["observaciones"], PDO::PARAM_STR);
	$query->bindValue(":puntos_aplicados", $compra->puntos_aplicados, PDO::PARAM_INT);
	$query->bindValue(":acumula_puntos", $compra->acumula_puntos, PDO::PARAM_BOOL);
	$query->bindValue(":costo_despacho", $compra->costo_despacho, PDO::PARAM_INT);
	$query->bindValue(":id_vendedor", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

	$query->execute();

	$id_venta = $pdo->lastInsertId();

	$monto_total_puntos = 0;

	foreach ($carrito as $producto) {
		
		//insertando detalle venta
		$query = $pdo->prepare("
			INSERT INTO venta_detalle(
			id_venta,
			codigo_barras,
			producto,
			precio_costo,
			precio_venta,
			cantidad,
			acumula_puntos) VALUES (
			:id_venta,
			:codigo_barras,
			:producto,
			:precio_costo,
			:precio_venta,
			:cantidad,
			:acumula_puntos)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":codigo_barras", $producto->codigo_barras, PDO::PARAM_STR);
		$query->bindValue(":producto", $producto->producto, PDO::PARAM_STR);
		$query->bindValue(":precio_costo", $producto->precio_costo, PDO::PARAM_INT);
		$query->bindValue(":precio_venta", $producto->precio_venta, PDO::PARAM_INT);
		$query->bindValue(":cantidad", $producto->cantidad, PDO::PARAM_INT);
		$query->bindValue(":acumula_puntos", $producto->acumula_puntos, PDO::PARAM_BOOL);

		$query->execute();

		if ($compra->acumula_puntos) {
	    	
	    	//monto total puntos es el total de los productos que acumulan puntos
	    	$monto_total_puntos += $producto->acumula_puntos ? $producto->precio_venta * $producto->cantidad : 0;

	    }

	}

	//aplicando descuento al total de productos que acumulan puntos
	$monto_total_puntos = $monto_total_puntos === 0 ? 0 : $monto_total_puntos - $compra->puntos_aplicados;

	//calculando puntos
	$puntos = round(($monto_total_puntos * 2) / 100);

	//insertando cliente a la venta
	$query = $pdo->prepare("SELECT id_cliente FROM compra_cliente WHERE id_compra = :id_compra");

	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);

	$query->execute();

	$id_cliente = $query->fetch(PDO::FETCH_COLUMN);

	if ($id_cliente) {

		$query = $pdo->prepare("INSERT INTO venta_cliente(id_venta, id_cliente) VALUES (:id_venta, :id_cliente)");

		$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
		$query->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);

		$query->execute();

		//sumando puntos acumulados del cliente
		$query = $pdo->prepare("UPDATE persona SET puntos = puntos + :puntos WHERE id_persona = :id_persona");

		$query->bindValue(":puntos", $puntos, PDO::PARAM_INT);
		$query->bindValue(":id_persona", $id_cliente, PDO::PARAM_INT);

		$query->execute();

		//restando puntos gastados del cliente
		$query = $pdo->prepare("UPDATE persona SET puntos = puntos - :puntos WHERE id_persona = :id_persona");

		$query->bindValue(":puntos", $compra->puntos_aplicados, PDO::PARAM_INT);
		$query->bindValue(":id_persona", $id_cliente, PDO::PARAM_INT);

		$query->execute();

	}

}

?>