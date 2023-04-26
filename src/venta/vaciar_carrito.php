<?php

require_once "configurar_montos.php";

session_start();

$_SESSION["sistema"]["venta"]["carrito"] = [];

$_SESSION["sistema"]["venta"]["descuento"] = 0;

configurarMontos();

?>