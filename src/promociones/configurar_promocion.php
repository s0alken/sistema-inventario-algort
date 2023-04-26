<?php

session_start();

$_SESSION["sistema"]["promocion"]["nombre_promocion"]     = $_POST["nombre_promocion"];
$_SESSION["sistema"]["promocion"]["fecha_inicio"]         = $_POST["fecha_inicio"];
$_SESSION["sistema"]["promocion"]["fecha_termino"]        = $_POST["fecha_termino"];
$_SESSION["sistema"]["promocion"]["hasta_agotar_stock"]   = isset($_POST["hasta_agotar_stock"]) ? "checked" : "";

echo json_encode($_SESSION["sistema"]["promocion"]);

?>