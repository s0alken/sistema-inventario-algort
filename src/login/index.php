<?php

require_once "../partials/header.php";
require_once "../controller/cargar_select.php";

?>

<body>

    <div class="row justify-content-center text-center m-0" style="">
      <div class="col-lg-5 p-0">
        <div class="box-content">
          <form action="login_controller.php" method="POST" id="form-login">

            <div class="form-group">
              <label for="nombre_usuario">Usuario:</label>
              <input type="text" name="nombre_usuario" class="form-control form-control-sm rounded-pill">
            </div>

            <div class="form-group">
              <label for="password">Contraseña:</label>
              <input type="password" name="password" class="form-control form-control-sm rounded-pill">
            </div>

            <div class="form-group">
              <label for="id_sucursal">Sucursal:</label>
              <select id="id_sucursal" name="id_sucursal" class="form-control form-control-sm rounded-pill">
                <?php cargarSucursal() ?>
              </select>
            </div>

            <div class="form-group">
              <div class="g-recaptcha" data-sitekey="6LeTfQcaAAAAAOdx9gO2f_QDR_-OhVHgI47FQKLN"></div>
            </div>

            <div class="form-group">
              <div class="alert alert-danger rounded-pill" role="alert"></div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Ingresar</button>
            </div>

            <hr class="mt-2 mb-3"/>

            <a href="http://algort.cl" target="_blank"><span>Desarrollado por Algort © <?php echo date("Y") ?></span></a>

          </form>
        </div>
      </div>
    </div>

<?php

require_once "../partials/footer.php";

?>