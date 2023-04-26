<?php

require_once "../controller/conexion.php";

function cargarSliders() {

	global $pdo;

    $query = $pdo->query("SELECT * FROM slider");

    $sliders = $query->fetchAll(); ?>

    <?php foreach($sliders as $slider): ?>

      <tr data-id-imagen="<?php echo $slider->id_slider ?>">
          <td class="align-middle text-center">
              <a href="<?php echo '../img/slider/' . $slider->imagen ?>" data-lightbox="example-set" data-title="<?php echo $slider->imagen ?>">
                <div class="tabla-img-container">
                  <img src="<?php echo '../img/slider/' . $slider->imagen . '?' . rand() ?>" alt="" class="example-image">
                </div>
              </a>
          </td>
          <td class="align-middle font-weight-bold" style="<?php echo 'color: ' . $slider->encabezado_color ?>"><?php echo $slider->encabezado ?></td>
          <td class="align-middle font-weight-bold" style="<?php echo 'color: ' . $slider->subtitulo_color ?>"><?php echo $slider->subtitulo ?></td>
          <td class="text-center align-middle">
              <div class="dropdown dropleft">
                <button class="btn btn-primary btn-sm" type="button" id="<?php echo $slider->id_slider ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="<?php echo $slider->id_slider ?>">
                  <button class="dropdown-item btn-cambiar-imagen" type="button" data-toggle="modal" data-target="#modal_cambiar_imagen" value="<?php echo $slider->id_slider ?>">Cambiar imagen</button>
                  <button class="dropdown-item btn-editar-texto-slider" type="button" value="<?php echo $slider->id_slider ?>">Editar texto</button>
                </div>
              </div>
          </td>
      </tr>

    <?php endforeach;

}

?>