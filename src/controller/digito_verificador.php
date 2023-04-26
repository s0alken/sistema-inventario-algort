<?php 

function digitoVerificador($rut){

	$s = 1;

	for($m = 0; $rut != 0; $rut /= 10) {

		$s = ($s + $rut % 10 * (9 - $m++ % 6)) % 11;

	}

	return chr($s ? $s + 47 : 75);

}

?>