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

$stock_promocion = $_SESSION["sistema"]["promocion"]["carrito"][$codigo_barras]["stock_promocion"];

?>

<form class="form-item form-modificar-producto" method="POST" action="<?php echo 'modificar_producto.php?redireccionar=false&codigo_barras=' . $codigo_barras ?>">

	<div class="form-row justify-content-center text-center">
		<div class="form-group col-md-10">
			<label for="stock_promocion">Stock en promoción:</label>
			<input type="number" class="form-control form-control-sm rounded-pill" id="stock_promocion" name="stock_promocion" min="1" max="<?php echo $max ?>" value="<?php echo $stock_promocion ?>" required>
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