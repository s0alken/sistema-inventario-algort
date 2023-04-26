<?php

function cargarCompatibilidad($productos, $compatibilidad_producto, $configurar_producto = false){

  $compatibilidad_producto = array_flip($compatibilidad_producto);

  $class = $configurar_producto ? "campo-producto" : "";

	foreach($productos as $producto): ?>

      <tr>
          <td><?php echo $producto->codigo_barras ?></td>
          <td><?php echo $producto->descripcion ?></td>
          <td><?php echo $producto->marca ?></td>
          <td class="text-center">
              <input type="checkbox" name="compatibilidad[]" value="<?php echo $producto->id_producto ?>" class="<?php echo $class ?>" <?php echo isset($compatibilidad_producto[$producto->id_producto]) ? "checked" : "" ?>>
          </td>
      </tr>

    <?php endforeach;
}

?>