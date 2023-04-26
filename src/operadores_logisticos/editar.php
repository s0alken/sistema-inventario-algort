<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/conexion.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
  SELECT
  o.id_operador_logistico,
  o.nombre_operador_logistico,
  o.id_tipo_operador_logistico,
  t.nombre_tipo_operador_logistico,
  o.monto_minimo_habilitar,
  o.costo_despacho,
  o.habilitado_despacho_gratis,
  IF(o.habilitado_despacho_gratis, 'checked', '') AS habilitar_despacho_gratis,
  o.monto_minimo_despacho_gratis
  FROM operador_logistico o
  INNER JOIN tipo_operador_logistico t ON t.id_tipo_operador_logistico = o.id_tipo_operador_logistico
  WHERE id_operador_logistico = :id_operador_logistico");

$query->bindValue(":id_operador_logistico", $_GET["id_operador_logistico"], PDO::PARAM_INT);
$query->execute();

$operador_logistico = $query->fetch();

$query = $pdo->prepare("SELECT id_ciudad FROM operador_logistico_ciudad WHERE id_operador_logistico = :id_operador_logistico");
$query->bindValue(":id_operador_logistico", $_GET["id_operador_logistico"], PDO::PARAM_INT);
$query->execute();

$ciudades_compatibles = $query->fetchAll(PDO::FETCH_COLUMN);
$seleccionar_todo = false;

$opciones_delivery_class = $operador_logistico->nombre_tipo_operador_logistico === "delivery" ? "d-block" : "d-none";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-operador-logistico-editar" action="<?php echo 'editar_controller.php?id_operador_logistico=' . $_GET['id_operador_logistico'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_operador_logistico">Nombre operador logístico:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_operador_logistico" name="nombre_operador_logistico" value="<?php echo $operador_logistico->nombre_operador_logistico ?>">
          <small id="nombre_operador_logistico_alerta" class="form-text text-danger font-weight-bold alerta">Este operador logístico ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_tipo_operador_logistico">Tipo operador logístico:</label>
          <select id="id_tipo_operador_logistico" name="id_tipo_operador_logistico" class="form-control form-control-sm rounded-pill">
            <?php cargarTipoOperadorLogistico($operador_logistico->id_tipo_operador_logistico) ?>
          </select>
        </div>
      </div>

      <div id="opciones_delivery" class="<?php echo $opciones_delivery_class ?>">
        
        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="monto_minimo_habilitar">Monto mínimo para habilitar:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="monto_minimo_habilitar" name="monto_minimo_habilitar" value="<?php echo $operador_logistico->monto_minimo_habilitar ?>">
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="costo_despacho">Costo de despacho:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="costo_despacho" name="costo_despacho" value="<?php echo $operador_logistico->costo_despacho ?>">
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-check col-md-4 mb-3">
            <input type="checkbox" class="form-check-input" id="habilitar_despacho_gratis" name="habilitar_despacho_gratis" <?php echo $operador_logistico->habilitar_despacho_gratis ?>>
            <label class="form-check-label" for="habilitar_despacho_gratis">habilitar despacho gratis</label>
          </div>
        </div>

        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="monto_minimo_despacho_gratis">Monto mínimo para despacho gratis:</label>
            <input type="number" min="0" class="form-control form-control-sm rounded-pill" id="monto_minimo_despacho_gratis" name="monto_minimo_despacho_gratis" value="<?php echo $operador_logistico->monto_minimo_despacho_gratis ?>" <?php echo $operador_logistico->habilitado_despacho_gratis ? "" : "disabled" ?>>
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