<?php

session_start();

$_SESSION["sistema"]["producto"]["id_familia"]               = $_POST["id_familia"];
$_SESSION["sistema"]["producto"]["id_categoria"]             = $_POST["id_categoria"];
$_SESSION["sistema"]["producto"]["id_subcategoria"]          = $_POST["id_subcategoria"];
$_SESSION["sistema"]["producto"]["codigo_barras"]            = $_POST["codigo_barras"];
$_SESSION["sistema"]["producto"]["codigo_maestro"]           = $_POST["codigo_maestro"];
$_SESSION["sistema"]["producto"]["n_fabricante_1"]           = $_POST["n_fabricante_1"];
$_SESSION["sistema"]["producto"]["n_fabricante_2"]           = $_POST["n_fabricante_2"];
$_SESSION["sistema"]["producto"]["n_fabricante_3"]           = $_POST["n_fabricante_3"];
$_SESSION["sistema"]["producto"]["descripcion"]              = $_POST["descripcion"];
$_SESSION["sistema"]["producto"]["precio_costo"]             = $_POST["precio_costo"];
$_SESSION["sistema"]["producto"]["precio_venta"]             = $_POST["precio_venta"];
$_SESSION["sistema"]["producto"]["stock"]                    = $_POST["stock"];
$_SESSION["sistema"]["producto"]["stock_critico"]            = $_POST["stock_critico"];
$_SESSION["sistema"]["producto"]["medidas"]                  = isset($_POST["medidas"]) ? $_POST["medidas"] : [];
$_SESSION["sistema"]["producto"]["id_marca"]                 = $_POST["id_marca"];
$_SESSION["sistema"]["producto"]["id_proveedor"]             = $_POST["id_proveedor"];
$_SESSION["sistema"]["producto"]["id_procedencia"]           = $_POST["id_procedencia"];
$_SESSION["sistema"]["producto"]["ubicacion"]                = isset($_POST["ubicacion"]) ? $_POST["ubicacion"] : [];
$_SESSION["sistema"]["producto"]["observaciones"]            = $_POST["observaciones"];
$_SESSION["sistema"]["producto"]["caracteristicas_tecnicas"] = $_POST["caracteristicas_tecnicas"];
$_SESSION["sistema"]["producto"]["compatibilidad"]           = isset($_POST["compatibilidad"]) ? json_decode($_POST["compatibilidad"]) : [];
$_SESSION["sistema"]["producto"]["imagenes"]                 = isset($_POST["imagenes"]) ? json_decode($_POST["imagenes"]) : [];
$_SESSION["sistema"]["producto"]["puntos"]                   = strlen($_POST["precio_venta"]) === 0 ? 0 : round(($_POST["precio_venta"] * 2) / 100);
$_SESSION["sistema"]["producto"]["acumula_puntos"]           = isset($_POST["acumula_puntos"]) ? "checked" : "";
$_SESSION["sistema"]["producto"]["habilitado_tienda"]        = isset($_POST["habilitado_tienda"]) ? "checked" : "";

echo json_encode($_SESSION["sistema"]["producto"]);

?>