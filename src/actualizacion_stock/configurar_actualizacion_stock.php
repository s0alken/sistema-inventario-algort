<?php

require_once "../controller/conexion.php";

session_start();

$query = $pdo->prepare("SELECT * FROM medio_pago WHERE id_medio_pago = :id_medio_pago");
$query->bindValue(":id_medio_pago", $_POST["id_medio_pago"], PDO::PARAM_INT);
$query->execute();

$medio_pago = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM documento WHERE id_documento = :id_documento");
$query->bindValue(":id_documento", $_POST["id_documento"], PDO::PARAM_INT);
$query->execute();

$documento = $query->fetch(PDO::FETCH_ASSOC);

$_SESSION["sistema"]["actualizacion_stock"]["medio_pago"]         = $medio_pago;
$_SESSION["sistema"]["actualizacion_stock"]["documento"]          = $documento;
$_SESSION["sistema"]["actualizacion_stock"]["n_documento_compra"] = $_POST["n_documento_compra"];
$_SESSION["sistema"]["actualizacion_stock"]["observaciones"]      = $_POST["observaciones"];

echo json_encode($_SESSION["sistema"]["actualizacion_stock"]);

?>