<?php

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

<div class="modal fade" id="modal_crear_subcategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-subcategoria-crear" action="../subcategorias/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_familia_nueva_subcategoria">Familia:</label>
              <select id="id_familia_nueva_subcategoria" name="id_familia_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
                <?php cargarFamilia($id_familia_nueva_subcategoria, false, true) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_categoria_nueva_subcategoria">Categoría:</label>
              <select id="id_categoria_nueva_subcategoria" name="id_categoria_nueva_subcategoria" class="form-control form-control-sm rounded-pill">
                <?php cargarCategoria($id_familia_nueva_subcategoria, $id_familia_nueva_subcategoria) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_subcategoria">Nombre subcategoría:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_subcategoria" name="nombre_subcategoria">
              <small id="nombre_subcategoria_alerta" class="form-text text-danger font-weight-bold alerta">Esta subcategoría ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10">
              <button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cancelar</button>
            </div>
          </div>

        </form>       
      </div>
    </div>
  </div>
</div>