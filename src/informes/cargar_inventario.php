<?php

require_once "../controller/conexion.php";

function cargarInventario() {

	global $pdo;

    $query = $pdo->prepare("
        SELECT
        p.id_producto,
        p.codigo_barras,
        p.descripcion,
        m.nombre_marca AS marca,
        p.precio_costo,
        p.precio_venta,
        sp.stock,
        sc.nombre_subcategoria AS subcategoria
        FROM producto p
        INNER JOIN marca m ON m.id_marca = p.id_marca
        INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
        INNER JOIN subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
        WHERE p.habilitado
        AND sp.id_sucursal = :id_sucursal
        ORDER BY p.id_producto DESC");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $productos = $query->fetchAll(); ?>

    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <th scope="col">Valor costo neto</th>
            <th scope="col">Valor venta con IVA</th>
            <th scope="col">Stock</th>
            <th scope="col">Subcategoría</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($productos as $producto): ?>

            <tr>
                <td><?php echo $producto->codigo_barras ?></td>
                <td><?php echo $producto->descripcion ?></td>
                <td><?php echo $producto->marca ?></td>
                <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_costo) ?></td>
                <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_venta) ?></td>
                <td><?php echo $producto->stock ?></td>
                <td><?php echo $producto->subcategoria ?></td>
            </tr>

        <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

<?php } ?>