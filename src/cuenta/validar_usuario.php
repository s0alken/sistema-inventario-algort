<?php

require_once "../controller/conexion.php";

session_start();

//comprobando si el usuario existe
$query = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE habilitado AND nombre_usuario = :nombre_usuario AND id_usuario != :id_usuario LIMIT 1");
$query->bindValue(":nombre_usuario", $_POST["nombre_usuario_cuenta"], PDO::PARAM_STR);
$query->bindValue(":id_usuario", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);
$query->execute();

echo json_encode(array("existe" => $query->fetch(PDO::FETCH_COLUMN) > 0));

?>