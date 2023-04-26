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

$id_producto = $_GET["id_producto"];

$campos_obligatorios = array("codigo_barras", "descripcion", "stock", "stock_critico", "precio_costo", "precio_venta");

//comprobando que se hayan completado los campos obligatorios
foreach ($campos_obligatorios as $campo) {

  if (strlen($_POST[$campo]) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa los campos obligatorios!"));
    exit();

  }

}

//comprobando si el código de barras está en uso
$query = $pdo->prepare("
	SELECT * FROM producto
	WHERE habilitado
	AND codigo_barras = :codigo_barras
	AND id_producto != :id_producto
	LIMIT 1");

$query->bindValue(":codigo_barras", $_POST["codigo_barras"], PDO::PARAM_STR);
$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
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

	echo json_encode(array("guardado" => false, "mensaje" => "¡El precio de venta es menor al precio de costo!"));
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
		UPDATE producto SET
		codigo_barras = :codigo_barras,
		codigo_maestro = :codigo_maestro,
		n_fabricante_1 = :n_fabricante_1,
		n_fabricante_2 = :n_fabricante_2,
		n_fabricante_3 = :n_fabricante_3,
		descripcion = :descripcion,
		precio_costo = :precio_costo,
		precio_venta = :precio_venta,
		stock_critico = :stock_critico,
		id_subcategoria = :id_subcategoria,
		id_marca = :id_marca,
		id_proveedor = :id_proveedor,
		id_procedencia = :id_procedencia,
		observaciones = :observaciones,
		caracteristicas_tecnicas = :caracteristicas_tecnicas,
		acumula_puntos = :acumula_puntos,
		habilitado_tienda = :habilitado_tienda
		WHERE id_producto = :id_producto");

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
	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);

	$query->execute();

	//actualizando stock
	$query = $pdo->prepare("UPDATE stock_producto SET stock = :stock WHERE id_producto = :id_producto AND id_sucursal = :id_sucursal");

	$query->bindValue(":stock", $_POST["stock"], PDO::PARAM_INT);
	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);

	$query->execute();

	//editando ubicación
	if (isset($_POST["ubicacion"])) {

		//comprobando si el producto tiene ubicación establecida
		$query = $pdo->prepare("
	    SELECT
	    u.id_nivel,
	    n.id_seccion,
	    s.id_locker,
	    l.id_bodega
	    FROM ubicacion u
	    INNER JOIN nivel n ON n.id_nivel = u.id_nivel
	    INNER JOIN seccion s ON s.id_seccion = n.id_seccion
	    INNER JOIN locker l ON l.id_locker = s.id_locker
	    INNER JOIN bodega b ON b.id_bodega = l.id_bodega
	    WHERE u.id_producto = :id_producto
	    AND b.id_sucursal = :id_sucursal");

	  $query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	  $query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
	  $query->execute();

	  $ubicacion_producto = $query->fetch();

	  //si el producto tiene ubicación, se actualiza
	  if ($ubicacion_producto) {

	  	//se actualiza solamente la ubicación de la sucursal actual 
	    $query = $pdo->prepare("
	    	UPDATE ubicacion u
	    	INNER JOIN nivel n ON n.id_nivel = u.id_nivel
		    INNER JOIN seccion s ON s.id_seccion = n.id_seccion
		    INNER JOIN locker l ON l.id_locker = s.id_locker
		    INNER JOIN bodega b ON b.id_bodega = l.id_bodega
	    	SET u.id_nivel = :id_nivel
	    	WHERE u.id_producto = :id_producto
	    	AND b.id_sucursal = :id_sucursal");

	    $query->bindValue(":id_nivel", $_POST["ubicacion"]["id_nivel"], PDO::PARAM_INT);
		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->bindValue(":id_sucursal", $_SESSION["sistema"]["sucursal"]->id_sucursal, PDO::PARAM_INT);
		
		$query->execute();

	  //si no tiene ubicación se hace el insert
	  } else { 

	  	$query = $pdo->prepare("INSERT INTO ubicacion(id_producto, id_nivel) VALUES (:id_producto, :id_nivel)");

		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->bindValue(":id_nivel", $_POST["ubicacion"]["id_nivel"], PDO::PARAM_INT);
		
		$query->execute();

	  }

	}

	//editar las medidas es un poco enredado, y a continuación un botón de muestra:

	//comprobando si el producto tiene medidas
	$query = $pdo->prepare("SELECT * FROM medida_producto WHERE id_producto = :id_producto");
	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	$query->execute();

	//hoy no se refactoriza, mañana sí...

	$medida_producto = $query->fetchAll();

	//obteniendo id_categoria del producto
	$query = $pdo->prepare("
		SELECT sc.id_categoria FROM producto p
		INNER JOIN subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
		WHERE id_producto = :id_producto");

	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	$query->execute();

	$id_categoria = $query->fetch()->id_categoria;

	//insertando medidas
	if (isset($_POST["medidas"])) {

		//comprobando que las medidas se hayan completado
        foreach ($_POST["medidas"] as $valor_medida) {

            if (strlen($valor_medida) === 0) {

                throw new Exception("¡Completa todos los campos!");

            }

        }

        //comprobando si el producto se cambió de categoría
        if ($_POST["id_categoria"] === $id_categoria) {
        	
        	foreach ($_POST["medidas"] as $id_medida => $valor_medida) {

				$query = $pdo->prepare("
					UPDATE medida_producto SET
					valor_medida = :valor_medida
					WHERE id_medida = :id_medida
					AND id_producto = :id_producto");

				$query->bindValue(":valor_medida", $valor_medida, PDO::PARAM_STR);
				$query->bindValue(":id_medida", $id_medida, PDO::PARAM_INT);
				$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
				
				$query->execute();

			}

        } else {

        	//borrando medidas de la categoría antigua
        	$query = $pdo->prepare("DELETE FROM medida_producto WHERE id_producto = :id_producto");
			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->execute();

        	foreach ($_POST["medidas"] as $id_medida => $valor_medida) {

				$query = $pdo->prepare("INSERT INTO medida_producto(id_producto, id_medida, valor_medida) VALUES (:id_producto, :id_medida, :valor_medida)");

				$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
				$query->bindValue(":id_medida", $id_medida, PDO::PARAM_INT);
				$query->bindValue(":valor_medida", $valor_medida, PDO::PARAM_STR);
				
				$query->execute();

			}

        }

	//si $_POST["medidas"] no está seteado
	//pero el producto tenía medidas
	//se borran todas las medidas
	} else if ($medida_producto){

		$query = $pdo->prepare("DELETE FROM medida_producto WHERE id_producto = :id_producto");
		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->execute();

	}

	//comprobando si el producto tiene compatibilidades
	$query = $pdo->prepare("SELECT id_producto_b FROM compatibilidad WHERE id_producto_a = :id_producto");
	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	$query->execute();

	$compatibilidad_producto = $query->fetchAll(PDO::FETCH_COLUMN);

	//insertando compatibilidad
	if (isset($_POST["compatibilidad"])) {

		$compatibilidad = json_decode($_POST["compatibilidad"]);

		$compatibilidad_guardar = array_diff($compatibilidad, $compatibilidad_producto);
		$compatibilidad_eliminar = array_diff($compatibilidad_producto, $compatibilidad);

		foreach ($compatibilidad_guardar as $compatibilidad) {

			$query = $pdo->prepare("INSERT INTO compatibilidad(id_producto_a, id_producto_b) VALUES (:id_producto_a, :id_producto_b)");

			$query->bindValue(":id_producto_a", $id_producto, PDO::PARAM_INT);
			$query->bindValue(":id_producto_b", $compatibilidad, PDO::PARAM_INT);
			
			$query->execute();

			//reinsertando compatibilidad con valores intercambiados (relación bidireccional)
			$query = $pdo->prepare("INSERT INTO compatibilidad(id_producto_a, id_producto_b) VALUES (:id_producto_a, :id_producto_b)");

			$query->bindValue(":id_producto_a", $compatibilidad, PDO::PARAM_INT);
			$query->bindValue(":id_producto_b", $id_producto, PDO::PARAM_INT);
			
			$query->execute();

		}

		foreach ($compatibilidad_eliminar as $compatibilidad) {

			$query = $pdo->prepare("
				DELETE FROM compatibilidad
				WHERE id_producto_a = :id_producto_1 AND id_producto_b = :compatibilidad_1
				OR id_producto_b = :id_producto_2 AND id_producto_a = :compatibilidad_2");

			$query->bindValue(":id_producto_1", $id_producto, PDO::PARAM_INT);
			$query->bindValue(":compatibilidad_1", $compatibilidad, PDO::PARAM_INT);
			$query->bindValue(":id_producto_2", $id_producto, PDO::PARAM_INT);
			$query->bindValue(":compatibilidad_2", $compatibilidad, PDO::PARAM_INT);
			$query->execute();

		}

	//si $_POST["compatibilidad"] no está seteado
	//pero el producto tenía compatibilidades
	//se borran todas las compatibilitades
	} else if ($compatibilidad_producto){

		$query = $pdo->prepare("DELETE FROM compatibilidad WHERE id_producto_a = :id_producto_a OR id_producto_b = :id_producto_b");
		$query->bindValue(":id_producto_a", $id_producto, PDO::PARAM_INT);
		$query->bindValue(":id_producto_b", $id_producto, PDO::PARAM_INT);
		$query->execute();

	}

	//comprobando si el producto tiene imágenes
	$query = $pdo->prepare("SELECT id_imagen FROM imagen_producto WHERE id_producto = :id_producto");
	$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
	$query->execute();

	$imagen_producto = $query->fetchAll(PDO::FETCH_COLUMN);

	//insertando imágenes preseleccionadas
	if (isset($_POST["imagenes"])) {

		$imagenes = json_decode($_POST["imagenes"]);

		$imagenes_guardar = array_diff($imagenes, $imagen_producto);
		$imagenes_eliminar = array_diff($imagen_producto, $imagenes);

		foreach ($imagenes_guardar as $id_imagen) {

			$query = $pdo->prepare("INSERT INTO imagen_producto(id_imagen, id_producto) VALUES (:id_imagen, :id_producto)");

			$query->bindValue(":id_imagen", $id_imagen, PDO::PARAM_INT);
			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->execute();

		}

		foreach ($imagenes_eliminar as $id_imagen) {

			$query = $pdo->prepare("DELETE FROM imagen_producto WHERE id_imagen = :id_imagen AND id_producto = :id_producto");
			$query->bindValue(":id_imagen", $id_imagen, PDO::PARAM_INT);
			$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
			$query->execute();

		}

	//si $_POST["imagenes"] no está seteado
	//pero el producto tenía imágenes
	//se borran todas las imágenes
	} else if ($imagen_producto){

		$query = $pdo->prepare("DELETE FROM imagen_producto WHERE id_producto = :id_producto");
		$query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
		$query->execute();

	}

	//subiendo imágenes nuevas
	//comprobando si se subieron imágenes
	if ($_FILES["imagenes_nuevas"]["error"][0] === UPLOAD_ERR_OK) {
		
		subirImagen($_FILES["imagenes_nuevas"], $id_producto);

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "Producto editado exitosamente";
    $_SESSION["sistema"]["redireccion"] = "../productos/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>