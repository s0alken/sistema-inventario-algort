<?php

require_once "../controller/conexion.php";

session_start();

$query = $pdo->prepare("SELECT * FROM medio_pago WHERE id_medio_pago = :id_medio_pago");
$query->bindValue(":id_medio_pago", $_POST["id_medio_pago"], PDO::PARAM_INT);
$query->execute();

$medio_pago = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["cotizacion"]["medio_pago"]    = $medio_pago;
$_SESSION["sistema"]["cotizacion"]["observaciones"] = $_POST["observaciones"];

echo json_encode($_SESSION["sistema"]["cotizacion"]);

?>