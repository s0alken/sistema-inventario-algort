<?php

require_once "../controller/conexion.php";

session_start();

$query = $pdo->prepare("SELECT * FROM documento WHERE id_documento = :id_documento");
$query->bindValue(":id_documento", $_POST["id_documento"], PDO::PARAM_INT);
$query->execute();

$documento = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM medio_pago WHERE id_medio_pago = :id_medio_pago");
$query->bindValue(":id_medio_pago", $_POST["id_medio_pago"], PDO::PARAM_INT);
$query->execute();

$medio_pago = $query->fetch(PDO::FETCH_ASSOC);

$n_boleta = $_SESSION["sistema"]["venta"]["n_boleta"];
$n_factura = $_SESSION["sistema"]["venta"]["n_factura"];
$n_guia_despacho = $_SESSION["sistema"]["venta"]["n_guia_despacho"];
$n_redcompra = $_SESSION["sistema"]["venta"]["n_redcompra"];

$_SESSION["sistema"]["venta"]["documento"]          = $documento;
$_SESSION["sistema"]["venta"]["medio_pago"]         = $medio_pago;
$_SESSION["sistema"]["venta"]["observaciones"]      = $_POST["observaciones"];
$_SESSION["sistema"]["venta"]["n_boleta"]           = isset($_POST["n_boleta"]) ? $_POST["n_boleta"] : $n_boleta;
$_SESSION["sistema"]["venta"]["n_factura"]          = isset($_POST["n_factura"]) ? $_POST["n_factura"] : $n_factura;
$_SESSION["sistema"]["venta"]["n_guia_despacho"]    = isset($_POST["n_guia_despacho"]) ? $_POST["n_guia_despacho"] : $n_guia_despacho;
$_SESSION["sistema"]["venta"]["n_redcompra"]        = isset($_POST["n_redcompra"]) ? $_POST["n_redcompra"] : $n_redcompra;

echo json_encode($_SESSION["sistema"]["venta"]);

?>