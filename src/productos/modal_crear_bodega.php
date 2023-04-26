<div class="modal fade" id="modal_crear_bodega" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-bodega-crear" action="../bodegas/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_bodega">Nombre bodega:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_bodega" name="nombre_bodega">
              <small id="nombre_bodega_alerta" class="form-text text-danger font-weight-bold alerta">Esta bodega ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_locker_nueva_bodega">Nombre locker:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_locker_nueva_bodega" name="nombre_locker_nueva_bodega">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_seccion_nueva_bodega">Nombre sección:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_seccion_nueva_bodega" name="nombre_seccion_nueva_bodega">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="nombre_nivel_nueva_bodega">Nombre nivel:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_nivel_nueva_bodega" name="nombre_nivel_nueva_bodega">
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