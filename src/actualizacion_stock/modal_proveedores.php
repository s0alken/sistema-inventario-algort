<?php

require_once "../controller/conexion.php";

$query = $pdo->query("
  SELECT
  p.rut,
  p.nombre_persona AS nombre,
  p.giro,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion,
  p.telefono,
  p.correo
  FROM proveedor pr
  INNER JOIN persona p ON p.id_persona = pr.id_persona
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE p.habilitada");

$proveedores = $query->fetchAll();

?>

<div class="modal fade bd-example-modal-lg" id="modal_proveedores" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_proveedores" id="input_tabla_proveedores" class="form-control form-control-sm rounded-pill" placeholder="Buscar proveedor">
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_proveedores">
            <thead>
              <tr>
                <th scope="col">Rut</th>
                <th scope="col">Nombre</th>
                <th scope="col">Dirección</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Correo</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>

            <?php foreach ($proveedores as $proveedor): ?>

              <tr>
                <td><?php echo $proveedor->rut ?></td>
                <td><?php echo $proveedor->nombre ?></td>
                <td><?php echo $proveedor->direccion ?></td>
                <td><?php echo $proveedor->telefono ?></td>
                <td><?php echo $proveedor->correo ?></td>
                <td>
                  <button class="btn btn-primary btn-sm btn-block rounded-pill btn-agregar-proveedor-actualizacion-stock" type="button" value="<?php echo $proveedor->rut ?>">Seleccionar</button></td>
              </tr>
              
            <?php endforeach ?>

            </tbody>
          </table>
        </div>
        <!-- Fin Tabla  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>