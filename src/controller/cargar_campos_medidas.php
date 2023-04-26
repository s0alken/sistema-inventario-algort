<?php

require_once "cargar_medidas_categoria.php";

//si se seteó carga_automatica, ejecutamos la función automaticamente.
//esto es para cuando necesitamos cargar los campos en tiempo real mientras elegimos
//familia, categoria, etc... (la variable carga_automatica va a estar seteada mediante AJAX)

if (isset($_POST["carga_automatica"])) {

	cargarCamposMedidas($_POST["id_categoria"], null, filter_var($_POST["configurar_producto"], FILTER_VALIDATE_BOOLEAN));

}

function cargarCamposMedidas($id_categoria, $valores = null, $configurar_producto = false){

  $class = $configurar_producto ? "form-control campo-producto" : "form-control";

	$medidas = cargarMedidasCategoria($id_categoria);

	$col_num = $medidas ? 12 / count($medidas) : 0;

	$col_break = "col-md-" . $col_num; ?>

	<?php foreach($medidas as $medida): ?>

	    <div class="<?php echo 'form-group ' . $col_break ?>">
        <label for="<?php echo $medida->nombre_medida ?>"><?php echo ucfirst($medida->nombre_medida) . ":" ?></label>
        <div class="input-group input-group-sm">
          <input type="number" class="<?php echo $class ?>" name="<?php echo 'medidas[' . $medida->id_medida . ']' ?>" value=<?php echo $valores ? $valores[$medida->id_medida] : "" ?>>
          <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $medida->abreviacion_unidad_medida ?></div>
          </div>
        </div>
        <small class="form-text text-danger font-weight-bold alerta">Dato inválido</small>
      </div>
	    
	<?php endforeach;

}

?>
