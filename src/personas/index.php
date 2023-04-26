<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_personas.php";

$tipo_persona = $_GET["tipo_persona"];

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-4 mb-3">
        <input type="text" name="myInput" id="myInput" class="form-control form-control-sm rounded-pill" placeholder="<?php echo 'Buscar ' . $tipo_persona ?>">
      </div>
      <div class="col-lg-4 mb-3">
        <a href="<?php echo 'crear.php?tipo_persona=' . $tipo_persona ?>"><button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill"><?php echo "Crear " . $tipo_persona ?></button></a>
      </div>
    </div>
    
    <!-- Tabla  -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered table-sm datatable" id="myTable">
        <thead>
          <tr>
            <th scope="col">Rut</th>
            <th scope="col">Nombre</th>
            <th scope="col">Dirección</th>
            <th scope="col">Teléfono</th>
            <th scope="col">Correo</th>
            <?php if ($tipo_persona === "cliente"): ?>
              <th scope="col">Puntos compra</th>
            <?php endif ?>
            <th scope="col">Opciones</th>
          </tr>
        </thead>
        <tbody>

        <?php

          cargarPersonas($tipo_persona);
          
        ?>

        </tbody>
      </table>
    </div>
    <!-- Fin Tabla  -->

  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>