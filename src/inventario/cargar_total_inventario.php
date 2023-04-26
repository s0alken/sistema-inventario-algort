<?php

require_once "../controller/conexion.php";

function cargarTotalInventario($id_sucursal) {

	global $pdo;

    $query = $pdo->prepare("
      SELECT
      sp.stock,
      sp.stock * p.precio_costo AS total_precio_costo,
      sp.stock * p.precio_venta AS total_precio_venta
      FROM producto p
      INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
      INNER JOIN sucursal s ON s.id_sucursal = sp.id_sucursal
      WHERE p.habilitado
      AND sp.stock >= 1
      AND sp.id_sucursal = :id_sucursal
      AND s.habilitada
      ORDER BY p.id_producto DESC");

    $query->bindValue(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
    $query->execute();


    $productos = $query->fetchAll();

    $cantidad_productos = 0;
    $total_precio_costo = 0;
    $total_precio_venta = 0;

    foreach ($productos as $producto) {

      $cantidad_productos += $producto->stock;
      $total_precio_costo += $producto->total_precio_costo;
      $total_precio_venta += $producto->total_precio_venta;

    }

    $cantidad_productos = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cantidad_productos);
    $total_precio_costo = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_precio_costo);
    $total_precio_venta = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_precio_venta);

    ?>

    <div class="form-row">
      <div class="col-lg-4 mb-3 text-center text-lg-left">
        <label for="cantidad_productos">Cantidad unidad productos:</label>
        <input type="text" id="cantidad_productos" class="form-control form-control-sm rounded-pill" value="<?php echo $cantidad_productos ?>" disabled>
      </div>
      <div class="col-lg-4 mb-3 text-center text-lg-left">
        <label for="total_precio_costo">Total costo inventario:</label>
        <input type="text" id="total_precio_costo" class="form-control form-control-sm rounded-pill" value="<?php echo $total_precio_costo ?>" disabled>
      </div>
      <div class="col-lg-4 mb-3 text-center text-lg-left">
        <label for="total_precio_venta">Total valor venta:</label>
        <input type="text" id="total_precio_venta" class="form-control form-control-sm rounded-pill" value="<?php echo $total_precio_venta ?>" disabled>
      </div>
    </div>

 <?php } ?>