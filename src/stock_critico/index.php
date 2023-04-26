<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_stock_critico.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar producto en stock crítico">
      </div>
      <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
        <div class="col-lg-4 mb-3">
          <a href="../productos/iniciar_producto.php"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Crear producto</button></a>
        </div>
      <?php endif ?>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable tabla-producto tabla-pointer" id="myTable">
        <thead>
          <tr>
            <th scope="col">Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <th scope="col">Precio</th>
            <th scope="col">Stock</th>
            <th scope="col">Subcategoría</th>
            <th scope="col">Opciones</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarStockCritico();
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

    <!-- Modals  -->

      <?php

      require_once "../productos/modal_producto.php";
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