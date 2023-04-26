<?php

session_start();

$codigo_barras = $_POST["codigo_barras"];

if (array_key_exists($codigo_barras, $_SESSION["sistema"]["actualizacion_stock"]["carrito"])) {

	echo json_encode(array("existe" => true, "mensaje" => "¡Ya agregaste este producto a la actualización de stock!"));

} else {

	echo json_encode(array("existe" => false));

}

?>