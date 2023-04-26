<?php

require_once "../controller/conexion.php";

function cargarSucursales() {

	global $pdo;

    $query = $pdo->query("
      SELECT
      s.id_sucursal,
      s.nombre_sucursal,
      CONCAT(s.direccion, ' #', s.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion
      FROM sucursal s
      INNER JOIN ciudad c ON c.id_ciudad = s.id_ciudad
      INNER JOIN comuna co ON co.id_comuna = c.id_comuna
      INNER JOIN region r ON r.id_region = co.id_region
      WHERE s.habilitada");

    $sucursales = $query->fetchAll(); ?>

    <?php foreach($sucursales as $sucursal): ?>

        <tr data-id-sucursal="<?php echo $sucursal->id_sucursal ?>">
            <td><?php echo $sucursal->nombre_sucursal ?></td>
            <td><?php echo $sucursal->direccion ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $sucursal->id_sucursal ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $sucursal->id_sucursal ?>">
                    <a href="<?php echo 'editar.php?id_sucursal=' . $sucursal->id_sucursal ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $sucursal->id_sucursal ?>" data-item="sucursal">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>