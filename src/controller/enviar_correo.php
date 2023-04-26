<?php


function enviarCorreo($correo_destino = null, $asunto, $mensaje) {

	global $pdo;

	//obteniendo el correo de la empresa
	$query = $pdo->query("
        SELECT p.correo
        FROM empresa e
        INNER JOIN persona p ON p.id_persona = e.id_persona");

	$correo_empresa = $query->fetch(PDO::FETCH_COLUMN);

    $correo_destino = $correo_destino === null ? $correo_empresa : $correo_destino; 

    $headers = 'From: ' . $correo_empresa . "\r\n" .
               'Reply-To: ' . $correo_empresa . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    $correo_enviado = mail($correo_destino, $asunto, $mensaje, $headers);

    if (!$correo_enviado) {
    	
    	throw new Exception("Error al enviar correo");
    	
    }

}

?>