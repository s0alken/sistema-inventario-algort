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

		include 'barcode128.php';
		include_once "../../base_de_datos.php";

		$id_repuesto = $_GET["id_repuesto"];

		$query = $base_de_datos->prepare("SELECT codigo_barra, descripcion, precio_venta FROM repuesto WHERE id_repuesto = :id_repuesto");
		$query->bindParam(':id_repuesto', $id_repuesto, PDO::PARAM_INT);
    	$query->execute();

		$repuesto = $query->fetch(PDO::FETCH_OBJ);
		
		$descripcion = ucwords($repuesto->descripcion);
		$codigo_barra = $repuesto->codigo_barra;
		$precio = '$' . preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $repuesto->precio_venta);

		for($i=1; $i <= 1; $i++){
			
			echo "<p class='inline'><span ><b>" . $descripcion . " </b><span>" . bar128(stripcslashes($codigo_barra)) . "<span><b>Precio: " . $precio . " </b><span></p>&nbsp&nbsp&nbsp&nbsp";
		}

		?>
	</div>
</body>
</html>