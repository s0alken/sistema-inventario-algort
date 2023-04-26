<?php

function cargarCarrito($carrito) {

    foreach($carrito as $producto): ?>

        <tr>
            <td><?php echo $producto->codigo_barras ?></td>
            <td><?php echo $producto->nombre ?></td>
            <td class="text-nowrap"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_venta) ?></td>
            <td><?php echo $producto->cantidad ?></td>
            <td><?php echo $producto->descuento_porcentaje . "%" ?></td>
            <td class="text-nowrap"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->descuento_dinero) ?></td>
            <td class="text-nowrap"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->total_descuento) ?></td>
            <td class="text-nowrap"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->subtotal) ?></td>
        </tr>

    <?php endforeach;

}

?>