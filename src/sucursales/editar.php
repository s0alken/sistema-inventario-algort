<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
  SELECT
  s.nombre_sucursal,
  s.direccion,
  s.n_direccion,
  s.id_ciudad,
  c.id_comuna,
  co.id_region
  FROM sucursal s
  INNER JOIN ciudad c ON c.id_ciudad = s.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  WHERE s.id_sucursal = :id_sucursal");

$query->bindValue(":id_sucursal", $_GET["id_sucursal"], PDO::PARAM_INT);
$query->execute();

$sucursal = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-sucursal-editar" action="<?php echo 'editar_controller.php?id_sucursal=' . $_GET['id_sucursal'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">

        <div class="form-group col-md-4">
          <label for="nombre_sucursal">Nombre sucursal:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_sucursal" name="nombre_sucursal" value="<?php echo $sucursal->nombre_sucursal ?>">
          <small id="nombre_sucursal_alerta" class="form-text text-danger font-weight-bold alerta">Esta sucursal ya existe</small>
        </div>

      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-3">
          <label for="direccion">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion" name="direccion" value="<?php echo $sucursal->direccion ?>">
        </div>

        <div class="form-group col-md-1">
          <label for="n_direccion">N°:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="n_direccion" name="n_direccion" value="<?php echo $sucursal->n_direccion ?>">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_region_nueva_sucursal">Region:</label>
          <select id="id_region_nueva_sucursal" name="id_region_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarRegion($sucursal->id_region) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_comuna_nueva_sucursal">Comuna:</label>
          <select id="id_comuna_nueva_sucursal" name="id_comuna_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarComuna($sucursal->id_comuna, $sucursal->id_region) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_ciudad_nueva_sucursal">Ciudad:</label>
          <select id="id_ciudad_nueva_sucursal" name="id_ciudad_nueva_sucursal" class="form-control form-control-sm rounded-pill">
            <?php cargarCiudad($sucursal->id_ciudad, $sucursal->id_comuna) ?>
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