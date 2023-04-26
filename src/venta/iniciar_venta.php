<?php

require_once "../controller/conexion.php";

function iniciarVenta(){

  global $pdo;

  //obteniendo documento tipo voucher
  $query = $pdo->query("SELECT * FROM documento WHERE nombre_documento = 'voucher'");

  $documento = $query->fetch(PDO::FETCH_ASSOC);

  //obteniendo el medio de pago
  $query = $pdo->prepare("
    SELECT
    dmp.id_medio_pago,
    mp.nombre_medio_pago
    FROM documento_medio_pago dmp
    INNER JOIN medio_pago mp ON mp.id_medio_pago = dmp.id_medio_pago
    WHERE mp.nombre_medio_pago = 'efectivo'
    AND dmp.id_documento = :id_documento");

  $query->bindParam(":id_documento", $documento["id_documento"], PDO::PARAM_INT);
  $query->execute();

  $medio_pago = $query->fetch(PDO::FETCH_ASSOC);

  $_SESSION["sistema"]["venta"]["carrito"]              = [];
  $_SESSION["sistema"]["venta"]["cliente"]              = [];
  $_SESSION["sistema"]["venta"]["documento"]            = $documento;
  $_SESSION["sistema"]["venta"]["medio_pago"]           = $medio_pago;
  $_SESSION["sistema"]["venta"]["descuento_porcentaje"] = 0;
  $_SESSION["sistema"]["venta"]["descuento_dinero"]     = 0;
  $_SESSION["sistema"]["venta"]["total_descuento"]      = 0;
  $_SESSION["sistema"]["venta"]["monto_neto"]           = 0;
  $_SESSION["sistema"]["venta"]["total_iva"]            = 0;
  $_SESSION["sistema"]["venta"]["total_a_pagar"]        = 0;
  $_SESSION["sistema"]["venta"]["monto_total"]          = 0;
  $_SESSION["sistema"]["venta"]["observaciones"]        = "";
  $_SESSION["sistema"]["venta"]["cantidad"]             = 0;
  $_SESSION["sistema"]["venta"]["puntos"]               = 0;
  $_SESSION["sistema"]["venta"]["n_boleta"]             = "";
  $_SESSION["sistema"]["venta"]["n_factura"]            = "";
  $_SESSION["sistema"]["venta"]["n_guia_despacho"]      = "";
  $_SESSION["sistema"]["venta"]["n_redcompra"]          = "";

}

?>