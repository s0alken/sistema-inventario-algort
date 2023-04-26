<?php

require_once "../controller/conexion.php";
require_once "cargar_ciudades_compatibles.php";

$query = $pdo->query("
  SELECT
  c.id_ciudad,
  c.nombre_ciudad AS nombre,
  co.nombre_comuna AS comuna,
  r.nombre_region AS region
  FROM ciudad c
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  ORDER BY c.nombre_ciudad");

$ciudades = $query->fetchAll();

?>

<div class="modal fade bd-example-modal-lg" id="modal_ciudades_compatibles" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_ciudades_compatibles" id="input_tabla_ciudades_compatibles" class="form-control form-control-sm rounded-pill" placeholder="Buscar ciudad">
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_ciudades_compatibles">
            <thead>
              <tr>
                <th scope="col">Ciudad</th>
                <th scope="col">Comuna</th>
                <th scope="col">Región</th>
                <th scope="col">Seleccionar</th>
              </tr>
            </thead>
            <tbody>

            <?php cargarCiudadesCompatibles($ciudades, $ciudades_compatibles, $seleccionar_todo) ?>

            </tbody>
          </table>
        </div>
        <!-- Fin Tabla  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm rounded-pill" data-dismiss="modal">Aceptar</button>
        <button type="button" class="btn btn-warning btn-sm rounded-pill btn-seleccionar-todo">Seleccionar todo</button>
        <button type="button" class="btn btn-success btn-sm rounded-pill btn-deseleccionar-todo">Deseleccionar todo</button>
      </div>
    </div>
  </div>
</div>