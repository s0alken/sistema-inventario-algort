<?php

require_once "../partials/header.php";
require_once "../partials/navbar.php";
require_once "../partials/sidebar.php";
require_once "cargar_inventario.php";

?>

<!-- Contenido  -->
<div id="content">
  <div class="box-content">

    <div class="form-row">
      <div class="col-lg-3 mb-3 d-flex align-items-end">
        <button type="button" id="btn_exportar" class="btn btn-primary btn-sm btn-block rounded-pill">Exportar a Excel</button>
      </div>
    </div>
    
    <div id="resultados"><?php cargarInventario() ?></div>
    
  </div>
</div>
<!-- Fin Contenido  -->

<?php

require_once "../partials/footer.php";

?>