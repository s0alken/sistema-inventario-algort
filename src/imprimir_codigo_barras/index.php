<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 13px;}
</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
</head>
<body onload="window.print();">
	<div style="margin-left: 0">
		<?php

		include_once "../controller/conexion.php";
		include_once "barcode128.php";

		$query = $pdo->prepare("
			SELECT
			p.codigo_barras,
			p.descripcion,
			p.precio_venta
			FROM producto p
			INNER JOIN marca m ON m.id_marca = p.id_marca
			WHERE
			p.id_producto = :id_producto");

		$query->bindParam(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
    	$query->execute();

    	$producto = $query->fetch();
		
		$codigo_barras = $producto->codigo_barras;
		$nombre_producto = $producto->descripcion;
		$precio_venta = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_venta);

		for($i=1; $i <= 1; $i++){
			
			echo "<p class='inline'>" . bar128(stripcslashes($codigo_barras)) . "<span ><b>Precio: ".  $precio_venta ." </b><span></p>&nbsp&nbsp&nbsp&nbsp";
		}

		?>
	</div>
</body>
</html>