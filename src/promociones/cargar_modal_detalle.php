<?php

require_once "../controller/conexion.php";

$id_promocion = $_POST["id"];

$query = $pdo->prepare("
    SELECT
    p.id_promocion,
    p.nombre_promocion as nombre,
    DATE_FORMAT(p.fecha_inicio, '%d-%m-%Y') AS fecha_inicio,
    DATE_FORMAT(p.fecha_termino, '%d-%m-%Y') AS fecha_termino,
    p.descuento_porcentaje,
    p.descuento_dinero,
    IF(p.hasta_agotar_stock, 'Sí', 'No') AS hasta_agotar_stock,
    pe.nombre_promocion_estado AS estado
    FROM promocion p
    INNER JOIN promocion_estado pe ON pe.id_promocion_estado = p.id_promocion_estado
    WHERE p.id_promocion = :id_promocion");

$query->bindValue(":id_promocion", $id_promocion, PDO::PARAM_INT);
$query->execute();

$promocion = $query->fetch();

$query = $pdo->prepare("
    SELECT
    codigo_barras,
    precio_venta AS precio_ahora,
    stock_promocion
    FROM promocion_detalle
    WHERE id_promocion = :id_promocion");

$query->bindValue(":id_promocion", $id_promocion, PDO::PARAM_INT);
$query->execute();

$carrito = $query->fetchAll();

foreach ($carrito as $producto) {
  
  $query = $pdo->prepare("
    SELECT
    CONCAT(p.descripcion, ' ', m.nombre_marca) AS nombre,
    p.precio_venta
    FROM producto p
    INNER JOIN marca m ON m.id_marca = p.id_marca
    WHERE p.habilitado
    AND p.codigo_barras = :codigo_barras");
  
  $query->bindValue(":codigo_barras", $producto->codigo_barras, PDO::PARAM_STR);
  $query->execute();

  $datos_producto = $query->fetch();

  $producto->nombre = $datos_producto->nombre;
  $producto->precio_antes = $datos_producto->precio_venta;
  $producto->total_descontado = $producto->precio_antes - $producto->precio_ahora;

}

$alert_classes = array("en espera"   => "alert-primary",
                       "en vigencia" => "alert-success",
                       "terminada"   => "alert-danger")

?>

<div class="p-0 px-md-4">

  <div class="d-none d-sm-block">

    <div class="form-row justify-content-center">
    
      <div class="col-sm-6">
        
        <div class="<?php echo 'alert rounded-pill d-block text-center font-weight-bold m-0 ' . $alert_classes[$promocion->estado]  ?>" role="alert">
          <?php echo "Esta promoción se encuentra " . $promocion->estado ?>
        </div>

      </div>

    </div>

    <hr>

  </div>

  <div class="form-row">

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="nombre_promocion">Nombre promocíón:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_promocion" disabled value="<?php echo ucfirst($promocion->nombre) ?>">
    </div>

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="fecha_inicio">Fecha inicio:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="fecha_inicio" disabled value="<?php echo $promocion->fecha_inicio ?>">
    </div>

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="fecha_termino">Fecha término:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="fecha_termino" disabled value="<?php echo $promocion->fecha_termino ?>">
    </div>

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="descuento_porcentaje">Descuento en porcentaje:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="descuento_porcentaje" disabled value="<?php echo $promocion->descuento_porcentaje . '%' ?>">
    </div>

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="descuento_dinero">Descuento en dinero:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="descuento_dinero" disabled value="<?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $promocion->descuento_dinero) ?>">
    </div>

    <div class="col-sm-4">
      <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="hasta_agotar_stock">Hasta agotar stock:</label>
      <input type="text" class="form-control form-control-sm rounded-pill" id="hasta_agotar_stock" disabled value="<?php echo $promocion->hasta_agotar_stock ?>">
    </div>

  </div>

  <hr>

    <!-- Productos  -->
    <div class="table-responsive">
    <table class="table table-bordered table-sm mt-0 mt-sm-3">
      <thead>
        <tr>
          <th scope="col">Código de barras</th>
            <th scope="col">Producto</th>
            <th scope="col">Total descontado</th>
            <th scope="col">Precio antes</th>
            <th scope="col">Precio ahora</th>
            <th scope="col">Stock en promoción</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($carrito as $producto): ?>
          
          <tr>
            <td><?php echo $producto->codigo_barras ?></td>
            <td><?php echo $producto->nombre ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->total_descontado) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_antes) ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_ahora) ?></td>
            <td><?php echo $producto->stock_promocion ?></td>
          </tr>

        <?php endforeach ?>

      </tbody>
    </table>
    </div>
    <!-- Fin Productos  -->

    <div class="d-sm-none">
      
      <hr>

      <div class="form-row justify-content-center">
      
        <div class="col-sm-6">
          
          <div class="<?php echo 'alert rounded-pill d-block text-center font-weight-bold m-0 ' . $alert_classes[$promocion->estado]  ?>" role="alert">
            <?php echo "Esta promoción se encuentra " . $promocion->estado ?>
          </div>

        </div>

      </div>

    </div>

    <hr>

    <div class="form-row justify-content-center mb-2">
        <div class="col-sm-4">
          <button type="button" class="btn btn-danger btn-sm btn-block rounded-pill" data-dismiss="modal">Cerrar</button>
        </div>
    </div>

</div>
