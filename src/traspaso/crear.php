<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "cargar_productos_traspaso.php";

$productos = $_SESSION["sistema"]["traspaso"]["productos"];
$observaciones = $_SESSION["sistema"]["traspaso"]["observaciones"];

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-traspaso-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row">

        <div class="col-sm-4 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="codigo_barras_traspaso" placeholder="Código de barras" autofocus>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_productos">Buscar producto manualmente</button>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" id="btn_vaciar_traspaso" class="btn btn-primary btn-sm btn-block rounded-pill">Vaciar traspaso</button>
        </div>

      </div>

      <!-- Productos  -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm mt-0 mt-sm-3" id="productos_traspaso">
          <thead>
            <tr>
              <th scope="col">Código de barras</th>
              <th scope="col">Producto</th>
              <th scope="col">Cantidad</th>
              <th scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>

            <?php cargarProductosTraspaso($productos) ?>

          </tbody>
        </table>
      </div>
      <!-- Fin Productos  -->

      <hr>

      <div class="form-row justify-content-center text-center">

        <div class="form-group col-md-6">
          <label for="observaciones">Observaciones:</label>
          <textarea id="observaciones" name="observaciones" class="form-control campo-traspaso"><?php echo $observaciones ?></textarea>
          <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 300 caracteres</small>
        </div>

      </div>

      <div class="form-row justify-content-center">
        <div class="col-sm-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Efectuar traspaso</button>
        </div>
      </div>
     
    </form>

    <?php

    require_once "modal_productos.php";
    require_once "modal_modificar_producto.php";
    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>