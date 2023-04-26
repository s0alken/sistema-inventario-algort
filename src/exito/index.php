<?php

require_once '../partials/header.php';
require_once '../partials/navbar.php';
require_once '../partials/sidebar.php';

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div id="exito" class="row justify-content-center text-center my-5">
      <div class="col-md-8">
        <div>
            <i class="fas fa-check-circle text-success"></i>
        </div>
        <div class="mt-4">
            <h3>¡Muy bien!</h3>
        </div>
        <div>
            <h4><?php echo $_SESSION["sistema"]["mensaje"] ?></h4>
        </div>
        <div class="form-row justify-content-center mt-3">
          <div class="form-group col-md-6">
            <a href="<?php echo $_SESSION['sistema']['redireccion'] ?>"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Aceptar</button></a>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once '../partials/footer.php';

?>