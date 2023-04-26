<?php

require_once "../controller/conexion.php";

function cargarSecciones() {

	global $pdo;

    $query = $pdo->prepare("
    SELECT
    seccion.id_seccion,
    seccion.nombre_seccion,
    locker.nombre_locker,
    bodega.nombre_bodega
    FROM seccion
    INNER JOIN locker ON locker.id_locker = seccion.id_locker
    INNER JOIN bodega ON bodega.id_bodega = locker.id_bodega
    WHERE seccion.habilitada
    AND bodega.id_sucursal = :id_sucursal");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $secciones = $query->fetchAll(); ?>

    <?php foreach($secciones as $seccion): ?>

        <tr data-id-seccion="<?php echo $seccion->id_seccion ?>">
            <td><?php echo $seccion->nombre_seccion ?></td>
            <td><?php echo $seccion->nombre_locker ?></td>
            <td><?php echo $seccion->nombre_bodega ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $seccion->id_seccion ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $seccion->id_seccion ?>">
                    <a href="<?php echo 'editar.php?id_seccion=' . $seccion->id_seccion ?>"><button class="dropdown-item" type="button">Editar</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>