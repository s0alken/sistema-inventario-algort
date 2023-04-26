<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    session_start();
    cargarCarrito($_SESSION["sistema"]["actualizacion_stock"]["carrito"]);

}

function cargarCarrito($carrito) {

    foreach($carrito as $codigo_barras => $producto): ?>

        <tr>
            <td><?php echo $codigo_barras ?></td>
            <td><?php echo $producto["nombre"] ?></td>
            <td><?php echo $producto["cantidad"] ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto["precio_costo"]) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto["precio_venta"]) ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $codigo_barras ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $codigo_barras ?>">
                    <button type="button" class="dropdown-item btn-modificar-producto" value="<?php echo $codigo_barras ?>">Modificar</button>
                    <button type="button" class="dropdown-item btn-quitar-del-carrito" value="<?php echo $codigo_barras ?>">Quitar de la actualización de stock</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>