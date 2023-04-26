<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarCompras($filtros);

}

function cargarVentasWebProcesar($filtros = []) {

	global $pdo;

    if ($filtros) {

        $query = $pdo->prepare("
            SELECT
            c.id_compra,
            DATE_FORMAT(c.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            d.nombre_documento AS documento,
            mp.nombre_medio_pago AS medio_pago,
            op.nombre_operador_logistico AS operador_logistico,
            c.puntos_aplicados,
            c.costo_despacho,
            ce.nombre_compra_estado AS estado
            FROM compra c
            INNER JOIN documento d ON d.id_documento = c.id_documento
            INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
            INNER JOIN operador_logistico op ON op.id_operador_logistico = c.id_operador_logistico
            INNER JOIN compra_estado ce ON ce.id_compra_estado = c.id_compra_estado
            WHERE NOT ce.cierra_venta
            AND c.fecha BETWEEN :fecha_inicio AND :fecha_termino
            ORDER BY c.id_compra DESC");

        $query->bindValue(":fecha_inicio", $filtros["fecha_inicio"], PDO::PARAM_STR);
        $query->bindValue(":fecha_termino", $filtros["fecha_termino"] . " 23:59:59", PDO::PARAM_STR);
        $query->execute();

    } else {

        $query = $pdo->query("
            SELECT
            c.id_compra,
            DATE_FORMAT(c.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            d.nombre_documento AS documento,
            mp.nombre_medio_pago AS medio_pago,
            op.nombre_operador_logistico AS operador_logistico,
            c.puntos_aplicados,
            c.costo_despacho,
            ce.nombre_compra_estado AS estado
            FROM compra c
            INNER JOIN documento d ON d.id_documento = c.id_documento
            INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
            INNER JOIN operador_logistico op ON op.id_operador_logistico = c.id_operador_logistico
            INNER JOIN compra_estado ce ON ce.id_compra_estado = c.id_compra_estado
            WHERE NOT ce.cierra_venta
            ORDER BY c.id_compra DESC");

    }

    $compras = $query->fetchAll();

    foreach ($compras as $compra) {

        $query = $pdo->prepare("SELECT SUM(precio_venta * cantidad) FROM compra_detalle WHERE id_compra = :id_compra");

        $query->bindValue(":id_compra", $compra->id_compra, PDO::PARAM_INT);
        $query->execute();

        $monto_total = $query->fetch(PDO::FETCH_COLUMN);

        $compra->total_a_pagar = "$" . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $monto_total - $compra->puntos_aplicados + $compra->costo_despacho);

        //cliente
        $query = $pdo->prepare("
          SELECT
          p.rut AS rut_cliente,
          p.nombre_persona AS nombre_cliente
          FROM compra_cliente cc
          INNER JOIN persona p ON p.id_persona = cc.id_cliente
          WHERE cc.id_compra = :id_compra");

        $query->bindValue(":id_compra", $compra->id_compra, PDO::PARAM_INT);
        $query->execute();

        $compra->cliente = $query->fetch();

        //si no se encuentra el cliente se busca en la tabla comprador
        if (!$compra->cliente) {

          $query = $pdo->prepare("
            SELECT
            com.rut AS rut_cliente,
            com.nombre_comprador AS nombre_cliente
            FROM comprador com
            WHERE com.id_compra = :id_compra");

          $query->bindValue(":id_compra", $compra->id_compra, PDO::PARAM_INT);
          $query->execute();

          $compra->cliente = $query->fetch();

        }

    }

    foreach($compras as $compra): ?>

        <tr data-id-compra="<?php echo $compra->id_compra ?>">
            <td><?php echo $compra->id_compra ?></td>
            <td><?php echo $compra->fecha ?></td>
            <td><?php echo ucfirst($compra->cliente->nombre_cliente) ?></td>
            <td><?php echo $compra->cliente->rut_cliente ?></td>
            <td><?php echo ucfirst($compra->documento) ?></td>
            <td><?php echo ucfirst($compra->medio_pago) ?></td>
            <td><?php echo ucfirst($compra->operador_logistico) ?></td>
            <td><?php echo $compra->total_a_pagar ?></td>
            <td><?php echo ucfirst($compra->estado) ?></td>
            <td>
                <a href="<?php echo 'procesar_venta_web.php?id_compra=' . $compra->id_compra ?>" class="btn btn-primary btn-sm btn-block rounded-pill">
                Procesar
                </a>
            </td>
        </tr>

    <?php endforeach;

}

?>