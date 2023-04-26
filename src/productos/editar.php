<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/cargar_campos_medidas.php";
require_once "cargar_compatibilidad.php";
require_once "cargar_imagenes.php";

$query = $pdo->prepare("
  SELECT
  p.id_producto,
  p.codigo_barras,
  p.codigo_maestro,
  p.n_fabricante_1,
  p.n_fabricante_2,
  p.n_fabricante_3,
  p.descripcion,
  p.precio_costo,
  p.precio_venta,
  sp.stock,
  p.stock_critico,
  p.id_marca,
  p.id_proveedor,
  p.id_procedencia,
  p.observaciones,
  p.caracteristicas_tecnicas,
  p.acumula_puntos,
  p.habilitado_tienda,
  p.id_subcategoria,
  sc.id_categoria,
  c.id_familia
  FROM producto p
  INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
  INNER JOIN subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
  INNER JOIN categoria c ON c.id_categoria = sc.id_categoria
  INNER JOIN familia f ON f.id_familia = c.id_familia
  WHERE p.id_producto = :id_producto
  AND sp.id_sucursal = :id_sucursal");

$query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$producto = $query->fetch();

//seteando puntos
$producto->puntos = round(($producto->precio_venta * 2) / 100);

$query = $pdo->prepare("SELECT id_producto_b FROM compatibilidad WHERE id_producto_a = :id_producto");

$query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
$query->execute();

$compatibilidad_producto = $query->fetchAll(PDO::FETCH_COLUMN);

$query = $pdo->prepare("SELECT id_imagen FROM imagen_producto WHERE id_producto = :id_producto");

$query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
$query->execute();

$imagenes_producto = $query->fetchAll(PDO::FETCH_COLUMN);

$query = $pdo->prepare("SELECT id_medida, valor_medida FROM medida_producto WHERE id_producto = :id_producto");

$query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
$query->execute();

$medidas = $query->fetchAll(PDO::FETCH_KEY_PAIR);

//comprobando si la sucursal tiene bodegas disponibles
$query = $pdo->prepare("
  SELECT * FROM nivel n
  INNER JOIN seccion s ON s.id_seccion = n.id_seccion
  INNER JOIN locker l ON l.id_locker = s.id_locker
  INNER JOIN bodega b ON b.id_bodega =  l.id_locker
  WHERE n.habilitado AND b.id_sucursal = :id_sucursal
  LIMIT 1");

$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$ubicacion = $query->fetch();

if ($ubicacion) {

  //comprobando si el producto tiene ubicación establecida
  $query = $pdo->prepare("
    SELECT
    u.id_nivel,
    n.id_seccion,
    s.id_locker,
    l.id_bodega
    FROM ubicacion u
    INNER JOIN nivel n ON n.id_nivel = u.id_nivel
    INNER JOIN seccion s ON s.id_seccion = n.id_seccion
    INNER JOIN locker l ON l.id_locker = s.id_locker
    INNER JOIN bodega b ON b.id_bodega = l.id_bodega
    WHERE u.id_producto = :id_producto
    AND b.id_sucursal = :id_sucursal");

  $query->bindValue(":id_producto", $_GET["id_producto"], PDO::PARAM_INT);
  $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
  $query->execute();

  $ubicacion_producto = $query->fetch();

  if ($ubicacion_producto) {

    $producto->id_nivel   = $ubicacion_producto->id_nivel;
    $producto->id_seccion = $ubicacion_producto->id_seccion;
    $producto->id_locker  = $ubicacion_producto->id_locker;
    $producto->id_bodega  = $ubicacion_producto->id_bodega;

  } else {

    //obteniendo el id_bodega de la primera bodega con niveles ordenada alfabéticamente
    $query = $pdo->prepare("
      SELECT b.id_bodega FROM bodega b
      WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
      (SELECT 1 FROM nivel n
      INNER JOIN seccion s ON s.id_seccion = n.id_seccion
      INNER JOIN locker l ON l.id_locker = s.id_locker
      WHERE l.id_bodega = b.id_bodega
      AND n.habilitado)
      ORDER BY b.nombre_bodega
      LIMIT 1");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $producto->id_bodega = $query->fetch(PDO::FETCH_COLUMN);

    //obteniendo el id_locker del primer locker con niveles ordenado alfabéticamente
    $query = $pdo->prepare("
      SELECT l.id_locker FROM locker l
      WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
      (SELECT 1 FROM nivel n
      INNER JOIN seccion s ON s.id_seccion = n.id_seccion
      WHERE s.id_locker = l.id_locker
      AND n.habilitado)
      ORDER BY l.nombre_locker
      LIMIT 1");

    $query->bindValue(":id_bodega", $producto->id_bodega, PDO::PARAM_INT);
    $query->execute();

    $producto->id_locker = $query->fetch(PDO::FETCH_COLUMN);

    //obteniendo el id_seccion de la primera sección con niveles ordenada alfabéticamente
    $query = $pdo->prepare("
      SELECT s.id_seccion FROM seccion s
      WHERE s.id_locker = :id_locker AND s.habilitada AND EXISTS
      (SELECT 1 FROM nivel n
      WHERE n.id_seccion = s.id_seccion
      AND n.habilitado)
      ORDER BY s.nombre_seccion
      LIMIT 1");

    $query->bindValue(":id_locker", $producto->id_locker, PDO::PARAM_INT);
    $query->execute();

    $producto->id_seccion = $query->fetch(PDO::FETCH_COLUMN);

    //obteniendo el id_nivel del primer nivel ordenado alfabéticamente
    $query = $pdo->prepare("SELECT id_nivel FROM nivel WHERE id_seccion = :id_seccion AND habilitado ORDER BY nombre_nivel");

    $query->bindValue(":id_seccion", $producto->id_seccion, PDO::PARAM_INT);
    $query->execute();

    $producto->id_nivel = $query->fetch(PDO::FETCH_COLUMN);

  }

}

$configurar_producto = false;

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-producto-editar" action="<?php echo 'editar_controller.php?id_producto=' . $_GET['id_producto'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="id_familia">Familia:</label>
          <select id="id_familia" name="id_familia" class="form-control form-control-sm rounded-pill">
            <?php cargarFamilia($producto->id_familia, true); ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="id_categoria">Categoría:</label>
          <select id="id_categoria" name="id_categoria" class="form-control form-control-sm rounded-pill">
            <?php cargarCategoria($producto->id_categoria, $producto->id_familia, true); ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="id_subcategoria">Subcategoría:</label>
          <select id="id_subcategoria" name="id_subcategoria" class="form-control form-control-sm rounded-pill">
            <?php cargarSubcategoria($producto->id_subcategoria, $producto->id_categoria); ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="codigo_barras"><span class="text-danger">*</span> Código de barras:</label>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="<?php echo $producto->codigo_barras ?>">
            <div class="input-group-prepend">
              <button id="btn_generar_codigo_barras" type="button" class="btn btn-primary" data-target="#codigo_barras" data-toggle="tooltip" data-placement="right" title="Generar código de barras"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <small id="codigo_barras_alerta" class="form-text text-danger font-weight-bold alerta">Este código de barras ya está en uso</small>
        </div>
        <div class="form-group col-md-6">
          <label for="codigo_maestro">Código maestro:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="codigo_maestro" name="codigo_maestro" value="<?php echo $producto->codigo_maestro ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="n_fabricante_1">N° fabricante 1:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="n_fabricante_1" name="n_fabricante_1" value="<?php echo $producto->n_fabricante_1 ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="n_fabricante_2">N° fabricante 2:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="n_fabricante_2" name="n_fabricante_2" value="<?php echo $producto->n_fabricante_2 ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="n_fabricante_3">N° fabricante 3:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="n_fabricante_3" name="n_fabricante_3" value="<?php echo $producto->n_fabricante_3 ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col">
          <label for="descripcion"><span class="text-danger">*</span> Descripción:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="descripcion" name="descripcion" value="<?php echo $producto->descripcion ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-3">
          <label for="precio_costo"><span class="text-danger">*</span> Valor costo neto:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="precio_costo" name="precio_costo" min="0" value="<?php echo $producto->precio_costo ?>">
        </div>
        <div class="form-group col-md-3">
          <label for="precio_venta"><span class="text-danger">*</span> Valor venta con IVA:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="precio_venta" name="precio_venta" min="0" value="<?php echo $producto->precio_venta ?>">
          <small id="precio_venta_alerta" class="form-text text-danger font-weight-bold alerta">El valor de costo neto debe ser mayor o igual al valor de venta con IVA</small>
        </div>
        <div class="form-group col-md-3">
          <label for="stock"><span class="text-danger">*</span> Stock:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="stock" name="stock" min="0" value="<?php echo $producto->stock ?>">
        </div>
        <div class="form-group col-md-3">
          <label for="stock_critico"><span class="text-danger">*</span> Stock crítico:</label>
          <input type="number" class="form-control form-control-sm rounded-pill" id="stock_critico" name="stock_critico" min="0" value="<?php echo $producto->stock_critico ?>">
        </div>
      </div>

      <div class="form-row" id="campos_medidas">
        <?php cargarCamposMedidas($producto->id_categoria, $medidas) ?>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="id_marca">Marca:</label>
          <select id="id_marca" name="id_marca" class="form-control form-control-sm rounded-pill">
            <?php cargarMarca($producto->id_marca) ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="id_proveedor">Proveedor:</label>
          <select id="id_proveedor" name="id_proveedor" class="form-control form-control-sm rounded-pill">
            <?php cargarProveedor($producto->id_proveedor) ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="id_procedencia">Procedencia:</label>
          <select id="id_procedencia" name="id_procedencia" class="form-control form-control-sm rounded-pill">
            <?php cargarProcedencia($producto->id_procedencia) ?>
          </select>
        </div>
      </div>

      <?php if ($ubicacion): ?>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="id_bodega">Bodega:</label>
            <select id="id_bodega" name="ubicacion[id_bodega]" class="form-control form-control-sm rounded-pill">
              <?php cargarBodega($producto->id_bodega, true) ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="id_locker">Locker:</label>
            <select id="id_locker" name="ubicacion[id_locker]" class="form-control form-control-sm rounded-pill">
              <?php cargarLocker($producto->id_locker, $producto->id_bodega, true) ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="id_seccion">Sección:</label>
            <select id="id_seccion" name="ubicacion[id_seccion]" class="form-control form-control-sm rounded-pill">
              <?php cargarSeccion($producto->id_seccion, $producto->id_locker, true) ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="id_nivel">Nivel:</label>
            <select id="id_nivel" name="ubicacion[id_nivel]" class="form-control form-control-sm rounded-pill">
              <?php cargarNivel($producto->id_nivel, $producto->id_seccion) ?>
            </select>
          </div>
      </div>
        
      <?php endif ?>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="observaciones">Observaciones:</label>
          <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Máximo 1.500 caracteres"><?php echo $producto->observaciones ?></textarea>
          <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 1.500 caracteres</small>
        </div>
        <div class="form-group col-md-6">
          <label for="caracteristicas_tecnicas">Caracteristicas técnicas:</label>
          <textarea class="form-control" id="caracteristicas_tecnicas" name="caracteristicas_tecnicas" rows="2" placeholder="Máximo 1.500 caracteres"><?php echo $producto->caracteristicas_tecnicas ?></textarea>
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
          <input type="number" class="form-control form-control-sm rounded-pill" id="puntos" name="puntos" value="<?php echo $producto->puntos ?>" disabled>
        </div>
      </div>

      <div class="form-row justify-content-end text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input" id="acumula_puntos" name="acumula_puntos" <?php echo $producto->acumula_puntos ? "checked" : "" ?>>
          <label class="form-check-label" for="acumula_puntos">Acumula puntos</label>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-check col-md-4 mb-3">
          <input type="checkbox" class="form-check-input" id="habilitado_tienda" name="habilitado_tienda" <?php echo $producto->habilitado_tienda ? "checked" : "" ?>>
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

    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>