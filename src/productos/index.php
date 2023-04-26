<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_productos.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="Buscar producto">
      </div>
      <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
        <div class="col-lg-4 mb-3">
          <a href="iniciar_producto.php"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Crear producto</button></a>
        </div>
      <?php endif ?>
      <div class="col-lg-4 mb-3">
        <button type="button" id="btn_exportar" class="btn btn-primary btn-sm btn-block rounded-pill">Exportar a Excel</button>
      </div>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm tabla-producto tabla-pointer" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
              <th scope="col">Valor costo neto</th>
            <?php endif ?>
            <th scope="col">Valor venta con IVA</th>
            <th scope="col">Stock</th>
            <th scope="col">Subcategoría</th>
            <th scope="col" class="no-exportar">Opciones</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarProductos();
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

    <?php

    require_once "modal_producto.php";
    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>