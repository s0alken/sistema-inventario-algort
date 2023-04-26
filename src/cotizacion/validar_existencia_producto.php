<?php

session_start();

$codigo_barras = $_POST["codigo_barras"];

if (array_key_exists($codigo_barras, $_SESSION["sistema"]["cotizacion"]["carrito"])) {

	echo json_encode(array("existe" => true, "mensaje" => "¡Ya agregaste este producto a la cotización!"));

} else {

	echo json_encode(array("existe" => false));

}

?>