<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";
require_once "../controller/cargar_select.php";

$ciudades_compatibles = [];
$seleccionar_todo = true;

//obteniendo el primer operador logístico seleccionado alfabéticamente
$query = $pdo->query("SELECT * FROM tipo_operador_logistico ORDER BY nombre_tipo_operador_logistico");

$tipo_operador_logistico = $query->fetch();

$opciones_delivery_class = $tipo_operador_logistico->nombre_tipo_operador_logistico === "delivery" ? "d-block" : "d-none";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-operador-logistico-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_operador_logistico">Nombre operador logístico:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_operador_logistico" name="nombre_operador_logistico">
          <small id="nombre_operador_logistico_alerta" class="form-text text-danger font-weight-bold alerta">Este operador logístico ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_tipo_operador_logistico">Tipo operador logístico:</label>
          <select id="id_tipo_operador_logistico" name="id_tipo_operador_logistico" class="form-control form-control-sm rounded-pill">
            <?php cargarTipoOperadorLogistico($tipo_operador_logistico->id_tipo_operador_logistico) ?>
          </select>
        </div>
      </div>

      <div id="opciones_delivery" class="<?php echo $opciones_delivery_class ?>">
        
        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="monto_minimo_habilitar">Monto mínimo para habilitar:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="monto_minimo_habilitar" name="monto_minimo_habilitar" value="0">
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="costo_despacho">Costo de despacho:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="costo_despacho" name="costo_despacho" value="0">
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-check col-md-4 mb-3">
            <input type="checkbox" class="form-check-input" id="habilitar_despacho_gratis" name="habilitar_despacho_gratis">
            <label class="form-check-label" for="habilitar_despacho_gratis">habilitar despacho gratis</label>
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="monto_minimo_despacho_gratis">Monto mínimo para despacho gratis:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="monto_minimo_despacho_gratis" name="monto_minimo_despacho_gratis" value="0" disabled>
          </div>
        </div>

        <div class="form-row justify-content-center">
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-warning btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_ciudades_compatibles">
            Seleccionar ciudades compatibles
            </button>
          </div>
        </div>

      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>

    </form>
    
    <?php

    require_once "modal_ciudades_compatibles.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>