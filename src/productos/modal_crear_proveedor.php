<?php

//obteniendo id_region de la primera región ordenada alfabéticamente
$query = $pdo->query("SELECT id_region FROM region ORDER BY nombre_region LIMIT 1");

$id_region = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo id_comuna de la primera comuna ordenada alfabéticamente
$query = $pdo->prepare("SELECT id_comuna FROM comuna WHERE id_region = :id_region ORDER BY nombre_comuna LIMIT 1");

$query->bindValue(":id_region", $id_region, PDO::PARAM_INT);
$query->execute();

$id_comuna = $query->fetch(PDO::FETCH_COLUMN);

//obteniendo id_ciudad de la primera ciudad ordenada alfabéticamente
$query = $pdo->prepare("SELECT id_ciudad FROM ciudad WHERE id_comuna = :id_comuna ORDER BY nombre_ciudad LIMIT 1");

$query->bindValue(":id_comuna", $id_comuna, PDO::PARAM_INT);
$query->execute();

$id_ciudad = $query->fetch(PDO::FETCH_COLUMN);

?>

<div class="modal fade" id="modal_crear_proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-item form-persona-crear" action="../personas/crear_controller.php?tipo_persona=proveedor&redireccionar=false&configurar_producto=true&configurar_venta=false&configurar_cotizacion=false&configurar_actualizacion_stock=false" method="POST">

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-3">
              <label for="rut"><span class="text-danger">(*)</span> Rut:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="rut" name="rut">
              <small id="rut_incorrecto_alerta" class="form-text text-danger font-weight-bold alerta">Rut incorrecto</small>
              <small id="rut_alerta" class="form-text text-danger font-weight-bold alerta">Este Rut ya existe</small>
            </div>

            <div class="form-group col-md-3">
              <label for="nombre_persona"><span class="text-danger">(*)</span> Nombre:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="nombre_persona" name="nombre_persona">
              <small id="nombre_persona_alerta" class="form-text text-danger font-weight-bold alerta">Nombre inválido</small>
            </div>

            <div class="form-group col-md-6">
              <label for="giro">Giro:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="giro" name="giro">
              <small id="giro_alerta" class="form-text text-danger font-weight-bold alerta">Giro inválido</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-5">
              <label for="direccion"><span class="text-danger">(*)</span> Dirección:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="direccion" name="direccion">
              <small id="direccion_alerta" class="form-text text-danger font-weight-bold alerta">Dirección inválida</small>
            </div>

            <div class="form-group col-md-1">
              <label for="n_direccion"><span class="text-danger">(*)</span> N°:</label>
              <input type="number" class="form-control form-control-sm rounded-pill" id="n_direccion" name="n_direccion">
              <small id="n_direccion_alerta" class="form-text text-danger font-weight-bold alerta">Número inválido</small>
            </div>

            <div class="form-group col-md-2">
              <label for="id_region">Region:</label>
              <select id="id_region" name="id_region" class="form-control form-control-sm rounded-pill">
                <?php cargarRegion($id_region) ?>
              </select>
            </div>

            <div class="form-group col-md-2">
              <label for="id_comuna">Comuna:</label>
              <select id="id_comuna" name="id_comuna" class="form-control form-control-sm rounded-pill">
                <?php cargarComuna($id_comuna, $id_region) ?>
              </select>
            </div>

            <div class="form-group col-md-2">
              <label for="id_ciudad">Ciudad:</label>
              <select id="id_ciudad" name="id_ciudad" class="form-control form-control-sm rounded-pill">
                <?php cargarCiudad($id_ciudad, $id_comuna) ?>
              </select>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-4">
              <label for="telefono"><span class="text-danger">(*)</span> Teléfono:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="telefono" name="telefono">
              <small id="telefono_alerta" class="form-text text-danger font-weight-bold alerta">Teléfono incorrecto</small>
            </div>

            <div class="form-group col-md-4">
              <label for="telefono_alternativo">Teléfono 2:</label>
              <input type="text" class="form-control form-control-sm rounded-pill" id="telefono_alternativo" name="telefono_alternativo">
              <small id="telefono_contacto_alerta" class="form-text text-danger font-weight-bold alerta">Teléfono incorrecto</small>
            </div>

            <div class="form-group col-md-4">
              <label for="correo"><span class="text-danger">(*)</span> Correo:</label>
              <input type="email" class="form-control form-control-sm rounded-pill" id="correo" name="correo">
              <small id="correo_alerta" class="form-text text-danger font-weight-bold alerta">Correo incorrecto</small>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-check col-md-4 mb-3">
              <input type="checkbox" class="form-check-input" id="persona_alternativa" name="persona_alternativa" value="cliente">
              <label class="form-check-label" for="persona_alternativa">Habilitar como cliente</label>
            </div>
          </div>

          <div class="form-row justify-content-center text-center">
            <div class="form-group col-md-4">
              <span class="text-danger">(*) Campos obligatorios</span>
            </div>
          </div>

          <div class="form-row justify-content-center">
            <div class="form-group col-md-4">
              <button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
            </div>
            <div class="form-group col-md-4">
              <button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cancelar</button>
            </div>
          </div>

        </form>      
      </div>
    </div>
  </div>
</div>