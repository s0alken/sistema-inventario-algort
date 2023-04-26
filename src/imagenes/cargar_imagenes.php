<?php

require_once "../controller/conexion.php";

function cargarImagenes() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM imagen");

    $imagenes = $query->fetchAll(); ?>

    <?php foreach($imagenes as $imagen): ?>

      <tr data-id-imagen="<?php echo $imagen->id_imagen ?>">
          <td class="align-middle text-center">
              <a href="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>" data-lightbox="example-set" data-title="<?php echo $imagen->nombre_imagen ?>">
                <div class="tabla-img-container">
                  <img src="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>" alt="" class="example-image">
                </div>
              </a>
          </td>
          <td class="align-middle"><?php echo $imagen->nombre_imagen ?></td>
          <td class="text-center align-middle">
              <div class="dropdown dropleft">
                <button class="btn btn-primary btn-sm" type="button" id="<?php echo $imagen->id_imagen ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="<?php echo $imagen->id_imagen ?>">
                  <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $imagen->id_imagen ?>" data-item="imagen">Eliminar</button>
                </div>
              </div>
          </td>
      </tr>

    <?php endforeach;

}

?>