<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
  SELECT
  s.nombre_seccion,
  s.id_locker,
  l.id_bodega
  FROM seccion s
  INNER JOIN locker l ON l.id_locker = s.id_locker 
  INNER JOIN bodega b ON b.id_bodega = l.id_bodega
  WHERE s.id_seccion = :id_seccion");

$query->bindValue(":id_seccion", $_GET["id_seccion"], PDO::PARAM_INT);
$query->execute();

$seccion = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-seccion-editar" action="<?php echo 'editar_controller.php?id_seccion=' . $_GET['id_seccion'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_bodega_nueva_seccion">Bodega:</label>
          <select id="id_bodega_nueva_seccion" name="id_bodega_nueva_seccion" class="form-control form-control-sm rounded-pill">
            <?php cargarBodega($seccion->id_bodega, false, false, true) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_locker_nueva_seccion">Locker:</label>
          <select id="id_locker_nueva_seccion" name="id_locker_nueva_seccion" class="form-control form-control-sm rounded-pill">
            <?php cargarLocker($seccion->id_locker, $seccion->id_bodega) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_seccion">Nombre sección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_seccion" name="nombre_seccion" value="<?php echo $seccion->nombre_seccion ?>">
          <small id="nombre_seccion_alerta" class="form-text text-danger font-weight-bold alerta">Esta sección ya existe</small>
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