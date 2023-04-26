<?php

require_once "../controller/cargar_empresa.php";
require_once "../controller/formatear_fecha.php";
require_once "cargar_montos.php";

$id_venta = $_GET["id_venta"];

$query = $pdo->prepare("
    SELECT
    v.id_venta,
    DATE_FORMAT(v.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
    d.nombre_documento AS documento,
    mp.nombre_medio_pago AS medio_pago,
    v.puntos_aplicados,
    v.acumula_puntos,
    v.costo_despacho,
    v.descuento_porcentaje,
    v.descuento_dinero
    FROM venta v
    INNER JOIN documento d ON d.id_documento = v.id_documento
    INNER JOIN medio_pago mp ON mp.id_medio_pago = v.id_medio_pago
    WHERE v.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$venta = $query->fetch();

$query = $pdo->prepare("
    SELECT
    vd.codigo_barras,
    vd.producto AS nombre,
    vd.precio_venta,
    vd.descuento_porcentaje,
    vd.descuento_dinero,
    vd.cantidad,
    vd.acumula_puntos
    FROM venta_detalle vd
    WHERE vd.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$carrito = $query->fetchAll();

cargarMontos($venta, $carrito);

$puntos_acumulados    = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->puntos_acumulados);
$puntos_aplicados     = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->puntos_aplicados);
$descuento_porcentaje = $venta->descuento_porcentaje . "%";
$descuento_dinero     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->descuento_dinero);
$monto_neto           = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->monto_neto);
$total_iva            = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->total_iva);
$total_a_pagar        = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->total_a_pagar);
$monto_total          = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->monto_total);

//cliente
$query = $pdo->prepare("
  SELECT
  p.rut,
  p.nombre_persona AS nombre,
  p.giro AS giro,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion,
  p.telefono AS telefono,
  p.correo AS correo
  FROM venta_cliente cc
  INNER JOIN persona p ON p.id_persona = cc.id_cliente
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE cc.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$cliente = $query->fetch();

$empresa = cargarEmpresa();

$mensaje_IVA_incluido = "El IVA incluido en " . ($venta->documento === "voucher" ? "este " : "esta ") . $venta->documento . " es de " . $total_iva;

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>ticket de venta</title>
</head>
<body>

	<div class="ticket">
		
		<div class="text-center">
			<div><?php echo $empresa->nombre_empresa ?></div>
			<div><?php echo $empresa->razon_social ?></div>
			<div><?php echo $empresa->rut ?></div>
			<div><?php echo "Giro: " . $empresa->giro ?></div>
			<div><?php echo $empresa->direccion ?></div>
		</div>

		<div class="mt mb"><?php echo "N° venta: " . $venta->id_venta ?></div>

		<div><?php echo "Fecha: " . $venta->fecha ?></div>

		<?php if ($cliente): ?>
			<div class="mt">
				<div><?php echo "RUT Cliente: " . $cliente->rut ?></div>
				<div><?php echo "Nombre: " . $cliente->nombre ?></div>
				<div><?php echo "Giro: " . $cliente->giro ?></div>
				<div><?php echo "Dirección: " . $cliente->direccion ?></div>
				<div><?php echo "Teléfono: " . $cliente->telefono ?></div>
				<div><?php echo "Correo: " . $cliente->correo ?></div>
			</div>
		<?php endif ?>

		<table class="mt mb">
			<thead>
				<tr>
					<th>Cant</th>
					<th>Producto</th>
					<th>Subtotal</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($carrito as $producto): ?>
				<tr>
					<td><?php echo $producto->cantidad ?></td>
					<td><?php echo $producto->nombre ?></td>
					<td class="text-right"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->subtotal) ?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>

		<div class="text-right mb"><?php echo "Monto total: " . $total_a_pagar ?></div>
		<div><?php echo $mensaje_IVA_incluido ?></div>

		<hr>

	</div>

</body>

<script type="text/javascript">

	window.print();

</script>

</html>