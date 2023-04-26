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

//comprobando si el nivel existe
$query = $pdo->prepare("
    SELECT * FROM nivel
    WHERE habilitado AND nombre_nivel = :nombre_nivel
    AND id_seccion = :id_seccion
    AND id_nivel != :id_nivel
    LIMIT 1");

$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);
$query->bindValue(":id_nivel", $_GET["id_nivel"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el nivel " . $_POST["nombre_nivel"] . " dentro de la sección seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE nivel SET nombre_nivel = :nombre_nivel, id_seccion = :id_seccion WHERE id_nivel = :id_nivel");

	$query->bindValue(":nombre_nivel", $_POST["nombre_nivel"], PDO::PARAM_STR);
	$query->bindValue(":id_seccion", $_POST["id_seccion_nuevo_nivel"], PDO::PARAM_INT);
    $query->bindValue(":id_nivel", $_GET["id_nivel"], PDO::PARAM_INT);

	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Nivel editado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../niveles/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>