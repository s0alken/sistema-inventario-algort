<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$usuario = $_SESSION["sistema"]["usuario"];

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-cuenta-editar" action="editar_controller.php?&redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_usuario_cuenta">Nombre usuario:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_usuario_cuenta" name="nombre_usuario_cuenta" value="<?php echo $usuario->nombre_usuario ?>">
          <small id="nombre_usuario_cuenta_alerta" class="form-text text-danger font-weight-bold alerta">Este usuario ya está registrado</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input" id="cambiar_password" name="cambiar_password">
          <label class="form-check-label" for="cambiar_password">Cambiar password</label>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="password">Contraseña actual:</label>
          <input type="password" class="form-control form-control-sm rounded-pill" id="password" name="password" disabled="">
          <small id="password_alerta" class="form-text text-danger font-weight-bold alerta">La contraseña no debe tener espacios</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="password_nuevo">Contraseña nueva:</label>
          <input type="password" class="form-control form-control-sm rounded-pill" id="password_nuevo" name="password_nuevo" disabled="">
          <small id="password_nuevo_alerta" class="form-text text-danger font-weight-bold alerta">La contraseña no coincide</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="password_reingreso">Repite la contraseña:</label>
          <input type="password" class="form-control form-control-sm rounded-pill" id="password_reingreso" name="password_reingreso" disabled="">
          <small id="password_reingreso_alerta" class="form-text text-danger font-weight-bold alerta">La contraseña no coincide</small>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
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