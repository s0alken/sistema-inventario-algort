<?php

require_once "../controller/conexion.php";

function cargarLockers() {

	global $pdo;

    $query = $pdo->prepare("
    SELECT
    locker.id_locker,
    locker.nombre_locker,
    bodega.nombre_bodega
    FROM locker
    INNER JOIN bodega ON bodega.id_bodega = locker.id_bodega
    WHERE locker.habilitado
    AND bodega.id_sucursal = :id_sucursal");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $lockers = $query->fetchAll(); ?>

    <?php foreach($lockers as $locker): ?>

        <tr data-id-locker="<?php echo $locker->id_locker ?>">
            <td><?php echo $locker->nombre_locker ?></td>
            <td><?php echo $locker->nombre_bodega ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $locker->id_locker ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $locker->id_locker ?>">
                    <a href="<?php echo 'editar.php?id_locker=' . $locker->id_locker ?>"><button class="dropdown-item" type="button">Editar</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>