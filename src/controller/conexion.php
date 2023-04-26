<?php


//local
$dbname = "sistema";
$user = "root";
$pass = "";

/*
//hosting
$dbname = "mtalcacl_2m_sistema";
$user = "mtalcacl_mtalcacl";
$pass = "#Talca1-1608-.,"; 
*/

try {

	$pdo = new PDO("mysql:host=localhost; dbname=$dbname; charset=UTF8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

} catch (PDOException $e){

	die("Algo salió mal: " . $e->getMessage());

}

?>