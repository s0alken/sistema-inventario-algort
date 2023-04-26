<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form id="form_elegir_sucursal" action="validar_origen_destino.php" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_sucursal_origen">Sucursal de origen:</label>
          <select id="id_sucursal_origen" name="id_sucursal_origen" class="form-control form-control-sm rounded-pill">
            <?php cargarSucursal() ?>
          </select>
        </div>

        <div class="form-group col-md-4">
          <label for="id_sucursal_destino">Sucursal de destino:</label>
          <select id="id_sucursal_destino" name="id_sucursal_destino" class="form-control form-control-sm rounded-pill">
            <?php cargarSucursal() ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Continuar</button>
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