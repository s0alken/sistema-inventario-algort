<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-usuario-crear" action="crear_controller.php?&redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_usuario">Nombre usuario:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_usuario" name="nombre_usuario">
          <small id="nombre_usuario_alerta" class="form-text text-danger font-weight-bold alerta">Este usuario ya está registrado</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="password">Contraseña:</label>
          <input type="password" class="form-control form-control-sm rounded-pill" id="password" name="password">
          <small id="password_alerta" class="form-text text-danger font-weight-bold alerta">La contraseña no debe tener espacios</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="password_reingreso">Repite la contraseña:</label>
          <input type="password" class="form-control form-control-sm rounded-pill" id="password_reingreso" name="password_reingreso">
          <small id="password_reingreso_alerta" class="form-text text-danger font-weight-bold alerta">La contraseña no coincide</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_permiso">Permiso:</label>
          <select id="id_permiso" name="id_permiso" class="form-control form-control-sm rounded-pill">
            <?php cargarPermiso() ?>
          </select>
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