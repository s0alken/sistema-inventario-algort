<?php

function configurarStock($id_sucursal){

  global $pdo;

  $query = $pdo->query("SELECT id_producto FROM producto");

  $productos = $query->fetchAll(PDO::FETCH_COLUMN);

  foreach ($productos as $id_producto) {

    $query = $pdo->prepare("INSERT INTO stock_producto(id_producto, stock, id_sucursal) VALUES (:id_producto, 0, :id_sucursal)");

    $query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
    $query->bindValue(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
    $query->execute();

  }


}

?>