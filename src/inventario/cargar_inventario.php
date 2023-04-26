<?php

require_once "../controller/conexion.php";

function cargarInventario($id_sucursal) {

	global $pdo;

    $query = $pdo->prepare("
      SELECT
      p.id_producto,
      p.codigo_barras,
      p.descripcion,
      m.nombre_marca AS marca,
      sp.stock,
      sp.stock * p.precio_costo AS total_precio_costo,
      sp.stock * p.precio_venta AS total_precio_venta
      FROM producto p
      INNER JOIN marca m ON m.id_marca = p.id_marca
      INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
      INNER JOIN sucursal s ON s.id_sucursal = sp.id_sucursal
      WHERE p.habilitado
      AND sp.stock >= 1
      AND sp.id_sucursal = :id_sucursal
      AND s.habilitada
      ORDER BY p.id_producto DESC");

    $query->bindValue(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $productos = $query->fetchAll(); ?>

    <?php foreach($productos as $producto): ?>

        <tr data-id-producto="<?php echo $producto->id_producto ?>">
            <td><?php echo $producto->codigo_barras ?></td>
            <td><?php echo $producto->descripcion ?></td>
            <td><?php echo $producto->marca ?></td>
            <td><?php echo $producto->stock ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->total_precio_costo) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->total_precio_venta) ?></td>
        </tr>

    <?php endforeach;

}

?>