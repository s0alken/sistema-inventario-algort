<?php

function obtenerUrlActual(){

	$url_actual = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

	$url_actual = str_replace("index.php", "", $url_actual);

	$posicion_ultimo_slash = strrpos($url_actual, "/");

	$posicion_penultimo_slash = strrpos($url_actual, "/", $posicion_ultimo_slash - strlen($url_actual) - 1);

	$largo = $posicion_ultimo_slash - $posicion_penultimo_slash + 1;

	//return substr($url_actual, $posicion_penultimo_slash, $largo);

	return substr($url_actual, $posicion_penultimo_slash);
	
}

?>