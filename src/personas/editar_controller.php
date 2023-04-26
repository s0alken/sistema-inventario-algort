<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador && $_GET["tipo_persona"] === "proveedor") {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

$campos_obligatorios = array("rut", "nombre_persona", "direccion", "n_direccion", "id_ciudad", "telefono", "correo");

foreach ($campos_obligatorios as $campo) {

  if (strlen($_POST[$campo]) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa los campos obligatorios!"));
    exit();

  }

}

$rut_limpio = preg_replace("/[\.\-]/", "", $_POST["rut"]);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Rut inválido!"));
	exit();

}

//comprobando si persona existe
$query = $pdo->prepare("SELECT * FROM persona WHERE habilitada AND rut = :rut AND id_persona != :id_persona LIMIT 1");
$query->bindValue(":rut", $_POST["rut"], PDO::PARAM_STR);
$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el rut " . $_POST["rut"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		UPDATE persona SET
		rut = :rut,
		nombre_persona = :nombre_persona,
		giro = :giro,
		direccion = :direccion,
		n_direccion = :n_direccion,
		id_ciudad = :id_ciudad,
		telefono = :telefono,
		telefono_alternativo = :telefono_alternativo,
		correo = :correo
		WHERE id_persona = :id_persona");

	$query->bindValue(":rut", $_POST["rut"], PDO::PARAM_STR);
	$query->bindValue(":nombre_persona", $_POST["nombre_persona"], PDO::PARAM_STR);
	$query->bindValue(":giro", strlen($_POST["giro"]) === 0 ? "particular" : $_POST["giro"], PDO::PARAM_STR);
	$query->bindValue(":direccion", $_POST["direccion"], PDO::PARAM_STR);
	$query->bindValue(":n_direccion", $_POST["n_direccion"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad"], PDO::PARAM_INT);
	$query->bindValue(":telefono", $_POST["telefono"], PDO::PARAM_STR);
	$query->bindValue(":telefono_alternativo", $_POST["telefono_alternativo"], PDO::PARAM_STR);
	$query->bindValue(":correo", $_POST["correo"], PDO::PARAM_STR);
	$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);

	$query->execute();

	$tipo_persona = $_GET["tipo_persona"];
	$persona_alternativa = $tipo_persona === "cliente" ? "proveedor" : "cliente";

	//comprobando si está habilitada como persona alternativa
	$query = $pdo->prepare("SELECT COUNT(*) AS cantidad FROM " . $persona_alternativa . " WHERE id_persona = :id_persona LIMIT 1");
	$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);
	$query->execute();

	$habilitada_persona_alternativa = $query->fetch(PDO::FETCH_COLUMN) > 0;

	if (isset($_POST["persona_alternativa"]) && !$habilitada_persona_alternativa) {
		
		$query = $pdo->prepare("INSERT INTO " . $persona_alternativa . "(id_persona) VALUES (:id_persona)");
		$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);
		$query->execute();

	}

	if (!isset($_POST["persona_alternativa"]) && $habilitada_persona_alternativa) {
		
		$query = $pdo->prepare("DELETE FROM " . $persona_alternativa . " WHERE id_persona = :id_persona");
		$query->bindValue(":id_persona", $_GET["id_persona"], PDO::PARAM_INT);
		$query->execute();

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡" . ucfirst($_GET["tipo_persona"]) . " editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../personas/index.php?tipo_persona=" . $_GET["tipo_persona"];

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>