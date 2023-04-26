<?php

require_once "../controller/conexion.php";

$id_compra_estado = $_POST["id_elemento"];

$query = $pdo->prepare("SELECT cierra_venta FROM compra_estado WHERE id_compra_estado = :id_compra_estado");
$query->bindValue(":id_compra_estado", $id_compra_estado, PDO::PARAM_INT);
$query->execute();

$cierra_venta = $query->fetch(PDO::FETCH_COLUMN); ?>

<?php if ($cierra_venta): ?>

	<button type="button" class="btn btn-success btn-sm btn-block rounded-pill" data-toggle="modal" data-target="#modal_cerrar_venta_web">Guardar</button>

<?php else: ?>

	<button type="submit" class="btn btn-success btn-sm btn-block rounded-pill">Guardar</button>

<?php endif ?>