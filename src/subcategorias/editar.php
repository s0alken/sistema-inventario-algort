<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";

$query = $pdo->prepare("
  SELECT * FROM subcategoria sc
  INNER JOIN categoria c ON c.id_categoria = sc.id_categoria 
  INNER JOIN familia f ON f.id_familia = c.id_familia
  WHERE sc.id_subcategoria = :id_subcategoria");

$query->bindValue(":id_subcategoria", $_GET["id_subcategoria"], PDO::PARAM_INT);
$query->execute();

$subcategoria = $query->fetch();

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-subcategoria-editar" action="<?php echo 'editar_controller.php?id_subcategoria=' . $_GET['id_subcategoria'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_familia_nueva_subcategoria">Familia:</label>
          <select id="id_familia_nueva_subcategoria" name="id_familia_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
            <?php cargarFamilia($subcategoria->id_familia, false, true) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_categoria_nueva_subcategoria">Categoría:</label>
          <select id="id_categoria_nueva_subcategoria" name="id_categoria_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
            <?php cargarCategoria($subcategoria->id_categoria, $subcategoria->id_familia) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_subcategoria">Nombre subcategoría:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_subcategoria" name="nombre_subcategoria" value="<?php echo $subcategoria->nombre_subcategoria ?>">
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