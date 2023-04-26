<div id="modal_subir_slider" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-imagen-crear" action="crear_controller.php?redireccionar=false" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="imagen_nueva">Imagen:</label>
              <label class="btn btn-success btn-block btn-sm rounded-pill">
                <input class="d-none" type="file" id="imagen_nueva" name="imagen_nueva" data-btn-imagen="subir-imagen" accept="image/*">
                <span class="subir-imagen"><i class="fas fa-upload mr-2 fa-fw"></i>Seleccionar</span>
              </label>
              <small class="text-muted">La resolución de la imágen debe ser de 1920x1080 para una óptima visibilidad de la misma en el sitio web</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="encabezado">Encabezado:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="encabezado" name="encabezado">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="encabezado_color">Color encabezado:</label>
              <input type="color" class="form-control form-control-sm rounded-pill" id="encabezado_color" name="encabezado_color">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="subtitulo">Subtítulo:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="subtitulo" name="subtitulo">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="subtitulo_color">Color subtítulo:</label>
              <input type="color" class="form-control form-control-sm rounded-pill" id="subtitulo_color" name="subtitulo_color">
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill" disabled>Guardar</button>
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