<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";

$query = $pdo->query("SELECT color_sistema FROM personalizacion");

$color_sistema = $query->fetch(PDO::FETCH_COLUMN);

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-color-sistema-editar" action="editar_controller.php?redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="color_sistema">Color sistema:</label>
          <input type="color" class="form-control form-control-sm rounded-pill" id="color_sistema" name="color_sistema" value="<?php echo $color_sistema ?>">
        </div>
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