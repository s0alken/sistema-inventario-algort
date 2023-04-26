<?php

function cargarNumeroDocumento($id_venta, $documento, $medio_pago){

  global $pdo;

  $tablas = array("boleta"           => array("tabla" => "venta_boleta",        "columna" => "n_boleta",        "label" => "n° boleta:"),
                  "factura"          => array("tabla" => "venta_factura",       "columna" => "n_factura",       "label" => "n° factura:"),
                  "guía de despacho" => array("tabla" => "venta_guia_despacho", "columna" => "n_guia_despacho", "label" => "n° guía despacho:"),
                  "boleta redcompra" => array("tabla" => "venta_redcompra",     "columna" => "n_redcompra",     "label" => "n° redcompra:"),
                  "voucher"          => array("tabla" => "venta_voucher",       "columna" => "n_voucher",       "label" => "n° voucher:")
                  );

  $tabla   = $tablas[$documento]["tabla"];
  $columna = $tablas[$documento]["columna"];
  $label   = $tablas[$documento]["label"];

  $numero_documento = [];

  if ($documento != "voucher") {

    $query = $pdo->prepare("SELECT " . $columna . " FROM " . $tabla . " WHERE id_venta = :id_venta");

    $query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
    $query->execute();

    $numero = $query->fetch(PDO::FETCH_COLUMN);

    $numero_documento[$documento]["numero"] = $numero === FALSE ? "no registrado" : $numero;
    $numero_documento[$documento]["label"] = $label;
    $numero_documento[$documento]["id"] = $columna;

  }

  if ($medio_pago === "redcompra" && $documento != "boleta redcompra") {

    $query = $pdo->prepare("SELECT n_redcompra FROM venta_redcompra WHERE id_venta = :id_venta");

    $query->bindValue(":id_venta", $id_venta, PDO::PARAM_INT);
    $query->execute();

    $numero = $query->fetch(PDO::FETCH_COLUMN);

    $numero_documento["redcompra"]["numero"] = $numero === FALSE ? "no registrado" : $numero;

  }

  return $numero_documento;

}

?>



