<?php

require_once "../controller/conexion.php";

function cargarprocedencias() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM procedencia WHERE habilitada");

    $procedencias = $query->fetchAll(); ?>

    <?php foreach($procedencias as $procedencia): ?>

        <tr data-id-procedencia="<?php echo $procedencia->id_procedencia ?>">
            <td><?php echo $procedencia->nombre_procedencia ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $procedencia->id_procedencia ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $procedencia->id_procedencia ?>">
                    <a href="<?php echo 'editar.php?id_procedencia=' . $procedencia->id_procedencia ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $procedencia->id_procedencia ?>" data-item="procedencia">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>