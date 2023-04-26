<?php

require_once "../controller/conexion.php";

session_start();

$codigo_barras = $_POST["codigo_barras"];

$query = $pdo->prepare("
	SELECT sp.stock FROM stock_producto sp
	INNER JOIN producto p ON p.id_producto = sp.id_producto
	WHERE p.habilitado
	AND p.codigo_barras = :codigo_barras
	AND sp.id_sucursal = :id_sucursal");

$query->bindValue(":codigo_barras", $codigo_barras, PDO::PARAM_STR);
$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
$query->execute();

$max = $query->fetch(PDO::FETCH_COLUMN);

$producto = $_SESSION["sistema"]["venta"]["carrito"][$codigo_barras];

$cantidad             = $producto["cantidad"];
$descuento_porcentaje = $producto["descuento_porcentaje"];
$descuento_dinero     = $producto["descuento_dinero"];

?>

<form class="form-item form-modificar-producto" method="POST" action="<?php echo 'modificar_producto.php?redireccionar=false&codigo_barras=' . $codigo_barras ?>">

	<div class="form-row justify-content-center text-center">
	    <div class="form-group col-md-10">
	      <label for="cantidad_producto">Cantidad:</label>
	      <input type="number" class="form-control form-control-sm rounded-pill" id="cantidad_producto" name="cantidad_producto" min="1" max="<?php echo $max ?>" value="<?php echo $cantidad ?>" required>
	    </div>
	</div>

    <div class="form-row justify-content-center text-center">
    	<div class="form-group col-md-10">
    		<label for="descuento_porcentaje_producto">Descuento en porcentaje:</label>
            <div class="input-group input-group-sm">
            	<input type="number" class="form-control" id="descuento_porcentaje_producto" name="descuento_porcentaje_producto" min="0" max="100" value="<?php echo $descuento_porcentaje ?>" required>
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
    			<input type="number" class="form-control" id="descuento_dinero_producto" name="descuento_dinero_producto" min="0" value="<?php echo $descuento_dinero ?>" required>
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