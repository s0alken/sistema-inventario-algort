<?php

require_once "../controller/conexion.php";

function cargarBodegas() {

	global $pdo;

    $query = $pdo->prepare("SELECT * FROM bodega WHERE habilitada AND id_sucursal = :id_sucursal");

    $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
    $query->execute();

    $bodegas = $query->fetchAll(); ?>

    <?php foreach($bodegas as $bodega): ?>

        <tr data-id-bodega="<?php echo $bodega->id_bodega ?>">
            <td><?php echo $bodega->nombre_bodega ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $bodega->id_bodega ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $bodega->id_bodega ?>">
                    <a href="<?php echo 'editar.php?id_bodega=' . $bodega->id_bodega ?>"><button class="dropdown-item" type="button">Editar</button></a>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>