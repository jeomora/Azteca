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

}

/* End of file Tester.php */
/* Location: ./application/controllers/Tester.php */