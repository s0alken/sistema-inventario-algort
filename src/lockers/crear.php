<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-locker-crear" action="crear_controller.php?redireccionar=true&configurar_producto=false" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_bodega_nuevo_locker">Bodega:</label>
          <select id="id_bodega_nuevo_locker" name="id_bodega_nuevo_locker" class="form-control form-control-sm rounded-pill">
            <?php cargarBodega() ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_locker">Nombre locker:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_locker" name="nombre_locker">
          <small id="nombre_locker_alerta" class="form-text text-danger font-weight-bold alerta">Este locker ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>

    </form>
    
    <?php

    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>