<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
  SELECT
  n.nombre_nivel,
  n.id_seccion,
  s.id_locker,
  l.id_bodega
  FROM nivel n
  INNER JOIN seccion s ON s.id_seccion = n.id_seccion 
  INNER JOIN locker l ON l.id_locker = s.id_locker 
  INNER JOIN bodega b ON b.id_bodega = l.id_bodega
  WHERE n.id_nivel = :id_nivel");

$query->bindValue(":id_nivel", $_GET["id_nivel"], PDO::PARAM_INT);
$query->execute();

$nivel = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-nivel-editar" action="<?php echo 'editar_controller.php?id_nivel=' . $_GET['id_nivel'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_bodega_nuevo_nivel">Bodega:</label>
          <select id="id_bodega_nuevo_nivel" name="id_bodega_nuevo_nivel" class="form-control form-control-sm rounded-pill">
            <?php cargarBodega($nivel->id_bodega, false, true) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_locker_nuevo_nivel">Locker:</label>
          <select id="id_locker_nuevo_nivel" name="id_locker_nuevo_nivel" class="form-control form-control-sm rounded-pill">
            <?php cargarLocker($nivel->id_locker, $nivel->id_bodega, false, true) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_seccion_nuevo_nivel">Sección:</label>
          <select id="id_seccion_nuevo_nivel" name="id_seccion_nuevo_nivel" class="form-control form-control-sm rounded-pill">
            <?php cargarSeccion($nivel->id_seccion, $nivel->id_locker) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_nivel">Nombre nivel:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_nivel" name="nombre_nivel" value="<?php echo $nivel->nombre_nivel ?>">
          <small id="nombre_nivel_alerta" class="form-text text-danger font-weight-bold alerta">Este nivel ya existe</small>
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
