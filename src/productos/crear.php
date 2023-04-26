<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/cargar_campos_medidas.php";
require_once "cargar_compatibilidad.php";
require_once "cargar_imagenes.php";

$id_familia               = $_SESSION["sistema"]["producto"]["id_familia"];
$id_categoria             = $_SESSION["sistema"]["producto"]["id_categoria"];
$id_subcategoria          = $_SESSION["sistema"]["producto"]["id_subcategoria"];
$codigo_barras            = $_SESSION["sistema"]["producto"]["codigo_barras"];
$codigo_maestro           = $_SESSION["sistema"]["producto"]["codigo_maestro"];
$n_fabricante_1           = $_SESSION["sistema"]["producto"]["n_fabricante_1"];
$n_fabricante_2           = $_SESSION["sistema"]["producto"]["n_fabricante_2"];
$n_fabricante_3           = $_SESSION["sistema"]["producto"]["n_fabricante_3"];
$descripcion              = $_SESSION["sistema"]["producto"]["descripcion"];
$precio_costo             = $_SESSION["sistema"]["producto"]["precio_costo"];
$precio_venta             = $_SESSION["sistema"]["producto"]["precio_venta"];
$stock                    = $_SESSION["sistema"]["producto"]["stock"];
$stock_critico            = $_SESSION["sistema"]["producto"]["stock_critico"];
$id_marca                 = $_SESSION["sistema"]["producto"]["id_marca"];
$id_proveedor             = $_SESSION["sistema"]["producto"]["id_proveedor"];
$id_procedencia           = $_SESSION["sistema"]["producto"]["id_procedencia"];
$ubicacion                = $_SESSION["sistema"]["producto"]["ubicacion"];
$observaciones            = $_SESSION["sistema"]["producto"]["observaciones"];
$caracteristicas_tecnicas = $_SESSION["sistema"]["producto"]["caracteristicas_tecnicas"];
$puntos                   = $_SESSION["sistema"]["producto"]["puntos"];
$medidas                  = $_SESSION["sistema"]["producto"]["medidas"];
$compatibilidad_producto  = $_SESSION["sistema"]["producto"]["compatibilidad"];
$imagenes_producto        = $_SESSION["sistema"]["producto"]["imagenes"];
$acumula_puntos           = $_SESSION["sistema"]["producto"]["acumula_puntos"];
$habilitado_tienda        = $_SESSION["sistema"]["producto"]["habilitado_tienda"];

$configurar_producto = true;

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-producto-crear" action="crear_controller.php?redireccionar=true" method="POST">

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="id_familia">Familia:</label>
          <select id="id_familia" name="id_familia" class="form-control form-control-sm rounded-pill campo-producto">
            <?php cargarFamilia($id_familia, true); ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill mt-3" data-toggle="modal" data-target="#modal_crear_familia">Crear familia</button>
        </div>
        <div class="form-group col-md-4">
          <label for="id_categoria">Categoría:</label>
          <select id="id_categoria" name="id_categoria" class="form-control form-control-sm rounded-pill campo-producto">
            <?php cargarCategoria($id_categoria, $id_familia, true); ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill mt-3" data-toggle="modal" data-target="#modal_crear_categoria">Crear categoría</button>
        </div>
        <div class="form-group col-md-4">
          <label for="id_subcategoria">Subcategoría:</label>
          <select id="id_subcategoria" name="id_subcategoria" class="form-control form-control-sm rounded-pill campo-producto">
            <?php cargarSubcategoria($id_subcategoria, $id_categoria); ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill mt-3" data-toggle="modal" data-target="#modal_crear_subcategoria">Crear subcategoría</button>
        </div>
      </div>

      <div class="form-row">

        <div class="form-group col-md-6">
          <label for="codigo_barras"><span class="text-danger">*</span> Código de barras:</label>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control campo-producto" id="codigo_barras" name="codigo_barras" value="<?php echo $codigo_barras ?>">
            <div class="input-group-prepend">
              <button id="btn_generar_codigo_barras" type="button" class="btn btn-primary" data-target="#codigo_barras" data-toggle="tooltip" data-placement="right" title="Generar código de barras"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <small id="codigo_barras_alerta" class="form-text text-danger font-weight-bold alerta">Este código de barras ya está en uso</small>
        </div>
        <div class="form-group col-md-6">
          <label for="codigo_maestro">Código maestro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-producto" id="codigo_maestro" name="codigo_maestro" value="<?php echo $codigo_maestro ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="n_fabricante_1">N° fabricante 1:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-producto" id="n_fabricante_1" name="n_fabricante_1" value="<?php echo $n_fabricante_1 ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="n_fabricante_2">N° fabricante 2:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-producto" id="n_fabricante_2" name="n_fabricante_2" value="<?php echo $n_fabricante_2 ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="n_fabricante_3">N° fabricante 3:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-producto" id="n_fabricante_3" name="n_fabricante_3" value="<?php echo $n_fabricante_3 ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col">
          <label for="descripcion"><span class="text-danger">*</span> Descripción:</label>
          <input type="text" class="form-control form-control-sm rounded-pill campo-producto" id="descripcion" name="descripcion" value="<?php echo $descripcion ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="precio_costo"><span class="text-danger">*</span> Valor costo neto:</label>
          <input type="number" class="form-control form-control-sm rounded-pill campo-producto" id="precio_costo" name="precio_costo" min="0" value="<?php echo $precio_costo ?>">
        </div>
        <div class="form-group col-md-3">
          <label for="precio_venta"><span class="text-danger">*</span> Valor venta con IVA:</label>
          <input type="number" class="form-control form-control-sm rounded-pill campo-producto" id="precio_venta" name="precio_venta" min="0" value="<?php echo $precio_venta ?>">
          <small id="precio_venta_alerta" class="form-text text-danger font-weight-bold alerta">El valor de costo neto debe ser mayor o igual al valor de venta con IVA</small>
        </div>
        <div class="form-group col-md-3">
          <label for="stock"><span class="text-danger">*</span> Stock:</label>
          <input type="number" class="form-control form-control-sm rounded-pill campo-producto" id="stock" name="stock" min="0" value="<?php echo $stock ?>">
        </div>
        <div class="form-group col-md-3">
          <label for="stock_critico"><span class="text-danger">*</span> Stock crítico:</label>
          <input type="number" class="form-control form-control-sm rounded-pill campo-producto" id="stock_critico" name="stock_critico" min="0" value="<?php echo $stock_critico ?>">
        </div>
      </div>

      <div class="form-row" id="campos_medidas">
        <?php cargarCamposMedidas($id_categoria, $medidas, true) ?>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="id_marca">Marca:</label>
          <select id="id_marca" name="id_marca" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
            <?php cargarMarca($id_marca) ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_marca">Crear marca</button>
        </div>
        <div class="form-group col-md-4">
          <label for="id_proveedor">Proveedor:</label>
          <select id="id_proveedor" name="id_proveedor" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
            <?php cargarProveedor($id_proveedor) ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_proveedor">Crear proveedor</button>
        </div>
        <div class="form-group col-md-4">
          <label for="id_procedencia">Procedencia:</label>
          <select id="id_procedencia" name="id_procedencia" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
            <?php cargarProcedencia($id_procedencia) ?>
          </select>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_procedencia">Crear procedencia</button>
        </div>
      </div>
        
      <?php if ($ubicacion): ?>
        
        <div class="form-row">

          <div class="form-group col-md-3">
            <label for="id_bodega">Bodega:</label>
            <select id="id_bodega" name="ubicacion[id_bodega]" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
              <?php cargarBodega($ubicacion["id_bodega"], true) ?>
            </select>
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_bodega">Crear bodega</button>
          </div>

          <div class="form-group col-md-3">
            <label for="id_locker">Locker:</label>
            <select id="id_locker" name="ubicacion[id_locker]" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
              <?php cargarLocker($ubicacion["id_locker"], $ubicacion["id_bodega"], true) ?>
            </select>
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_locker">Crear locker</button>
          </div>

          <div class="form-group col-md-3">
            <label for="id_seccion">Sección:</label>
            <select id="id_seccion" name="ubicacion[id_seccion]" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
              <?php cargarSeccion($ubicacion["id_seccion"], $ubicacion["id_locker"], true) ?>
            </select>
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_seccion">Crear sección</button>
          </div>

          <div class="form-group col-md-3">
            <label for="id_nivel">Nivel:</label>
            <select id="id_nivel" name="ubicacion[id_nivel]" class="form-control form-control-sm rounded-pill mb-3 campo-producto">
              <?php cargarNivel($ubicacion["id_nivel"], $ubicacion["id_seccion"]) ?>
            </select>
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_nivel">Crear nivel</button>
          </div>

        </div>

      <?php else: ?>

        <div class="form-row justify-content-center text-center">

          <div class="col-12 text-danger font-weight-bold"><h3><i class="fas fa-exclamation-triangle"></i></h3></div>
          <div class="col-12 text-danger font-weight-bold">Esta sucursal no tiene bodegas creadas</div>
          <div class="form-group col-md-3 mt-2">
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_crear_bodega">Crear bodega</button>
          </div>

        </div>

      <?php endif ?>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="observaciones">Observaciones:</label>
          <textarea class="form-control campo-producto" id="observaciones" name="observaciones" rows="2" placeholder="Máximo 1.500 caracteres"><?php echo $observaciones ?></textarea>
          <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 1.500 caracteres</small>
        </div>
        <div class="form-group col-md-6">
          <label for="caracteristicas_tecnicas">Caracteristicas técnicas:</label>
          <textarea class="form-control campo-producto" id="caracteristicas_tecnicas" name="caracteristicas_tecnicas" rows="2" placeholder="Máximo 1.500 caracteres"><?php echo $caracteristicas_tecnicas ?></textarea>
          <small id="caracteristicas_tecnicas_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 1.500 caracteres</small>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="btn_subir_imagen">Compatibilidad:</label>
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_compatibilidad">Seleccionar</button>
        </div>
        <div class="form-group col-md-4">
          <label for="btn_subir_imagen">Imágenes:</label>
          <button type="button" id="btn_subir_imagen" class="btn btn-success btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_imagenes">Seleccionar</button>
        </div>
        <div class="form-group col-md-4">
          <label for="puntos">Puntos compra:</label>
          <input type="number" class="form-control form-control-sm rounded-pill campo-producto" id="puntos" name="puntos" value="<?php echo $puntos ?>" disabled>
        </div>
      </div>

      <div class="form-row justify-content-end text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input campo-producto" id="acumula_puntos" name="acumula_puntos" <?php echo $acumula_puntos ?>>
          <label class="form-check-label" for="acumula_puntos">Acumula puntos</label>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input campo-producto" id="habilitado_tienda" name="habilitado_tienda" <?php echo $habilitado_tienda ?>>
          <label class="form-check-label" for="habilitado_tienda">Mostrar producto en tienda</label>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <span class="text-danger">(*) Campos obligatorios</span>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>

      <!-- Modals -->
      <?php

      require_once "modal_compatibilidad.php";
      require_once "modal_imagenes.php";
      require_once "modal_subir_imagen.php";

      ?>

    </form>

    <?php

    require_once "modal_crear_familia.php";
    require_once "modal_crear_categoria.php";
    require_once "modal_crear_subcategoria.php";
    require_once "modal_crear_marca.php";
    require_once "modal_crear_proveedor.php";
    require_once "modal_crear_procedencia.php";
    require_once "modal_crear_bodega.php";
    require_once "modal_crear_locker.php";
    require_once "modal_crear_seccion.php";
    require_once "modal_crear_nivel.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>