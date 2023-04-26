<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    session_start();
    cargarCarrito($_SESSION["sistema"]["promocion"]["carrito"]);

}

function cargarCarrito($carrito) {

    foreach($carrito as $codigo_barras => $producto): ?>

        <tr>
            <td><?php echo $codigo_barras ?></td>
            <td><?php echo $producto["nombre"] ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto["total_descontado"]) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto["precio_antes"]) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto["precio_ahora"]) ?></td>
            <td><?php echo $producto["stock_promocion"] ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $codigo_barras ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $codigo_barras ?>">
                    <button type="button" class="dropdown-item btn-modificar-producto" value="<?php echo $codigo_barras ?>">Modificar stock en promoción</button>
                    <button type="button" class="dropdown-item btn-quitar-del-carrito" value="<?php echo $codigo_barras ?>">Quitar de la promoción</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>