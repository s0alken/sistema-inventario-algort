<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";

$query = $pdo->prepare("SELECT * FROM unidad_medida WHERE id_unidad_medida = :id_unidad_medida");
$query->bindValue(":id_unidad_medida", $_GET["id_unidad_medida"], PDO::PARAM_INT);
$query->execute();

$unidad_medida = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-unidad-medida-editar" action="<?php echo 'editar_controller.php?id_unidad_medida=' . $_GET['id_unidad_medida'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_unidad_medida">Nombre unidad de medida:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_unidad_medida" name="nombre_unidad_medida" value="<?php echo $unidad_medida->nombre_unidad_medida ?>">
          <small id="nombre_unidad_medida_alerta" class="form-text text-danger font-weight-bold alerta">Esta unidad de medida ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="abreviacion_unidad_medida">Abreviación unidad de medida:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="abreviacion_unidad_medida" name="abreviacion_unidad_medida" value="<?php echo $unidad_medida->abreviacion_unidad_medida ?>">
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