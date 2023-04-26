<?php

require_once "configurar_montos.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

unset($_SESSION["sistema"]["cotizacion"]["carrito"][$codigo_barras]);

configurarMontos();

?>