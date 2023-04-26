<?php

require_once "../controller/conexion.php";
require_once "configurar_montos.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

if (strlen($codigo_barras) === 0) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Debes especificar un código de barras!"));
  exit();

}

if (array_key_exists($codigo_barras, $_SESSION["sistema"]["promocion"]["carrito"])) {

	echo json_encode(array("agregado" => false, "mensaje" => "¡Ya agregaste este producto a la promoción!"));

} else {

	$query = $pdo->prepare("
		SELECT
		p.id_producto,
		CONCAT(p.descripcion, ' ', m.nombre_marca) AS nombre,
		p.precio_venta,
		sp.stock
		FROM producto p
		INNER JOIN marca m ON m.id_marca = p.id_marca
		INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
		WHERE p.habilitado
		AND p.codigo_barras = :codigo_barras
		AND sp.id_sucursal = :id_sucursal");

	$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
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

	$_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras] = array("id_producto"      => $producto->id_producto,
															             "nombre"           => $producto->nombre,
															             "precio_antes"     => $producto->precio_venta,
															             "precio_ahora"     => $producto->precio_venta,
															             "stock_promocion"  => $producto->stock,
															             "total_descontado" => 0);

	configurarMontos();

	echo json_encode(array("agregado" => true, "mensaje" => "¡Producto agregado a la promoción!"));

}

?>