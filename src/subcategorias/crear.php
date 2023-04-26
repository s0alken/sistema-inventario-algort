<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->query("
  SELECT f.id_familia FROM familia f
  WHERE f.habilitada AND EXISTS
  (SELECT 1 FROM categoria c
  WHERE c.id_familia = f.id_familia
  AND c.habilitada)
  ORDER BY f.nombre_familia
  LIMIT 1");

$id_familia_nueva_subcategoria = $query->fetch()->id_familia;

$query = $pdo->prepare("SELECT id_categoria from categoria WHERE id_familia = :id_familia AND habilitada ORDER BY nombre_categoria LIMIT 1");

$query->bindValue(":id_familia", $id_familia_nueva_subcategoria, PDO::PARAM_INT);
$query->execute();

$id_categoria_nueva_subcategoria = $query->fetch()->id_categoria;

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-subcategoria-crear" action="crear_controller.php?redireccionar=true&configurar_producto=false" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_familia_nueva_subcategoria">Familia:</label>
          <select id="id_familia_nueva_subcategoria" name="id_familia_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
            <?php cargarFamilia($id_familia_nueva_subcategoria, false, true) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_categoria_nueva_subcategoria">Categoría:</label>
          <select id="id_categoria_nueva_subcategoria" name="id_categoria_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
            <?php cargarCategoria($id_familia_nueva_subcategoria, $id_familia_nueva_subcategoria) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_subcategoria">Nombre subcategoría:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_subcategoria" name="nombre_subcategoria">
          <small id="nombre_subcategoria_alerta" class="form-text text-danger font-weight-bold alerta">Esta subcategoría ya existe</small>
        </div>
      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>

    </form>
    
    <?php

    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>