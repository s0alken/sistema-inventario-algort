<?php

session_start();

date_default_timezone_set("America/Santiago");

$codigo_barras = date("dmYHis");

echo json_encode($codigo_barras);

?>