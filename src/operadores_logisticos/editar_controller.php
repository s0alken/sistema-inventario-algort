<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

$id_operador_logistico = $_GET["id_operador_logistico"];

//comprobando que se hayan completado los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si el operador logístico existe
$query = $pdo->prepare("
	SELECT * FROM operador_logistico
	WHERE habilitado
	AND nombre_operador_logistico = :nombre_operador_logistico
	AND id_operador_logistico != :id_operador_logistico
	LIMIT 1");

$query->bindValue(":nombre_operador_logistico", $_POST["nombre_operador_logistico"], PDO::PARAM_STR);
$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el operador logístico " . $_POST["nombre_operador_logistico"] . "!"));
	exit();

}

//obteniendo el nombre del tipo de operador logístico seleccionado
$query = $pdo->prepare("
  SELECT nombre_tipo_operador_logistico
  FROM tipo_operador_logistico
  WHERE id_tipo_operador_logistico = :id_tipo_operador_logistico");

$query->bindValue(":id_tipo_operador_logistico", $_POST["id_tipo_operador_logistico"], PDO::PARAM_INT);
$query->execute();

$tipo_operador_logistico = $query->fetch(PDO::FETCH_COLUMN);

$operador_es_delivery = $tipo_operador_logistico === "delivery";

$habilitado_despacho_gratis = $operador_es_delivery && isset($_POST["habilitar_despacho_gratis"]);

if ($operador_es_delivery) {
    
  //comprobando que el monto mínimo para habilitar no sea menor que cero
  if (intval($_POST["monto_minimo_habilitar"]) < 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡El monto mínimo para habilitar debe ser igual o mayor a cero!"));
    exit();

  }

  //comprobando que el costo de despacho no sea menor que cero
  if (intval($_POST["costo_despacho"]) < 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡El costo de despacho debe ser igual o mayor a cero!"));
    exit();

  }

  if ($habilitado_despacho_gratis) {
    
    //comprobando que el monto mínimo para despacho gratis
    //no sea menor que el monto mínimo para habilitar
    if (intval($_POST["monto_minimo_despacho_gratis"]) < intval($_POST["monto_minimo_habilitar"])) {

      echo json_encode(array("guardado" => false,
                             "mensaje" => "¡El monto mínimo para el despacho gratis debe ser igual o mayor al monto mínimo para habilitar!"));

      exit();

    }

  }

}

try {  

	$pdo->beginTransaction();

	//obteniendo monto mínimo para habilitar despacho gratis original
	$query = $pdo->prepare("
		SELECT monto_minimo_despacho_gratis
		FROM operador_logistico
		WHERE id_operador_logistico = :id_operador_logistico");

	$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
	$query->execute();

	$monto_minimo_despacho_gratis = $query->fetch(PDO::FETCH_COLUMN);

	$monto_minimo_habilitar = $operador_es_delivery ? $_POST["monto_minimo_habilitar"] : 0;
  	$costo_despacho = $operador_es_delivery ? $_POST["costo_despacho"] : 0;
  	$monto_minimo_despacho_gratis = $habilitado_despacho_gratis ? $_POST["monto_minimo_despacho_gratis"] :
  	                                ($operador_es_delivery && !isset($_POST["habilitar_despacho_gratis"]) ?
  	                                $monto_minimo_despacho_gratis : 0);

	$query = $pdo->prepare("
		UPDATE operador_logistico SET
		nombre_operador_logistico = :nombre_operador_logistico,
		id_tipo_operador_logistico = :id_tipo_operador_logistico,
		monto_minimo_habilitar = :monto_minimo_habilitar,
		costo_despacho = :costo_despacho,
		habilitado_despacho_gratis = :habilitado_despacho_gratis,
		monto_minimo_despacho_gratis = :monto_minimo_despacho_gratis
		WHERE id_operador_logistico = :id_operador_logistico");

	$query->bindValue(":nombre_operador_logistico", $_POST["nombre_operador_logistico"], PDO::PARAM_STR);
	$query->bindValue(":id_tipo_operador_logistico", $_POST["id_tipo_operador_logistico"], PDO::PARAM_INT);
	$query->bindValue(":monto_minimo_habilitar", $monto_minimo_habilitar, PDO::PARAM_INT);
  	$query->bindValue(":costo_despacho", $costo_despacho, PDO::PARAM_INT);
  	$query->bindValue(":habilitado_despacho_gratis", $habilitado_despacho_gratis, PDO::PARAM_BOOL);
  	$query->bindValue(":monto_minimo_despacho_gratis", $monto_minimo_despacho_gratis, PDO::PARAM_INT);
	$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
	
	$query->execute();

	//insertando ciudades compatibles con el operador logístico.
    //Si el tipo de operador elegido es delivery, las ciudades compatibles
    //serán solo las que seleccionó el usuario, de lo contrario lo serán todas.
	if ($operador_es_delivery) {
    
	    if (!isset($_POST["ciudades_compatibles"])) {
	      
	      throw new Exception("¡Debes seleccionar por lo menos una ciudad compatible!");

	    }

	    //comprobando si el operador logístico tiene ciudades compatibles
		$query = $pdo->prepare("SELECT id_ciudad FROM operador_logistico_ciudad WHERE id_operador_logistico = :id_operador_logistico");
		$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
		$query->execute();

		$ciudades_operador_logistico = $query->fetchAll(PDO::FETCH_COLUMN);

		$ciudades_compatibles = json_decode($_POST["ciudades_compatibles"]);

		$ciudades_guardar = array_diff($ciudades_compatibles, $ciudades_operador_logistico);
		$ciudades_eliminar = array_diff($ciudades_operador_logistico, $ciudades_compatibles);

		foreach ($ciudades_guardar as $id_ciudad) {

			$query = $pdo->prepare("
				INSERT INTO operador_logistico_ciudad(
				id_operador_logistico,
				id_ciudad) VALUES (
				:id_operador_logistico,
				:id_ciudad)");

			$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
			$query->bindValue(":id_ciudad", $id_ciudad, PDO::PARAM_INT);
			$query->execute();

		}

		foreach ($ciudades_eliminar as $id_ciudad) {

			$query = $pdo->prepare("
				DELETE FROM operador_logistico_ciudad
				WHERE id_ciudad = :id_ciudad
				AND id_operador_logistico = :id_operador_logistico");

			$query->bindValue(":id_ciudad", $id_ciudad, PDO::PARAM_INT);
			$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
			$query->execute();

		}

	} else {

		//comprobando las ciudades que ya tiene el operador logístico
		$query = $pdo->prepare("
			SELECT id_ciudad
			FROM ciudad
			WHERE id_ciudad NOT IN
			(SELECT id_ciudad 
			FROM operador_logistico_ciudad
			WHERE id_operador_logistico = :id_operador_logistico)");

		$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
		$query->execute();

		$ciudades_guardar = $query->fetchAll(PDO::FETCH_COLUMN);

		foreach ($ciudades_guardar as $id_ciudad) {

			$query = $pdo->prepare("
				INSERT INTO operador_logistico_ciudad(
				id_operador_logistico,
				id_ciudad) VALUES (
				:id_operador_logistico,
				:id_ciudad)");

			$query->bindValue(":id_operador_logistico", $id_operador_logistico, PDO::PARAM_INT);
			$query->bindValue(":id_ciudad", $id_ciudad, PDO::PARAM_INT);
			$query->execute();

		}

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Operador logístico editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../operadores_logisticos/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>