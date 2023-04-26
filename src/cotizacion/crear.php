<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_carrito.php";

$query = $pdo->query("SELECT COUNT(*) + 1 FROM cotizacion");

$n_cotizacion = $query->fetch(PDO::FETCH_COLUMN);

date_default_timezone_set("America/Santiago");

$fecha = formatearFecha(date("Y-m-d H:i:s"));

$empresa = cargarEmpresa();

$carrito              = $_SESSION["sistema"]["cotizacion"]["carrito"];
$cliente              = $_SESSION["sistema"]["cotizacion"]["cliente"];
$medio_pago           = $_SESSION["sistema"]["cotizacion"]["medio_pago"];
$observaciones        = $_SESSION["sistema"]["cotizacion"]["observaciones"];
$descuento_porcentaje = $_SESSION["sistema"]["cotizacion"]["descuento_porcentaje"];
$descuento_dinero     = $_SESSION["sistema"]["cotizacion"]["descuento_dinero"];

$puntos          = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["puntos"]);
$total_descuento = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_descuento"]);
$monto_neto      = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["monto_neto"]);
$total_iva       = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_iva"]);
$total_a_pagar   = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["total_a_pagar"]);
$monto_total     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $_SESSION["sistema"]["cotizacion"]["monto_total"]);

$rut_cliente       = isset($cliente["rut_cliente"])       ? $cliente["rut_cliente"]       : "";
$nombre_cliente    = isset($cliente["nombre_cliente"])    ? $cliente["nombre_cliente"]    :  "";
$giro_cliente      = isset($cliente["giro_cliente"])      ? $cliente["giro_cliente"]      : "";
$direccion_cliente = isset($cliente["direccion_cliente"]) ? $cliente["direccion_cliente"] : "";
$telefono_cliente  = isset($cliente["telefono_cliente"])  ? $cliente["telefono_cliente"]  : "";
$correo_cliente    = isset($cliente["correo_cliente"])    ? $cliente["correo_cliente"]    : "";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-cotizacion-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row">
        
        <?php require_once "../partials/datos_empresa.php" ?>

        <div class="col-sm-4 text-center text-red">
          
          <div class="border-red p-3">

            <div class="font-weight-bold"><?php echo "RUT: " . $empresa->rut ?></div>
            <div class="font-weight-bold h5 my-1"><?php echo "Cotización N° " . $n_cotizacion ?></div>
            <div class="font-weight-bold"><?php echo $fecha ?></div>

          </div>

          <div class="font-weight-bold mt-2">Cotización válida por 5 días</div>

        </div>

      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-3 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_cliente" name="rut_cliente" placeholder="Rut cliente" value="<?php echo $rut_cliente ?>" data-tipo-persona="cliente">
          <small id="rut_cliente_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
        </div>

        <div class="col-sm-3 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_clientes">Buscar cliente manualmente</button>
        </div>

        <div class="col-sm-3 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_cliente">Crear cliente</button>
        </div>

        <div class="col-sm-3 mb-3 mb-sm-0">
          <button type="button" id="btn_quitar_cliente" class="btn btn-primary btn-sm btn-block rounded-pill">Quitar cliente de la cotización</button>
        </div>

      </div>

      <div class="form-row">

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="nombre_cliente">Nombre cliente:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_cliente" disabled value="<?php echo $nombre_cliente ?>">
        </div>

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="giro_cliente">Giro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="giro_cliente" disabled value="<?php echo $giro_cliente ?>">
        </div>

      </div>

      <div class="form-row">

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="direccion_cliente">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion_cliente" disabled value="<?php echo $direccion_cliente ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="telefono_cliente">Teléfono:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_cliente" disabled value="<?php echo $telefono_cliente ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="correo_cliente">Correo:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="correo_cliente" disabled value="<?php echo $correo_cliente ?>">
        </div>

      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-4 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="codigo_barras_cotizacion" placeholder="Código de barras" autofocus>
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
              <th scope="col">Precio</th>
              <th scope="col">Cantidad</th>
              <th scope="col">Descuento %</th>
              <th scope="col">Descuento $</th>
              <th scope="col">Total descuento</th>
              <th scope="col">Subtotal</th>
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

      <div class="form-row">

        <div class="col-sm-7">

          <div class="form-row mb-2">
            <label for="id_medio_pago" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Condición de pago:</label>
            <div class="col-sm-4">
              <select class="form-control form-control-sm rounded-pill campo-cotizacion" id="id_medio_pago" name="id_medio_pago">
                <?php cargarMedioPago($medio_pago["id_medio_pago"]) ?>
              </select>
            </div>   
          </div>

          <div class="form-row mb-2">
            <label for="descuento_porcentaje" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Descuento en porcentaje:</label>
            <div class="col-sm-4">
              <div class="input-group input-group-sm">
                <input id="descuento_porcentaje" type="number" class="form-control" min="0" max="100" value="<?php echo $descuento_porcentaje ?>">
                <div class="input-group-prepend">
                  <div class="input-group-text">%</div>
                </div>
              </div>
            </div> 
            <div class="col-sm-4 mt-3 mt-sm-0">
              <button type="button" class="btn btn-warning btn-sm btn-block rounded-pill btn-aplicar-descuento" data-target="#descuento_porcentaje">Aplicar</button>
            </div>
          </div>

          <div class="form-row mb-2">
            <label for="descuento_dinero" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Descuento en dinero:</label>
            <div class="col-sm-4">
              <div class="input-group input-group-sm">
                <input id="descuento_dinero" type="number" class="form-control" min="0" value="<?php echo $descuento_dinero ?>">
                <div class="input-group-prepend">
                  <div class="input-group-text">$</div>
                </div>
              </div>
            </div> 
            <div class="col-sm-4 mt-3 mt-sm-0">
              <button type="button" class="btn btn-warning btn-sm btn-block rounded-pill btn-aplicar-descuento" data-target="#descuento_dinero">Aplicar</button>
            </div>
          </div>

          <div class="form-row mb-2">
            <label for="total_descuento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Total venta sin dto:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm rounded-pill" id="total_descuento" disabled value="<?php echo $monto_total ?>">
            </div> 
          </div>

          <div class="form-row mb-2">
            <label for="observaciones" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Observaciones:</label>
            <div class="col-sm-8">
              <textarea id="observaciones" name="observaciones" class="form-control campo-cotizacion" rows="2" placeholder="Máximo 300 caracteres"><?php echo $observaciones ?></textarea>
              <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 300 caracteres</small>
            </div>   
          </div>

        </div>

        <div class="col-sm-5">

          <div class="form-row mb-2 justify-content-end">
            <label for="monto_neto" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Monto neto:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="monto_neto" disabled value="<?php echo $monto_neto ?>">
            </div>   
          </div>

          <div class="form-row mb-2 justify-content-end">
            <label for="total_iva" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Total IVA:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="total_iva" disabled value="<?php echo $total_iva ?>">
            </div>   
          </div>

          <div class="form-row mb-2 justify-content-end">
            <label for="total_a_pagar" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Total a pagar:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="total_a_pagar" disabled value="<?php echo $total_a_pagar ?>">
            </div>   
          </div>
          
        </div>

      </div>

      <hr>

      <div class="form-row justify-content-center">
        <div class="col-sm-4">
          <button type="submit" class="btn btn-success btn-sm btn-block rounded-pill" disabled>Generar cotización</button>
        </div>
      </div>

      <div class="text-center my-2">
        <div>La disponibilidad de stock debe confirmarse al momento de hacer efectiva la compra, la cotización no implica existencia de stock y todos los productos se entienden disponibles salvo venta previa</div>
      </div>
     
    </form>

    <?php

    require_once "modal_crear_cliente.php";
    require_once "modal_clientes.php";
    require_once "modal_productos.php";
    require_once "modal_configurar_producto.php";
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