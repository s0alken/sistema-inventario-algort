<?php

require_once "conexion.php";

function cargarSucursal($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE habilitada ORDER BY nombre_sucursal");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_sucursal;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_sucursal;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarMarca($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM marca WHERE habilitada ORDER BY nombre_marca");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_marca;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_marca;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarProveedor($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("
    SELECT
    pr.id_persona AS id_proveedor,
    p.nombre_persona AS nombre_proveedor
    FROM proveedor pr
    INNER JOIN persona p ON p.id_persona = pr.id_persona
    WHERE p.habilitada ORDER BY nombre_proveedor");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_proveedor;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_proveedor;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarProcedencia($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM procedencia WHERE habilitada ORDER BY nombre_procedencia");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_procedencia;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_procedencia;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarBodega($id_selected = null, $modo_nivel = false, $modo_seccion = false, $modo_locker = false) {
  
  global $pdo;

  $query1 = "SELECT * FROM bodega WHERE habilitada AND id_sucursal = :id_sucursal ORDER BY nombre_bodega";

  //si modo_nivel es true se cargan solo las bodegas que tienen niveles
  $query2 = "SELECT * FROM bodega b
             WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
             (SELECT 1 FROM nivel n
             INNER JOIN seccion s ON s.id_seccion = n.id_seccion
             INNER JOIN locker l ON l.id_locker = s.id_locker
             WHERE l.id_bodega = b.id_bodega
             AND n.habilitado)
             ORDER BY b.nombre_bodega";

  //si modo_seccion es true se cargan solo las bodegas que tienen secciones
  $query3 = "SELECT * FROM bodega b
             WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
             (SELECT 1 FROM seccion s
             INNER JOIN locker l ON l.id_locker = s.id_locker
             WHERE l.id_bodega = b.id_bodega
             AND s.habilitada)
             ORDER BY b.nombre_bodega";

  //si modo_locker es true se cargan solo las bodegas que tienen locker
  $query4 = "SELECT * FROM bodega b
             WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
             (SELECT 1 FROM locker l
             WHERE l.id_bodega = b.id_bodega
             AND l.habilitado)
             ORDER BY b.nombre_bodega";

  $str = $modo_nivel ? $query2 : ($modo_seccion ? $query3 : ($modo_locker ? $query4 : $query1));

  $query = $pdo->prepare($str);

  $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_bodega;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_bodega;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarLocker($id_selected = null, $id_bodega = null, $modo_nivel = false, $modo_seccion = false) {
  
  global $pdo;

  $query1 = "SELECT * FROM locker WHERE id_bodega = :id_bodega AND habilitado ORDER BY nombre_locker";

  //si modo_nivel es true se cargan solo los lockers que tienen niveles
  $query2 = "SELECT * FROM locker l
             WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
             (SELECT 1 FROM nivel n
             INNER JOIN seccion s ON s.id_seccion = n.id_seccion
             WHERE s.id_locker = l.id_locker
             AND n.habilitado)
             ORDER BY l.nombre_locker";

  //si modo_seccion es true se cargan solo los locker que tienen seccion
  $query3 = "SELECT * FROM locker l
            WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
            (SELECT 1 FROM seccion s
            WHERE s.id_locker = l.id_locker 
            AND s.habilitada)
            ORDER BY l.nombre_locker";

  $str = $modo_nivel ? $query2 : ($modo_seccion ? $query3 : $query1);

  $query = $pdo->prepare($str);
  $query->bindParam(":id_bodega", $id_bodega, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_locker;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_locker;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarSeccion($id_selected = null, $id_locker = null, $modo_nivel = false) {
  
  global $pdo;

  $query1 = "SELECT * FROM seccion WHERE id_locker = :id_locker AND habilitada ORDER BY nombre_seccion";

  //si modo_nivel es true se cargan solo las secciones que tienen niveles
  $query2 = "SELECT * FROM seccion s
             WHERE s.id_locker = :id_locker AND s.habilitada AND EXISTS
             (SELECT 1 FROM nivel n
             WHERE n.id_seccion = s.id_seccion
             AND n.habilitado)
             ORDER BY s.nombre_seccion";

  $str = $modo_nivel ? $query2 : $query1;

  $query = $pdo->prepare($str);
  $query->bindParam(":id_locker", $id_locker, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_seccion;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_seccion;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarNivel($id_selected = null, $id_seccion = null) {
  
  global $pdo;

  $query = $pdo->prepare("SELECT * FROM nivel WHERE id_seccion = :id_seccion AND habilitado ORDER BY nombre_nivel");
  $query->bindParam(":id_seccion", $id_seccion, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_nivel;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_nivel;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarFamilia($id_selected = null, $modo_subcategoria = false, $modo_categoria = false) {
  
  global $pdo;

  $query1 = "SELECT * FROM familia WHERE habilitada ORDER BY nombre_familia";

  //si modo_subcategoria es true se cargan solo las familias que tienen subcategorías
  $query2 = "SELECT * FROM familia f
             WHERE f.habilitada AND EXISTS
             (SELECT 1 FROM subcategoria sc
             INNER JOIN categoria c ON c.id_categoria = sc.id_categoria
             WHERE c.id_familia = f.id_familia
             AND sc.habilitada)
             ORDER BY f.nombre_familia";

  //si modo_categoria es true se cargan solo las familias que tienen categorías
  $query3 = "SELECT * FROM familia f
             WHERE f.habilitada AND EXISTS
             (SELECT 1 FROM categoria c
             WHERE c.id_familia = f.id_familia
             AND c.habilitada)
             ORDER BY f.nombre_familia";

  $str = $modo_subcategoria ? $query2 : ($modo_categoria ? $query3 : $query1);

  $query = $pdo->query($str);

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_familia;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_familia;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarCategoria($id_selected = null, $id_familia = null, $modo_subcategoria = false) {
  
  global $pdo;

  $query1 = "SELECT * from categoria WHERE id_familia = :id_familia AND habilitada ORDER BY nombre_categoria";

  //si modo_subcategoria es true se cargan solo las categorías que tienen subcategorías
  $query2 = "SELECT * from categoria c
             WHERE c.id_familia = :id_familia AND c.habilitada AND EXISTS
             (SELECT 1 FROM subcategoria sc
             WHERE sc.id_categoria = c.id_categoria
             AND sc.habilitada)
             ORDER BY c.nombre_categoria";

  $str = $modo_subcategoria ? $query2 : $query1;

  $query = $pdo->prepare($str);
  $query->bindParam(':id_familia', $id_familia, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_categoria;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_categoria;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarSubCategoria($id_selected = null, $id_categoria = null) {
  
  global $pdo;

  $query = $pdo->prepare("SELECT * from subcategoria WHERE id_categoria = :id_categoria AND habilitada ORDER BY nombre_subcategoria");
  $query->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_subcategoria;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_subcategoria;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarRegion($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM region ORDER BY nombre_region");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_region;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_region;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarComuna($id_selected = null, $id_region = null) {
  
  global $pdo;

  $query = $pdo->prepare("SELECT * FROM comuna WHERE id_region = :id_region ORDER BY nombre_comuna");
  $query->bindParam(':id_region', $id_region, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_comuna;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_comuna;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarCiudad($id_selected = null, $id_comuna = null) {
  
  global $pdo;

  $query = $pdo->prepare("SELECT * FROM ciudad WHERE id_comuna = :id_comuna ORDER BY nombre_ciudad");
  $query->bindParam(':id_comuna', $id_comuna, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_ciudad;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_ciudad;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarUnidadMedida($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * from unidad_medida WHERE habilitada ORDER BY nombre_unidad_medida");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_unidad_medida;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_unidad_medida;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarPermiso($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM permiso WHERE nombre_permiso != 'superadministrador' ORDER BY nombre_permiso");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_permiso;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_permiso;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarDocumento($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM documento ORDER BY nombre_documento");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_documento;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_documento;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarMedioPago($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM medio_pago ORDER BY nombre_medio_pago");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_medio_pago;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_medio_pago;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}


function cargarDocumentoMedioPago($id_selected = null, $id_documento) {
  
  global $pdo;

  $query = $pdo->prepare("
    SELECT
    dmp.id_medio_pago,
    mp.nombre_medio_pago
    FROM documento_medio_pago dmp
    INNER JOIN medio_pago mp ON mp.id_medio_pago = dmp.id_medio_pago
    WHERE dmp.id_documento = :id_documento ORDER BY mp.nombre_medio_pago");

  $query->bindParam(":id_documento", $id_documento, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_medio_pago;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_medio_pago;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarCompraEstado($id_selected = null, $id_tipo_operador_logistico = null) {
  
  global $pdo;

  $query = $pdo->prepare("
    SELECT
    t.id_compra_estado,
    ce.nombre_compra_estado
    FROM tipo_operador_logistico_estado t
    INNER JOIN compra_estado ce ON ce.id_compra_estado = t.id_compra_estado
    WHERE t.id_tipo_operador_logistico = :id_tipo_operador_logistico
    ORDER BY t.id_compra_estado");

  $query->bindParam(':id_tipo_operador_logistico', $id_tipo_operador_logistico, PDO::PARAM_INT);
  $query->execute();

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_compra_estado;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_compra_estado;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

function cargarTipoOperadorLogistico($id_selected = null) {
  
  global $pdo;

  $query = $pdo->query("SELECT * FROM tipo_operador_logistico ORDER BY nombre_tipo_operador_logistico");

  $opciones = $query->fetchAll();

  foreach($opciones as $opcion):

    $value = $opcion->id_tipo_operador_logistico;
    $selected = $id_selected == $value ? "selected" : "";
    $nombre = $opcion->nombre_tipo_operador_logistico;

    echo "<option " . $selected . " value=" . $value . ">" . $nombre . "</option>";

  endforeach;

}

?>