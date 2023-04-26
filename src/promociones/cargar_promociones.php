<?php

require_once "../controller/conexion.php";

function cargarPromociones() {

	global $pdo;

    $query = $pdo->query("
        SELECT
        p.id_promocion,
        p.nombre_promocion as nombre,
        DATE_FORMAT(p.fecha_inicio, '%d-%m-%Y') AS fecha_inicio,
        DATE_FORMAT(p.fecha_termino, '%d-%m-%Y') AS fecha_termino,
        p.descuento_porcentaje,
        p.descuento_dinero,
        IF(p.hasta_agotar_stock, 'Sí', 'No') AS hasta_agotar_stock,
        pe.nombre_promocion_estado AS estado
        FROM promocion p
        INNER JOIN promocion_estado pe ON pe.id_promocion_estado = p.id_promocion_estado
        ORDER BY p.id_promocion DESC");

    $promociones = $query->fetchAll(); ?>

    <?php foreach($promociones as $promocion): ?>

        <tr data-id-detalle="<?php echo $promocion->id_promocion ?>" data-instancia="promociones">
            <td><?php echo ucfirst($promocion->nombre) ?></td>
            <td><?php echo $promocion->fecha_inicio ?></td>
            <td><?php echo $promocion->fecha_termino ?></td>
            <td><?php echo $promocion->descuento_porcentaje . "%" ?></td>
            <td><?php echo "$ " . preg_replace("/\B(?=(\d{3})+(?!\d))/", ".", $promocion->descuento_dinero) ?></td>
            <td><?php echo $promocion->hasta_agotar_stock ?></td>
            <td><?php echo ucfirst($promocion->estado) ?></td>
        </tr>

    <?php endforeach;

}

?>