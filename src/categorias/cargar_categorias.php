<?php

require_once "../controller/conexion.php";

function cargarCategorias() {

	global $pdo;

    $query = $pdo->query("
    SELECT
    categoria.id_categoria,
    categoria.nombre_categoria,
    familia.nombre_familia
    FROM categoria
    INNER JOIN familia ON familia.id_familia = categoria.id_familia
    WHERE categoria.habilitada");

    $categorias = $query->fetchAll(); ?>

    <?php foreach($categorias as $categoria): ?>

        <tr data-id-categoria="<?php echo $categoria->id_categoria ?>">
            <td><?php echo $categoria->nombre_categoria ?></td>
            <td><?php echo $categoria->nombre_familia ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $categoria->id_categoria ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $categoria->id_categoria ?>">
                    <a href="<?php echo 'editar.php?id_categoria=' . $categoria->id_categoria ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $categoria->id_categoria ?>" data-item="categoria">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>