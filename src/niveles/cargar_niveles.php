<?php

require_once "../controller/conexion.php";

function cargarNiveles() {

	global $pdo;

    $query = $pdo->prepare("
    SELECT
    nivel.id_nivel,
    nivel.nombre_nivel,
    seccion.nombre_seccion,
    locker.nombre_locker,
    bodega.nombre_bodega
    FROM nivel
    INNER JOIN seccion ON seccion.id_seccion = nivel.id_seccion
    INNER JOIN locker ON locker.id_locker = seccion.id_locker
    INNER JOIN bodega ON bodega.id_bodega = locker.id_bodega
    WHERE nivel.habilitado
    AND bodega.id_sucursal = :id_sucursal");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $nivels = $query->fetchAll(); ?>

    <?php foreach($nivels as $nivel): ?>

        <tr data-id-nivel="<?php echo $nivel->id_nivel ?>">
            <td><?php echo $nivel->nombre_nivel ?></td>
            <td><?php echo $nivel->nombre_seccion ?></td>
            <td><?php echo $nivel->nombre_locker ?></td>
            <td><?php echo $nivel->nombre_bodega ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $nivel->id_nivel ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $nivel->id_nivel ?>">
                    <a href="<?php echo 'editar.php?id_nivel=' . $nivel->id_nivel ?>"><button class="dropdown-item" type="button">Editar</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>