<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    session_start();
    cargarProductosTraspaso($_SESSION["sistema"]["traspaso"]["productos"]);

}

function cargarProductosTraspaso($productos) {

    foreach($productos as $codigo_barras => $producto): ?>

        <tr>
            <td><?php echo $codigo_barras ?></td>
            <td><?php echo $producto["nombre"] ?></td>
            <td><?php echo $producto["cantidad"] ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $codigo_barras ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $codigo_barras ?>">
                    <button type="button" class="dropdown-item btn-modificar-producto" value="<?php echo $codigo_barras ?>">Modificar</button>
                    <button type="button" class="dropdown-item btn-quitar-del-traspaso" value="<?php echo $codigo_barras ?>">Quitar del traspaso</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>