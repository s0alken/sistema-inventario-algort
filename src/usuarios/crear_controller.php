<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando que se hayan completado todos los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando que el nombre de usuario no tenga espacios
if (!preg_match("/^\S*$/", $_POST["nombre_usuario"])) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡El nombre de usuario no debe tener espacios!"));
	exit();

}

//comprobando si usuario existe
$query = $pdo->prepare("SELECT * FROM usuario WHERE habilitado AND nombre_usuario = :nombre_usuario LIMIT 1");
$query->bindValue(":nombre_usuario", $_POST["nombre_usuario"], PDO::PARAM_STR);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el usuario " . $_POST["nombre_usuario"] . "!"));
	exit();

}

//comprobando que contraseña no tenga espacios
if (!preg_match("/^\S*$/", $_POST["password"])) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡La contraseña no debe tener espacios!"));
	exit();

}

//comprobando que las contraseñas coincidan
if ($_POST["password"] != $_POST["password_reingreso"]) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las contraseñas no coinciden!"));
	exit();

}

try {  

	$pdo->beginTransaction();

  	$query = $pdo->prepare("INSERT INTO usuario(nombre_usuario, password, id_permiso) VALUES (:nombre_usuario, :password, :id_permiso)");

	$query->bindValue(":nombre_usuario", $_POST["nombre_usuario"], PDO::PARAM_STR);
	$query->bindValue(":password", password_hash($_POST["password"], PASSWORD_DEFAULT), PDO::PARAM_STR);
	$query->bindValue(":id_permiso", $_POST["id_permiso"], PDO::PARAM_INT);

	$query->execute();

	$pdo->commit();

	$_SESSION["sistema"]["mensaje"] = "¡Usuario creado exitosamente!";
	$_SESSION["sistema"]["redireccion"] = "../usuarios/";

	echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));
  
} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>