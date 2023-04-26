<?php

require_once "../controller/conexion.php";

session_start();

$id_producto = $_POST["id_producto"];

$query = $pdo->prepare("
    SELECT
    p.id_producto,
    p.codigo_barras,
    p.codigo_maestro,
    p.n_fabricante_1,
    p.n_fabricante_2,
    p.n_fabricante_3,
    p.descripcion,    
    p.precio_venta,
    p.stock_critico,
    p.id_subcategoria,
    m.nombre_marca AS marca,
    pe.nombre_persona AS proveedor,
    pr.nombre_procedencia AS procedencia,
    SUM(sp.stock) AS stock,
    p.observaciones,
    p.caracteristicas_tecnicas,
    p.acumula_puntos
    FROM producto p
    INNER JOIN stock_producto sp ON sp.id_producto = p.id_producto
    INNER JOIN sucursal s ON s.id_sucursal = sp.id_sucursal
    INNER JOIN marca m ON m.id_marca = p.id_marca
    INNER JOIN persona pe ON pe.id_persona = p.id_proveedor
    INNER JOIN procedencia pr ON pr.id_procedencia = p.id_procedencia
    WHERE p.id_producto = :id_producto
    AND s.habilitada
    GROUP BY p.id_producto");

$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
$query->execute();

$producto = $query->fetch();

//seteando puntos
$producto->puntos = $producto->acumula_puntos ? round(($producto->precio_venta * 2) / 100) : 0;

//stock del producto por sucursal
$query = $pdo->prepare("
    SELECT
    sp.stock,
    s.nombre_sucursal AS sucursal
    FROM stock_producto sp
    INNER JOIN sucursal s ON s.id_sucursal = sp.id_sucursal
    WHERE sp.id_producto = :id_producto
    AND s.habilitada");

$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
$query->execute();

$stocks_producto = $query->fetchAll();

//imágenes del producto
$query = $pdo->prepare("
    SELECT
    i.nombre_imagen
    FROM imagen_producto ip
    INNER JOIN imagen i ON i.id_imagen = ip.id_imagen
    WHERE ip.id_producto = :id_producto");

$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
$query->execute();

$imagenes = $query->fetchAll();

//compatibilidades del producto
$query = $pdo->prepare("
    SELECT p.codigo_barras, p.descripcion, p.precio_venta
    FROM compatibilidad c
    INNER JOIN producto p ON p.id_producto = c.id_producto_b
    WHERE c.id_producto_a = :id_producto");

$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
$query->execute();

$compatibilidades = $query->fetchAll();

//comprobando si el producto tiene ubicación establecida
$query = $pdo->prepare("
    SELECT
    n.nombre_nivel AS nivel,
    s.nombre_seccion AS seccion,
    l.nombre_locker AS locker,
    b.nombre_bodega AS bodega,
    su.nombre_sucursal AS sucursal
    FROM ubicacion u
    INNER JOIN nivel n ON n.id_nivel = u.id_nivel
    INNER JOIN seccion s ON s.id_seccion = n.id_seccion
    INNER JOIN locker l ON l.id_locker = s.id_locker
    INNER JOIN bodega b ON b.id_bodega = l.id_bodega
    INNER JOIN sucursal su ON su.id_sucursal = b.id_sucursal
    WHERE u.id_producto = :id_producto
    AND b.id_sucursal = :id_sucursal");

$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$ubicacion = $query->fetch();

?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-5">
           
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">

              <?php if ($imagenes): ?>
                
                <div class="carousel-inner">

                    <?php foreach ($imagenes as $key => $imagen): ?>

                    <div class="<?php echo $key === 0 ? 'carousel-item active' : 'carousel-item' ?>">
                        <div class="modal-img-container">
                            <a href="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>" data-lightbox="example-set" data-title="<?php echo $imagen->nombre_imagen ?>">
                                <img class="img-thumbnail" src="<?php echo '../img/productos/' . $imagen->nombre_imagen ?>"  alt="">
                            </a>
                        </div>
                    </div>

                    <?php endforeach ?>

                </div>

                <?php if (count($imagenes) > 1): ?>
                    
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                <?php endif ?>

              <?php else: ?>

                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <div class="modal-img-container">
                          <img class="img-thumbnail" src="../img/productos/default.jpg"  alt="">
                        </div>
                    </div>

                </div>

              <?php endif ?>

            </div>

        </div>

        <div class="col-lg-4 text-capitalize">

            <div class="d-flex justify-content-between">
                <h4 class="text-empresa font-weight-bold text-capitalize m-0"><?php echo $producto->descripcion ?></h4>
            </div>

            <hr class="my-2"> 

            <?php if ($producto->codigo_barras != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Código de barras:</div>
                <div><?php echo $producto->codigo_barras ?></div>
            </div>

            <?php endif ?>

            <?php if ($producto->codigo_maestro != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Código maestro:</div>
                <div><?php echo $producto->codigo_maestro ?></div>
            </div>

            <?php endif ?>

            <?php if ($producto->n_fabricante_1 != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">N° fabricante 1:</div>
                <div><?php echo $producto->n_fabricante_1 ?></div>
            </div>
            
            <?php endif ?>

            <?php if ($producto->n_fabricante_2 != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">N° fabricante 2:</div>
                <div><?php echo $producto->n_fabricante_2 ?></div>
            </div>
            
            <?php endif ?>

            <?php if ($producto->n_fabricante_3 != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">N° fabricante 3:</div>
                <div><?php echo $producto->n_fabricante_3 ?></div>
            </div>
            
            <?php endif ?>

            <?php if ($producto->marca != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Marca:</div>
                <div><?php echo $producto->marca ?></div>
            </div>
            
            <?php endif ?>

            <?php if ($producto->proveedor != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Proveedor:</div>
                <div><?php echo $producto->proveedor ?></div>
            </div>
            
            <?php endif ?>

            <?php if ($producto->procedencia != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Procedencia:</div>
                <div><?php echo $producto->procedencia ?></div>
            </div>
            
            <?php endif ?>

            <?php foreach ($stocks_producto as $stock_producto): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold"><?php echo 'Stock ' . $stock_producto->sucursal . ":" ?></div>
                <div><?php echo $stock_producto->stock ?></div>
            </div>

            <?php endforeach ?>

            <?php if ($producto->stock_critico != ""): ?>

            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Stock crítico:</div>
                <div><?php echo $producto->stock_critico ?></div>
            </div>
            
            <?php endif ?>

        </div>

        <div class="col-lg-3 text-capitalize">

            <div class="text-right mb-3">
                <h1 class="text-empresa font-weight-bold"><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->precio_venta) ?></h1>
                <div>Precio producto</div>
            </div>

            <hr class="my-2"> 

            <div class="text-right mb-3">
                <h1 class="text-empresa font-weight-bold"><?php echo preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $producto->puntos) ?></h1>
                <div>Puntos compra</div>
            </div>

            <hr class="my-2">

            <div class="text-right mb-3">
                <div class="mt-2"><?php echo $producto->stock > 0 ? "disponible" : "agotado" ?></div>
            </div>

        </div>

    </div>
    <!-- Fin Row -->

    <div class="row">

        <?php if ($producto->observaciones != ""): ?>

        <div class="col-12 col-lg-4">
          <h5 class="text-empresa font-weight-bold text-capitalize my-2">Observaciones</h5>
          <p class="m-0"><?php echo $producto->observaciones ?></p>
        </div>

        <?php endif ?>

        <?php if ($producto->caracteristicas_tecnicas != ""): ?>

        <div class="col-12 col-lg-4">
          <h5 class="text-empresa font-weight-bold text-capitalize my-2">Características técnicas</h5>
          <p class="m-0"><?php echo $producto->caracteristicas_tecnicas ?></p>
        </div>

        <?php endif ?>

        <?php if ($ubicacion): ?>

        <div class="col-12 col-lg-4">
            <h5 class="text-empresa font-weight-bold text-capitalize my-2">Ubicación</h5>
            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Bodega:</div>
                <div><?php echo $ubicacion->bodega ?></div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Locker:</div>
                <div><?php echo $ubicacion->locker ?></div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Sección:</div>
                <div><?php echo $ubicacion->seccion ?></div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="text-empresa font-weight-bold">Nivel:</div>
                <div><?php echo $ubicacion->nivel ?></div>
            </div>
        </div>

        <?php endif ?>

    </div>
    <!-- Fin Row -->

    <?php if ($compatibilidades): ?>
       
       <div class="row">

            <div class="col-12">

              <h5 class="text-empresa font-weight-bold text-capitalize my-2">Compatibilidad</h5>

              <!-- Tabla Aplicación -->
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th scope="col">Código de barras</th>
                      <th scope="col">Producto</th>
                      <th scope="col">Precio</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($compatibilidades as $compatibilidad): ?>
              
                      <tr>
                        <td><?php echo $compatibilidad->codigo_barras ?></td>
                        <td><?php echo $compatibilidad->descripcion ?></td>
                        <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $compatibilidad->precio_venta) ?></td>
                      </tr>

                    <?php endforeach ?>

                  </tbody>
                </table>
              </div>
              <!-- Fin Tabla Aplicación -->

            </div>
            <!-- Fin Col -->

        </div>
        <!-- Fin Row -->

    <?php endif ?>

</div>
<!-- Fin Container Fluid -->