<?php

require_once "../controller/conexion.php";

session_start();

//obteniendo el id_familia de la primera familia con subcategorías ordenada alfabéticamente
$query = $pdo->query("
  SELECT f.id_familia FROM familia f
  WHERE f.habilitada AND EXISTS
  (SELECT 1 FROM subcategoria sc
  INNER JOIN categoria c ON c.id_categoria = sc.id_categoria
  WHERE c.id_familia = f.id_familia
  AND sc.habilitada)
  ORDER BY f.nombre_familia
  LIMIT 1");

$id_familia = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_categoria de la primera categoría con subcategorías ordenada alfabéticamente
$query = $pdo->prepare("
  SELECT c.id_categoria FROM categoria c
  WHERE c.id_familia = :id_familia AND c.habilitada AND EXISTS
  (SELECT 1 FROM subcategoria sc
  WHERE sc.id_categoria = c.id_categoria
  AND sc.habilitada)
  ORDER BY c.nombre_categoria
  LIMIT 1");

$query->bindValue(":id_familia", $id_familia, PDO::PARAM_INT);
$query->execute();

$id_categoria = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_subcategoria de la primera subcategoría ordenada alfabéticamente
$query = $pdo->prepare("SELECT id_subcategoria from subcategoria WHERE id_categoria = :id_categoria AND habilitada ORDER BY nombre_subcategoria");
$query->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
$query->execute();

$id_subcategoria = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_marca de la primera marca ordenada alfabéticamente
$query = $pdo->query("SELECT id_marca FROM marca WHERE marca.habilitada ORDER BY nombre_marca LIMIT 1");

$id_marca = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_proveedor de la primera persona/proveedor ordenada alfabéticamente
$query = $pdo->query("
    SELECT
    pr.id_persona AS id_proveedor
    FROM proveedor pr
    INNER JOIN persona p ON p.id_persona = pr.id_persona
    WHERE p.habilitada ORDER BY p.nombre_persona LIMIT 1");

$id_proveedor = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_procedencia de la primera procedencia ordenada alfabéticamente
$query = $pdo->query("SELECT id_procedencia FROM procedencia WHERE procedencia.habilitada ORDER BY nombre_procedencia LIMIT 1");

$id_procedencia = $query->fetch(PDO::FETCH_COLUMN);

//comprobando si la sucursal tiene bodegas disponibles
$query = $pdo->prepare("
  SELECT * FROM nivel n
  INNER JOIN seccion s ON s.id_seccion = n.id_seccion
  INNER JOIN locker l ON l.id_locker = s.id_locker
  INNER JOIN bodega b ON b.id_bodega =  l.id_bodega
  WHERE n.habilitado AND b.id_sucursal = :id_sucursal
  LIMIT 1");

$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

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

  $id_bodega = $query->fetch(PDO::FETCH_COLUMN);

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

  $query->bindValue(":id_bodega", $id_bodega, PDO::PARAM_INT);
  $query->execute();

  $id_locker = $query->fetch(PDO::FETCH_COLUMN);

  //obteniendo el id_seccion de la primera sección con niveles ordenada alfabéticamente
  $query = $pdo->prepare("
    SELECT s.id_seccion FROM seccion s
    WHERE s.id_locker = :id_locker AND s.habilitada AND EXISTS
    (SELECT 1 FROM nivel n
    WHERE n.id_seccion = s.id_seccion
    AND n.habilitado)
    ORDER BY s.nombre_seccion
    LIMIT 1");

  $query->bindValue(":id_locker", $id_locker, PDO::PARAM_INT);
  $query->execute();

  $id_seccion = $query->fetch(PDO::FETCH_COLUMN);

  //obteniendo el id_nivel del primer nivel ordenado alfabéticamente
  $query = $pdo->prepare("SELECT id_nivel FROM nivel WHERE id_seccion = :id_seccion AND habilitado ORDER BY nombre_nivel");

  $query->bindValue(":id_seccion", $id_seccion, PDO::PARAM_INT);
  $query->execute();

  $id_nivel = $query->fetch(PDO::FETCH_COLUMN);

  $ubicacion = array("id_bodega" => $id_bodega, "id_locker" => $id_locker, "id_seccion" => $id_seccion, "id_nivel" => $id_nivel);

}

//seteando acumulación de puntos
$query = $pdo->query("SELECT IF(acumula_puntos, 'checked', '') FROM personalizacion");

$acumula_puntos = $query->fetch(PDO::FETCH_COLUMN);

$_SESSION["sistema"]["producto"]["id_familia"] = $id_familia;
$_SESSION["sistema"]["producto"]["id_categoria"] = $id_categoria;
$_SESSION["sistema"]["producto"]["id_subcategoria"] = $id_subcategoria;
$_SESSION["sistema"]["producto"]["codigo_barras"] = "";
$_SESSION["sistema"]["producto"]["codigo_maestro"] = "";
$_SESSION["sistema"]["producto"]["n_fabricante_1"] = "";
$_SESSION["sistema"]["producto"]["n_fabricante_2"] = "";
$_SESSION["sistema"]["producto"]["n_fabricante_3"] = "";
$_SESSION["sistema"]["producto"]["descripcion"] = "";
$_SESSION["sistema"]["producto"]["precio_costo"] = 0;
$_SESSION["sistema"]["producto"]["precio_venta"] = 0;
$_SESSION["sistema"]["producto"]["stock"] = 0;
$_SESSION["sistema"]["producto"]["stock_critico"] = 0;
$_SESSION["sistema"]["producto"]["medidas"] = [];
$_SESSION["sistema"]["producto"]["id_marca"] = $id_marca;
$_SESSION["sistema"]["producto"]["id_proveedor"] = $id_proveedor;
$_SESSION["sistema"]["producto"]["id_procedencia"] = $id_procedencia;
$_SESSION["sistema"]["producto"]["ubicacion"] = $ubicacion ? $ubicacion : [];
$_SESSION["sistema"]["producto"]["observaciones"] = "";
$_SESSION["sistema"]["producto"]["caracteristicas_tecnicas"] = "";
$_SESSION["sistema"]["producto"]["compatibilidad"] = [];
$_SESSION["sistema"]["producto"]["imagenes"] = [];
$_SESSION["sistema"]["producto"]["puntos"] = 0;
$_SESSION["sistema"]["producto"]["acumula_puntos"] = $acumula_puntos;
$_SESSION["sistema"]["producto"]["habilitado_tienda"] = "checked";

header("Location: crear.php");

exit();

?>