<div id="modal_cambiar_imagen" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        
        <form class="form-item form-imagen-cambiar" action="cambiar_imagen_controller.php?redireccionar=false&id_slider=" method="POST">

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10 text-center">
              <label class="btn btn-success btn-block btn-sm rounded-pill">
                <input class="d-none" type="file" id="imagen_cambiar" name="imagen_cambiar" data-btn-imagen="cambiar-imagen" accept="image/*">
                <span class="cambiar-imagen"><i class="fas fa-upload mr-2 fa-fw"></i>Seleccionar</span>
              </label>
              <small class="text-muted">La resolución de la imágen debe ser de 1920x1080 para una óptima visibilidad de la misma en el sitio web</small>
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="col-md-5 mb-3 mb-md-0">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Aceptar</button>
            </div>
            <div class="col-md-5">
              <button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cerrar</button>
            </div>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>