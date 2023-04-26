<div class="modal fade" id="modal_crear_locker" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-locker-crear" action="../lockers/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="id_bodega_nuevo_locker">Bodega:</label>
              <select id="id_bodega_nuevo_locker" name="id_bodega_nuevo_locker" class="form-control form-control-sm rounded-pill">
                <?php cargarBodega() ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_locker">Nombre locker:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_locker" name="nombre_locker">
              <small id="nombre_locker_alerta" class="form-text text-danger font-weight-bold alerta">Este locker ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_seccion_nuevo_locker">Nombre sección:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_seccion_nuevo_locker" name="nombre_seccion_nuevo_locker">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_nivel_nuevo_locker">Nombre nivel:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_nivel_nuevo_locker" name="nombre_nivel_nuevo_locker">
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