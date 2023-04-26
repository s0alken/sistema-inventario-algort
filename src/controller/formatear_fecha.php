<?php

function formatearFecha($fecha) {

	$meses = array("01" => "enero",
                     "02" => "febrero",
                     "03" => "marzo",
                     "04" => "abril",
                     "05" => "mayo",
                     "06" => "junio",
                     "07" => "julio",
                     "08" => "agosto",
                     "09" => "septiembre",
                     "10" => "ocutubre",
                     "11" => "noviembre",
                     "12" => "diciembre");

	$dia = date("d", strtotime($fecha));
	$mes = $meses[date("m", strtotime($fecha))];
	$año = date("Y", strtotime($fecha));

	return $dia . " " . ucfirst($mes) . " " . $año;

}

?>