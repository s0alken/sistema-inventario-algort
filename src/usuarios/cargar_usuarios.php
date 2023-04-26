<?php

require_once "../controller/conexion.php";

function cargarUsuarios() {

	global $pdo;

    $query = $pdo->prepare("
        SELECT
        u.id_usuario,
        u.nombre_usuario,
        p.nombre_permiso
        FROM usuario u
        INNER JOIN permiso p ON p.id_permiso = u.id_permiso
        WHERE u.habilitado
        AND u.id_usuario != :id_usuario
        AND p.nombre_permiso != 'superadministrador'
        ORDER BY u.nombre_usuario");

    $query->bindValue(":id_usuario", $_SESSION["sistema"]["usuario"]->id_usuario, PDO::PARAM_INT);
    $query->execute();

    $usuarios = $query->fetchAll(); ?>

    <?php foreach($usuarios as $usuario): ?>

        <tr data-id-usuario="<?php echo $usuario->id_usuario ?>">
            <td><?php echo $usuario->nombre_usuario ?></td>
            <td><?php echo $usuario->nombre_permiso ?></td>
            <td class="text-center">
                <div class="dropdown dropleft">
                  <button class="btn btn-primary btn-sm" type="button" id="<?php echo $usuario->id_usuario ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="<?php echo $usuario->id_usuario ?>">
                    <a href="<?php echo 'editar.php?id_usuario=' . $usuario->id_usuario ?>"><button class="dropdown-item" type="button">Cambiar permiso</button></a>
                    <button type="button" class="dropdown-item btn-eliminar-item" value="<?php echo $usuario->id_usuario ?>" data-item="usuario">Eliminar</button>
                  </div>
                </div>
            </td>
        </tr>

    <?php endforeach;

}

?>