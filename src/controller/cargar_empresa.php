<?php

require_once "../controller/conexion.php";

function cargarEmpresa(){

  global $pdo;

  $query = $pdo->query("
    SELECT
    p.rut,
    p.nombre_fantasia AS nombre_empresa,
    p.nombre_persona AS razon_social,
    p.giro,
    CONCAT(p.direccion, ' ', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion,
    p.telefono,
    p.correo
    FROM empresa e
    INNER JOIN persona p ON p.id_persona = e.id_persona
    INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
    INNER JOIN comuna co ON co.id_comuna = c.id_comuna
    INNER JOIN region r ON r.id_region = co.id_region");

  return $query->fetch();

}

?>