<?php

$codigo_barras = $_POST["codigo_barras"];

?>

<form class="form-item form-configurar-producto" method="POST" action="<?php echo 'agregar_al_carrito.php?codigo_barras=' . $codigo_barras ?>">

	<div class="form-row justify-content-center text-center">
	    <div class="form-group col-md-10">
	      <label for="cantidad_producto">Cantidad:</label>
	      <input type="number" class="form-control form-control-sm rounded-pill" id="cantidad_producto" name="cantidad_producto" min="1" value="1" required>
	    </div>
	</div>

    <div class="form-row justify-content-center text-center">
    	<div class="form-group col-md-10">
    		<label for="descuento_porcentaje_producto">Descuento en porcentaje:</label>
            <div class="input-group input-group-sm">
            	<input type="number" class="form-control" id="descuento_porcentaje_producto" name="descuento_porcentaje_producto" min="0" max="100" value="0" required>
        		<div class="input-group-prepend">
        			<div class="input-group-text">%</div>
        		</div>
      		</div>
    	</div>
  	</div>

	<div class="form-row justify-content-center text-center">
		<div class="form-group col-md-10">
  			<label for="descuento_dinero_producto">Descuento en dinero:</label>
  			<div class="input-group input-group-sm">
    			<input type="number" class="form-control" id="descuento_dinero_producto" name="descuento_dinero_producto" min="0" value="0" required>
    			<div class="input-group-prepend">
      				<div class="input-group-text">$</div>
    			</div>
  			</div>
		</div>
	</div>

	<div class="form-row justify-content-center">
    	<div class="form-group col-md-10">
      		<button type="submit" class="btn btn-primary btn-sm btn-block rounded-pill">Guardar</button>
    	</div>
    	<div class="form-group col-md-10">
      		<button type="button" class="btn btn-secondary btn-sm btn-block rounded-pill" data-dismiss="modal">Cancelar</button>
    	</div>
  	</div>

</form>