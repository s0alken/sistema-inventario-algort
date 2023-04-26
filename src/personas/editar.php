<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/conexion.php";

$tipo_persona = $_GET["tipo_persona"];
$persona_alternativa = $tipo_persona === "cliente" ? "proveedor" : "cliente";

$query = $pdo->prepare("
  SELECT * FROM persona p
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  WHERE id_persona = :id_persona");

$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);
$query->execute();

$persona = $query->fetch();

//comprobando si está habilitada como persona alternativa
$query = $pdo->prepare("SELECT * FROM " . $persona_alternativa . " WHERE id_persona = :id_persona LIMIT 1");
$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_STR);
$query->execute();

$checked = $query->fetch() ? "checked" : "";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-persona-editar" action="<?php echo 'editar_controller.php?id_persona=' . $_GET["id_persona"] . '&tipo_persona=' . $tipo_persona . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-3">
          <label for="rut"><span class="text-danger">(*)</span> Rut:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut" name="rut" value="<?php echo $persona->rut ?>">
          <small id="rut_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
          <small id="rut_alerta" class="form-text text-danger font-weight-bold alerta">Este Rut ya existe</small>
        </div>

        <div class="form-group col-md-3">
          <label for="nombre_persona"><span class="text-danger">(*)</span> Nombre:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_persona" name="nombre_persona" value="<?php echo $persona->nombre_persona ?>">
        </div>

        <div class="form-group col-md-6">
          <label for="giro">Giro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="giro" name="giro" value="<?php echo $persona->giro ?>">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-5">
          <label for="direccion"><span class="text-danger">(*)</span> Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion" name="direccion" value="<?php echo $persona->direccion ?>">
        </div>

        <div class="form-group col-md-1">
          <label for="n_direccion"><span class="text-danger">(*)</span> N°:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="n_direccion" name="n_direccion" value="<?php echo $persona->n_direccion ?>">
        </div>

        <div class="form-group col-md-2">
          <label for="id_region">Region:</label>
          <select id="id_region" name="id_region" class="form-control form-control-sm rounded-pill">
            <?php cargarRegion($persona->id_region) ?>
          </select>
        </div>

        <div class="form-group col-md-2">
          <label for="id_comuna">Comuna:</label>
          <select id="id_comuna" name="id_comuna" class="form-control form-control-sm rounded-pill">
            <?php cargarComuna($persona->id_comuna, $persona->id_region) ?>
          </select>
        </div>

        <div class="form-group col-md-2">
          <label for="id_ciudad">Ciudad:</label>
          <select id="id_ciudad" name="id_ciudad" class="form-control form-control-sm rounded-pill">
            <?php cargarCiudad($persona->id_ciudad, $persona->id_comuna) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="telefono"><span class="text-danger">(*)</span> Teléfono:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono" name="telefono" value="<?php echo $persona->telefono ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="telefono_alternativo">Teléfono 2:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_alternativo" name="telefono_alternativo" value="<?php echo $persona->telefono_alternativo ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="correo"><span class="text-danger">(*)</span> Correo:</label>
          <input type="email" class="form-control form-control-sm rounded-pill" id="correo" name="correo" value="<?php echo $persona->correo ?>">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input" id="persona_alternativa" name="persona_alternativa" value="<?php echo $persona_alternativa ?>" <?php echo $checked ?>>
          <label class="form-check-label" for="persona_alternativa"><?php echo "Habilitar como " . $persona_alternativa ?></label>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <span class="text-danger">(*) Campos obligatorios</span>
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