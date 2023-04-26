<?php

require_once "../controller/conexion.php";

//obteniendo texto imagen
$query = $pdo->prepare("SELECT encabezado, encabezado_color, subtitulo, subtitulo_color FROM slider WHERE id_slider = :id_slider");
$query->bindValue(":id_slider", $_POST["id_slider"], PDO::PARAM_INT);
$query->execute();

$texto_imagen = $query->fetch();

echo json_encode($texto_imagen);

?>