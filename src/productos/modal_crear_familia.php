<div class="modal fade" id="modal_crear_familia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-familia-crear" action="../familias/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="nombre_familia">Nombre familia:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_familia" name="nombre_familia">
              <small id="nombre_familia_alerta" class="form-text text-danger font-weight-bold alerta">Esta familia ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="nombre_categoria_nueva_familia">Nombre categoría:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_categoria_nueva_familia" name="nombre_categoria_nueva_familia">
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="nombre_subcategoria_nueva_familia">Nombre subcategoría:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_subcategoria_nueva_familia" name="nombre_subcategoria_nueva_familia">
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-5">
              <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill btn_agregar_medida">Agregar medida</button>
            </div>
          </div>

          <div class="medidas">

          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-5">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
            </div>
            <div class="form-group col-md-5">
              <button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cancelar</button>
            </div>
          </div>

        </form>        
      </div>
    </div>
  </div>
</div>