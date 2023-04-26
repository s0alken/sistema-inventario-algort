<?php

require_once "../controller/conexion.php";
require_once "../controller/validar_largo.php";

session_start();

$id_compra = $_GET["id_compra"];

$query = $pdo->prepare("SELECT * FROM compra WHERE id_compra = :id_compra");
$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
$query->execute();

$compra = $query->fetch();

if ($compra->id_compra_estado > 4) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "Esta venta ya fue procesada"));
	exit();

}

//comprobando que los motivos del rechazo no excedan los 500 caracteres
if (largoExcedido($_POST["motivo_rechazo"], 500)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El motivo del rechazo no debe exceder los 500 caracteres!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	//restaurando stock
	$query = $pdo->prepare("SELECT codigo_barras, cantidad FROM compra_detalle WHERE id_compra = :id_compra");

	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
	$query->execute();

	$carrito = $query->fetchAll();

	foreach ($carrito as $producto) {
		
		$query = $pdo->prepare("SELECT id_producto FROM producto WHERE habilitado AND codigo_barras = :codigo_barras");

		$query->bindValue(":codigo_barras", $producto->codigo_barras, PDO::PARAM_STR);
		$query->execute();

		$id_producto = $query->fetch(PDO::FETCH_COLUMN);

		$query = $pdo->prepare("UPDATE stock_producto SET stock = stock + :cantidad WHERE id_producto = :id_producto AND id_sucursal = 1");

		$query->bindValue(":cantidad", $producto->cantidad, PDO::PARAM_INT);
		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_STR);

		$query->execute();

	}

	//rechazando compra
	$query = $pdo->prepare("UPDATE compra SET id_compra_estado = 10 WHERE id_compra = :id_compra");

	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);

	$query->execute();

	//insertando motivos del rechazo
	$sin_motivos = "El vendedor no ha señalado los motivos";
	
	$query = $pdo->prepare("INSERT INTO compra_motivo_rechazo(id_compra, motivo_rechazo) VALUES (:id_compra, :motivo_rechazo)");

	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
	$query->bindValue(":motivo_rechazo", strlen($_POST["motivo_rechazo"]) === 0 ? $sin_motivos : $_POST["motivo_rechazo"], PDO::PARAM_STR);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "La compra ha sido rechazada <br> Se repuso el stock retenido";
    $_SESSION["sistema"]["redireccion"] = "../ventas_web_procesar/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>