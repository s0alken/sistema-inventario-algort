<?php

session_start();

$codigo_barras = $_POST["codigo_barras"];

unset($_SESSION["sistema"]["traspaso"]["productos"][$codigo_barras]);

?>