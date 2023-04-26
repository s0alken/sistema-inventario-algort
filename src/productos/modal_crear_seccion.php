<?php

//obteniendo el id_bodega de la primera bodega con lockers ordenada alfabéticamente
$query = $pdo->prepare("
  SELECT b.id_bodega FROM bodega b
  WHERE b.habilitada AND b.id_sucursal = :id_sucursal AND EXISTS
  (SELECT 1 FROM locker l
  WHERE l.id_bodega = b.id_bodega
  AND l.habilitado)
  ORDER BY b.nombre_bodega
  LIMIT 1");

$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$id_bodega_nueva_seccion = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo el id_locker del primer locker ordenado alfabéticamente
$query = $pdo->prepare("SELECT id_locker FROM locker WHERE id_bodega = :id_bodega AND habilitado ORDER BY nombre_locker LIMIT 1");

$query->bindValue(":id_bodega", $id_bodega_nueva_seccion, PDO::PARAM_INT);
$query->execute();

$id_locker_nueva_seccion = $query->fetch(PDO::FETCH_COLUMN);

?>

<div class="modal fade" id="modal_crear_seccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-seccion-crear" action="../secciones/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_bodega_nueva_seccion">Bodega:</label>
              <select id="id_bodega_nueva_seccion" name="id_bodega_nueva_seccion" class="form-control form-control-sm rounded-pill">
                <?php cargarBodega($id_bodega_nueva_seccion, false, false, true) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_locker_nueva_seccion">Locker:</label>
              <select id="id_locker_nueva_seccion" name="id_locker_nueva_seccion" class="form-control form-control-sm rounded-pill">
                <?php cargarLocker($id_locker_nueva_seccion, $id_bodega_nueva_seccion) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_seccion">Nombre sección:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_seccion" name="nombre_seccion">
              <small id="nombre_seccion_alerta" class="form-text text-danger font-weight-bold alerta">Esta sección ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_nivel_nueva_seccion">Nombre nivel:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_nivel_nueva_seccion" name="nombre_nivel_nueva_seccion">
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