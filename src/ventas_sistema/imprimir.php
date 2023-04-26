<?php

require_once "../partials/header.php";
require_once "../controller/conexion.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_montos.php";
require_once "cargar_carrito.php";
require_once "cargar_numero_documento.php";

$id_venta = $_GET["id"];

$query = $pdo->prepare("
    SELECT
    v.id_venta,
    DATE_FORMAT(v.fecha, '%d-%m-%Y') AS fecha,
    TIME_FORMAT(v.fecha, '%H:%i:%s') AS hora,
    d.nombre_documento AS documento,
    mp.nombre_medio_pago AS medio_pago,
    v.puntos_aplicados,
    v.acumula_puntos,
    v.costo_despacho,
    v.descuento_porcentaje,
    v.descuento_dinero,
    v.observaciones
    FROM venta v
    INNER JOIN documento d ON d.id_documento = v.id_documento
    INNER JOIN medio_pago mp ON mp.id_medio_pago = v.id_medio_pago
    WHERE v.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$venta = $query->fetch();

$query = $pdo->prepare("
    SELECT
    vd.codigo_barras,
    vd.producto AS nombre,
    vd.precio_venta,
    vd.descuento_porcentaje,
    vd.descuento_dinero,
    vd.cantidad,
    vd.acumula_puntos
    FROM venta_detalle vd
    WHERE vd.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$carrito = $query->fetchAll();

cargarMontos($venta, $carrito);

$puntos_acumulados    = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->puntos_acumulados);
$puntos_aplicados     = preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->puntos_aplicados);
$descuento_porcentaje = $venta->descuento_porcentaje . "%";
$descuento_dinero     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->descuento_dinero);
$monto_neto           = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->monto_neto);
$total_iva            = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->total_iva);
$total_a_pagar        = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->total_a_pagar);
$monto_total          = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->monto_total);

//cliente
$query = $pdo->prepare("
  SELECT
  p.rut AS rut_cliente,
  p.nombre_persona AS nombre_cliente,
  p.giro AS giro_cliente,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_cliente,
  p.telefono AS telefono_cliente,
  p.correo AS correo_cliente
  FROM venta_cliente cc
  INNER JOIN persona p ON p.id_persona = cc.id_cliente
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE cc.id_venta = :id_venta");

$query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
$query->execute();

$cliente = $query->fetch();

$rut_cliente       = isset($cliente->rut_cliente)       ? $cliente->rut_cliente       : "";
$nombre_cliente    = isset($cliente->nombre_cliente)    ? $cliente->nombre_cliente    : "";
$giro_cliente      = isset($cliente->giro_cliente)      ? $cliente->giro_cliente      : "";
$direccion_cliente = isset($cliente->direccion_cliente) ? $cliente->direccion_cliente : "";
$telefono_cliente  = isset($cliente->telefono_cliente)  ? $cliente->telefono_cliente  : "";
$correo_cliente    = isset($cliente->correo_cliente)    ? $cliente->correo_cliente    : "";

$n_documento = cargarNumeroDocumento($venta->id_venta, $venta->documento, $venta->medio_pago);

$empresa = cargarEmpresa();

$fecha = formatearFecha($venta->fecha);

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
            <input type="text" class="form-control form-control-sm rounded-pill text-center" disabled value="<?php echo ucfirst($venta->documento) ?>">
          </div>
          <div class="font-weight-bold h5 m-0"><?php echo "Venta N° " . $venta->id_venta ?></div>
          <div class="font-weight-bold"><?php echo $fecha ?></div>

        </div>

      </div>

    </div>

    <hr>
      
      <?php if ($cliente): ?>
        
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

      <?php endif ?>

      <!-- Productos  -->
      <div class="table-responsive">
      <table class="table table-bordered table-sm mt-0 mt-sm-3" id="carrito">
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
              <label for="n_venta" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">N° venta:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="n_venta" disabled value="<?php echo ucfirst($venta->id_venta) ?>">
              </div> 
            </div>

            <?php if ($venta->documento != "voucher"): ?>

              <div class="form-row mb-2">
                <label for="<?php echo $n_documento[$venta->documento]['id'] ?>" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold"><?php echo ucfirst($n_documento[$venta->documento]["label"]) ?></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control form-control-sm rounded-pill" id="<?php echo $n_documento[$venta->documento]['id'] ?>" disabled value="<?php echo ucfirst($n_documento[$venta->documento]['numero']) ?>">
                </div> 
              </div>
              
            <?php endif ?>

            <?php if ($venta->medio_pago === "redcompra" && $venta->documento != "boleta redcompra"): ?>

              <div class="form-row mb-2">
                <label for="n_redcompra" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">N° redcompra:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control form-control-sm rounded-pill" id="n_redcompra" disabled value="<?php echo ucfirst($n_documento['redcompra']['numero']) ?>">
                </div> 
              </div>
              
            <?php endif ?>

            <div class="form-row mb-2">
              <label for="fecha" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Fecha:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="fecha" disabled value="<?php echo ucfirst($venta->fecha) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="hora" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Hora:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="hora" disabled value="<?php echo ucfirst($venta->hora) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="documento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Tipo documento:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="documento" disabled value="<?php echo ucfirst($venta->documento) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="medio_pago" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Medio de pago:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="medio_pago" disabled value="<?php echo ucfirst($venta->medio_pago) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="total_descuento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total venta sin dto:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_descuento" disabled value="<?php echo $monto_total ?>">
              </div> 
            </div>

            <?php if (strlen($venta->observaciones) > 0): ?>

              <div class="form-row mb-2">
                <label for="observaciones" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Observaciones:</label>
                <div class="col-sm-8">
                  <p><?php echo ucfirst($venta->observaciones) ?></p>
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
              <label for="descuento_porcentaje" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Descuento %:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="descuento_porcentaje" disabled value="<?php echo $descuento_porcentaje ?>">
              </div>   
            </div>

            <div class="form-row mb-2 justify-content-end">
              <label for="descuento_dinero" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Descuento $:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="descuento_dinero" disabled value="<?php echo $descuento_dinero ?>">
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