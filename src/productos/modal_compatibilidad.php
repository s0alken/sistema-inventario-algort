<?php

require_once "../controller/conexion.php";

if (isset($_GET["id_producto"])) {

  $query = $pdo->prepare("
    SELECT
    p.id_producto,
    p.codigo_barras,
    p.descripcion,
    m.nombre_marca AS marca
    FROM producto p
    INNER JOIN marca m ON m.id_marca = p.id_marca
    WHERE p.habilitado AND p.id_producto != :id_producto
    ORDER BY p.id_producto DESC");

  $query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
  $query->execute();

  $productos = $query->fetchAll();

} else {

  $query = $pdo->query("
    SELECT
    p.id_producto,
    p.codigo_barras,
    p.descripcion,
    m.nombre_marca AS marca
    FROM producto p
    INNER JOIN marca m ON m.id_marca = p.id_marca
    WHERE p.habilitado
    ORDER BY p.id_producto DESC");

  $productos = $query->fetchAll();

}

$class = $configurar_producto ? "btn btn-primary btn-sm rounded-pill campo-producto" : "btn btn-primary btn-sm rounded-pill";

?>

<div class="modal fade bd-example-modal-lg" id="modal_compatibilidad" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_compatibilidad" id="input_tabla_compatibilidad" class="form-control form-control-sm rounded-pill" placeholder="Buscar producto">
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_compatibilidad">
            <thead>
              <tr>
                <th scope="col">Código</th>
                <th scope="col">Descripción</th>
                <th scope="col">marca</th>
                <th scope="col">Seleccionar</th>
              </tr>
            </thead>
            <tbody>

            <?php cargarCompatibilidad($productos, $compatibilidad_producto, $configurar_producto) ?>

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