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
  FROM cliente cl
  INNER JOIN persona p ON p.id_persona = cl.id_persona
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE p.habilitada");

$clientes = $query->fetchAll();

?>

<div class="modal fade bd-example-modal-lg" id="modal_clientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_clientes" id="input_tabla_clientes" class="form-control form-control-sm rounded-pill" placeholder="Buscar cliente">
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_clientes">
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

            <?php foreach ($clientes as $cliente): ?>

              <tr>
                <td><?php echo $cliente->rut ?></td>
                <td><?php echo $cliente->nombre ?></td>
                <td><?php echo $cliente->direccion ?></td>
                <td><?php echo $cliente->telefono ?></td>
                <td><?php echo $cliente->correo ?></td>
                <td>
                  <button class="btn btn-primary btn-sm btn-block rounded-pill btn-agregar-cliente-cotizacion" type="button" value="<?php echo $cliente->rut ?>">Seleccionar</button></td>
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