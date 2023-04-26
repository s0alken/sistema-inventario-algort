<?php

function cargarImagenes($imagenes, $imagenes_producto, $configurar_producto = false){

  $imagenes_producto = array_flip($imagenes_producto);

  $class = $configurar_producto ? "campo-producto" : "";

	foreach($imagenes as $imagen): ?>

    <tr>
        <td class="align-middle text-center">
          <a href="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>" data-lightbox="example-set" data-title="<?php echo $imagen->nombre_imagen ?>">
            <div class="tabla-img-container">
              <img src="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>" alt="" class="example-image">
            </div>
          </a>
        </td>
        <td class="align-middle"><?php echo $imagen->nombre_imagen ?></td>
        <td class="text-center align-middle">
            <input type="checkbox" name="imagenes[]" value="<?php echo $imagen->id_imagen ?>" class="<?php echo $class ?>" <?php echo isset($imagenes_producto[$imagen->id_imagen]) ? "checked" : "" ?>>
        </td>
    </tr>

  <?php endforeach;

}

?>