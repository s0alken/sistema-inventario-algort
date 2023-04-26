<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("SELECT * FROM locker WHERE id_locker = :id_locker");
$query->bindValue(":id_locker", $_GET["id_locker"], PDO::PARAM_INT);
$query->execute();

$locker = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-locker-editar" action="<?php echo 'editar_controller.php?id_locker=' . $_GET['id_locker'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_bodega_nuevo_locker">Bodega:</label>
          <select id="id_bodega_nuevo_locker" name="id_bodega_nuevo_locker" class="form-control form-control-sm rounded-pill">
            <?php cargarBodega($locker->id_bodega) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_locker">Nombre locker:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_locker" name="nombre_locker" value="<?php echo $locker->nombre_locker ?>">
          <small id="nombre_locker_alerta" class="form-text text-danger font-weight-bold alerta">Este locker ya existe</small>
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