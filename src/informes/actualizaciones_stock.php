<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_actualizaciones_stock.php";

date_default_timezone_set("America/Santiago");

$fecha = date("Y-m-d");

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="fecha_inicio">Filtrar desde:</label>
        <input type="date" class="form-control form-control-sm rounded-pill" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha ?>" max="<?php echo $fecha ?>">
      </div>
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="fecha_fin">Hasta:</label>
        <input type="date" class="form-control form-control-sm rounded-pill" id="fecha_termino" name="fecha_termino" value="<?php echo $fecha ?>" max="<?php echo $fecha ?>">
      </div>
      <div class="col-lg-3 mb-3 d-flex align-items-end">
        <button type="button" id="btn_filtrar" class="btn btn-primary btn-sm btn-block rounded-pill" value="cargar_actualizaciones_stock.php">Filtrar</button>
      </div>
      <div class="col-lg-3 mb-3 d-flex align-items-end">
        <button type="button" id="btn_exportar" class="btn btn-primary btn-sm btn-block rounded-pill">Exportar a Excel</button>
      </div>
    </div>

    <div id="resultados"><?php cargarActualizacionesStock() ?></div>

    <!-- Modals  -->

      <?php

      require_once "../partials/modal_eliminar_item.php";
      require_once "../partials/snackbar.php";

      ?>

    <!-- Fin Modals  -->
    
  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>