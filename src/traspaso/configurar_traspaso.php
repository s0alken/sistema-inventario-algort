<?php

require_once "../controller/conexion.php";

session_start();

$_SESSION["sistema"]["traspaso"]["observaciones"] = $_POST["observaciones"];

echo json_encode($_SESSION["sistema"]["traspaso"]);

?>