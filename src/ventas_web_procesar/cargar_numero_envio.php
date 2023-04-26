<?php

require_once "../controller/conexion.php";

if (isset($_POST["carga_automatica"])) {

    cargarNumeroEnvio($_POST["id_compra_estado"], $_POST["id_compra"]);

}

function cargarNumeroEnvio($id_compra_estado, $id_compra){

	global $pdo;

	$query = $pdo->prepare("SELECT nombre_compra_estado AS estado FROM compra_estado WHERE id_compra_estado = :id_compra_estado");

	$query->bindValue(":id_compra_estado", $id_compra_estado, PDO::PARAM_INT);
	$query->execute();

	$estado = $query->fetch(PDO::FETCH_COLUMN); ?>

	<?php if ($estado === "entregado a transporte"):

		$query = $pdo->prepare("SELECT n_envio FROM compra_n_envio WHERE id_compra = :id_compra");

		$query->bindValue(":id_compra", $id_compra, PDO::PARAM_INT);
		$query->execute();

		$n_envio = $query->fetch(PDO::FETCH_COLUMN); ?>
	
		<div class="form-row mb-2">
			<label for="n_envio" class="col-sm-4 col-form-label-sm text-center text-sm-left m-0">N° envío:</label>
		    <div class="col-sm-5">
		      <input type="text" class="form-control form-control-sm rounded-pill" id="n_envio" name="n_envio" value="<?php echo $n_envio ?>">
		    </div> 
		</div>

	<?php endif ?>

<?php } ?>

