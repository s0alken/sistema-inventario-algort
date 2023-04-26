<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    $filtros = $_POST["filtros"];

    cargarCotizaciones($filtros);

}

function cargarCotizaciones($filtros = []) {

	global $pdo;

    if ($filtros) {

        $query = $pdo->prepare("
            SELECT
            c.id_cotizacion,
            DATE_FORMAT(c.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            p.nombre_persona AS cliente,
            mp.nombre_medio_pago AS medio_pago,
            IF(c.observaciones = '', 'Sin observaciones', c.observaciones) AS observaciones
            FROM cotizacion c
            INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
            INNER JOIN persona p ON p.id_persona = c.id_cliente
            WHERE c.fecha BETWEEN :fecha_inicio AND :fecha_termino
            ORDER BY c.id_cotizacion DESC");

        $query->bindValue(":fecha_inicio", $filtros["fecha_inicio"], PDO::PARAM_STR);
        $query->bindValue(":fecha_termino", $filtros["fecha_termino"] . " 23:59:59", PDO::PARAM_STR);
        $query->execute();

    } else {

        $query = $pdo->query("
            SELECT
            c.id_cotizacion,
            DATE_FORMAT(c.fecha, '%d-%m-%Y %H:%i:%s') AS fecha,
            p.nombre_persona AS cliente,
            mp.nombre_medio_pago AS medio_pago,
            IF(c.observaciones = '', 'Sin observaciones', c.observaciones) AS observaciones
            FROM cotizacion c
            INNER JOIN medio_pago mp ON mp.id_medio_pago = c.id_medio_pago
            INNER JOIN persona p ON p.id_persona = c.id_cliente
            ORDER BY c.id_cotizacion DESC");

    }

    $cotizaciones = $query->fetchAll(); ?>

    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm" id="tabla_filtrar">
        <thead>
          <tr>
            <th scope="col">N° cotización</th>
            <th scope="col">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">Medio de pago</th>
            <th scope="col">Observaciones</th>
            <th scope="col" class="no-exportar">Opciones</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($cotizaciones as $cotizacion): ?>

            <tr data-id-cotizacion="<?php echo $cotizacion->id_cotizacion ?>">
                <td><?php echo $cotizacion->id_cotizacion ?></td>
                <td><?php echo $cotizacion->fecha ?></td>
                <td><?php echo $cotizacion->cliente ?></td>
                <td><?php echo $cotizacion->medio_pago ?></td>
                <td><?php echo ucfirst($cotizacion->observaciones) ?></td>
                <td class="td-opciones">
                    <a href="<?php echo '../imprimir_cotizacion/index.php?id_cotizacion=' . $cotizacion->id_cotizacion ?>" class="btn btn-primary btn-sm btn-block rounded-pill text-nowrap" target="_blank">Imprimir</a>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

<?php } ?>