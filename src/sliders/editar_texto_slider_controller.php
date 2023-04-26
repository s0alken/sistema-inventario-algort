<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando que se hayan completado los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

try {  

	$pdo->beginTransaction();

	//editando slider
	$query = $pdo->prepare("
		UPDATE slider SET
		encabezado = :encabezado,
		encabezado_color = :encabezado_color,
		subtitulo = :subtitulo,
		subtitulo_color = :subtitulo_color
		WHERE id_slider = :id_slider");

	$query->bindValue(":encabezado", $_POST["encabezado_editar"], PDO::PARAM_STR);
	$query->bindValue(":encabezado_color", $_POST["encabezado_color_editar"], PDO::PARAM_STR);
	$query->bindValue(":subtitulo", $_POST["subtitulo_editar"], PDO::PARAM_STR);
	$query->bindValue(":subtitulo_color", $_POST["subtitulo_color_editar"], PDO::PARAM_STR);
	$query->bindValue(":id_slider", $_GET["id_slider"], PDO::PARAM_INT);
	$query->execute();
	
	$query->execute();

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "Texto editado exitosamente!";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>