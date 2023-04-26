<?php

require_once "../controller/conexion.php";

$query = $pdo->prepare("SELECT nombre_documento FROM documento WHERE id_documento = :id_documento");
$query->bindValue(":id_documento", $_POST["id_elemento"], PDO::PARAM_INT);
$query->execute();

$nombre_documento = $query->fetch(PDO::FETCH_COLUMN);

$type = $nombre_documento === "voucher" ? "submit" : "button"; ?>

<button type="<?php echo $type ?>" class="btn btn-success btn-sm btn-block rounded-pill">Efectuar venta</button>