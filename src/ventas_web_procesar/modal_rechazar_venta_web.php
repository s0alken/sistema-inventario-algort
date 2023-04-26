<div class="modal fade" id="modal_rechazar_venta_web" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-venta-web-rechazar" action="<?php echo 'rechazar_venta_web.php?redireccionar=true&id_compra=' . $id_compra ?>" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-10">
              <label for="motivo_rechazo">Explica los motivos del rechazo al cliente:</label>
              <textarea id="motivo_rechazo" name="motivo_rechazo" class="form-control" rows="3" placeholder="Ej: no tenemos stock, etc."></textarea>
              <small id="motivo_rechazo_alerta" class="form-text text-danger font-weight-bold alerta">Has excedido el límite de 500 caracteres</small>
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-10">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Aceptar</button>
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