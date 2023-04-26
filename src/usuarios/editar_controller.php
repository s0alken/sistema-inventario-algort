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

//evitando que el usuario se registre como superadministrador
if (intval($_POST["id_permiso"]) === 3) {
	
	echo json_encode(array("guardado" => false, "mensaje" => "¡No puedes efectuar esta acción!"));
    exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE usuario SET id_permiso = :id_permiso WHERE id_usuario = :id_usuario");

	$query->bindValue(":id_permiso", $_POST["id_permiso"], PDO::PARAM_INT);
	$query->bindValue(":id_usuario", $_GET["id_usuario"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Permiso de usuario editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../usuarios/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>