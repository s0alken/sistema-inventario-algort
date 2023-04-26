<div class="modal fade" id="modal_crear_categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-categoria-crear" action="../categorias/crear_controller.php?redireccionar=false&configurar_producto=true" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="id_familia_nueva_categoria">Familia:</label>
              <select id="id_familia_nueva_categoria" name="id_familia_nueva_categoria" class="form-control form-control-sm rounded-pill">
                <?php cargarFamilia() ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="nombre_categoria">Nombre categoría:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_categoria" name="nombre_categoria">
              <small id="nombre_categoria_alerta" class="form-text text-danger font-weight-bold alerta">Esta categoría ya existe</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="nombre_subcategoria_nueva_categoria">Nombre subcategoría:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_subcategoria_nueva_categoria" name="nombre_subcategoria_nueva_categoria">
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