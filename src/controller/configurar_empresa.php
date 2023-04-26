<?php

require_once "conexion.php";

function configurarEmpresa() {

	global $pdo;

	$query = $pdo->query("
		SELECT
		persona.id_persona AS id_empresa,
		persona.rut,
	    persona.nombre_persona AS razon_social,
		giro.giro,
		CONCAT(direccion.calle, ' #', direccion.numero) AS direccion,
		comuna.nombre_comuna AS comuna,
		ciudad.nombre_ciudad AS ciudad,
		telefono.telefono,
		correo.correo
		FROM
		empresa
		INNER JOIN persona on persona.id_persona = empresa.id_persona
		INNER JOIN giro ON giro.id_persona = persona.id_persona
		INNER JOIN direccion ON direccion.id_persona = persona.id_persona
		INNER JOIN ciudad ON ciudad.id_ciudad = direccion.id_ciudad
		INNER JOIN comuna ON comuna.id_comuna = ciudad.id_comuna
		INNER JOIN telefono ON telefono.id_persona = persona.id_persona
		INNER JOIN correo ON correo.id_persona = persona.id_persona");

	$empresa = $query->fetch(PDO::FETCH_OBJ);

	$_SESSION["sistema"]["empresa"] = $empresa;

}

?>