<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_promociones.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar promoción">
      </div>
      <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
        <div class="col-lg-4 mb-3">
          <a href="iniciar_promocion.php"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Crear promoción</button></a>
        </div>
      <?php endif ?>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable tabla-pointer tabla-detalle" id="myTable">
        <thead>
          <tr>
            <th scope="col">Promoción</th>
            <th scope="col">Inicio</th>
            <th scope="col">Término</th>
            <th scope="col">Desc. en %</th>
            <th scope="col">Desc. en $</th>
            <th scope="col">Hasta agotar stock</th>
            <th scope="col">Estado</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarPromociones();
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

    <?php

    require_once "modal_detalle.php";
    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>