<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";

$query = $pdo->prepare("SELECT * FROM familia WHERE id_familia = :id_familia");
$query->bindValue(":id_familia", $_GET["id_familia"], PDO::PARAM_INT);
$query->execute();

$familia = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-familia-editar" action="<?php echo 'editar_controller.php?id_familia=' . $_GET['id_familia'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_familia">Nombre familia:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_familia" name="nombre_familia" value="<?php echo $familia->nombre_familia ?>">
          <small id="nombre_familia_alerta" class="form-text text-danger font-weight-bold alerta">Esta familia ya existe</small>
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