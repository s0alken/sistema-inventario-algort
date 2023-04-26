<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

foreach ($_POST as $item) {

  if (!is_array($item) && strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}

//comprobando si la categoría existe
$query = $pdo->prepare("
    SELECT * FROM categoria
    WHERE habilitada
    AND nombre_categoria = :nombre_categoria
    AND id_familia = :id_familia
    AND id_categoria != :id_categoria
    LIMIT 1");

$query->bindValue(":nombre_categoria", $_POST["nombre_categoria"], PDO::PARAM_STR);
$query->bindValue(":id_familia", $_POST["id_familia_nueva_categoria"], PDO::PARAM_INT);
$query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false,
                           "mensaje"  => "¡Ya existe la categoría " . $_POST["nombre_categoria"] . " dentro de la familia seleccionada!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("UPDATE categoria SET nombre_categoria = :nombre_categoria, id_familia = :id_familia WHERE id_categoria = :id_categoria");

	$query->bindValue(":nombre_categoria", $_POST["nombre_categoria"], PDO::PARAM_STR);
	$query->bindValue(":id_familia", $_POST["id_familia_nueva_categoria"], PDO::PARAM_INT);
    $query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);

	$query->execute();

    //editando medidas de la categoría
    if (isset($_POST["medidas_categoria"])) {

        //comprobando que nombres de medidas se hayan completado
        foreach ($_POST["medidas_categoria"] as $medida) {

            if (strlen($medida["nombre_medida"]) === 0) {

                throw new Exception("¡Completa todos los campos!");

            }

        }
    
        foreach ($_POST["medidas_categoria"] as $id_medida => $medida) {

            $query = $pdo->prepare("
                UPDATE medida SET
                nombre_medida = :nombre_medida,
                id_unidad_medida = :id_unidad_medida
                WHERE id_medida = :id_medida");

            $query->bindValue(":nombre_medida", $medida["nombre_medida"], PDO::PARAM_STR);
            $query->bindValue(":id_unidad_medida", $medida["id_unidad_medida"], PDO::PARAM_INT);
            $query->bindValue(":id_medida", $id_medida, PDO::PARAM_INT);

            $query->execute();

        }

    }

	//insertando medidas nuevas
    if (isset($_POST["medidas"])) {

        $id_nuevas_medidas = [];

        //comprobando que nombres de medidas se hayan completado
        foreach ($_POST["medidas"] as $medida) {

            if (strlen($medida["nombre_medida"]) === 0) {

                throw new Exception("¡Completa todos los campos!");

            }

        }
    
        foreach ($_POST["medidas"] as $medida) {

            $query = $pdo->prepare("
                INSERT INTO medida(
                id_categoria,
                nombre_medida,
                id_unidad_medida)
                VALUES (
                :id_categoria,
                :nombre_medida,
                :id_unidad_medida)");

            $query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);
            $query->bindValue(":nombre_medida", $medida["nombre_medida"], PDO::PARAM_STR);
            $query->bindValue(":id_unidad_medida", $medida["id_unidad_medida"], PDO::PARAM_INT);

            $query->execute();

            array_push($id_nuevas_medidas, $pdo->lastInsertId());

        }

        //obteniendo productos que pertenecen a la categoría
        $query = $pdo->prepare("
            SELECT
            id_producto
            FROM producto p
            INNER JOIN subcategoria sc ON sc.id_subcategoria = p.id_subcategoria
            WHERE sc.id_categoria = :id_categoria");

        $query->bindValue(":id_categoria", $_GET["id_categoria"], PDO::PARAM_INT);
        $query->execute();

        $id_productos = $query->fetchAll(PDO::FETCH_COLUMN);

        //enlazando nuevas medidas a los productos
        foreach ($id_productos as $id_producto) {

            foreach ($id_nuevas_medidas as $id_nueva_medida) {

                $query = $pdo->prepare("INSERT INTO medida_producto(id_producto, id_medida) VALUES (:id_producto, :id_medida)");

                $query->bindValue(":id_producto", $id_producto, PDO::PARAM_INT);
                $query->bindValue(":id_medida", $id_nueva_medida, PDO::PARAM_INT);

                $query->execute();

            }

        }

    }

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Categoría editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../categorias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>