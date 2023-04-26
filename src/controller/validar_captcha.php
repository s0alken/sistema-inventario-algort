<?php

function captchaValido($response){

	/*
	$url = "https://www.google.com/recaptcha/api/siteverify";
	$data = array("secret" => "6LeTfQcaAAAAABvwt2PfQnEBOu9PG4wuUX_vVWvq", "response" => $response);

	$options = array(
	    "http" => array(
	        "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
	        "method"  => "POST",
	        "content" => http_build_query($data)
	    )
	);

	$context  = stream_context_create($options);
	$result = json_decode(file_get_contents($url, false, $context));

	return $result->success;
	*/

	$url = "https://www.google.com/recaptcha/api/siteverify";

	$fields = [
	    "secret"   => "6LeTfQcaAAAAABvwt2PfQnEBOu9PG4wuUX_vVWvq",
	    "response" => $response
	];

	//url-ify the data for the POST
	$fields_string = http_build_query($fields);

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	//So that curl_exec returns the contents of the cURL; rather than echoing it
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

	//execute post
	$response = curl_exec($ch);

	//clse connection
	curl_close($ch);

	$result = json_decode($response);

	return $result->success;

}

?>