<?php

session_start();

require_once "../controller/conexion.php";
require_once "../controller/validar_captcha.php";
require_once "../venta/iniciar_venta.php";

$campos_obligatorios = array("nombre_usuario", "password");

//validandp campos completados
foreach ($campos_obligatorios as $campo) {
	
	if (strlen($_POST[$campo]) === 0) {
		
		echo json_encode(array("autenticado" => false, "error" => "Completa todos los campos"));
		exit();

	}
}

//validando captcha
if (!captchaValido($_POST["g-recaptcha-response"])) {
	
	echo json_encode(array("autenticado" => false, "error" => "Debes completar el captcha"));
	exit();

}

$query = $pdo->prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario AND habilitado");

$query->bindValue(":nombre_usuario", $_POST["nombre_usuario"], PDO::PARAM_STR);
$query->execute();

$usuario = $query->fetch();

if($usuario && password_verify($_POST["password"], $usuario->password)) {

	$_SESSION["sistema"]["usuario"] = new stdClass();

	$_SESSION["sistema"]["usuario"]->id_usuario = $usuario->id_usuario;
	$_SESSION["sistema"]["usuario"]->nombre_usuario = $usuario->nombre_usuario;
	$_SESSION["sistema"]["usuario"]->administrador = $usuario->id_permiso === 1 || $usuario->id_permiso === 3;

	$query = $pdo->prepare("SELECT * FROM sucursal WHERE id_sucursal = :id_sucursal");

	$query->bindValue(":id_sucursal", $_POST["id_sucursal"], PDO::PARAM_INT);
	$query->execute();

	$sucursal = $query->fetch();

	$_SESSION["sistema"]["sucursal"] = $sucursal;

	//se inicia la sesión de venta para disponer de la cantidad de productos en el carrito
	iniciarVenta();

	echo json_encode(array("autenticado" => true));

} else {

    echo json_encode(array("autenticado" => false, "error" => "Usuario o contraseña incorrectos"));

}

?>