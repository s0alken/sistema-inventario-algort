<?php

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

session_start();

$rut_proveedor = $_POST["rut_proveedor"];

if (strlen($rut_proveedor) === 0) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Debes especificar un RUT!"));
  exit();

}

$rut_limpio = preg_replace("/[\.\-]/", "", $rut_proveedor);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Rut no válido!"));
  exit();

}

$query = $pdo->prepare("
  SELECT
  p.id_persona AS id_proveedor,
  p.rut AS rut_proveedor,
  p.nombre_persona AS nombre_proveedor,
  p.giro AS giro_proveedor,
  CONCAT(p.direccion, ' #', p.n_direccion, ', ', c.nombre_ciudad, ', ', r.nombre_region) AS direccion_proveedor,
  p.telefono AS telefono_proveedor,
  p.correo AS correo_proveedor
  FROM proveedor pr
  INNER JOIN persona p ON p.id_persona = pr.id_persona
  INNER JOIN ciudad c ON c.id_ciudad = p.id_ciudad
  INNER JOIN comuna co ON co.id_comuna = c.id_comuna
  INNER JOIN region r ON r.id_region = co.id_region
  WHERE p.habilitada AND p.rut = :rut");

$query->bindValue(":rut", $rut_proveedor, PDO::PARAM_STR);
$query->execute();

$proveedor = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["actualizacion_stock"]["proveedor"] = $proveedor ? $proveedor : $_SESSION["sistema"]["actualizacion_stock"]["proveedor"];

if (!$proveedor) {

  echo json_encode(array("agregado" => false, "mensaje" => "¡Proveedor no encontrado!"));
  exit();

}

echo json_encode(array_merge(array("agregado" => true, "mensaje" => "¡Proveedor agregado a la actualización de stock!"), $proveedor));

?>