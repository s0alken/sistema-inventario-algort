<?php

session_start();

$documento  = $_SESSION["sistema"]["venta"]["documento"]["nombre_documento"];
$medio_pago = $_SESSION["sistema"]["venta"]["medio_pago"]["nombre_medio_pago"];

$ids = array("boleta"           => array("id" => "n_boleta",        "label" => "boleta"), 
             "factura"          => array("id" => "n_factura",       "label" => "factura"),
             "guía de despacho" => array("id" => "n_guia_despacho", "label" => "guía de despacho"),
             "boleta redcompra" => array("id" => "n_redcompra",     "label" => "redcompra"),
             "voucher"          => array("id" => "n_voucher",       "label" => "voucher")
              );

$id    = $ids[$documento]["id"];
$label = $ids[$documento]["label"];

?>

<?php if ($documento != "voucher"): ?>

  <div class="form-row justify-content-center text-center">
    <div class="form-group col-md-10">
      <label for="<?php echo $id ?>"><?php echo 'Ingrese n° de ' . $label . ':' ?></label>
      <input type="number" class="form-control form-control-sm rounded-pill campo-venta" id="<?php echo $id ?>" name="<?php echo $id ?>" value="<?php echo $_SESSION['sistema']['venta'][$id] ?>">
    </div>
  </div>

<?php endif ?>

<?php if ($medio_pago === "redcompra" && $documento != "boleta redcompra"): ?>

  <div class="form-row justify-content-center text-center">
    <div class="form-group col-md-10">
      <label for="n_redcompra">Ingrese n° de redcompra</label>
      <input type="number" class="form-control form-control-sm rounded-pill campo-venta" id="n_redcompra" name="n_redcompra" value="<?php echo $_SESSION['sistema']['venta']['n_redcompra'] ?>">
    </div>
  </div>

<?php endif ?>



