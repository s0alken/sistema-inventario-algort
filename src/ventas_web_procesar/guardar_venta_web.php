<?php

require_once "../controller/conexion.php";
require_once "../controller/enviar_correo.php";
require_once "cerrar_venta_web.php";

session_start();

$id_compra = $_GET["id_compra"];
$id_compra_estado = $_POST["id_compra_estado"];

$query = $pdo->prepare("
	SELECT
	ce.cierra_venta
	FROM compra c
	INNER JOIN compra_estado ce ON ce.id_compra_estado = c.id_compra_estado
	WHERE c.id_compra = :id_compra");

$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
$query->execute();

$compra_terminada = $query->fetch(PDO::FETCH_COLUMN);

//comprobando estado
if ($compra_terminada) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "Esta venta ya se cerró"));
	exit();

}

$query = $pdo->prepare("SELECT * FROM compra_estado WHERE id_compra_estado = :id_compra_estado");

$query->bindValue(":id_compra_estado", $id_compra_estado, PDO::PARAM_INT);
$query->execute();

$compra_estado = $query->fetch();

if ($compra_estado->nombre_compra_estado === "entregado a transporte" && strlen($_POST["n_envio"]) === 0) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "Ingresa el número de envio"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	//actualizando estado de compra
	$query = $pdo->prepare("UPDATE compra SET id_compra_estado = :id_compra_estado WHERE id_compra = :id_compra");

	$query->bindValue(":id_compra_estado", $id_compra_estado, PDO::PARAM_INT);
	$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
	
	$query->execute();

	
	if ($compra_estado->nombre_compra_estado === "entregado a transporte") {
		
		//comprobando si ya se había ingresado un n° de envio
		$query = $pdo->prepare("SELECT COUNT(n_envio) AS cantidad FROM compra_n_envio WHERE id_compra = :id_compra");

		$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
		$query->execute();

		$n_envio_ingresado = $query->fetch(PDO::FETCH_COLUMN) > 0;

		//si ya hay uno ingresado, se actualiza
		if ($n_envio_ingresado) {
			
			$query = $pdo->prepare("UPDATE compra_n_envio SET n_envio = :n_envio WHERE id_compra = :id_compra");

			$query->bindValue(":n_envio", $_POST["n_envio"], PDO::PARAM_STR);
			$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
			
			$query->execute();

		} else {

			$query = $pdo->prepare("INSERT INTO compra_n_envio(id_compra, n_envio) VALUES (:id_compra, :n_envio)");

			$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
			$query->bindValue(":n_envio", $_POST["n_envio"], PDO::PARAM_STR);
			
			$query->execute();

		}

	}

	//si el tipo de estado elegido cierra venta,
	//se cierra la venta definitivamente
	if ($compra_estado->cierra_venta) {
		
		cerrarVentaWeb($id_compra);
		
	}

    //enviando correo de notificación al cliente.
	if ($_SERVER["SERVER_NAME"] != "localhost") {

		//obteniendo el correo del cliente
		$query = $pdo->prepare("
		  SELECT
		  p.correo
		  FROM compra_cliente cc
		  INNER JOIN persona p ON p.id_persona = cc.id_cliente
		  WHERE cc.id_compra = :id_compra");

		$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
		$query->execute();

		$correo_destino_cliente = $query->fetch(PDO::FETCH_COLUMN);

		//si no se encuentra el correo se busca en la tabla comprador
		if (!$correo_destino_cliente) {

		  $query = $pdo->prepare("SELECT correo FROM comprador WHERE id_compra = :id_compra");

		  $query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
		  $query->execute();

		  $correo_destino_cliente = $query->fetch(PDO::FETCH_COLUMN);

		}

		//preparando mensaje
		$mensaje_correo_cliente = $compra_estado->notificacion . ".\n";

		//adjuntando n° de envío
		if ($compra_estado->nombre_compra_estado === "entregado a transporte") {
			
			$mensaje_correo_cliente .= "Tu número de envío es: " . $_POST["n_envio"] . "\n";

		}

		$mensaje_correo_cliente .= "\n";
		$mensaje_correo_cliente .= "Revisa el estado de tu compra en ";
		$mensaje_correo_cliente .= "https://" . $_SERVER["SERVER_NAME"] . "/tienda/php/seguimiento_compra.php?id_compra=" . $id_compra;
		
		//enviando correo
		enviarCorreo($correo_destino_cliente, $compra_estado->notificacion, $mensaje_correo_cliente);

	}

	$pdo->commit();

    $mensaje = $compra_estado->cierra_venta ? "¡Venta web guardada y cerrada exitosamente!" : "¡Venta web guardada exitosamente!";
    $redireccion = $compra_estado->cierra_venta ? "../ventas_sistema/" : "../ventas_web_procesar/";

    $_SESSION["sistema"]["mensaje"] = $mensaje;
    $_SESSION["sistema"]["redireccion"] = $redireccion;

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>