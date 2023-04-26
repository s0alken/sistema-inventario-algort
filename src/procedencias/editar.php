<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";

$query = $pdo->prepare("SELECT * FROM procedencia WHERE id_procedencia = :id_procedencia");
$query->bindValue(":id_procedencia", $_GET["id_procedencia"], PDO::PARAM_INT);
$query->execute();

$procedencia = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-procedencia-editar" action="<?php echo 'editar_controller.php?id_procedencia=' . $_GET['id_procedencia'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_procedencia">Nombre procedencia:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_procedencia" name="nombre_procedencia" value="<?php echo $procedencia->nombre_procedencia ?>">
          <small id="nombre_procedencia_alerta" class="form-text text-danger font-weight-bold alerta">Esta procedencia ya existe</small>
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