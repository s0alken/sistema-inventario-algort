<?php

require_once "../controller/conexion.php";

$codigo_barras = $_POST["codigo_barras"];

$query = $pdo->prepare("SELECT precio_costo, precio_venta FROM producto WHERE habilitado AND codigo_barras = :codigo_barras");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->execute();

$producto = $query->fetch();

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
    		<label for="precio_costo">Valor costo neto:</label>
            <input type="number" class="form-control form-control-sm rounded-pill" id="precio_costo" name="precio_costo" min="0" value="<?php echo $producto->precio_costo ?>" required>
        </div>
    </div>

	<div class="form-row justify-content-center text-center">
    	<div class="form-group col-md-10">
    		<label for="precio_venta">Valor venta con IVA:</label>
            <input type="number" class="form-control form-control-sm rounded-pill" id="precio_venta" name="precio_venta" min="0" value="<?php echo $producto->precio_venta ?>" required>
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