<?php

require_once "../controller/conexion.php";

function cargarOperadoresLogisticos() {

	global $pdo;

    $query = $pdo->query("
      SELECT
      o.id_operador_logistico,
      o.nombre_operador_logistico,
      t.nombre_tipo_operador_logistico,
      IF(o.habilitado, 'Habilitado', 'Deshabilitado') AS estado,
      IF(o.habilitado, 'Deshabilitar', 'Habilitar') AS btn_estado
      FROM operador_logistico o
      INNER JOIN tipo_operador_logistico t ON t.id_tipo_operador_logistico = o.id_tipo_operador_logistico
      WHERE NOT o.eliminado");

    $operadores_logisticos = $query->fetchAll(); ?>

    <?php foreach($operadores_logisticos as $operador_logistico): ?>

        <tr data-id-operador-logistico="<?php echo $operador_logistico->id_operador_logistico ?>">
            <td><?php echo $operador_logistico->nombre_operador_logistico ?></td>
            <td><?php echo $operador_logistico->nombre_tipo_operador_logistico ?></td>
            <td><?php echo $operador_logistico->estado ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $operador_logistico->id_operador_logistico ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $operador_logistico->id_operador_logistico ?>">
                    <a href="<?php echo 'editar.php?id_operador_logistico=' . $operador_logistico->id_operador_logistico ?>">
                      <button class="dropdown-item" type="button">Editar</button>
                    </a>
                    <form class="form-item" action="<?php echo 'cambiar_estado.php?redireccionar=false&id_operador_logistico=' . $operador_logistico->id_operador_logistico ?>" method="POST">
                      <button class="dropdown-item" type="submit"><?php echo $operador_logistico->btn_estado ?></button>
                    </form>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $operador_logistico->id_operador_logistico ?>" data-item="operador_logistico">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>