<?php

function cargarCiudadesCompatibles($ciudades, $ciudades_compatibles, $seleccionar_todo = false){

  $ciudades_compatibles = array_flip($ciudades_compatibles);

	foreach($ciudades as $ciudad): ?>

      <tr>
          <td><?php echo $ciudad->nombre ?></td>
          <td><?php echo $ciudad->comuna ?></td>
          <td><?php echo $ciudad->region ?></td>
          <td class="text-center">
              <input type="checkbox" name="ciudades_compatibles[]" value="<?php echo $ciudad->id_ciudad ?>" <?php echo $seleccionar_todo || isset($ciudades_compatibles[$ciudad->id_ciudad]) ? "checked" : "" ?>>
          </td>
      </tr>

    <?php endforeach;
}

?>