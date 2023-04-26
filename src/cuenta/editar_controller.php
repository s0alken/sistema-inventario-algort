<?php

session_start();

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "error" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando que el nombre de usuario no tenga espacios
if (!preg_match("/^\S*$/", $_POST["nombre_usuario_cuenta"])) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Nombre de usuario no válido!"));
	exit();

}

//comprobando si usuario existe
$query = $pdo->prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario AND id_usuario != :id_usuario LIMIT 1");
$query->bindValue(":nombre_usuario", $_POST["nombre_usuario_cuenta"], PDO::PARAM_STR);
$query->bindValue(":id_usuario", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el usuario " . $_POST["nombre_usuario_cuenta"] . "!"));
	exit();

}

//validaciones si el usuario cambia password
if (isset($_POST["cambiar_password"])) {
	
	$query = $pdo->prepare("SELECT password FROM usuario WHERE id_usuario = :id_usuario");
	$query->bindValue(":id_usuario", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);

	$query->execute();

	//comprobando si la contraseña es correcta
	if (!password_verify($_POST["password"], $query->fetch()->password)) {

		echo json_encode(array("guardado" => false, "mensaje" => "¡Contraseña incorrecta!"));
		exit();

	}

	//comprobando que contraseña nueva no tenga espacios
	if (!preg_match("/^\S*$/", $_POST["password_nuevo"])) {

	    echo json_encode(array("guardado" => false, "mensaje" => "¡Contraseña no válida!"));
		exit();

	}

	//comprobando que contraseñas coinciden
	if ($_POST["password_nuevo"] != $_POST["password_reingreso"]) {

		echo json_encode(array("guardado" => false, "mensaje" => "¡Las contraseñas no coinciden!"));
		exit();

	}

}

try {  

	$pdo->beginTransaction();

	$query1 = "UPDATE usuario SET nombre_usuario = :nombre_usuario, password = :password WHERE id_usuario = :id_usuario";
	$query2 = "UPDATE usuario SET nombre_usuario = :nombre_usuario WHERE id_usuario = :id_usuario";
	
	$str = isset($_POST["cambiar_password"]) ? $query1 : $query2;

  	$query = $pdo->prepare($str);

	$query->bindValue(":nombre_usuario", $_POST["nombre_usuario_cuenta"], PDO::PARAM_STR);

	//pasando password nuevo si el usuario lo cambia
	if (isset($_POST["cambiar_password"])) {

		$query->bindValue(":password", password_hash($_POST["password_nuevo"], PASSWORD_DEFAULT), PDO::PARAM_STR);

	}

	$query->bindValue(":id_usuario", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);

	$query->execute();

	$pdo->commit();

	//reseteando el nombre de usuario
	$_SESSION["sistema"]["usuario"]->nombre_usuario = $_POST["nombre_usuario_cuenta"];

	$_SESSION["sistema"]["mensaje"] = "¡Cuenta editada exitosamente!";
	$_SESSION["sistema"]["redireccion"] = "../inicio/";

	echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));
  
} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>