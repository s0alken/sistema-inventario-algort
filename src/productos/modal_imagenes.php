<?php

require_once "../controller/conexion.php";

$query = $pdo->query("SELECT * FROM imagen");

$imagenes = $query->fetchAll();

$class = $configurar_producto ? "btn btn-primary btn-sm rounded-pill campo-producto" : "btn btn-primary btn-sm rounded-pill";

?>

<div class="modal fade bd-example-modal-lg" id="modal_imagenes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_imagenes" id="input_tabla_imagenes" class="form-control form-control-sm rounded-pill" placeholder="Buscar imagen">
          </div>
          <div class="col-lg-5 mb-3">
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_subir_imagen">Subir imagen</button>
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_imagenes">
            <thead>
              <tr>
                <th scope="col">Imagen</th>
                <th scope="col">Nombre</th>
                <th scope="col">Seleccionar</th>
              </tr>
            </thead>
            <tbody>

            <?php cargarImagenes($imagenes, $imagenes_producto, $configurar_producto) ?>

            </tbody>
          </table>
        </div>
        <!-- Fin Tabla  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="<?php echo $class ?>" data-dismiss="modal">Aceptar</button>
        <button type="button" class="btn btn-warning btn-sm rounded-pill btn-seleccionar-todo">Seleccionar todo</button>
        <button type="button" class="btn btn-success btn-sm rounded-pill btn-deseleccionar-todo">Deseleccionar todo</button>
      </div>
    </div>
  </div>
</div>