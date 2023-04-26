<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_montos.php";
require_once "cargar_carrito.php";
require_once "cargar_numero_envio.php";

$id_compra = $_GET["id_compra"];

$query = $pdo->query("SELECT COUNT(*) + 1 FROM venta");

$n_venta = $query->fetch(PDO::FETCH_COLUMN);

date_default_timezone_set("America/Santiago");

$fecha = formatearFecha(date("Y-m-d H:i:s"));

$empresa = cargarEmpresa();

$query = $pdo->prepare("
    SELECT
    c.id_compra,
    c.fecha,
    d.nombre_documento AS documento,
    mp.nombre_medio_pago AS medio_pago,
    op.nombre_operador_logistico AS operador_logistico,
    op.id_tipo_operador_logistico,
    t.nombre_tipo_operador_logistico AS tipo_operador_logistico,
    c.puntos_aplicados,
    c.acumula_puntos,
    c.costo_despacho,
    c.id_compra_estado,
    ce.cierra_venta
    FROM compra c
    INNER JOIN documento d ON d.id_documento = c.id_documento
    INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
    INNER JOIN operador_logistico op ON op.id_operador_logistico = c.id_operador_logistico
    INNER JOIN tipo_operador_logistico t ON t.id_tipo_operador_logistico = op.id_tipo_operador_logistico
    INNER JOIN compra_estado ce ON ce.id_compra_estado = c.id_compra_estado
    WHERE c.id_compra = :id_compra");

$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
$query->execute();

$compra = $query->fetch();

$query = $pdo->prepare("
    SELECT
    codigo_barras,
    producto AS nombre,
    precio_venta AS precio,
    cantidad,
    precio_venta * cantidad AS subtotal,
    acumula_puntos
    FROM compra_detalle
    WHERE id_compra = :id_compra");

$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
$query->execute();

$carrito = $query->fetchAll();

cargarMontos($compra, $carrito);

$puntos_acumulados = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->puntos_acumulados);
$puntos_aplicados  = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->puntos_aplicados);
$monto_neto        = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->monto_neto);
$total_iva         = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->total_iva);
$total_a_pagar     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->total_a_pagar);
$monto_total       = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->monto_total);
$total_productos   = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->total_productos);
$costo_despacho    = $compra->costo_despacho === 0 ? "gratis" : "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compra->costo_despacho);

//cliente
$query = $pdo->prepare("
  SELECT
  p.rut AS rut_cliente,
  p.nombre_persona AS nombre_cliente,
  p.giro AS giro_cliente,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_cliente,
  p.telefono AS telefono_cliente,
  p.correo AS correo_cliente
  FROM compra_cliente cc
  INNER JOIN persona p ON p.id_persona = cc.id_cliente
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE cc.id_compra = :id_compra");

$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
$query->execute();

$cliente = $query->fetch();

//si no se encuentra el cliente se busca en la tabla comprador
if (!$cliente) {

  $query = $pdo->prepare("
    SELECT
    com.rut AS rut_cliente,
    com.nombre_comprador AS nombre_cliente,
    com.giro AS giro_cliente,
    CONCAT(com.direccion, ' #', com.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_cliente,
    com.telefono AS telefono_cliente,
    com.correo AS correo_cliente
    FROM comprador com
    INNER JOIN ciudad c ON c.id_ciudad = com.id_ciudad
    INNER JOIN comuna co ON co.id_comuna = c.id_comuna
    INNER JOIN region r ON r.id_region = co.id_region
    WHERE com.id_compra = :id_compra");

  $query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
  $query->execute();

  $cliente = $query->fetch();

  //este tipo de cliente no acumula puntos
  $puntos_acumulados = 0;

}

$rut_cliente       = isset($cliente->rut_cliente)       ? $cliente->rut_cliente       : "";
$nombre_cliente    = isset($cliente->nombre_cliente)    ? $cliente->nombre_cliente    : "";
$giro_cliente      = isset($cliente->giro_cliente)      ? $cliente->giro_cliente      : "";
$direccion_cliente = isset($cliente->direccion_cliente) ? $cliente->direccion_cliente : "";
$telefono_cliente  = isset($cliente->telefono_cliente)  ? $cliente->telefono_cliente  : "";
$correo_cliente    = isset($cliente->correo_cliente)    ? $cliente->correo_cliente    : "";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-venta-procesar" action="<?php echo 'guardar_venta_web.php?redireccionar=true&id_compra=' . $id_compra ?>" method="POST">

      <div class="form-row">
        
        <div class="col-sm-2 d-flex">
     
          <div class="logo-form-img-container align-self-center">
            <img src="<?php echo '../img/logo.png?' . rand() ?>">
          </div>

        </div>

        <div class="col-sm-6">
          
          <div class="py-3 py-sm-0 px-sm-3">
            
            <div class="font-weight-bold"><?php echo $empresa->nombre_empresa ?></div>
            <div class="mb-2 text-muted"><?php echo $empresa->giro ?></div>
            <div><?php echo "Dirección: " . $empresa->direccion ?></div>
            <div><?php echo "Teléfono: " . $empresa->telefono ?></div>
            <div><?php echo "Correo: " . $empresa->correo ?></div>

          </div>

        </div>

        <div class="col-sm-4 text-center text-red">
          
          <div class="border-red p-3">

            <div class="font-weight-bold"><?php echo "RUT: " . $empresa->rut ?></div>
            <div>
              <input type="text" class="form-control form-control-sm rounded-pill" disabled value="<?php echo ucfirst($compra->documento) ?>">
            </div>
            <div class="font-weight-bold h5 m-0"><?php echo "Venta N° " . $n_venta ?></div>
            <div class="font-weight-bold"><?php echo $fecha ?></div>

          </div>

        </div>

      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-4">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="rut_cliente">Rut cliente:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_cliente" disabled value="<?php echo $rut_cliente ?>">
        </div>

        <div class="col-sm-4">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="nombre_cliente">Nombre cliente:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_cliente" disabled value="<?php echo $nombre_cliente ?>">
        </div>

        <div class="col-sm-4">
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

      <!-- Productos  -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm mt-0 mt-sm-3">
          <thead>
            <tr>
              <th scope="col">Código de barras</th>
              <th scope="col">Producto</th>
              <th scope="col">Precio</th>
              <th scope="col">Cantidad</th>
              <th scope="col">Subtotal</th>
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
            <label for="medio_pago" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Medio de pago:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="medio_pago" disabled value="<?php echo ucfirst($compra->medio_pago) ?>">
            </div> 
          </div>

          <div class="form-row mb-2">
            <label for="operador_logistico" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Operador logístico:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="operador_logistico" disabled value="<?php echo ucfirst($compra->operador_logistico) ?>">
            </div> 
          </div>

          <div class="form-row mb-2">
            <label for="total_descuento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Total venta sin dto:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="total_descuento" disabled value="<?php echo $monto_total ?>">
            </div> 
          </div>

          <?php if ($compra->tipo_operador_logistico === "delivery"): ?>

            <div class="form-row mb-2">
              <label for="total_productos" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Total productos:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_productos" disabled value="<?php echo $total_productos ?>">
              </div> 
            </div>
            
            <div class="form-row mb-2">
              <label for="costo_despacho" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">Costo despacho:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="costo_despacho" disabled value="<?php echo ucfirst($costo_despacho) ?>">
              </div> 
            </div>

          <?php endif ?>

          <div class="form-row mb-2">
            <label for="id_compra_estado" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 text-danger font-weight-bold">Estado compra:</label>
            <div class="col-sm-5">
              <select class="form-control form-control-sm rounded-pill text-primary font-weight-bold" id="id_compra_estado" name="id_compra_estado" data-id-compra="<?php echo $compra->id_compra ?>">
                <?php cargarCompraEstado($compra->id_compra_estado, $compra->id_tipo_operador_logistico) ?>
              </select>
            </div>   
          </div>

          <div id="campo_n_envio">
            
            <?php cargarNumeroEnvio($compra->id_compra_estado, $compra->id_compra) ?>

          </div>

        </div>

        <div class="col-sm-5">

          <div class="form-row mb-2 justify-content-end">
            <label for="puntos" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Puntos acumulados:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="puntos" disabled value="<?php echo $puntos_acumulados ?>">
            </div>   
          </div>

          <div class="form-row mb-2 justify-content-end">
            <label for="puntos" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Puntos aplicados:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill" id="puntos" disabled value="<?php echo $puntos_aplicados ?>">
            </div>   
          </div>

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

      <div class="form-row justify-content-center mb-2">
        <div id="btn_guardar_venta" class="col-sm-4">
          <?php if ($compra->cierra_venta): ?>
            <button type="button" class="btn btn-success btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_cerrar_venta_web">Guardar</button>
          <?php else: ?>
            <button type="submit" class="btn btn-success btn-sm btn-block rounded-pill">Guardar</button>
          <?php endif ?>
        </div>
      </div>

      <?php require_once "modal_cerrar_venta_web.php"; ?>

    </form>

    <div class="form-row justify-content-center">
      <div class="col-sm-4">
        <button type="button" class="btn btn-danger btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_rechazar_venta_web">Rechazar venta</button>
      </div>
    </div>

    <?php

    require_once "../partials/snackbar.php";
    require_once "modal_rechazar_venta_web.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>