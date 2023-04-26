<?php

require_once "../controller/conexion.php";
require_once "configurar_montos.php";

session_start();

foreach ($_POST as $campo) {
	
	if (strlen($campo) === 0) {
		
		echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
  		exit();

	}

}

$codigo_barras = $_GET["codigo_barras"];

$cantidad             = intval($_POST["cantidad_producto"]);
$descuento_porcentaje = intval($_POST["descuento_porcentaje_producto"]);
$descuento_dinero     = intval($_POST["descuento_dinero_producto"]);

if (strlen($codigo_barras) === 0) {

  echo json_encode(array("guardado" => false, "mensaje" => "¡Debes especificar un código de barras!"));
  exit();

}

//la cotización admite solo 25 items
if (count($_SESSION["sistema"]["cotizacion"]["carrito"]) + 1 > 25) {

	echo json_encode(array("guardado" => false, "mensaje" => "Haz excedido el límite de items"));
  	exit();

}

if ($cantidad < 1) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡La cantidad debe ser igual o mayor que 1!"));
	exit();

}

if ($descuento_porcentaje > 100 || $descuento_porcentaje < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El porcentaje de descuento debe ser entre 0 y 100!"));
	exit();

}

if ($descuento_dinero < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El descuento en dinero debe ser cero o mayor!"));
	exit();

}

$query = $pdo->prepare("
	SELECT
	p.id_producto,
	CONCAT(p.descripcion, ' ', m.nombre_marca) AS nombre,
	p.precio_venta,
	p.acumula_puntos
	FROM producto p
	INNER JOIN marca m ON m.id_marca = p.id_marca
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->execute();

$producto = $query->fetch();

if (!$producto) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Producto no encontrado!"));
	exit();

}

$_SESSION["sistema"]["cotizacion"]["carrito"][$codigo_barras] = array("id_producto"          => $producto->id_producto,
																	  "nombre"               => $producto->nombre,
																	  "precio_venta"         => $producto->precio_venta,
																	  "precio_descuento"     => $producto->precio_venta,
																	  "cantidad"             => $cantidad,
																	  "descuento_porcentaje" => $descuento_porcentaje,
																	  "descuento_dinero"     => $descuento_dinero,
																	  "total_descuento"      => 0,
																	  "subtotal"             => $producto->precio_venta,
																	  "acumula_puntos"       => $producto->acumula_puntos);

configurarMontos();

echo json_encode(array("guardado" => true, "redireccionar" => false));

?>