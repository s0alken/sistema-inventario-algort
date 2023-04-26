<?php

require_once "../controller/conexion.php";

$query = $pdo->query("
  SELECT
  p.codigo_barras,
  p.descripcion,
  m.nombre_marca AS marca,
  p.precio_venta AS precio
  FROM producto p
  INNER JOIN marca m ON m.id_marca = p.id_marca
  WHERE p.habilitado
  ORDER BY p.id_producto DESC");

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
                <th scope="col">Precio</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>

            <?php foreach ($productos as $producto): ?>

              <tr>
                <td><?php echo $producto->codigo_barras ?></td>
                <td><?php echo $producto->descripcion ?></td>
                <td><?php echo $producto->marca ?></td>
                <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio) ?></td>
                <td>
                  <button class="btn btn-primary btn-sm btn-block rounded-pill text-nowrap btn-configurar-producto" type="button" value="<?php echo $producto->codigo_barras ?>" data-target="cotizacion">Agregar a la cotización</button></td>
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