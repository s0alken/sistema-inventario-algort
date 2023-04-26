<?php

require_once "../controller/conexion.php";
require_once "configurar_montos.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

if (strlen($codigo_barras) === 0) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Debes especificar un código de barras!"));
  exit();

}

if (array_key_exists($codigo_barras, $_SESSION["sistema"]["venta"]["carrito"])) {

	$query = $pdo->prepare("
		SELECT sp.stock FROM stock_producto sp
		INNER JOIN producto p ON p.id_producto = sp.id_producto
		WHERE p.habilitado
		AND p.codigo_barras = :codigo_barras
		AND sp.id_sucursal = :id_sucursal");

	$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
	$query->execute();

	$stock = $query->fetch(PDO::FETCH_COLUMN);

	$cantidad_nueva = $_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["cantidad"] + 1;

	if ($cantidad_nueva > $stock) {

		echo json_encode(array("agregado" => false, "mensaje" => "¡Ya no quedan unidades del producto!"));
		exit();

	}

	$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]["cantidad"] = $cantidad_nueva;

	configurarMontos();

	echo json_encode(array("agregado" => true, "mensaje" => "¡Producto agregado a la venta!"));

} else {

	$query = $pdo->prepare("
		SELECT
		p.id_producto,
		CONCAT(p.descripcion, ' ', m.nombre_marca) AS nombre,
		p.precio_costo,
		p.precio_venta,
		sp.stock,
		p.acumula_puntos
		FROM producto p
		INNER JOIN marca m ON m.id_marca = p.id_marca
		INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
		WHERE p.habilitado
		AND p.codigo_barras = :codigo_barras
		AND sp.id_sucursal = :id_sucursal");

	$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_STR);
	$query->execute();

	$producto = $query->fetch();

	if (!$producto) {

		echo json_encode(array("agregado" => false, "mensaje" => "¡Producto no encontrado!"));
		exit();

	}

	if ($producto->stock <= 0) {

		echo json_encode(array("agregado" => false, "mensaje" => "¡Producto sin stock!"));
		exit();

	}

	$_SESSION["sistema"]["venta"]["carrito"][$codigo_barras] = array("id_producto"          => $producto->id_producto,
																	 "nombre"               => $producto->nombre,
																	 "precio_costo"         => $producto->precio_costo,
																	 "precio_venta"         => $producto->precio_venta,
																	 "precio_descuento"     => $producto->precio_venta,
																	 "cantidad"             => 1,
																	 "descuento_porcentaje" => 0,
																	 "descuento_dinero"     => 0,
																	 "total_descuento"      => 0,
																	 "subtotal"             => $producto->precio_venta,
																	 "acumula_puntos"       => $producto->acumula_puntos);

	configurarMontos();

	echo json_encode(array("agregado" => true, "mensaje" => "¡Producto agregado a la venta!"));

}

?>