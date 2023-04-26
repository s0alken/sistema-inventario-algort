<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_carrito.php";

//$query = $pdo->query("SELECT COUNT(*) + 1 FROM promocion");

//$n_promocion = $query->fetch(PDO::FETCH_COLUMN);
$n_promocion = 666;

$fecha = formatearFecha(date("Y-m-d H:i:s"));

$fecha_inicio = date("Y-m-d");;
$fecha_termino = date("Y-m-d");;

$empresa = cargarEmpresa();

$carrito              = $_SESSION["sistema"]["promocion"]["carrito"];
$nombre_promocion     = $_SESSION["sistema"]["promocion"]["nombre_promocion"];
$fecha_inicio         = $_SESSION["sistema"]["promocion"]["fecha_inicio"];
$fecha_termino        = $_SESSION["sistema"]["promocion"]["fecha_termino"];
$nombre_promocion     = $_SESSION["sistema"]["promocion"]["nombre_promocion"];
$descuento_porcentaje = $_SESSION["sistema"]["promocion"]["descuento_porcentaje"];
$descuento_dinero     = $_SESSION["sistema"]["promocion"]["descuento_dinero"];
$hasta_agotar_stock   = $_SESSION["sistema"]["promocion"]["hasta_agotar_stock"];

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-promocion-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-8">
          <label for="nombre_promocion">Nombre promoción:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-promocion" id="nombre_promocion" name="nombre_promocion" value="<?php echo $nombre_promocion ?>">
          <small id="nombre_promocion_alerta" class="form-text text-danger font-weight-bold alerta">Esta promoción ya existe</small>
        </div>
      </div>

       <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="fecha_inicio">Fecha de inicio:</label>
          <input type="date" class="form-control form-control-sm rounded-pill campo-promocion" id="fecha_inicio" name="fecha_inicio" min="<?php echo $fecha_inicio ?>" value="<?php echo $fecha_inicio ?>">
        </div>

        <div class="form-group col-md-4">
          <label for="fecha_termino">Fecha de término:</label>
          <input type="date" class="form-control form-control-sm rounded-pill campo-promocion" id="fecha_termino" name="fecha_termino" min="<?php echo $fecha_termino ?>" value="<?php echo $fecha_termino ?>">
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input campo-promocion" id="hasta_agotar_stock" name="hasta_agotar_stock" <?php echo $hasta_agotar_stock ?>>
          <label class="form-check-label" for="hasta_agotar_stock">O hasta agotar stock</label>
        </div>
      </div>

      <!--
      <div class="form-row justify-content-center text-center">
        <div class="col-md-4">
          <label>Término de promoción:</label>
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="termino_promocion" id="hasta_agotar_stock" value="hasta_agotar_stock" checked>
            <label class="form-check-label" for="hasta_agotar_stock">
              Hasta agotar stock
            </label>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="termino_promocion" id="hasta_fecha_limite" value="hasta_fecha_limite">
            <label class="form-check-label" for="hasta_fecha_limite">
              Hasta fecha límite
            </label>
          </div>
        </div>
      </div>

      <div id="fecha_limite_promocion" class="d-none">
        
        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-4">
            <label for="fecha_termino">Fecha de término:</label>
            <input type="date" class="form-control form-control-sm rounded-pill" id="fecha_termino" name="fecha_termino" min="<?php echo $fecha_inicio ?>" value="<?php echo $fecha_termino ?>">
            <small id="nombre_promocion_alerta" class="form-text text-danger font-weight-bold alerta">Esta promoción ya existe</small>
          </div>
        </div>

      </div>
      -->

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-sm-4">
          <label for="descuento_porcentaje">Descuento en porcentaje:</label>
          <div class="input-group input-group-sm">
            <input id="descuento_porcentaje" type="number" class="form-control" min="0" max="100" value="<?php echo $descuento_porcentaje ?>">
            <div class="input-group-prepend">
              <div class="input-group-text">%</div>
            </div>
          </div>
          <button type="button" class="btn btn-warning btn-sm btn-block rounded-pill btn-aplicar-descuento-promocion mt-3" data-target="#descuento_porcentaje">Aplicar</button>
        </div> 

        <div class="form-group col-sm-4">
          <label for="nombre_seccion">Descuento en dinero:</label>
          <div class="input-group input-group-sm">
            <input id="descuento_dinero" type="number" class="form-control" min="0" value="<?php echo $descuento_dinero ?>">
            <div class="input-group-prepend">
              <div class="input-group-text">$</div>
            </div>
          </div>
          <button type="button" class="btn btn-warning btn-sm btn-block rounded-pill btn-aplicar-descuento-promocion mt-3" data-target="#descuento_dinero">Aplicar</button>
        </div>
      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-4 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="codigo_barras_promocion" placeholder="Código de barras" autofocus>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_productos">Buscar producto manualmente</button>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" id="btn_vaciar_carrito" class="btn btn-primary btn-sm btn-block rounded-pill">Vaciar carrito</button>
        </div>

      </div>

      <!-- Productos  -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm mt-0 mt-sm-3" id="carrito">
          <thead>
            <tr>
              <th scope="col">Código de barras</th>
              <th scope="col">Producto</th>
              <th scope="col">Total descontado</th>
              <th scope="col">Precio antes</th>
              <th scope="col">Precio ahora</th>
              <th scope="col">Stock en promoción</th>
              <th scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>

            <?php cargarCarrito($carrito) ?>

          </tbody>
        </table>
      </div>
      <!-- Fin Productos  -->

      <hr>

      <div class="form-row justify-content-center">
        <div class="col-sm-4">
          <button type="submit" class="btn btn-success btn-sm btn-block rounded-pill" disabled>Crear promoción</button>
        </div>
      </div>
     
    </form>

    <?php

    require_once "modal_productos.php";
    require_once "modal_modificar_producto.php";
    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>