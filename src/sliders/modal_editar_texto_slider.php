<div class="modal fade" id="modal_editar_texto_slider" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-texto-imagen-editar" action="editar_texto_slider_controller.php?redireccionar=false&id_slider=" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="encabezado_editar">Encabezado:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="encabezado_editar" name="encabezado_editar">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="encabezado_color_editar">Color encabezado:</label>
              <input type="color" class="form-control form-control-sm rounded-pill" id="encabezado_color_editar" name="encabezado_color_editar">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="subtitulo_editar">Subtítulo:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="subtitulo_editar" name="subtitulo_editar">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="subtitulo_color_editar">Color subtítulo:</label>
              <input type="color" class="form-control form-control-sm rounded-pill" id="subtitulo_color_editar" name="subtitulo_color_editar">
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