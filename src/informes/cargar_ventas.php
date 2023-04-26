<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarVentas($filtros);

}

function cargarVentas($filtros = []) {

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

    $total_redcompra = 0;
    $total_efectivo = 0;
    $total_transferencia = 0;
    $total_cheques = 0;

    foreach ($ventas as $venta) {
        
        //obteniendo monto total de la venta
        $query = $pdo->prepare("
            SELECT
            SUM(ROUND((vd.precio_venta - ((vd.precio_venta * vd.descuento_porcentaje) / 100)) * vd.cantidad) - (vd.descuento_dinero * vd.cantidad))
            AS monto_total
            FROM venta_detalle vd
            WHERE vd.id_venta = :id_venta");

        $query->bindValue(":id_venta", $venta->id_venta, PDO::PARAM_INT);
        $query->execute();

        $monto_total = $query->fetch(PDO::FETCH_COLUMN);

        //aplicando descuentos correspondientes
        $total_a_pagar = round($monto_total - (($monto_total * $venta->descuento_porcentaje) / 100)) - $venta->descuento_dinero;

        $venta->total_a_pagar = $total_a_pagar - $venta->puntos_aplicados + $venta->costo_despacho;

        //calculando los montos totales por tipo de medio de pago
        $total_redcompra += $venta->medio_pago === "redcompra" ? $venta->total_a_pagar : 0;
        $total_efectivo += $venta->medio_pago === "efectivo" ? $venta->total_a_pagar : 0;
        $total_transferencia += $venta->medio_pago === "transferencia bancaria" ? $venta->total_a_pagar : 0;
        $total_cheques += $venta->medio_pago === "cheque a 30 días" || $venta->medio_pago === "cheque al día" ? $venta->total_a_pagar : 0;

        //obteniendo detalle de la venta
        $query = $pdo->prepare("
            SELECT
            vd.codigo_barras,
            vd.producto,
            vd.cantidad,
            ROUND(vd.precio_venta - ((vd.precio_venta * vd.descuento_porcentaje) / 100)) - vd.descuento_dinero AS precio_venta,
            ROUND((vd.precio_venta - ((vd.precio_venta * vd.descuento_porcentaje) / 100)) * vd.cantidad) - (vd.descuento_dinero * vd.cantidad)
            AS subtotal,
            vd.precio_costo
            FROM venta_detalle vd
            WHERE vd.id_venta = :id_venta");

        $query->bindValue(":id_venta", $venta->id_venta, PDO::PARAM_INT);
        $query->execute();

        $venta->detalle = $query->fetchAll();

    }

    $total_redcompra = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_redcompra);
    $total_efectivo = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_efectivo);
    $total_transferencia = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_transferencia);
    $total_cheques = "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $total_cheques);

    ?>

    <div class="form-row">
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="total_redcompra">Total redcompra:</label>
        <input type="text" id="total_redcompra" class="form-control form-control-sm rounded-pill" value="<?php echo $total_redcompra ?>" disabled>
      </div>
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="total_efectivo">Total efectivo:</label>
        <input type="text" id="total_efectivo" class="form-control form-control-sm rounded-pill" value="<?php echo $total_efectivo ?>" disabled>
      </div>
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="total_transferencia">Total transferencia:</label>
        <input type="text" id="total_transferencia" class="form-control form-control-sm rounded-pill" value="<?php echo $total_transferencia ?>" disabled>
      </div>
      <div class="col-lg-3 mb-3 text-center text-lg-left">
        <label for="total_cheque">Total cheques:</label>
        <input type="text" id="total_cheque" class="form-control form-control-sm rounded-pill" value="<?php echo $total_cheques ?>" disabled>
      </div>
    </div>

    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">N° venta</th>
            <th scope="col">Fecha</th>
            <th scope="col">Total</th>
            <th scope="col">Vendedor</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio venta</th>
            <th scope="col">Precio costo</th>
            <th scope="col">Desc. % total venta</th>
            <th scope="col">Desc. $ total venta</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach ($ventas as $venta): ?>

            <?php foreach($venta->detalle as $detalle): ?>

                <tr data-id-venta="<?php echo $venta_detalle->id_venta ?>">
                    <td><?php echo $venta->id_venta ?></td>
                    <td><?php echo $venta->fecha ?></td>
                    <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $detalle->subtotal) ?></td>
                    <td><?php echo $venta->vendedor ?></td>
                    <td><?php echo $detalle->producto ?></td>
                    <td><?php echo preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $detalle->cantidad) ?></td>
                    <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $detalle->precio_venta) ?></td>
                    <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $detalle->precio_costo) ?></td>
                    <td><?php echo preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->descuento_porcentaje . "%") ?></td>
                    <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $venta->descuento_dinero) ?></td>
                </tr>

            <?php endforeach; ?>
            
        <?php endforeach ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

<?php } ?>