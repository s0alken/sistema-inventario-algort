<?php

require_once "../controller/conexion.php";

$query = $pdo->prepare("
  SELECT
  p.codigo_barras,
  p.descripcion,
  m.nombre_marca AS marca,
  p.precio_venta AS precio,
  sp.stock
  FROM producto p
  INNER JOIN marca m ON m.id_marca = p.id_marca
  INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
  WHERE p.habilitado
  AND sp.stock > 0
  AND sp.id_sucursal = :id_sucursal
  ORDER BY p.id_producto DESC");

$query->bindValue(":id_sucursal", $_SESSION["sistema"]["traspaso"]["id_sucursal_origen"], PDO::PARAM_INT);
$query->execute();

$productos = $query->fetchAll();

?>

<div class="modal fade bd-example-modal-lg" id="modal_productos" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="form-row">
          <div class="col-lg-5 mb-3">
            <input type="text" name="input_tabla_productos" id="input_tabla_productos" class="form-control form-control-sm rounded-pill" placeholder="Buscar producto">
          </div>
        </div>
        
        <!-- Tabla  -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-sm" id="tabla_modal_productos">
            <thead>
              <tr>
                <th scope="col">Código de barras</th>
                <th scope="col">Producto</th>
                <th scope="col">Marca</th>
                <th scope="col">Stock</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>

            <?php foreach ($productos as $producto): ?>

              <tr>
                <td><?php echo $producto->codigo_barras ?></td>
                <td><?php echo $producto->descripcion ?></td>
                <td><?php echo $producto->marca ?></td>
                <td><?php echo $producto->stock ?></td>
                <td>
                  <button class="btn btn-primary btn-sm btn-block rounded-pill text-nowrap btn-agregar-traspaso" type="button" value="<?php echo $producto->codigo_barras ?>">Agregar al traspaso</button></td>
              </tr>
              
            <?php endforeach ?>

            </tbody>
          </table>
        </div>
        <!-- Fin Tabla  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>