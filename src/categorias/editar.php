<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "../controller/cargar_select.php";
require_once "../controller/cargar_medidas_categoria.php";

$query = $pdo->prepare("SELECT * FROM categoria WHERE id_categoria = :id_categoria");
$query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);
$query->execute();

$categoria = $query->fetch();

$medidas_categoria = cargarMedidasCategoria($_GET["id_categoria"]);

?>

<!-- Contenido  -->
<div id="content">

  <div class="box-content">

    <form class="form-item form-categoria-editar" action="<?php echo 'editar_controller.php?id_categoria=' . $_GET['id_categoria'] . '&redireccionar=true' ?>" method="POST">

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="id_familia_nueva_categoria">Familia:</label>
          <select id="id_familia_nueva_categoria" name="id_familia_nueva_categoria" class="form-control form-control-sm rounded-pill">
            <?php cargarFamilia($categoria->id_familia) ?>
          </select>
        </div>
      </div>

      <div class="form-row justify-content-center text-center">
        <div class="form-group col-md-4">
          <label for="nombre_categoria">Nombre categoría:</label>
          <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_categoria" name="nombre_categoria" value="<?php echo $categoria->nombre_categoria ?>">
          <small id="nombre_categoria_alerta" class="form-text text-danger font-weight-bold alerta">Esta categoría ya existe</small>
        </div>
      </div>

      <?php foreach ($medidas_categoria as $medida_categoria): ?>

        <div class="form-row justify-content-center text-center">

          <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm rounded-pill" name="<?php echo 'medidas_categoria['. $medida_categoria->id_medida .'][nombre_medida]' ?>" placeholder="Nombre medida" value="<?php echo $medida_categoria->nombre_medida ?>">
          </div>

          <div class="form-group col-md-3">
            <select name="<?php echo 'medidas_categoria['. $medida_categoria->id_medida .'][id_unidad_medida]' ?>" class="form-control form-control-sm rounded-pill">
              <?php cargarUnidadMedida($medida_categoria->id_unidad_medida) ?>
            </select>
          </div>

          <div class="form-group col-md-2">
            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill btn-eliminar-item" value="<?php echo $medida_categoria->id_medida ?>" data-item="medida">Eliminar</button>
          </div>

        </div>
        
      <?php endforeach ?>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill btn_agregar_medida">Agregar medida</button>
        </div>
      </div>

      <div class="medidas">

      </div>

      <div class="form-row justify-content-center">
        <div class="form-group col-md-4">
          <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
        </div>
      </div>

    </form>

    <?php

    require_once "../partials/modal_eliminar_item.php";
    require_once "../partials/snackbar.php";

    ?>
    
  </div>
  
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>