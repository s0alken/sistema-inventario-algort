<?php

require_once "configurar_montos.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

unset($_SESSION["sistema"]["venta"]["carrito"][$codigo_barras]);

configurarMontos();

?>