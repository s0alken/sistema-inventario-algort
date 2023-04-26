<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-categoria-crear" action="crear_controller.php?redireccionar=true&configurar_producto=false" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_familia_nueva_categoria">Familia:</label>
          <select id="id_familia_nueva_categoria" name="id_familia_nueva_categoria" class="form-control form-control-sm rounded-pill">
            <?php cargarFamilia() ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_categoria">Nombre categoría:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_categoria" name="nombre_categoria">
          <small id="nombre_categoria_alerta" class="form-text text-danger font-weight-bold alerta">Esta categoría ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill btn_agregar_medida">Agregar medida</button>
        </div>
      </div>

      <div class="medidas">

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