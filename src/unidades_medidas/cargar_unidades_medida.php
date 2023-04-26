<?php

require_once "../controller/conexion.php";

function cargarUnidadesMedida() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM unidad_medida WHERE habilitada");

    $unidades_medidas = $query->fetchAll(); ?>

    <?php foreach($unidades_medidas as $unidad_medida): ?>

        <tr data-id-unidad-medida="<?php echo $unidad_medida->id_unidad_medida ?>">
            <td><?php echo $unidad_medida->nombre_unidad_medida ?></td>
            <td><?php echo $unidad_medida->abreviacion_unidad_medida ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $unidad_medida->id_unidad_medida ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $unidad_medida->id_unidad_medida ?>">
                    <a href="<?php echo 'editar.php?id_unidad_medida=' . $unidad_medida->id_unidad_medida ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $unidad_medida->id_unidad_medida ?>" data-item="unidad_medida">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>