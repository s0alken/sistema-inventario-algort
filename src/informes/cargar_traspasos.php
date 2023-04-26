<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarTraspasos($filtros);

}

function cargarTraspasos($filtros = []) {

	global $pdo;

    if ($filtros) {

        $query = $pdo->prepare("
            SELECT
            td.id_traspaso,
            DATE_FORMAT(t.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            td.producto,
            so.nombre_sucursal AS sucursal_origen,
            sd.nombre_sucursal AS sucursal_destino,
            td.cantidad,
            t.observaciones
            FROM traspaso_detalle td
            INNER JOIN traspaso t ON t.id_traspaso = td.id_traspaso
            INNER JOIN sucursal so ON so.id_sucursal = t.id_sucursal_origen
            INNER JOIN sucursal sd ON sd.id_sucursal = t.id_sucursal_destino
            WHERE t.fecha BETWEEN :fecha_inicio AND :fecha_termino
            ORDER BY td.id_traspaso DESC");

        $query->bindValue(":fecha_inicio", $filtros["fecha_inicio"], PDO::PARAM_STR);
        $query->bindValue(":fecha_termino", $filtros["fecha_termino"] . " 23:59:59", PDO::PARAM_STR);
        $query->execute();

    } else {

        $query = $pdo->query("
            SELECT
            td.id_traspaso,
            DATE_FORMAT(t.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            td.producto,
            so.nombre_sucursal AS sucursal_origen,
            sd.nombre_sucursal AS sucursal_destino,
            td.cantidad,
            t.observaciones
            FROM traspaso_detalle td
            INNER JOIN traspaso t ON t.id_traspaso = td.id_traspaso
            INNER JOIN sucursal so ON so.id_sucursal = t.id_sucursal_origen
            INNER JOIN sucursal sd ON sd.id_sucursal = t.id_sucursal_destino
            ORDER BY td.id_traspaso DESC");

    }

    $traspasos = $query->fetchAll(); ?>

    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">N° traspaso</th>
            <th scope="col">Fecha</th>
            <th scope="col">Producto</th>
            <th scope="col">Origen</th>
            <th scope="col">Destino</th>
            <th scope="col">Stock traspasado</th>
            <th scope="col">Observaciones</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($traspasos as $traspaso): ?>

            <tr data-id-traspaso="<?php echo $traspaso->id_traspaso ?>">
                <td><?php echo $traspaso->id_traspaso ?></td>
                <td><?php echo $traspaso->fecha ?></td>
                <td><?php echo $traspaso->producto ?></td>
                <td><?php echo $traspaso->sucursal_origen ?></td>
                <td><?php echo $traspaso->sucursal_destino ?></td>
                <td><?php echo $traspaso->cantidad ?></td>
                <td><?php echo $traspaso->observaciones ?></td>
            </tr>

        <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

<?php } ?>