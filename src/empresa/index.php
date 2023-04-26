<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/conexion.php";

$query = $pdo->query("
  SELECT * FROM empresa e
  INNER JOIN persona p ON p.id_persona = e.id_persona
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna");

$empresa = $query->fetch();

$query = $pdo->query("SELECT * FROM datos_transferencia");

$datos_transferencia = $query->fetch();

$query = $pdo->query("SELECT IF(acumula_puntos, 'checked', '') FROM personalizacion");

$acumula_puntos = $query->fetch(PDO::FETCH_COLUMN);

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-persona-editar" action="editar_controller.php?redireccionar=true" method="POST">

      <h5 class="text-center">Datos empresa</h5>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="rut_empresa">Rut:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_empresa" name="rut_empresa" value="<?php echo $empresa->rut ?>">
          <small id="rut_empresa_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
          <small id="rut_empresa_alerta" class="form-text text-danger font-weight-bold alerta">Este Rut ya existe</small>
        </div>

        <div class="form-group col-md-4">
          <label for="nombre_persona">Razón social:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_persona" name="nombre_persona" value="<?php echo $empresa->nombre_persona ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="nombre_fantasia">Nombre de fantasía:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_fantasia" name="nombre_fantasia" value="<?php echo $empresa->nombre_fantasia ?>">
        </div>

        <div class="form-group col-12">
          <label for="giro">Giro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="giro" name="giro" value="<?php echo $empresa->giro ?>">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-5">
          <label for="direccion">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion" name="direccion" value="<?php echo $empresa->direccion ?>">
        </div>

        <div class="form-group col-md-1">
          <label for="n_direccion">N°:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="n_direccion" name="n_direccion" value="<?php echo $empresa->n_direccion ?>">
        </div>

        <div class="form-group col-md-2">
          <label for="id_region">Region:</label>
          <select id="id_region" name="id_region" class="form-control form-control-sm rounded-pill">
            <?php cargarRegion($empresa->id_region) ?>
          </select>
        </div>

        <div class="form-group col-md-2">
          <label for="id_comuna">Comuna:</label>
          <select id="id_comuna" name="id_comuna" class="form-control form-control-sm rounded-pill">
            <?php cargarComuna($empresa->id_comuna, $empresa->id_region) ?>
          </select>
        </div>

        <div class="form-group col-md-2">
          <label for="id_ciudad">Ciudad:</label>
          <select id="id_ciudad" name="id_ciudad" class="form-control form-control-sm rounded-pill">
            <?php cargarCiudad($empresa->id_ciudad, $empresa->id_comuna) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="telefono">Teléfono:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono" name="telefono" value="<?php echo $empresa->telefono ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="telefono_alternativo">Teléfono 2:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_alternativo" name="telefono_alternativo" value="<?php echo $empresa->telefono_alternativo ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="correo">Correo:</label>
          <input type="email" class="form-control form-control-sm rounded-pill" id="correo" name="correo" value="<?php echo $empresa->correo ?>">
        </div>
      </div>

      <hr>

      <h5 class="text-center">Datos transferencia</h5>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="banco">Banco:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="banco" name="banco" value="<?php echo $datos_transferencia->banco ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="tipo_cuenta">Tipo cuenta:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="tipo_cuenta" name="tipo_cuenta" value="<?php echo $datos_transferencia->tipo_cuenta ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="n_cuenta">N° cuenta:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="n_cuenta" name="n_cuenta" value="<?php echo $datos_transferencia->n_cuenta ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="nombre">Nombre titular cuenta:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre" name="nombre" value="<?php echo $datos_transferencia->nombre ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="rut_banco">Rut:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_banco" name="rut_banco" value="<?php echo $datos_transferencia->rut ?>" data-tipo-persona="banco">
          <small id="rut_banco_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
        </div>

        <div class="form-group col-md-4">
          <label for="correo_banco">Correo:</label>
          <input type="email" class="form-control form-control-sm rounded-pill" id="correo_banco" name="correo_banco" value="<?php echo $datos_transferencia->correo ?>">
        </div>
      </div>

      <hr>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="logo_empresa">Logo de la empresa:</label>
          <label class="btn btn-success btn-block btn-sm rounded-pill">
            <input class="d-none" type="file" id="logo_empresa" name="logo_empresa" data-btn-imagen="subir-imagen" accept="image/png">
            <span class="subir-imagen"><i class="fas fa-upload mr-2 fa-fw"></i>Seleccionar</span>
          </label>
          <small class="text-muted">El logo debe tener un fondo transparente para una óptima visibilidad de la misma en el sitio web</small>
        </div>
      </div>

      <hr>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4">
          <input type="checkbox" class="form-check-input" id="acumula_puntos" name="acumula_puntos" <?php echo $acumula_puntos ?>>
          <label class="form-check-label" for="acumula_puntos">Acumular puntos en productos</label>
        </div>
      </div>

      <hr>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
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