<?php

require "../controller/indicador_economico.php";
require "../controller/cargar_cantidad_ventas_web_procesar.php";
require "../controller/cargar_cantidad_stock_critico.php";

$cantidad_ventas_web_procesar = cargarCantidadVentasWebProcesar();

$cantidad_ventas_web = $cantidad_ventas_web_procesar > 1 ? $cantidad_ventas_web_procesar . " ventas web" : "1 venta web";

$mensaje_ventas_web_procesar = "Tienes " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cantidad_ventas_web) . " por procesar";

$cantidad_stock_critico = cargarCantidadStockCritico();

$cantidad_productos = $cantidad_stock_critico > 1 ? $cantidad_stock_critico . " productos" : "1 producto";

$mensaje_stock_critico = "Tienes " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $cantidad_productos) . " en stock crítico";

?>

<body>
  <!-- Navbar Top  -->
  <nav class="navbar navbar-expand fixed-top d-none d-sm-flex bg-empresa" id="nav-top">
    <div class="container">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="<?php echo $cantidad_ventas_web_procesar ? 'nav-item' : 'nav-item invisible' ?>" id="notificacion_ventas_web_procesar">
            <a class="nav-link text-warning" href="../ventas_web_procesar/">
              <i class="fas fa-exclamation-triangle mr-1"></i><span><?php echo $mensaje_ventas_web_procesar ?></span>
            </a>
          </li>
          <?php if ($_SESSION["sistema"]["usuario"]->administrador && $cantidad_stock_critico > 0): ?>
            <li class="nav-item">
              <a class="nav-link" href="../stock_critico/"><i class="fas fa-exclamation-triangle mr-1"></i><?php echo $mensaje_stock_critico ?></a>
            </li>
          <?php endif ?>
          <li class="nav-item">
            <a class="nav-link" href="#"><?php echo "Dólar: $" . $dailyIndicators->dolar->valor ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><?php echo "UF: $" . $dailyIndicators->uf->valor ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../venta/">Carrito: <span id="cantidad"><?php echo $_SESSION["sistema"]["venta"]["cantidad"] ?></span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo ucfirst($_SESSION["sistema"]["usuario"]->nombre_usuario) ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../cuenta/">Mi cuenta</a>

              <?php if($_SESSION["sistema"]["usuario"]->administrador): ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../empresa/">Mi empresa</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../usuarios/">Gestionar usuarios</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../personalizar/">Personalizar</a>
                <div class="dropdown-divider"></div>
              <?php endif ?>
              
              <a class="dropdown-item" href="../logout/">Cerrar sesión</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Fin Navbar Top  -->

  <!-- Navbar Bottom  -->
  <nav class="navbar navbar-expand fixed-top d-none d-sm-flex text-empresa" id="nav-bottom">
    <div class="container">
      <button type="button" id="sidebarCollapse">
        <i class="fas fa-bars text-empresa"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto text-center">
          
        </ul>
      </div>
    </div>
  </nav>
  <!-- Fin Navbar Bottom  -->

  <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light d-sm-none">
    <a class="navbar-brand" href="#">Menú</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Ventas
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../venta/">Realizar venta</a>
            <a class="dropdown-item" href="../ventas_sistema/">Ver ventas realizadas</a>
            <a class="dropdown-item" href="../ventas_web/">Ver ventas web</a>
            <a class="dropdown-item" href="../cotizacion/">Realizar cotización</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Productos
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if($_SESSION["sistema"]["usuario"]->administrador): ?>
              <a class="dropdown-item" href="../actualizacion_stock/">Actualizar stock</a>
            <?php endif ?>
            <a class="dropdown-item" href="../productos/">Productos</a>
            <?php if($_SESSION["sistema"]["usuario"]->administrador): ?>
              <a class="dropdown-item" href="../subcategorias/">Subcategorías</a>
              <a class="dropdown-item" href="../categorias/">Categorías</a>
              <a class="dropdown-item" href="../familas/">Familias</a>
              <a class="dropdown-item" href="../marcas/">Marcas</a>
              <a class="dropdown-item" href="../procedencias/">Procedencias</a>
              <a class="dropdown-item" href="../unidades_medidas/">Unidades de medida</a>
              <a class="dropdown-item" href="../imagenes/">Imagenes</a>
            <?php endif ?>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Clientes
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../personas/index.php?tipo_persona=cliente">Clientes</a>
          </div>
        </li>
        <?php if($_SESSION["sistema"]["usuario"]->administrador): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Proveedores
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../personas/index.php?tipo_persona=proveedor">Proveedores</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Sucursales
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../sucursales/">Sucursales</a>
              <a class="dropdown-item" href="../bodegas/">Bodegas</a>
              <a class="dropdown-item" href="../lockers/">Lockers</a>
              <a class="dropdown-item" href="../secciones/">Secciones</a>
              <a class="dropdown-item" href="../niveles/">Niveles</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Traspasos
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../traspaso/">Traspasar stock</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Mi web
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../sliders/">Sliders</a>
              <a class="dropdown-item" href="../operadores_logisticos/">Operadores logísticos</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Inventario
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

              <?php

              require_once "../controller/conexion.php";

              $query = $pdo->query("SELECT * FROM sucursal WHERE habilitada");

              $sucursales = $query->fetchAll();

              ?>

              <?php foreach ($sucursales as $sucursal): ?>

                  <a class="dropdown-item" href="<?php echo '../inventario/index.php?id_sucursal=' . $sucursal->id_sucursal ?>">
                    <?php echo ucfirst($sucursal->nombre_sucursal) ?>
                  </a>

              <?php endforeach ?>

            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Informes
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../informes/ventas.php">Historial de ventas</a>
              <a class="dropdown-item" href="../informes/cotizaciones.php">Historial de cotizaciones</a>
              <a class="dropdown-item" href="../informes/traspasos.php">Historial de traspasos</a>
              <a class="dropdown-item" href="../informes/actualizaciones_stock.php">Historial de act. de stock</a>
            </div>
          </li>
        <?php endif ?>
        <li class="nav-item">
          <a class="nav-link" href="../cuenta/">Mi cuenta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout/">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>