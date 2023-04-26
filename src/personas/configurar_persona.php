<?php

function configurarPersona($id_persona, $tipo_persona){

	global $pdo;

	$query = $pdo->prepare("
	  SELECT
	  p.id_persona AS id_" . $tipo_persona . ",
	  p.rut AS rut_" . $tipo_persona . ",
	  p.nombre_persona AS nombre_" . $tipo_persona . ",
	  p.giro AS giro_" . $tipo_persona . ",
	  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_" . $tipo_persona . ",
	  p.telefono AS telefono_" . $tipo_persona . ",
	  p.correo AS correo_" . $tipo_persona . "
	  FROM " . $tipo_persona . " tp
	  INNER JOIN persona p ON p.id_persona = tp.id_persona
	  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
	  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
	  INNER JOIN region r ON r.id_region = co.id_region
	  WHERE p.habilitada AND p.id_persona = :id_persona");

	$query->bindValue(":id_persona", $id_persona, PDO::PARAM_INT);
	$query->execute();

	$persona = $query->fetch(PDO::FETCH_ASSOC);
	
	return $persona;

}

?>