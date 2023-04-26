<?php

require_once "../controller/conexion.php";

function cargarSubcategorias() {

	global $pdo;

    $query = $pdo->query("
    SELECT
    subcategoria.id_subcategoria,
    subcategoria.nombre_subcategoria,
    categoria.nombre_categoria,
    familia.nombre_familia
    FROM subcategoria
    INNER JOIN categoria ON categoria.id_categoria = subcategoria.id_categoria
    INNER JOIN familia ON familia.id_familia = categoria.id_familia
    WHERE subcategoria.habilitada");

    $subcategorias = $query->fetchAll(); ?>

    <?php foreach($subcategorias as $subcategoria): ?>

        <tr data-id-subcategoria="<?php echo $subcategoria->id_subcategoria ?>">
            <td><?php echo $subcategoria->nombre_subcategoria ?></td>
            <td><?php echo $subcategoria->nombre_categoria ?></td>
            <td><?php echo $subcategoria->nombre_familia ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $subcategoria->id_subcategoria ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $subcategoria->id_subcategoria ?>">
                    <a href="<?php echo 'editar.php?id_subcategoria=' . $subcategoria->id_subcategoria ?>"><button class="dropdown-item" type="button">Editar</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $subcategoria->id_subcategoria ?>" data-item="subcategoria">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>