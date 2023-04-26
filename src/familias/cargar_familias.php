<?php

require_once "../controller/conexion.php";

function cargarFamilias() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM familia WHERE habilitada");

    $familias = $query->fetchAll(); ?>

    <?php foreach($familias as $familia): ?>

        <tr data-id-familia="<?php echo $familia->id_familia ?>">
            <td><?php echo $familia->nombre_familia ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $familia->id_familia ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $familia->id_familia ?>">
                    <a href="<?php echo 'editar.php?id_familia=' . $familia->id_familia ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $familia->id_familia ?>" data-item="familia">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>