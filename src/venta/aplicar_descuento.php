<?php

require_once "configurar_montos.php";

session_start();

$descuento = $_POST["descuento"];
$tipo_descuento = $_POST["tipo_descuento"];

$descuento = strlen($descuento) === 0 ? $_SESSION["sistema"]["venta"][$tipo_descuento] : intval($descuento);

if ($tipo_descuento === "descuento_porcentaje") {
	
	if ($descuento > 100 || $descuento < 0) {

		echo json_encode(array("aplicado" => false, "mensaje" => "¡El porcentaje de descuento debe ser entre 0 y 100!"));
		exit();

	}

} else {

	if ($descuento < 0) {

		echo json_encode(array("aplicado" => false, "mensaje" => "¡El descuento en dinero debe ser cero o mayor!"));
		exit();

	}

}

$_SESSION["sistema"]["venta"][$tipo_descuento] = $descuento;

configurarMontos();

echo json_encode(array("aplicado" => true, "mensaje" => "¡Descuento aplicado al total de la venta!"));

?>