<?php

function cargarCarrito($carrito) {

    foreach($carrito as $producto): ?>

        <tr>
            <td><?php echo $producto->codigo_barras ?></td>
            <td><?php echo $producto->nombre ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio) ?></td>
            <td><?php echo $producto->cantidad ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->subtotal) ?></td>
        </tr>

    <?php endforeach;

}

?>