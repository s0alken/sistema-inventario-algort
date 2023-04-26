 <!-- Wrapper  -->
  <div class="wrapper">
    <!-- Sidebar  -->
    <div id="sidebar">

      <div class="sidebar-header text-center bg-gris">
        <div class="logo-sidebar-img-container mx-auto mb-2">
          <img src="<?php echo '../img/logo.png?' . rand() ?>">
        </div>
        <h6><?php echo $_SESSION["sistema"]["usuario"]->nombre_usuario ?></h6>
        <h6><?php echo $_SESSION["sistema"]["sucursal"]->nombre_sucursal ?></h6>
      </div>

      <ul class="list-unstyled components">
        <li>
          <a href="#pageSubmenu1" data-toggle="collapse" aria-expanded="false">
            <div class="d-flex justify-content-between">
              <div><span class="icon-box"><i class="fas fa-shopping-cart"></i></span>Ventas</div>
              <div><i class="fas fa-chevron-right rotate"></i></div>
            </div>
          </a>
          <ul class="collapse list-unstyled" id="pageSubmenu1">
            <li>
              <a href="../venta/">Realizar venta</a>
            </li>
            <li>
              <a href="../ventas_sistema/">Ver ventas realizadas</a>
            </li>
            <li>
              <a href="../ventas_web/">Ver ventas web</a>
            </li>
            <li>
              <a href="../cotizacion/">Realizar cotización</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false">
            <div class="d-flex justify-content-between">
              <div><span class="icon-box"><i class="fas fa-tools"></i></span>Productos</div>
              <div><i class="fas fa-chevron-right rotate"></i></div>
            </div>
          </a>
          <ul class="collapse list-unstyled" id="pageSubmenu2">
            <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
              <li>
                <a href="../actualizacion_stock/">Actualizar stock</a>
              </li>
            <?php endif ?>
            <li>
              <a href="../productos/">Productos</a>
            </li>
            <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
              <li>
              <a href="../subcategorias/">Subcategorías</a>
              </li>
              <li>
                <a href="../categorias/">Categorías</a>
              </li>
              <li>
                <a href="../familias/">Familias</a>
              </li>
              <li>
                <a href="../marcas/">Marcas</a>
              </li>
              <li>
                <a href="../procedencias/">Procedencias</a>
              </li>
              <li>
                <a href="../unidades_medidas/">Unidades de medida</a>
              </li>
              <li>
                <a href="../imagenes/">Imagenes</a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li>
          <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false">
            <div class="d-flex justify-content-between">
              <div><span class="icon-box"><i class="fas fa-users"></i></span>Clientes</div>
              <div><i class="fas fa-chevron-right rotate"></i></div>
            </div>
          </a>
          <ul class="collapse list-unstyled" id="pageSubmenu3">
            <li>
              <a href="../personas/index.php?tipo_persona=cliente">Clientes</a>
            </li>
          </ul>
        </li>
        <?php if ($_SESSION["sistema"]["usuario"]->administrador): ?>
          <li>
            <a href="#pageSubmenu4" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-truck"></i></span>Proveedores</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu4">
              <li>
                <a href="../personas/index.php?tipo_persona=proveedor">Proveedores</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#pageSubmenu6" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-warehouse"></i></span>Sucursales</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu6">
              <li>
                <a href="../sucursales/">Sucursales</a>
              </li>
              <li>
                <a href="../bodegas/">Bodegas</a>
              </li>
              <li>
                <a href="../lockers/">Lockers</a>
              </li>
              <li>
                <a href="../secciones/">Secciones</a>
              </li>
              <li>
                <a href="../niveles/">Niveles</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#pageSubmenu16" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-exchange-alt"></i></span>Traspasos</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu16">
              <li>
                <a href="../traspaso/">Traspasar stock</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#pageSubmenu7" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fab fa-internet-explorer"></i></span>Mi web</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu7">
              <li>
                <a href="../sliders/">Sliders</a>
              </li>
              <li>
                <a href="../operadores_logisticos/">Operadores logísticos</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#pageSubmenu9" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-pencil-alt"></i></span>Notas</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu9">
              <li>
                <a href="#">Notas de débito</a>
              </li>
              <li>
                <a href="#">Notas de crédito</a>
              </li>
              <li>
                <a href="#">Notas de pedido</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#pageSubmenu11" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-dolly-flatbed"></i></span>Inventario</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu11">

              <?php

              require_once "../controller/conexion.php";

              $query = $pdo->query("SELECT * FROM sucursal WHERE habilitada");

              $sucursales = $query->fetchAll();

              ?>

              <?php foreach ($sucursales as $sucursal): ?>

                <li>
                  <a href="<?php echo '../inventario/index.php?id_sucursal=' . $sucursal->id_sucursal ?>"><?php echo ucfirst($sucursal->nombre_sucursal) ?></a>
                </li>

              <?php endforeach ?>

            </ul>
          </li>
          <li>
            <a href="#pageSubmenu12" data-toggle="collapse" aria-expanded="false">
              <div class="d-flex justify-content-between">
                <div><span class="icon-box"><i class="fas fa-clipboard-list"></i></span>Informes</div>
                <div><i class="fas fa-chevron-right rotate"></i></div>
              </div>
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu12">
              <li>
                <a href="../informes/ventas.php">Historial de ventas</a>
              </li>
              <li>
                <a href="../informes/cotizaciones.php">Historial de cotizaciones</a>
              </li>
              <li>
                <a href="../informes/traspasos.php">Historial de traspasos</a>
              </li>
              <li>
                <a href="../informes/actualizaciones_stock.php">Historial de act. de stock</a>
              </li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
    </div>
    <!-- Fin Sidebar  -->