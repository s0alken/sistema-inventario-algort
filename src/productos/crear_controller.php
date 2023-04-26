<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";
require_once "../controller/cargar_medidas_categoria.php";
require_once "../controller/subir_imagen.php";
require_once "../controller/validar_largo.php";

$campos_obligatorios = array("codigo_barras", "descripcion", "stock", "stock_critico", "precio_costo", "precio_venta");

//comprobando que se hayan completado los campos obligatorios
foreach ($campos_obligatorios as $campo) {

  if (strlen($_POST[$campo]) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa los campos obligatorios!"));
    exit();

  }

}

//comprobando si el código de barras está en uso
$query = $pdo->prepare("SELECT * FROM producto WHERE codigo_barras = :codigo_barras AND habilitado LIMIT 1");
$query->bindValue(":codigo_barras", $_POST["codigo_barras"], PDO::PARAM_STR);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El código de barras ya está en uso!"));
	exit();

}

//comprobando que los precios no sean menores a cero
if (intval($_POST["precio_costo"]) < 0 || intval($_POST["precio_venta"]) < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Los precios no deben ser menores a cero!"));
	exit();

}

//comprobando que el precio de venta no sea menor al precio de costo
if (intval($_POST["precio_costo"]) > intval($_POST["precio_venta"])) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡El precio de venta no debe ser menor al precio de costo!"));
	exit();

}

//comprobando que los valores de stock no sean menores a cero
if (intval($_POST["stock"]) < 0 || intval($_POST["stock_critico"]) < 0) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Los valores de stock no deben ser menores a cero!"));
	exit();

}

//comprobando que las observaciones no excedan los 1500 caracteres
if (largoExcedido($_POST["observaciones"], 1500)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las observaciones no deben exceder los 1.500 caracteres!"));
	exit();

}

//comprobando que las características técnicas no excedan los 1500 caracteres
if (largoExcedido($_POST["caracteristicas_tecnicas"], 1500)) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Las características técnicas no deben exceder los 1.500 caracteres!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		INSERT INTO producto(
		codigo_barras,
		codigo_maestro,
		n_fabricante_1,
		n_fabricante_2,
		n_fabricante_3,
		descripcion,
		precio_costo,
		precio_venta,
		stock_critico,
		id_subcategoria,
		id_marca,
		id_proveedor,
		id_procedencia,
		observaciones,
		caracteristicas_tecnicas,
		acumula_puntos,
		habilitado_tienda)
		VALUES (
		:codigo_barras,
		:codigo_maestro,
		:n_fabricante_1,
		:n_fabricante_2,
		:n_fabricante_3,
		:descripcion,
		:precio_costo,
		:precio_venta,
		:stock_critico,
		:id_subcategoria,
		:id_marca,
		:id_proveedor,
		:id_procedencia,
		:observaciones,
		:caracteristicas_tecnicas,
	    :acumula_puntos,
		:habilitado_tienda)");

	$query->bindValue(":codigo_barras", $_POST["codigo_barras"], PDO::PARAM_STR);
	$query->bindValue(":codigo_maestro", $_POST["codigo_maestro"], PDO::PARAM_STR);
	$query->bindValue(":n_fabricante_1", $_POST["n_fabricante_1"], PDO::PARAM_STR);
	$query->bindValue(":n_fabricante_2", $_POST["n_fabricante_2"], PDO::PARAM_STR);
	$query->bindValue(":n_fabricante_3", $_POST["n_fabricante_3"], PDO::PARAM_STR);
	$query->bindValue(":descripcion", $_POST["descripcion"], PDO::PARAM_STR);
	$query->bindValue(":precio_costo", $_POST["precio_costo"], PDO::PARAM_INT);
	$query->bindValue(":precio_venta", $_POST["precio_venta"], PDO::PARAM_INT);
	$query->bindValue(":stock_critico", $_POST["stock_critico"], PDO::PARAM_INT);
	$query->bindValue(":id_subcategoria", $_POST["id_subcategoria"], PDO::PARAM_INT);
	$query->bindValue(":id_marca", $_POST["id_marca"], PDO::PARAM_INT);
	$query->bindValue(":id_proveedor", $_POST["id_proveedor"], PDO::PARAM_INT);
	$query->bindValue(":id_procedencia", $_POST["id_procedencia"], PDO::PARAM_INT);
	$query->bindValue(":observaciones", $_POST["observaciones"], PDO::PARAM_STR);
	$query->bindValue(":caracteristicas_tecnicas", $_POST["caracteristicas_tecnicas"], PDO::PARAM_STR);
	$query->bindValue(":acumula_puntos", isset($_POST["acumula_puntos"]), PDO::PARAM_BOOL);
	$query->bindValue(":habilitado_tienda", isset($_POST["habilitado_tienda"]), PDO::PARAM_BOOL);

	$query->execute();

	$id_producto = $pdo->lastInsertId();

	//obteniendo todas las sucursales
	$query = $pdo->query("SELECT id_sucursal FROM sucursal");

	$id_sucursales = $query->fetchAll(PDO::FETCH_COLUMN);

	//insertando stock en todas las sucursales
	foreach ($id_sucursales as $id_sucursal) {

		$stock = $id_sucursal === $_SESSION["sistema"]["sucursal"]->id_sucursal ? $_POST["stock"] : 0;
		
		$query = $pdo->prepare("INSERT INTO stock_producto(id_producto, stock, id_sucursal) VALUES (:id_producto, :stock, :id_sucursal)");

		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->bindValue(":stock", $stock, PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $id_sucursal, PDO::PARAM_INT);

		$query->execute();

	}

	//insertando ubicación
	if (isset($_POST["ubicacion"])) {

		$query = $pdo->prepare("INSERT INTO ubicacion(id_producto, id_nivel) VALUES (:id_producto, :id_nivel)");

		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->bindValue(":id_nivel", $_POST["ubicacion"]["id_nivel"], PDO::PARAM_INT);
		
		$query->execute();

	}

	//insertando medidas
	if (isset($_POST["medidas"])) {

		//comprobando que las medidas se hayan completado
        foreach ($_POST["medidas"] as $valor_medida) {

            if (strlen($valor_medida) === 0) {

                throw new Exception("¡Completa todos los campos!");

            }

        }

		foreach ($_POST["medidas"] as $id_medida => $valor_medida) {

			$query = $pdo->prepare("INSERT INTO medida_producto(id_producto, id_medida, valor_medida) VALUES (:id_producto, :id_medida, :valor_medida)");

			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->bindValue(":id_medida", $id_medida, PDO::PARAM_INT);
			$query->bindValue(":valor_medida", $valor_medida, PDO::PARAM_STR);
			
			$query->execute();

		}

	}

	//insertando compatibilidad
	if (isset($_POST["compatibilidad"])) {

		$compatibilidad = json_decode($_POST["compatibilidad"]);

		foreach ($compatibilidad as $id_compatibilidad) {

			$query = $pdo->prepare("INSERT INTO compatibilidad(id_producto_a, id_producto_b) VALUES (:id_producto_a, :id_producto_b)");

			$query->bindValue(":id_producto_a", $id_producto, PDO::PARAM_INT);
			$query->bindValue(":id_producto_b", $id_compatibilidad, PDO::PARAM_INT);
			
			$query->execute();

			//reinsertando compatibilidad con valores intercambiados (relación bidireccional)
			$query = $pdo->prepare("INSERT INTO compatibilidad(id_producto_a, id_producto_b) VALUES (:id_producto_a, :id_producto_b)");

			$query->bindValue(":id_producto_a", $id_compatibilidad, PDO::PARAM_INT);
			$query->bindValue(":id_producto_b", $id_producto, PDO::PARAM_INT);
			
			$query->execute();

		}
	}

	//insertando imágenes preseleccionadas
	if (isset($_POST["imagenes"])) {

		$imagenes = json_decode($_POST["imagenes"]);
		
		foreach ($imagenes as $id_imagen) {

			$query = $pdo->prepare("INSERT INTO imagen_producto(id_imagen, id_producto) VALUES (:id_imagen, :id_producto)");

			$query->bindValue(":id_imagen", $id_imagen, PDO::PARAM_INT);
			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->execute();

		}
	}

	//subiendo imágenes nuevas
	//comprobando si se subieron imágenes
	if ($_FILES["imagenes_nuevas"]["error"][0] === UPLOAD_ERR_OK) {
		
		subirImagen($_FILES["imagenes_nuevas"], $id_producto);

	}

    $pdo->commit();

    unset($_SESSION["sistema"]["producto"]);

    $_SESSION["sistema"]["mensaje"] = "¡Producto creado exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../productos/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>