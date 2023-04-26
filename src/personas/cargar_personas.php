<?php

require_once "../controller/conexion.php";

function cargarPersonas($tipo_persona) {

	global $pdo;

    $query = $pdo->query("
      SELECT
      p.id_persona,
      p.rut,
      p.nombre_persona,
      p.giro,
      CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion,
      p.telefono,
      p.correo,
      p.puntos
      FROM $tipo_persona tp
      INNER JOIN persona p ON p.id_persona = tp.id_persona
      INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
      INNER JOIN comuna co ON co.id_comuna = c.id_comuna
      INNER JOIN region r ON r.id_region = co.id_region
      WHERE p.habilitada");

    $personas = $query->fetchAll(); ?>

    <?php foreach($personas as $persona): ?>

        <tr data-id-persona="<?php echo $persona->id_persona ?>">
            <td><?php echo $persona->rut ?></td>
            <td><?php echo $persona->nombre_persona ?></td>
            <td><?php echo $persona->direccion ?></td>
            <td><?php echo $persona->telefono ?></td>
            <td><?php echo $persona->correo ?></td>
            <?php if ($tipo_persona === "cliente"): ?>
              <td><?php echo preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $persona->puntos) ?></td>
            <?php endif ?>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $persona->id_persona ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $persona->id_persona ?>">
                    <a href="<?php echo 'editar.php?id_persona=' . $persona->id_persona . '&tipo_persona=' . $tipo_persona ?>"><button class="dropdown-item" type="button">Editar</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>