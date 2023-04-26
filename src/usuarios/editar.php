<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("SELECT id_permiso FROM usuario WHERE id_usuario = :id_usuario");

$query->bindValue(":id_usuario", $_GET["id_usuario"], PDO::PARAM_INT);
$query->execute();

$usuario = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-usuario-editar" action="<?php echo 'editar_controller.php?id_usuario=' . $_GET['id_usuario'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_permiso">Permiso:</label>
          <select id="id_permiso" name="id_permiso" class="form-control form-control-sm rounded-pill">
            <?php cargarPermiso($usuario->id_permiso) ?>
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