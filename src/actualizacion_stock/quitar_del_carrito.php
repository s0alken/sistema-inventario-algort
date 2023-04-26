<?php

session_start();

$codigo_barras = $_POST["codigo_barras"];

unset($_SESSION["sistema"]["actualizacion_stock"]["carrito"][$codigo_barras]);

?>