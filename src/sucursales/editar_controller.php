<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si la sucursal existe
$query = $pdo->prepare("
	SELECT * FROM sucursal
	WHERE habilitada
	AND nombre_sucursal = :nombre_sucursal
	AND id_ciudad = :id_ciudad
	AND id_sucursal != :id_sucursal LIMIT 1");

$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);
$query->bindValue(":id_sucursal", $_GET["id_sucursal"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la sucursal " . $_POST["nombre_sucursal"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		UPDATE sucursal SET
		nombre_sucursal = :nombre_sucursal,
		direccion = :direccion,
		n_direccion = :n_direccion,
		id_ciudad = :id_ciudad
		WHERE id_sucursal = :id_sucursal");

	$query->bindValue(":nombre_sucursal", $_POST["nombre_sucursal"], PDO::PARAM_STR);
	$query->bindValue(":direccion", $_POST["direccion"], PDO::PARAM_STR);
	$query->bindValue(":n_direccion", $_POST["n_direccion"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad_nueva_sucursal"], PDO::PARAM_INT);
	$query->bindValue(":id_sucursal", $_GET["id_sucursal"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Sucursal editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../sucursales/";

    //si se editó la sucursal actual, se actualizan los datos de la sesión "sucursal"
    if (intval($_GET["id_sucursal"]) === $_SESSION["sistema"]["sucursal"]->id_sucursal) {

    	$query = $pdo->prepare("SELECT * FROM sucursal WHERE id_sucursal = :id_sucursal");

		$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
		$query->execute();

		$sucursal = $query->fetch();

		$_SESSION["sistema"]["sucursal"] = $sucursal;

    }

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>