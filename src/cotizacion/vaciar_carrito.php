<?php

require_once "configurar_montos.php";

session_start();

$_SESSION["sistema"]["cotizacion"]["carrito"] = [];

$_SESSION["sistema"]["cotizacion"]["descuento"] = 0;

configurarMontos();

?>