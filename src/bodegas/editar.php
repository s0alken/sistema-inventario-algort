<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";

$query = $pdo->prepare("SELECT * FROM bodega WHERE id_bodega = :id_bodega");
$query->bindValue(":id_bodega", $_GET["id_bodega"], PDO::PARAM_INT);
$query->execute();

$bodega = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-bodega-editar" action="<?php echo 'editar_controller.php?id_bodega=' . $_GET['id_bodega'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_bodega">Nombre bodega:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_bodega" name="nombre_bodega" value="<?php echo $bodega->nombre_bodega ?>">
          <small id="nombre_bodega_alerta" class="form-text text-danger font-weight-bold alerta">Esta bodega ya existe</small>
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