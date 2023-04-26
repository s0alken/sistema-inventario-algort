<div id="modal_subir_imagen" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        
        <form class="form-item form-imagen-crear" action="crear_controller.php?redireccionar=true" method="POST">

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10 text-center">
              <label class="btn btn-success btn-block btn-sm rounded-pill">
                <input class="d-none" type="file" id="imagenes_nuevas" name="imagenes_nuevas[]" multiple data-btn-imagen="subir-imagen" accept="image/*">
                <span class="subir-imagen"><i class="fas fa-upload mr-2 fa-fw"></i>Seleccionar</span>
              </label>
              <span class="text-danger">*Para seleccionar múltiples imágenes deben estar en el mismo directorio</span>
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