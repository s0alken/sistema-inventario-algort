<?php

session_start();

require_once "../controller/conexion.php";
require_once "../controller/digito_verificador.php";

//obteniendo id de la empresa
$query = $pdo->query("SELECT id_persona FROM empresa");

$id_persona = $query->fetch(PDO::FETCH_COLUMN); 

$campos_obligatorios = array("rut_empresa", "nombre_persona", "nombre_fantasia", "direccion", "n_direccion", "id_ciudad", "telefono", "correo");

foreach ($campos_obligatorios as $campo) {

  if (strlen($_POST[$campo]) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa los campos obligatorios!"));
    exit();

  }

}

$rut_limpio = preg_replace("/[\.\-]/", "", $_POST["rut_empresa"]);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Rut de empresa inválido!"));
	exit();

}

//comprobando si persona existe
$query = $pdo->prepare("SELECT * FROM persona WHERE habilitada AND rut = :rut AND id_persona != :id_persona LIMIT 1");
$query->bindValue(":rut", $_POST["rut_empresa"], PDO::PARAM_STR);
$query->bindValue(":id_persona", $id_persona, PDO::PARAM_INT);
$query->execute();

if ($query->fetch()) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Ya existe el rut " . $_POST["rut_empresa"] . "!"));
	exit();

}

//datos transferencia
$campos_obligatorios = array("banco", "tipo_cuenta", "n_cuenta", "nombre", "rut_banco", "correo_banco");

foreach ($campos_obligatorios as $campo) {

  if (strlen($_POST[$campo]) === 0) {

    echo json_encode(array("guardado" => false, "mensaje" => "¡Completa los campos obligatorios!"));
    exit();

  }

}

$rut_limpio = preg_replace("/[\.\-]/", "", $_POST["rut_banco"]);

$rut = substr($rut_limpio, 0, -1);
$dv = strtoupper(substr($rut_limpio, strlen($rut_limpio) - 1));

//verificando si el rut es válido
if (digitoVerificador($rut) != $dv) {

	echo json_encode(array("guardado" => false, "mensaje" => "¡Rut de transferencia inválido!"));
	exit();

}

try {  

	$pdo->beginTransaction();

	$query = $pdo->prepare("
		UPDATE persona SET
		rut = :rut,
		nombre_persona = :nombre_persona,
		nombre_fantasia = :nombre_fantasia,
		giro = :giro,
		direccion = :direccion,
		n_direccion = :n_direccion,
		id_ciudad = :id_ciudad,
		telefono = :telefono,
		telefono_alternativo = :telefono_alternativo,
		correo = :correo
		WHERE id_persona = :id_persona");

	$query->bindValue(":rut", $_POST["rut_empresa"], PDO::PARAM_STR);
	$query->bindValue(":nombre_persona", $_POST["nombre_persona"], PDO::PARAM_STR);
	$query->bindValue(":nombre_fantasia", $_POST["nombre_fantasia"], PDO::PARAM_STR);
	$query->bindValue(":giro", $_POST["giro"], PDO::PARAM_STR);
	$query->bindValue(":direccion", $_POST["direccion"], PDO::PARAM_STR);
	$query->bindValue(":n_direccion", $_POST["n_direccion"], PDO::PARAM_STR);
	$query->bindValue(":id_ciudad", $_POST["id_ciudad"], PDO::PARAM_INT);
	$query->bindValue(":telefono", $_POST["telefono"], PDO::PARAM_STR);
	$query->bindValue(":telefono_alternativo", $_POST["telefono_alternativo"], PDO::PARAM_STR);
	$query->bindValue(":correo", $_POST["correo"], PDO::PARAM_STR);
	$query->bindValue(":id_persona", $id_persona, PDO::PARAM_INT);

	$query->execute();

	$query = $pdo->prepare("
		UPDATE datos_transferencia SET
		banco = :banco,
		tipo_cuenta = :tipo_cuenta,
		n_cuenta = :n_cuenta,
		nombre = :nombre,
		rut = :rut,
		correo = :correo");

	$query->bindValue(":banco", $_POST["banco"], PDO::PARAM_STR);
	$query->bindValue(":tipo_cuenta", $_POST["tipo_cuenta"], PDO::PARAM_STR);
	$query->bindValue(":n_cuenta", $_POST["n_cuenta"], PDO::PARAM_STR);
	$query->bindValue(":nombre", $_POST["nombre"], PDO::PARAM_STR);
	$query->bindValue(":rut", $_POST["rut_banco"], PDO::PARAM_STR);
	$query->bindValue(":correo", $_POST["correo_banco"], PDO::PARAM_INT);

	$query->execute();

	//actualizando opción de acumulación de puntos
	$query = $pdo->prepare("UPDATE personalizacion SET acumula_puntos = :acumula_puntos");

	$query->bindValue(":acumula_puntos", isset($_POST["acumula_puntos"]), PDO::PARAM_BOOL);

	$query->execute();

	//subiendo logo nuevo
	//comprobando si se subieron imágenes
	if ($_FILES["logo_empresa"]["error"] === UPLOAD_ERR_OK) {

		//comprobando si el logo existe
		if(file_exists("../img/logo.png")){

			//si existe se elimina
			if(!unlink("../img/logo.png")){

				throw new Exception("¡Error al cambiar imagen!");

			}

		}
		
		//logo empresa
		$file = $_FILES["logo_empresa"];

		//comprobando formatos permitidos
		if(explode("/", mime_content_type($file["tmp_name"]))[1] != "png"){

			throw new Exception("¡La imagen debe ser .png!");

		}

		//subiendo imagen al directorio
		if (!move_uploaded_file($file["tmp_name"], "../img/logo.png")){

			throw new Exception("¡Error al subir imagen!");

		}

	}

    $pdo->commit();

    $_SESSION["sistema"]["mensaje"] = "¡Empresa editada exitosamente!";
    $_SESSION["sistema"]["redireccion"] = "../inicio/";

    echo json_encode(array("guardado" => true, "redireccionar" => filter_var($_GET["redireccionar"], FILTER_VALIDATE_BOOLEAN)));

} catch (Exception $e) {

	$pdo->rollBack();

	echo json_encode(array("guardado" => false, "mensaje" => $e->getMessage()));

}

?>