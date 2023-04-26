<?php

session_start();

unset($_SESSION["sistema"]);

header("Location: ../index.php");

exit();

?>