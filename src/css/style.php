<?php

header("Content-type: text/css; charset: UTF-8");

require_once "../controller/conexion.php";

$query = $pdo->query("SELECT color_sistema FROM personalizacion");

$color_sistema = $query->fetch(PDO::FETCH_COLUMN);

?>

@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

body {
    font-family: 'Poppins', sans-serif;
    background: #fafafa;
}

/*============= NAVBAR =============*/
#nav-top {
    height: 2rem;
    color: #fff;
    font-size: 14px;
    z-index: 999;
}

#nav-top i.fa-exclamation-triangle {
  animation: alerta-intermitente 1.3s infinite;
}

@keyframes alerta-intermitente {

  from {
        opacity:0;
    }

    50% {
        opacity:1;
    }

    to {
        opacity:0;
    }

}

#nav-bottom {
    height: 5rem;
    background: #fff;
    margin-top: 2rem;
    font-size: .7rem;
    font-weight: bold;
    -webkit-box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
    -moz-box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
    box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
    z-index: 99;
}

#nav-bottom .nav-item {
    border-radius: 20px;
    margin: 0 10px;
    width: 9rem;
}

#nav-bottom .nav-item:hover {
    background: #f0f1f5;
}

#nav-bottom .nav-item i {
    font-size: 2rem;
}

#nav-smartphone {
    height: 3.5rem;
    color: #fff;
}

#sidebarCollapse {
    background: none;
    border: none;
    font-size: 2em;
    min-width: 3rem;
    height: 3rem;
    border-radius: 50%;
}

#sidebarCollapse:focus {
    outline: none;
}

#sidebarCollapse:hover {
    background: #f5f6fa;
}

a, a:hover, a:focus {
    color: inherit;
    text-decoration: none;
    transition: all 0.3s;
}

/*============= SIDEBAR =============*/
#sidebar {
    width: 15rem;
    position: fixed;
    left: 0;
    height: calc(100% - 7rem);
    z-index: 9;
    color: #4d4d4d;
    background: #fff;
    transition: all 0.3s;
    -webkit-box-shadow: 2px 3px 6px -1px rgba(0,0,0,0.23);
    -moz-box-shadow: 2px 3px 6px -1px rgba(0,0,0,0.23);
    box-shadow: 2px 3px 6px -1px rgba(0,0,0,0.23);
}

#sidebar.active {
    margin-left: -15rem;
}

#sidebar .sidebar-header {
    padding: 1rem;
    margin: 1rem;
    border-radius: 20px;
}

#sidebar .sidebar-header h6 {
    font-size: .8em;
    color: #737373;
    text-transform: capitalize;
}

#sidebar ul li a {
    padding: 15px 23px;
    font-size: .8rem;
    display: block;
}

#sidebar ul ul a {
    font-size: 0.75em !important;
    padding: 10px 0 10px 60px!important;
}

#sidebar ul li a[aria-expanded="false"]:hover, ul ul a:hover {
    background: #f5f6fa;
    color: <?php echo $color_sistema ?> !important;
}

#sidebar ul li a:hover {
    padding-left: 35px;
}

#sidebar ul ul a:hover {
    padding-left: 65px!important;
}

#sidebar ul li.active > a,
#sidebar a[aria-expanded="true"] {
    background: <?php echo $color_sistema ?>;
    color: #fff!important;
}

#sidebar a[data-toggle="collapse"] {
    position: relative;
}

#sidebar .rotate {
    font-size: 10px;
    -moz-transition: transform .1s linear;
    -webkit-transition: transform .1s linear;
    transition: transform .1s linear;
}

#sidebar .rotate.down {
    -moz-transform:rotate(90deg);
    -webkit-transform:rotate(90deg);
    transform:rotate(90deg);
}

#sidebar .icon-box {
    display: inline-block;
    width: 25px;
    margin-right: 12px;
}
#sidebar .mCSB_draggerRail {
    background: #f5f6fa !important;
}
#sidebar .mCSB_dragger_bar{
    background: <?php echo $color_sistema ?> !important;
}

/*============= CONTENT =============*/
.wrapper {
    display: flex;
    width: 100%;
    margin-top: 7rem;

}

#content {
    width: calc(100% - 15rem);
    min-height: 100vh;
    transition: all 0.3s;
    position: absolute;
    right: 0;
    background: #f5f6fa;
}

#content.active {
    width: 100%;
}

.box-content {
    margin: 2rem;
    padding: 2rem;
    background: #fff;
    border-radius: 15px;
    -webkit-box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
    -moz-box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
    box-shadow: 0px 3px 6px -1px rgba(0,0,0,0.23);
}

.modal {
    padding: 0!important;
}

.bg-empresa {
    background: <?php echo $color_sistema ?>;
}

.bg-gris {
    background: #f5f6fa;
}

.text-empresa {
    color: <?php echo $color_sistema ?>;
}

.txt-gris {
    color: #8a8a8a;
}

/*============= MODAL TABLA IMAGEN =============*/
#modal_producto {
    font-size: .8rem;
}

.logo-form-img-container {
    width: 100%;
    height: 8rem;
    position: relative;
}

.logo-sidebar-img-container {
    width: 7rem;
    height: 6rem;
    position: relative;
}

.tabla-img-container {
    width: 4rem;
    height: 4rem;
    position: relative;
}

.modal-img-container {
    width: 100%;
    height: 17rem;
    position: relative;
}

.logo-sidebar-img-container img,
.logo-form-img-container img,
.tabla-img-container img,
.modal-img-container img {
    max-height: 100%;
    max-width: 100%;
    width: auto;
    height: auto;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

.carousel-control-prev-icon {
 background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='<?php echo str_replace("#", "%23", $color_sistema) ?>' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
}

.carousel-control-next-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='<?php echo str_replace("#", "%23", $color_sistema) ?>' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
}

/*============= TABLAS =============*/

.table {
    font-size: .8rem;
}

.tabla-pointer tbody tr td:not(.td-opciones){
    cursor: pointer;
}

/*============= FORMS =============*/
select {
    text-transform: capitalize;
}

small.alerta, .alert {
    display: none;
}

.alert {
    padding: .25rem .5rem !important;
}

/*============= PANTALLA EXITO =============*/
#exito .fa-check-circle {
    font-size: 7rem;
}

/*============= VENTA =============*/
.border-red {
    border: 3px solid #ff1010;
}

.text-red {
    color: #ff1010 !important;
}

.input-group .input-group-text, .input-group  .btn {
    border-top-right-radius: 50rem !important;
    border-bottom-right-radius: 50rem !important;

}

.input-group .form-control {
    border-top-left-radius: 50rem !important;
    border-bottom-left-radius: 50rem !important;

}

/*============= SNACKBAR =============*/

.snackbar {
    visibility: hidden;
    z-index: 9999;
    bottom: 40px;
    color: white;
}

.snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
    animation: fadein 0.5s, fadeout 0.5s 3.5s;
}

/*============= CAPTCHA =============*/
.g-recaptcha > div {
    margin: 0 auto;
}

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 40px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 40px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 40px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 40px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@page {

    margin: 0mm;

}

@media print {

    body, .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
    }

}

@media (max-width: 768px) {

    #sidebar {
        margin-left: -15rem;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }

    #sidebar.active {
        margin-left: 0;
    }

    #content {
        width: 100%;
    }

    #content.active {
        width: calc(100% - 15rem);
    }

    #sidebarCollapse span {
        display: none;
    }

}

@media (max-width: 575.98px) {

    .wrapper {
        margin-top: 0;
    }

    #content {
        margin-top: 3.5rem !important;
    }

    /*
    .snackbar {
        bottom: 90px;
    }
    */

    .box-content {
        margin: .5rem;
        padding: .7rem;
    }

    .g-recaptcha {
        transform: scale(.80);
    }

}