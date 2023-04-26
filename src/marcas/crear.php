<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-marca-crear" action="crear_controller.php?redireccionar=true&configurar_producto=false" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_marca">Nombre marca:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_marca" name="nombre_marca">
          <small id="nombre_marca_alerta" class="form-text text-danger font-weight-bold alerta">Esta marca ya existe</small>
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