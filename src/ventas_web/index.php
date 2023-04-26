<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_ventas_web.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar venta web">
      </div>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable tabla-pointer tabla-detalle" id="myTable">
        <thead>
          <tr>
            <th scope="col">N° compra</th>
            <th scope="col">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">Rut</th>
            <th scope="col">Tipo documento</th>
            <th scope="col">Medio de pago</th>
            <th scope="col">Operador logístico</th>
            <th scope="col">Total</th>
            <th scope="col">Estado</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarVentasWeb();
          
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