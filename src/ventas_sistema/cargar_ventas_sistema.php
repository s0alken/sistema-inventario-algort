<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarCompras($filtros);

}

function cargarVentasSistema($filtros = []) {

	global $pdo;

    if ($filtros) {

        $query = $pdo->prepare("
            SELECT
            v.id_venta,
            DATE_FORMAT(v.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            d.nombre_documento AS documento,
            mp.nombre_medio_pago AS medio_pago,
            v.puntos_aplicados,
            v.costo_despacho,
            v.descuento_porcentaje,
            v.descuento_dinero,
            u.nombre_usuario AS vendedor
            FROM venta v
            INNER JOIN documento d ON d.id_documento = v.id_documento
            INNER JOIN medio_pago mp ON mp.id_medio_pago = v.id_medio_pago
            INNER JOIN usuario u ON u.id_usuario = v.id_vendedor
            AND v.fecha BETWEEN :fecha_inicio AND :fecha_termino
            ORDER BY v.id_venta DESC");

        $query->bindValue(":fecha_inicio", $filtros["fecha_inicio"], PDO::PARAM_STR);
        $query->bindValue(":fecha_termino", $filtros["fecha_termino"] . " 23:59:59", PDO::PARAM_STR);
        $query->execute();

    } else {

        $query = $pdo->query("
            SELECT
            v.id_venta,
            DATE_FORMAT(v.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            d.nombre_documento AS documento,
            mp.nombre_medio_pago AS medio_pago,
            v.puntos_aplicados,
            v.costo_despacho,
            v.descuento_porcentaje,
            v.descuento_dinero,
            u.nombre_usuario AS vendedor
            FROM venta v
            INNER JOIN documento d ON d.id_documento = v.id_documento
            INNER JOIN medio_pago mp ON mp.id_medio_pago = v.id_medio_pago
            INNER JOIN usuario u ON u.id_usuario = v.id_vendedor
            ORDER BY v.id_venta DESC");

    }

    $ventas = $query->fetchAll();

    foreach ($ventas as $venta) {

        $query = $pdo->prepare("
            SELECT
            SUM(ROUND((vd.precio_venta - ((vd.precio_venta * vd.descuento_porcentaje) / 100)) * vd.cantidad) - (vd.descuento_dinero * vd.cantidad))
            AS monto_total
            FROM venta_detalle vd
            WHERE vd.id_venta = :id_venta");

        $query->bindValue(":id_venta", $venta->id_venta, PDO::PARAM_INT);
        $query->execute();

        $monto_total = $query->fetch(PDO::FETCH_COLUMN);

        $total_a_pagar = round($monto_total - (($monto_total * $venta->descuento_porcentaje) / 100)) - $venta->descuento_dinero;

        $venta->total_a_pagar = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_a_pagar - $venta->puntos_aplicados + $venta->costo_despacho);

        //cliente
        $query = $pdo->prepare("
          SELECT
          p.rut AS rut_cliente,
          p.nombre_persona AS nombre_cliente
          FROM venta_cliente vc
          INNER JOIN persona p ON p.id_persona = vc.id_cliente
          WHERE vc.id_venta = :id_venta");

        $query->bindValue(":id_venta", $venta->id_venta, PDO::PARAM_INT);
        $query->execute();

        $venta->cliente = $query->fetch();

        //si no se encuentra el cliente se busca en la tabla comprador
        if (!$venta->cliente) {

            $venta->cliente = new stdClass();

            $venta->cliente->rut_cliente = "no registrado";
            $venta->cliente->nombre_cliente = "no registrado";

        }

    }

    foreach($ventas as $venta): ?>

        <tr data-id-detalle="<?php echo $venta->id_venta ?>" data-instancia="ventas_sistema">
            <td><?php echo $venta->id_venta ?></td>
            <td><?php echo $venta->fecha ?></td>
            <td><?php echo ucfirst($venta->cliente->nombre_cliente) ?></td>
            <td><?php echo ucfirst($venta->cliente->rut_cliente) ?></td>
            <td><?php echo ucfirst($venta->documento) ?></td>
            <td><?php echo ucfirst($venta->medio_pago) ?></td>
            <td><?php echo $venta->total_a_pagar ?></td>
            <td><?php echo ucfirst($venta->vendedor) ?></td>
            <td class="td-opciones">
                <a href="<?php echo '../imprimir_ticket/index.php?id_venta=' . $venta->id_venta ?>" class="btn btn-primary btn-sm btn-block rounded-pill text-nowrap" target="_blank">
                Imprimir ticket
                </a>
            </td>
        </tr>

    <?php endforeach;

}

?>