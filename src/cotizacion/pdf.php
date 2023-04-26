<?php

require_once "../controller/conexion.php";
require_once "../controller/cargar_empresa.php";
require_once "../controller/formatear_fecha.php";

$empresa = cargarEmpresa();

$id_cotizacion = $_GET["id_cotizacion"];

$query = $pdo->prepare("
	SELECT
	c.id_cotizacion,
	c.fecha,
	mp.nombre_medio_pago AS medio_pago,
	c.descuento,
	c.observaciones,
	p.nombre_persona
	FROM cotizacion c
	INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
	INNER JOIN persona p ON p.id_persona = c.id_cliente
	WHERE c.id_cotizacion = :id_cotizacion");

$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
$query->execute();

$cotizacion = $query->fetch();

$query = $pdo->prepare("SELECT * FROM cotizacion_detalle WHERE id_cotizacion = :id_cotizacion");

$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
$query->execute();

$cotizacion_detalle = $query->fetchAll();

$monto_total = 0;
//$puntos_propezca = 0;

foreach ($cotizacion_detalle as $producto) {

	$total_producto = $producto->cantidad * $producto->precio_venta;
	
	//total del producto con el descuento aplicado
	$subtotal = round($total_producto - (($total_producto * $producto->descuento) / 100));

	//monto total es el total de la cotización sin el descuento aplicado
    $monto_total += $subtotal;

	//$puntos_propezca += $producto["puntos_propezca"] * $producto["cantidad"];

	$producto->subtotal = $subtotal;
	$producto->total_descuento = $total_producto - $subtotal;

}

//total a pagar es el total de la cotización con el descuento aplicado
$total_a_pagar = round($monto_total - (($monto_total * $cotizacion->descuento) / 100));

$total_descuento = $monto_total - $total_a_pagar;

$monto_neto = round($total_a_pagar / 1.19);

$total_iva = $total_a_pagar - $monto_neto;

$cotizacion->total_a_pagar = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_a_pagar);

$cotizacion->total_descuento = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_descuento);

$cotizacion->monto_neto = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $monto_neto);

$cotizacion->total_iva = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_iva);

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		*{
			margin: 0;
			padding: 0;
		}
		.wrap{
			padding: 20px;
			width: 100%;
			position: absolute;
		}
		.img-izq{
			width: 19%;
			float: left;
		}
		.img-izq img{
			width: 80%;
		}
		.text-centro{
			width: 80%;
			float: left;
			text-align: center;
		}
		p.titulo{
			text-transform: uppercase;
			font-size: 24px;
			font-weight: bold;
		}
		p.sub{
			text-transform: uppercase;
			font-size: 18px;	
		}
		.one{
			padding-top: 20px;
		}
		hr{
			border: 1px solid #090909;
			width: 100%;
		}
		.fecha{
			padding: 15px;
			text-align: right;
		}
		.cotizacion{
			font-weight: bold;
			text-align: center;
			text-transform: uppercase;
		}
		.info{
			text-align: left;
			padding-top: 30px;
		}
		.info b{
			text-decoration: underline;
			font-weight: bolder;
			font-size: 18px;
		}
		table{
			width: 100%;
			margin-top: 20px;
		}
		th{
			border-bottom: 1px solid;
		}
		td{
			border-bottom: 1px solid;
		}
		th, td{
			padding: 10px;
		}
		.total{
			font-weight: bold;
			text-align: right;
			margin-top: 15px;
		}
		.observaciones{
			text-align: left;
			margin-top: 30px;			
		}
		.observaciones .titobs{
			font-weight: bolder;
			font-size: 20px;
		}
		.observaciones .textobs{
			font-size: 16px;
			margin-left: 20px;
		}
		.algort{
			margin-top: 60px;
			text-align: left;
			font-size: 18px;
		}
	</style>
</head>
<body>
	<div class="wrap">
		<div class="img-izq">
			<img src="<?php echo '../img/logo.png?' . rand() ?>">
		</div>
		<div class="text-centro">
			<p class="titulo"><?php echo $empresa->nombre_empresa ?></p>
			<p class="sub one"><?php echo $empresa->giro ?></p>
			<p class="sub"><?php echo "RUT: " . $empresa->rut ?></p>
		</div>
		<div class="fecha">
			<p class="fecha"><?php echo formatearFecha($cotizacion->fecha) ?></p>
		</div>
		<div class="cotizacion">
			<p class="coti"><?php echo "Cotizacion N°: " . $cotizacion->id_cotizacion ?></p>
		</div>
		<div class="info">
			<p class="info-text">
				Señores : <br>
				<?php echo $cotizacion->nombre_persona ?> <br>			
			</p>
			<br><br>
			<b>Presente</b>
		</div>
		<table>
			<thead>
				<tr>
					<th>Código</th>
					<th>Producto</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Descuento</th>
					<th>Total descuento</th>
					<th>Subtotal</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($cotizacion_detalle as $producto): ?>
				
				<tr>
					<td><?php echo $producto->codigo_barras ?></td>
					<td><?php echo $producto->producto ?></td>
					<td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_venta) ?></td>
					<td><?php echo $producto->cantidad ?></td>
					<td><?php echo $producto->descuento . "%" ?></td>
					<td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->total_descuento) ?></td>
					<td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->subtotal) ?></td>
				</tr>

			<?php endforeach ?>
			</tbody>
		</table>
		<div class="total">
			<p class=""><?php echo "Descuento global: " . $cotizacion->descuento . "%" ?></p>
			<p class=""><?php echo "Monto neto: $ " . $cotizacion->monto_neto ?></p>
			<p class=""><?php echo "Total IVA: $ " . $cotizacion->total_iva ?></p>
			<p class=""><?php echo "Total: $ " . $cotizacion->total_a_pagar ?></p>
		</div>
		<div class="observaciones">
			<p class="titobs">Observaciones :</p>
			<p class="textobs"><?php echo $cotizacion->observaciones ?></p>
		</div>
	</div>
</body>
</html>