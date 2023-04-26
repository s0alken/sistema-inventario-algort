<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_total_inventario.php";
require_once "cargar_inventario.php";

$id_sucursal = $_GET["id_sucursal"];

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar producto">
      </div>
    </div>

    <?php

      cargarTotalInventario($id_sucursal);

    ?>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable tabla-producto" id="myTable">
        <thead>
          <tr>
            <th scope="col">Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <th scope="col">Stock</th>
            <th scope="col">Total precio costo</th>
            <th scope="col">Total precio venta</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarInventario($id_sucursal);
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

    <?php

    require_once "../productos/modal_producto.php";
    require_once "../partials/snackbar.php";

    ?>

  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>