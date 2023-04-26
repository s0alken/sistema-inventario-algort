<?php

require_once "../partials/header.php";
require_once "../controller/conexion.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_montos.php";
require_once "cargar_carrito.php";

$id_compra = $_GET["id"];

$query = $pdo->prepare("
    SELECT
    c.id_compra,
    DATE_FORMAT(c.fecha, '%d-%m-%Y') AS fecha,
    TIME_FORMAT(c.fecha, '%H:%i:%s') AS hora,
    d.nombre_documento AS documento,
    mp.nombre_medio_pago AS medio_pago,
    op.nombre_operador_logistico AS operador_logistico,
    t.nombre_tipo_operador_logistico AS tipo_operador_logistico,
    c.puntos_aplicados,
    c.acumula_puntos,
    c.costo_despacho,
    ce.nombre_compra_estado AS estado
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
$nombre_cliente    = isset($cliente->nombre_cliente)    ? $cliente->nombre_cliente    :  "";
$giro_cliente      = isset($cliente->giro_cliente)      ? $cliente->giro_cliente      : "";
$direccion_cliente = isset($cliente->direccion_cliente) ? $cliente->direccion_cliente : "";
$telefono_cliente  = isset($cliente->telefono_cliente)  ? $cliente->telefono_cliente  : "";
$correo_cliente    = isset($cliente->correo_cliente)    ? $cliente->correo_cliente    : "";

$empresa = cargarEmpresa();

$fecha = formatearFecha($compra->fecha);

if ($compra->estado === "rechazada") {
  
  $query = $pdo->prepare("SELECT motivo_rechazo FROM compra_motivo_rechazo WHERE id_compra = :id_compra");

  $query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
  $query->execute();

  $compra->motivo_rechazo = $query->fetch(PDO::FETCH_COLUMN);

}

?>

<body>

  <!-- Contenido  -->

  <div class="p-5">

    <div class="form-row">
          
      <?php require_once "../partials/datos_empresa.php" ?>

      <div class="col-sm-4 text-center text-red">
        
        <div class="border-red p-3">

          <div class="font-weight-bold"><?php echo "RUT: " . $empresa->rut ?></div>
          <div>
            <input type="text" class="form-control form-control-sm rounded-pill text-center" disabled value="<?php echo ucfirst($compra->documento) ?>">
          </div>
          <div class="font-weight-bold h5 m-0"><?php echo "Compra N° " . $compra->id_compra ?></div>
          <div class="font-weight-bold"><?php echo $fecha ?></div>

        </div>

      </div>

    </div>

    <hr>
      
    <div class="form-row">

        <div class="col-sm-4">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="rut_cliente">Rut cliente:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_cliente" disabled value="<?php echo $rut_cliente ?>">
        </div>

        <div class="col-sm-4">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="nombre_cliente">Nombre cliente:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_cliente" disabled value="<?php echo ucwords($nombre_cliente) ?>">
        </div>

        <div class="col-sm-4">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="giro_cliente">Giro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="giro_cliente" disabled value="<?php echo ucfirst($giro_cliente) ?>">
        </div>

      </div>

      <div class="form-row">

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="direccion_cliente">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion_cliente" disabled value="<?php echo ucfirst($direccion_cliente) ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="telefono_cliente">Teléfono:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_cliente" disabled value="<?php echo $telefono_cliente ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="correo_cliente">Correo:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="correo_cliente" disabled value="<?php echo $correo_cliente ?>">
        </div>

      </div>

      <hr>

      <!-- Productos  -->
      <div class="table-responsive">
      <table class="table table-bordered table-sm mt-0 mt-sm-3" id="carrito">
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
              <label for="n_compra" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">N° compra:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="n_compra" disabled value="<?php echo ucfirst($compra->id_compra) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="estado" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Estado:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="estado" disabled value="<?php echo ucfirst($compra->estado) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="fecha" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Fecha:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="fecha" disabled value="<?php echo ucfirst($compra->fecha) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="hora" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Hora:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="hora" disabled value="<?php echo ucfirst($compra->hora) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="documento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Tipo documento:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="documento" disabled value="<?php echo ucfirst($compra->documento) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="medio_pago" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Medio de pago:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="medio_pago" disabled value="<?php echo ucfirst($compra->medio_pago) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="operador_logistico" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Operador logístico:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="operador_logistico" disabled value="<?php echo ucfirst($compra->operador_logistico) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="total_descuento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total venta sin dto:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_descuento" disabled value="<?php echo $monto_total ?>">
              </div> 
            </div>

            <?php if ($compra->tipo_operador_logistico === "delivery"): ?>

              <div class="form-row mb-2">
                <label for="total_productos" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total productos:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control form-control-sm rounded-pill" id="total_productos" disabled value="<?php echo $total_productos ?>">
                </div> 
              </div>
              
              <div class="form-row mb-2">
                <label for="costo_despacho" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Costo despacho:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control form-control-sm rounded-pill" id="costo_despacho" disabled value="<?php echo ucfirst($costo_despacho) ?>">
                </div> 
              </div>

            <?php endif ?>

            <?php if ($compra->estado === "rechazada"): ?>

              <div class="form-row mb-2">
                <label for="motivo_rechazo" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Motivo rechazo:</label>
                <div class="col-sm-8">
                  <p><?php echo ucfirst($compra->motivo_rechazo) ?></p>
                </div> 
              </div>
              
            <?php endif ?>

          </div>

          <div class="col-sm-5">

            <div class="form-row mb-2 justify-content-end">
              <label for="puntos" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Puntos acumulados:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="puntos_acumulados" disabled value="<?php echo $puntos_acumulados ?>">
              </div>   
            </div>

            <div class="form-row mb-2 justify-content-end">
              <label for="puntos" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Puntos aplicados:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="puntos_aplicados" disabled value="<?php echo $puntos_aplicados ?>">
              </div>   
            </div>

            <div class="form-row mb-2 justify-content-end">
              <label for="monto_neto" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Monto neto:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="monto_neto" disabled value="<?php echo $monto_neto ?>">
              </div>   
            </div>

            <div class="form-row mb-2 justify-content-end">
              <label for="total_iva" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total IVA:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_iva" disabled value="<?php echo $total_iva ?>">
              </div>   
            </div>

            <div class="form-row mb-2 justify-content-end">
              <label for="total_a_pagar" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total a pagar:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_a_pagar" disabled value="<?php echo $total_a_pagar ?>">
              </div>   
            </div>
            
          </div>

      </div>

  </div>

  <script type="text/javascript">
    
    window.print();

  </script>

<?php

require_once "../partials/footer.php";

?>