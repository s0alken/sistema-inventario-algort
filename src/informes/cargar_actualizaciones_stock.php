<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarActualizacionesStock($filtros);

}

function cargarActualizacionesStock($filtros = []) {

	global $pdo;

    if ($filtros) {

        $query = $pdo->prepare("
            SELECT
            a.id_actualizacion_stock,
            DATE_FORMAT(a.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            p.nombre_persona AS proveedor,
            a.n_documento_compra,
            mp.nombre_medio_pago AS medio_pago,
            d.nombre_documento AS documento,
            ad.codigo_barras,
            ad.producto,
            ad.cantidad,
            IF(a.observaciones = '', 'Ninguna', a.observaciones) AS observaciones
            FROM actualizacion_stock_detalle ad
            INNER JOIN actualizacion_stock a ON a.id_actualizacion_stock = ad.id_actualizacion_stock
            INNER JOIN persona p ON p.id_persona = a.id_proveedor
            INNER JOIN medio_pago mp ON mp.id_medio_pago = a.id_medio_pago
            INNER JOIN documento d ON d.id_documento = a.id_documento
            WHERE a.fecha BETWEEN :fecha_inicio AND :fecha_termino
            ORDER BY a.id_actualizacion_stock DESC");

        $query->bindValue(":fecha_inicio", $filtros["fecha_inicio"], PDO::PARAM_STR);
        $query->bindValue(":fecha_termino", $filtros["fecha_termino"] . " 23:59:59", PDO::PARAM_STR);
        $query->execute();

    } else {

        $query = $pdo->query("
            SELECT
            a.id_actualizacion_stock,
            DATE_FORMAT(a.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            p.nombre_persona AS proveedor,
            a.n_documento_compra,
            mp.nombre_medio_pago AS medio_pago,
            d.nombre_documento AS documento,
            ad.codigo_barras,
            ad.producto,
            ad.cantidad,
            IF(a.observaciones = '', 'Ninguna', a.observaciones) AS observaciones
            FROM actualizacion_stock_detalle ad
            INNER JOIN actualizacion_stock a ON a.id_actualizacion_stock = ad.id_actualizacion_stock
            INNER JOIN persona p ON p.id_persona = a.id_proveedor
            INNER JOIN medio_pago mp ON mp.id_medio_pago = a.id_medio_pago
            INNER JOIN documento d ON d.id_documento = a.id_documento
            ORDER BY a.id_actualizacion_stock DESC");

    }

    $actualizaciones_stock = $query->fetchAll(); ?>

    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">N° actualizacion</th>
            <th scope="col">Fecha</th>
            <th scope="col">Proveedor</th>
            <th scope="col">N° documento compra</th>
            <th scope="col">Medio de pago</th>
            <th scope="col">Tipo documento</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad actualizada</th>
            <th scope="col">Observaciones</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($actualizaciones_stock as $actualizacion_stock): ?>

            <tr data-id-actualizacion-stock="<?php echo $actualizacion_stock->id_actualizacion_stock ?>">
                <td><?php echo $actualizacion_stock->id_actualizacion_stock ?></td>
                <td><?php echo $actualizacion_stock->fecha ?></td>
                <td><?php echo $actualizacion_stock->proveedor ?></td>
                <td><?php echo $actualizacion_stock->n_documento_compra ?></td>
                <td><?php echo $actualizacion_stock->medio_pago ?></td>
                <td><?php echo $actualizacion_stock->documento ?></td>
                <td><?php echo $actualizacion_stock->producto ?></td>
                <td><?php echo $actualizacion_stock->cantidad ?></td>
                <td><?php echo ucfirst($actualizacion_stock->observaciones) ?></td>
            </tr>

        <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

<?php } ?>