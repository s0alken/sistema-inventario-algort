<?php

//obteniendo el id_bodega de la primera bodega con secciones ordenada alfabéticamente
$query = $pdo->prepare("
  SELECT b.id_bodega FROM bodega b
  WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
  (SELECT 1 FROM seccion s
  INNER JOIN locker l ON l.id_locker = s.id_locker
  WHERE l.id_bodega = b.id_bodega
  AND s.habilitada)
  ORDER BY b.nombre_bodega
  LIMIT 1");

$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$id_bodega_nuevo_nivel = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_locker del primer locker con secciones ordenado alfabéticamente
$query = $pdo->prepare("
  SELECT l.id_locker FROM locker l
  WHERE l.id_bodega = :id_bodega AND l.habilitado AND EXISTS
  (SELECT 1 FROM seccion s
  WHERE s.id_locker = l.id_locker
  AND s.habilitada)
  ORDER BY l.nombre_locker
  LIMIT 1");

$query->bindValue(":id_bodega", $id_bodega_nuevo_nivel, PDO::PARAM_INT);
$query->execute();

$id_locker_nuevo_nivel = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_seccion de la primera sección ordenado alfabéticamente
$query = $pdo->prepare("SELECT id_seccion FROM seccion WHERE id_locker = :id_locker AND habilitada ORDER BY nombre_seccion LIMIT 1");

$query->bindValue(":id_locker", $id_locker_nuevo_nivel, PDO::PARAM_INT);
$query->execute();

$id_seccion_nuevo_nivel = $query->fetch(PDO::FETCH_COLUMN);

?>

<div class="modal fade" id="modal_crear_nivel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-nivel-crear" action="../niveles/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_bodega_nuevo_nivel">Bodega:</label>
              <select id="id_bodega_nuevo_nivel" name="id_bodega_nuevo_nivel" class="form-control form-control-sm rounded-pill">
                <?php cargarBodega($id_bodega_nuevo_nivel, false, true) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_locker_nuevo_nivel">Locker:</label>
              <select id="id_locker_nuevo_nivel" name="id_locker_nuevo_nivel" class="form-control form-control-sm rounded-pill">
                <?php cargarLocker($id_locker_nuevo_nivel, $id_bodega_nuevo_nivel, false, true) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_seccion_nuevo_nivel">Sección:</label>
              <select id="id_seccion_nuevo_nivel" name="id_seccion_nuevo_nivel" class="form-control form-control-sm rounded-pill">
                <?php cargarSeccion($id_seccion_nuevo_nivel, $id_locker_nuevo_nivel) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_nivel">Nombre nivel:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_nivel" name="nombre_nivel">
              <small id="nombre_nivel_alerta" class="form-text text-danger font-weight-bold alerta">Este nivel ya existe</small>
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