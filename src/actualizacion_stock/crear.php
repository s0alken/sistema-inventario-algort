<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/formatear_fecha.php";
require_once "../controller/cargar_empresa.php";
require_once "cargar_carrito.php";

$query = $pdo->query("SELECT COUNT(*) + 1 FROM actualizacion_stock");

$n_actualizacion_stock = $query->fetch(PDO::FETCH_COLUMN);

date_default_timezone_set("America/Santiago");

$fecha = formatearFecha(date("Y-m-d H:i:s"));

$empresa = cargarEmpresa();

$carrito            = $_SESSION["sistema"]["actualizacion_stock"]["carrito"];
$proveedor          = $_SESSION["sistema"]["actualizacion_stock"]["proveedor"];
$medio_pago         = $_SESSION["sistema"]["actualizacion_stock"]["medio_pago"];
$documento          = $_SESSION["sistema"]["actualizacion_stock"]["documento"];
$n_documento_compra = $_SESSION["sistema"]["actualizacion_stock"]["n_documento_compra"];
$observaciones      = $_SESSION["sistema"]["actualizacion_stock"]["observaciones"];

$rut_proveedor       = isset($proveedor["rut_proveedor"])       ? $proveedor["rut_proveedor"]       : "";
$nombre_proveedor    = isset($proveedor["nombre_proveedor"])    ? $proveedor["nombre_proveedor"]    : "";
$giro_proveedor      = isset($proveedor["giro_proveedor"])      ? $proveedor["giro_proveedor"]      : "";
$direccion_proveedor = isset($proveedor["direccion_proveedor"]) ? $proveedor["direccion_proveedor"] : "";
$telefono_proveedor  = isset($proveedor["telefono_proveedor"])  ? $proveedor["telefono_proveedor"]  : "";
$correo_proveedor    = isset($proveedor["correo_proveedor"])    ? $proveedor["correo_proveedor"]    : "";

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-actualizacion-stock-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row">
        
        <?php require_once "../partials/datos_empresa.php" ?>

        <div class="col-sm-4 text-center text-red">
          
          <div class="border-red p-3">

            <div class="font-weight-bold"><?php echo "RUT: " . $empresa->rut ?></div>
            <div class="font-weight-bold h5 my-1"><?php echo "Ingreso productos N° " . $n_actualizacion_stock ?></div>
            <div class="font-weight-bold"><?php echo $fecha ?></div>

          </div>

        </div>

      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-4 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="rut_proveedor" name="rut_proveedor" placeholder="Rut proveedor" value="<?php echo $rut_proveedor ?>" data-tipo-persona="proveedor">
          <small id="rut_proveedor_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_proveedores">Buscar proveedor manualmente</button>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_proveedor">Crear proveedor</button>
        </div>

      </div>

      <div class="form-row">

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="nombre_proveedor">Nombre proveedor:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_proveedor" disabled value="<?php echo $nombre_proveedor ?>">
        </div>

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="giro_proveedor">Giro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="giro_proveedor" disabled value="<?php echo $giro_proveedor ?>">
        </div>

      </div>

      <div class="form-row">

        <div class="col-sm-6">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="direccion_proveedor">Dirección:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="direccion_proveedor" disabled value="<?php echo $direccion_proveedor ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="telefono_proveedor">Teléfono:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_proveedor" disabled value="<?php echo $telefono_proveedor ?>">
        </div>

        <div class="col-sm-3">
          <label class="col-form-label-sm text-center text-sm-left m-0 w-100" for="correo_proveedor">Correo:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="correo_proveedor" disabled value="<?php echo $correo_proveedor ?>">
        </div>

      </div>

      <hr>

      <div class="form-row">

        <div class="col-sm-4 mb-3 mb-sm-0">
          <input type="text" class="form-control form-control-sm rounded-pill" id="codigo_barras_actualizacion_stock" placeholder="Código de barras" autofocus>
        </div>

        <div class="col-sm-4 mb-3 mb-sm-0">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_productos">Buscar producto manualmente</button>
        </div>

      </div>

      <!-- Productos  -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm mt-0 mt-sm-3" id="carrito">
          <thead>
            <tr>
              <th scope="col">Código de barras</th>
              <th scope="col">Producto</th>
              <th scope="col">Cantidad</th>
              <th scope="col">Valor costo neto</th>
              <th scope="col">Valor venta con IVA</th>
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
            <label for="medio_pago" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Condición de pago:</label>
            <div class="col-sm-5">
              <select class="form-control form-control-sm rounded-pill campo-actualizacion-stock" id="id_medio_pago" name="id_medio_pago">
                <?php cargarMedioPago($medio_pago["id_medio_pago"]) ?>
              </select>
            </div>   
          </div>

          <div class="form-row mb-2">
            <label for="id_documento" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Tipo documento:</label>
            <div class="col-sm-5">
              <select class="form-control form-control-sm rounded-pill campo-actualizacion-stock" id="id_documento" name="id_documento">
                <?php cargarDocumento($documento["id_documento"]) ?>
              </select>
            </div>   
          </div>

          <div class="form-row mb-2">
            <label for="n_documento_compra" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">N° documento de compra:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control form-control-sm rounded-pill campo-actualizacion-stock" id="n_documento_compra" name="n_documento_compra" value="<?php echo $n_documento_compra ?>">
            </div> 
          </div>

          <div class="form-row mb-2">
            <label for="observaciones" class="col-sm-5 col-form-label-sm text-center text-sm-left m-0">Observaciones:</label>
            <div class="col-sm-7">
              <textarea id="observaciones" name="observaciones" class="form-control campo-actualizacion-stock" rows="2" placeholder="Máximo 300 caracteres"><?php echo $observaciones ?></textarea>
              <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 300 caracteres</small>
            </div>   
          </div>

        </div>

      </div>

      <hr>

      <div class="form-row justify-content-center">
        <div class="col-sm-4">
          <button type="submit" class="btn btn-success btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>
     
    </form>

    <?php

    require_once "modal_crear_proveedor.php";
    require_once "modal_proveedores.php";
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