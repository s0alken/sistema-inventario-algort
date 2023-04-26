<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

//obteniendo id_region de la primera región ordenada alfabéticamente
$query = $pdo->query("SELECT id_region FROM region ORDER BY nombre_region LIMIT 1");

$id_region = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo id_comuna de la primera comuna ordenada alfabéticamente
$query = $pdo->prepare("SELECT id_comuna FROM comuna WHERE id_region = :id_region ORDER BY nombre_comuna LIMIT 1");

$query->bindValue(":id_region", $id_region, PDO::PARAM_INT);
$query->execute();

$id_comuna = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo id_ciudad de la primera ciudad ordenada alfabéticamente
$query = $pdo->prepare("SELECT id_ciudad FROM ciudad WHERE id_comuna = :id_comuna ORDER BY nombre_ciudad LIMIT 1");

$query->bindValue(":id_comuna", $id_comuna, PDO::PARAM_INT);
$query->execute();

$id_ciudad = $query->fetch(PDO::FETCH_COLUMN);

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-sucursal-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">

        <div class="form-group col-md-4">
          <label for="nombre_sucursal">Nombre sucursal:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_sucursal" name="nombre_sucursal">
          <small id="nombre_sucursal_alerta" class="form-text text-danger font-weight-bold alerta">Esta sucursal ya existe</small>
        </div>

      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-3">
          <label for="direccion">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion" name="direccion">
        </div>

        <div class="form-group col-md-1">
          <label for="n_direccion">N°:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="n_direccion" name="n_direccion">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_region_nueva_sucursal">Region:</label>
          <select id="id_region_nueva_sucursal" name="id_region_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarRegion($id_region) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_comuna_nueva_sucursal">Comuna:</label>
          <select id="id_comuna_nueva_sucursal" name="id_comuna_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarComuna($id_comuna, $id_region) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_ciudad_nueva_sucursal">Ciudad:</label>
          <select id="id_ciudad_nueva_sucursal" name="id_ciudad_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarCiudad($id_ciudad, $id_comuna) ?>
          </select>
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