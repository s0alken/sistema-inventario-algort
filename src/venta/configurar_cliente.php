<?php

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

session_start();

$rut_cliente = $_POST["rut_cliente"];

if (strlen($rut_cliente) === 0) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Debes especificar un RUT!"));
  exit();

}

$rut_limpio = preg_replace("/[\.\-]/", "", $rut_cliente);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Rut no válido!"));
  exit();

}

$query = $pdo->prepare("
  SELECT
  p.id_persona AS id_cliente,
  p.rut AS rut_cliente,
  p.nombre_persona AS nombre_cliente,
  p.giro AS giro_cliente,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_cliente,
  p.telefono AS telefono_cliente,
  p.correo AS correo_cliente
  FROM cliente cl
  INNER JOIN persona p ON p.id_persona = cl.id_persona
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE p.habilitada AND p.rut = :rut");

$query->bindValue(":rut", $rut_cliente, PDO::PARAM_STR);
$query->execute();

$cliente = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["venta"]["cliente"] = $cliente ? $cliente : $_SESSION["sistema"]["venta"]["cliente"];

if (!$cliente) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Cliente no encontrado!"));
  exit();

}

echo json_encode(array_merge(array("agregado" => true, "mensaje" => "¡Cliente agregado a la venta!"), $cliente));

?>