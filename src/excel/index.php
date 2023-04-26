<?php

require_once "SimpleXLSX.php";
require_once "../controller/conexion.php";

$productos = [];

if ( $xlsx = SimpleXLSX::parse('excel.xlsx') ) {

	$productos_excel = $xlsx->rows();

	for ($i = 1; $i < count($productos_excel); $i++) { 
		
		$producto_excel = $productos_excel[$i];

		$codigo_barras = $producto_excel[0];

		$descripcion = $producto_excel[1] . " " . $producto_excel[2];

		$precio_costo = str_replace("$ ", "", $producto_excel[3]);

		$precio_costo = str_replace(".", "", $precio_costo);

		$precio_venta = round($producto_excel[4]);

		$producto = array("codigo_barras" => $codigo_barras,
			              "descripcion"   => $descripcion,
			              "precio_costo"  => $precio_costo,
			              "precio_venta"  => $precio_venta
			             );

		array_push($productos, $producto);

	}


} else {
	echo SimpleXLSX::parseError();
}

try {  

	$pdo->beginTransaction();

	foreach ($productos as $producto) {
	
		echo $producto["codigo_barras"] . " " . $producto["descripcion"] . " " . $producto["precio_costo"] . " " . $producto["precio_venta"] . "<br>";

		$query = $pdo->prepare("UPDATE producto SET precio_costo = :precio_costo, precio_venta = :precio_venta WHERE codigo_barras = :codigo_barras");

		$query->bindValue(":precio_costo", $producto["precio_costo"], PDO::PARAM_INT);
		$query->bindValue(":precio_venta", $producto["precio_venta"], PDO::PARAM_INT);
        $query->bindValue(":codigo_barras", $producto["codigo_barras"], PDO::PARAM_STR);

		$query->execute();

	}

	echo "proceso terminado!";

    $pdo->commit();

} catch (Exception $e) {

	$pdo->rollBack();

	echo $e->getMessage();

}

?>