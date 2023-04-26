<?php

require_once "obtener_url_actual.php";

session_start();

$url_actual = obtenerUrlActual();

$url_login_usuario   = "/login/";

$accesos_vendedor = array("/productos/",
	                      "/venta/",
	                      "/ventas_sistema/",
	                      "/ventas_sistema/imprimir.php",
	                      "/ventas_web/",
	                      "/ventas_web/imprimir.php",
	                      "/ventas_web_procesar/",
	                      "/ventas_web_procesar/procesar_venta_web.php",
	                      "/cotizacion/crear.php",
	                      "/informes/cotizaciones.php",
	                      "/imprimir_cotizacion/",
	                      "/cuenta/",
	                      "/personas/",
	                      "/logout/",
	                      "/exito/",
	                      "/inicio/");

$redirigir = true;

if(isset($_SESSION["sistema"]["usuario"])) {

	if ($url_actual === $url_login_usuario) {
		
		header("Location: ../");
		exit();

	}

	if ($redirigir && !$_SESSION["sistema"]["usuario"]->administrador && !in_array($url_actual, $accesos_vendedor)) {
		
		header("Location: ../");
		exit();

	}

} else if ($url_actual !== $url_login_usuario) {

	header("Location: ../login/");
	exit();

}

?>