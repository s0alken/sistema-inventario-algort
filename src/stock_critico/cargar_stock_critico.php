<?php

require_once "../controller/conexion.php";

function cargarStockCritico() {

	global $pdo;

    $query = $pdo->prepare("
        SELECT
        p.id_producto,
        p.codigo_barras,
        p.descripcion,
        m.nombre_marca AS marca,
        p.precio_venta AS precio,
        sp.stock,
        sc.nombre_subcategoria AS subcategoria
        FROM producto p
        INNER JOIN marca m ON m.id_marca = p.id_marca
        INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
        INNER JOIN subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
        WHERE p.habilitado
        AND sp.stock <= p.stock_critico
        AND sp.id_sucursal = :id_sucursal
        ORDER BY p.id_producto DESC");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $productos = $query->fetchAll(); ?>

    <?php foreach($productos as $producto): ?>

        <tr data-id-producto="<?php echo $producto->id_producto ?>">
            <td><?php echo $producto->codigo_barras ?></td>
            <td><?php echo $producto->descripcion ?></td>
            <td><?php echo $producto->marca ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio) ?></td>
            <td><?php echo $producto->stock ?></td>
            <td><?php echo $producto->subcategoria ?></td>
            <td class="text-center td-opciones">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $producto->id_producto ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $producto->id_producto ?>">
                    <button class="dropdown-item btn-agregar-venta" type="button" value="<?php echo $producto->codigo_barras ?>">Agregar a la venta</button>
                    <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
                        <a href="<?php echo '../productos/editar.php?id_producto=' . $producto->id_producto ?>"><button class="dropdown-item" type="button">Editar</button></a>
                        <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $producto->id_producto ?>" data-item="producto">Eliminar</button>
                    <?php endif ?>
                    <a href="<?php echo '../imprimir_codigo_barras/index.php?id_producto=' . $producto->id_producto ?>" target="_blank"><button class="dropdown-item" type="button">Imprimir código de barras</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>