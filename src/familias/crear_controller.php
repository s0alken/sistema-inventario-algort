<?php

session_start();

//comprobando si usuario puede realizar esta operación
if (!$_SESSION["sistema"]["usuario"]->administrador) {
    
    echo json_encode(array("guardado" => false, "mensaje" => "No tienes permisos para realizar esta operación"));
    exit();

}

require_once "../controller/conexion.php";

//comprobando que se hayan completado los campos
foreach ($_POST as $item) {

  if (strlen($item) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa todos los campos!"));
    exit();

  }

}


//comprobando si la familia existe
$query = $pdo->prepare("SELECT * FROM familia WHERE habilitada AND nombre_familia = :nombre_familia LIMIT 1");
$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe la familia " . $_POST["nombre_familia"] . "!"));
	exit();

}

try {  

	$pdo->beginTransaction();

    //insertando familia
	$query = $pdo->prepare("INSERT INTO familia(nombre_familia) VALUES (:nombre_familia)");

	$query->bindValue(":nombre_familia", $_POST["nombre_familia"], PDO::PARAM_STR);

	$query->execute();

	$id_familia = $pdo->lastInsertId();

    if (filter_var($_GET["configurar_producto"], FILTER_VALIDATE_BOOLEAN)) {

        //insertando categoría
        $query = $pdo->prepare("INSERT INTO categoria(nombre_categoria, id_familia) VALUES (:nombre_categoria, :id_familia)");

        $query->bindValue(":nombre_categoria", $_POST["nombre_categoria_nueva_familia"], PDO::PARAM_STR);
        $query->bindValue(":id_familia", $id_familia, PDO::PARAM_INT);

        $query->execute();

        $id_categoria = $pdo->lastInsertId();

        //insertando subcategoría
        $query = $pdo->prepare("INSERT INTO subcategoria(nombre_subcategoria, id_categoria) VALUES (:nombre_subcategoria, :id_categoria)");

        $query->bindValue(":nombre_subcategoria", $_POST["nombre_subcategoria_nueva_familia"], PDO::PARAM_STR);
        $query->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);

        $query->execute();

        $id_subcategoria = $pdo->lastInsertId();

        //insertando medidas
        if (isset($_POST["medidas"])) {

            //comprobando que nombres de medidas se hayan completado
            foreach ($_POST["medidas"] as $key => $medida) {

                if (strlen($medida["nombre_medida"]) === 0) {

                    throw new Exception("¡Completa todos los campos!");

                }

            }
        
            foreach ($_POST["medidas"] as $key => $medida) {

                $query = $pdo->prepare("INSERT INTO medida(id_categoria, nombre_medida, id_unidad_medida) VALUES (:id_categoria, :nombre_medida, :id_unidad_medida)");

                $query->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
                $query->bindValue(":nombre_medida", $medida["nombre_medida"], PDO::PARAM_STR);
                $query->bindValue(":id_unidad_medida", $medida["id_unidad_medida"], PDO::PARAM_INT);

                $query->execute();

            }

        }
    	
    	$_SESSION["sistema"]["producto"]["id_familia"] = $id_familia;
        $_SESSION["sistema"]["producto"]["id_categoria"] = $id_categoria;
        $_SESSION["sistema"]["producto"]["id_subcategoria"] = $id_subcategoria;
        $_SESSION["sistema"]["producto"]["medidas"] = [];
    	
    }

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Familia creada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../familias/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>