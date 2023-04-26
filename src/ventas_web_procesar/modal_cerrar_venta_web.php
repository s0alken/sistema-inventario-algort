<div class="modal fade" id="modal_cerrar_venta_web" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <div class="form-row justify-content-center text-center">
          <div class="form-group col-md-10">
            <div class="alert alert-danger rounded-pill mb-2 d-block" role="alert">
              ¡El estado seleccionado cierra la venta!
            </div>
            <label for="observaciones">Agregar observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3" placeholder="Máximo 300 caracteres"></textarea>
            <small id="observaciones_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 300 caracteres</small>
          </div>
        </div>

        <div class="form-row justify-content-center">
          <div class="form-group col-md-10">
            <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Cerrar venta</button>
          </div>
        </div>

        <div class="form-row justify-content-center">
          <div class="form-group col-md-10">
            <button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
   
      </div>
    </div>
  </div>
</div>