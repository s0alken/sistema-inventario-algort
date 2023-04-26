<?php

require_once "../controller/conexion.php";

session_start();

date_default_timezone_set("America/Santiago");

$_SESSION["sistema"]["promocion"]["carrito"]              = [];
$_SESSION["sistema"]["promocion"]["nombre_promocion"]     = "";
$_SESSION["sistema"]["promocion"]["fecha_inicio"]         = date("Y-m-d");
$_SESSION["sistema"]["promocion"]["fecha_termino"]        = date("Y-m-d");
$_SESSION["sistema"]["promocion"]["descuento_porcentaje"] = 0;
$_SESSION["sistema"]["promocion"]["descuento_dinero"]     = 0;
$_SESSION["sistema"]["promocion"]["hasta_agotar_stock"]   = "checked";

header("Location: crear.php");

exit();

?>