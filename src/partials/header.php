<?php

require_once "../controller/usuario_autenticado.php";

?>

<!doctype html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="../css/style.php">

  <!-- Datatables CSS -->
  <link rel="stylesheet" href="../vendor/datatables/datatables.min.css">

  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

  <!-- Font Awesome JS -->
  <script src="https://kit.fontawesome.com/8e0e07259f.js" crossorigin="anonymous"></script>

  <!-- Lightbox CSS -->
  <link rel="stylesheet" href="../vendor/lightbox/css/lightbox.css">

  <!-- Google Captcha -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <link rel="icon" href="<?php echo '../img/logo.png?' . rand() ?>">

  <title><?php echo $_SERVER["HTTP_HOST"] . " | Sistema" ?></title>

</head>