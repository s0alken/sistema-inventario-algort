<?php

require_once "../partials/header.php";
require_once "../controller/conexion.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_montos.php";
require_once "cargar_carrito.php";

$id_cotizacion = $_GET["id_cotizacion"];

$query = $pdo->prepare("
    SELECT
    cot.id_cotizacion,
    DATE_FORMAT(cot.fecha, '%d-%m-%Y') AS fecha,
    TIME_FORMAT(cot.fecha, '%H:%i:%s') AS hora,
    mp.nombre_medio_pago AS condicion_pago,
    cot.descuento_porcentaje,
    cot.descuento_dinero,
    cot.observaciones
    FROM cotizacion cot
    INNER JOIN medio_pago mp ON mp.id_medio_pago = cot.id_medio_pago
    WHERE cot.id_cotizacion = :id_cotizacion");

$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
$query->execute();

$cotizacion = $query->fetch();

$query = $pdo->prepare("
    SELECT
    cd.codigo_barras,
    cd.producto AS nombre,
    cd.precio_venta,
    cd.descuento_porcentaje,
    cd.descuento_dinero,
    cd.cantidad
    FROM cotizacion_detalle cd
    WHERE cd.id_cotizacion = :id_cotizacion");

$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
$query->execute();

$carrito = $query->fetchAll();

cargarMontos($cotizacion, $carrito);

$descuento_porcentaje = $cotizacion->descuento_porcentaje . "%";
$descuento_dinero     = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cotizacion->descuento_dinero);
$monto_neto           = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cotizacion->monto_neto);
$total_iva            = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cotizacion->total_iva);
$total_a_pagar        = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cotizacion->total_a_pagar);
$monto_total          = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cotizacion->monto_total);

//cliente
$query = $pdo->prepare("
  SELECT
  p.rut,
  p.nombre_persona AS nombre,
  p.giro,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion,
  p.telefono AS telefono,
  p.correo AS correo
  FROM cotizacion cot
  INNER JOIN persona p ON p.id_persona = cot.id_cliente
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE cot.id_cotizacion = :id_cotizacion");

$query->bindValue(":id_cotizacion", $id_cotizacion, PDO::PARAM_INT);
$query->execute();

$cliente = $query->fetch();

$empresa = cargarEmpresa();

$fecha = formatearFecha($cotizacion->fecha);

?>

<body>

  <!-- Contenido  -->

  <div class="p-5">

    <div class="form-row">
          
      <?php require_once "../partials/datos_empresa.php" ?>

      <div class="col-sm-4 text-center text-red">
        
        <div class="border-red p-3">

          <div class="font-weight-bold"><?php echo "RUT: " . $empresa->rut ?></div>
          <div class="font-weight-bold h5 m-0"><?php echo "Cotizacion N° " . $cotizacion->id_cotizacion ?></div>
          <div class="font-weight-bold"><?php echo $fecha ?></div>

        </div>

        <div class="font-weight-bold mt-2"><?php echo "Cotización válida hasta el " . date("d-m-Y", strtotime($cotizacion->fecha . "+ 5 days")); ?></div>

      </div>

    </div>

    <hr>
        
    <div class="form-row">

      <div class="col-sm-4">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="rut_cliente">Rut cliente:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="rut_cliente" disabled value="<?php echo $cliente->rut ?>">
      </div>

      <div class="col-sm-4">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="nombre_cliente">Nombre cliente:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_cliente" disabled value="<?php echo ucwords($cliente->nombre) ?>">
      </div>

      <div class="col-sm-4">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="giro_cliente">Giro:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="giro_cliente" disabled value="<?php echo ucfirst($cliente->giro) ?>">
      </div>

    </div>

    <div class="form-row">

      <div class="col-sm-6">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="direccion_cliente">Dirección:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="direccion_cliente" disabled value="<?php echo ucfirst($cliente->direccion) ?>">
      </div>

      <div class="col-sm-3">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="telefono_cliente">Teléfono:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_cliente" disabled value="<?php echo $cliente->telefono ?>">
      </div>

      <div class="col-sm-3">
        <label class="col-form-label-sm text-center text-sm-left m-0 w-100 font-weight-bold" for="correo_cliente">Correo:</label>
        <input type="text" class="form-control form-control-sm rounded-pill" id="correo_cliente" disabled value="<?php echo $cliente->correo ?>">
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
              <label for="medio_pago" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Condición de pago:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="medio_pago" disabled value="<?php echo ucfirst($cotizacion->condicion_pago) ?>">
              </div> 
            </div>

            <div class="form-row mb-2">
              <label for="total_descuento" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Total venta sin dto:</label>
              <div class="col-sm-5">
                <input type="text" class="form-control form-control-sm rounded-pill" id="total_descuento" disabled value="<?php echo $monto_total ?>">
              </div> 
            </div>

            <?php if (strlen($cotizacion->observaciones) > 0): ?>

              <div class="form-row mb-2">
                <label for="observaciones" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0 font-weight-bold">Observaciones:</label>
                <div class="col-sm-8">
                  <p><?php echo ucfirst($cotizacion->observaciones) ?></p>
                </div> 
              </div>
              
            <?php endif ?>

          </div>

          <div class="col-sm-5">

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

      <hr>

      <div class="text-center my-2">
        <div>La disponibilidad de stock debe confirmarse al momento de hacer efectiva la compra, la cotización no implica existencia de stock y todos los productos se entienden disponibles salvo venta previa</div>
      </div>

  </div>

  <script type="text/javascript">
    
    window.print();

  </script>

<?php

require_once "../partials/footer.php";

?>