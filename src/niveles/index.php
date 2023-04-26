<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_niveles.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar nivel">
      </div>
      <div class="col-lg-4 mb-3">
        <a href="crear.php"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Crear nivel</button></a>
      </div>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable" id="myTable">
        <thead>
          <tr>
            <th scope="col">Nivel</th>
            <th scope="col">Sección</th>
            <th scope="col">Locker</th>
            <th scope="col">Bodega</th>
            <th scope="col">Opciones</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarNiveles();
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

    <?php

    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>

  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>