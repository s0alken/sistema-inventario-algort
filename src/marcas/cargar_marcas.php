<?php

require_once "../controller/conexion.php";

function cargarMarcas() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM marca WHERE habilitada");

    $marcas = $query->fetchAll(); ?>

    <?php foreach($marcas as $marca): ?>

        <tr data-id-marca="<?php echo $marca->id_marca ?>">
            <td><?php echo $marca->nombre_marca ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $marca->id_marca ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $marca->id_marca ?>">
                    <a href="<?php echo 'editar.php?id_marca=' . $marca->id_marca ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $marca->id_marca ?>" data-item="marca">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>