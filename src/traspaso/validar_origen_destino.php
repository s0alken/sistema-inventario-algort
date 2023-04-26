<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

$id_sucursal_origen  = $_POST["id_sucursal_origen"];
$id_sucursal_destino = $_POST["id_sucursal_destino"];

if ($id_sucursal_origen === $id_sucursal_destino) {

	echo json_encode(array("valido" => false, "mensaje" => "¡Las sucursales de origen y destino deben ser diferentes!"));
	exit();

}

$_SESSION["sistema"]["traspaso"]["productos"] = [];
$_SESSION["sistema"]["traspaso"]["observaciones"] = "";
$_SESSION["sistema"]["traspaso"]["id_sucursal_origen"] = $id_sucursal_origen;
$_SESSION["sistema"]["traspaso"]["id_sucursal_destino"] = $id_sucursal_destino;

echo json_encode(array("valido" => true));

?>