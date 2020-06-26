<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tester extends CI_Controller {

	public function index(){
		$a = 1;
		$b = 1; 
		echo "0 - 1 - ";
		for ($i = 1; $i <= 15; $i++) {
			echo "{$a} - ";
			$a = $a + $b;
			$b = $a - $b;
		}
	}

	public function fibonacci($num){
		if($num>1){
			return fibonacci($num-1) + fibonacci($num-2);  //función recursiva
		}else if ($num==1) {//Caso base 1
			return 1;
		}else if ($num==0){//Caso base 0
			return 0;
		}else{
			echo "{Debes ingresar un tamaño mayor o igual a 1}";
			return -1; 
		}
	}

	public function numeros(){
		$val = '"$1,234.56"';
		$numero = preg_replace('/^[[:digit:]]+$/', '', $val);
		$num_pesos = str_replace('$', '', $numero);
		$num_coma = str_replace(',', '', $num_pesos);
		$restado = str_replace('"', '', $num_coma);
		echo "Número = {$numero}
			</br>Número pesos ={$num_pesos}
			</br>Número coma ={$num_coma}
			</br>Formateado ={$restado}
			</br>";
			echo str_replace(['\"',',','$'], '', '"$1,013,013,013,013,013.10"')."</br>";
			echo str_replace(['\"',',','$'], '', str_replace('"', '', '"$1,013.10"'))."</br>";
	}

	public function pruebas(){
		$key = 'APGoyQGOKAR5iXQ1wiO6i4jNczeMV7Sg';
		$cadena = 'ochoa25';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		echo "Encriptado: {$encrypted} <br>";
		echo "Desencriptar: {$decrypted} <br>";
	}


}

/* End of file Tester.php */
/* Location: ./application/controllers/Tester.php */