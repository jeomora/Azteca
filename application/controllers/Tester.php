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

		public function preciosBajosProveedor(){
		$query ="SELECT
			ct.id_cotizacion,
			p.codigo, p.nombre AS producto,
			UPPER(CONCAT(proveedor_m.first_name,' ',proveedor_m.last_name)) AS proveedor_minimo,
			ct.precio AS precio_minimo,
			ct.precio_promocion AS precio_promocion_minimo,
			ct.nombre AS promocion_minimo,
			ct.observaciones AS observaciones_minimo,
			ct.num_one AS num_one_minimo,
			ct.num_two AS num_two_minimo,
			ct.descuento AS descuento_minimo,
			UPPER(CONCAT(proveedor_s.first_name,' ',proveedor_s.last_name)) AS proveedor_siguiente,
			ps.precio AS precio_siguiente,
			ps.precio_promocion AS precio_promocion_siguiente,
			ps.nombre AS promocion_siguiente,
			ps.observaciones AS observaciones_siguiente,
			ps.num_one AS num_one_siguiente,
			ps.num_two AS num_two_siguiente,
			ps.descuento AS descuento_siguiente,
			UPPER(CONCAT(proveedor_max.first_name,' ',proveedor_max.last_name)) AS proveedor_maximo,
			ct_max.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio
			FROM  cotizaciones
				LEFT JOIN productos p ON cotizaciones.id_producto = p.id_producto
				JOIN cotizaciones ct
					ON ct.id_cotizacion = (SELECT
						p1.id_cotizacion 
						FROM cotizaciones p1
							WHERE cotizaciones.id_producto = p1.id_producto 
							AND p1.precio = (SELECT MIN(p2.precio)
							FROM cotizaciones p2
								WHERE p2.id_producto = p1.id_producto))
				JOIN cotizaciones ct_max ON ct_max.id_cotizacion = (SELECT ctz_max.id_cotizacion
					FROM cotizaciones ctz_max
					WHERE cotizaciones.id_producto = ctz_max.id_producto 
					AND ctz_max.precio = (SELECT MAX(ctz_precio_max.precio)
					FROM cotizaciones ctz_precio_max
					WHERE ctz_precio_max.id_producto = ctz_max.id_producto))
				LEFT JOIN cotizaciones ps 
					ON ps.id_cotizacion =(SELECT
						id_cotizacion 
						FROM cotizaciones
							WHERE cotizaciones.id_producto = ct.id_producto
							AND cotizaciones.precio >= ct.precio
							AND cotizaciones.id_cotizacion <> ct.id_cotizacion
							ORDER BY cotizaciones.precio ASC
							LIMIT 1)
				LEFT JOIN users proveedor_m ON ct.id_proveedor = proveedor_m.id
				LEFT JOIN users proveedor_s ON ps.id_proveedor = proveedor_s.id
				LEFT JOIN users proveedor_max ON ct_max.id_proveedor = proveedor_max.id
				GROUP BY ct.id_producto
				ORDER BY ct.id_producto DESC, ct.precio ASC;";
			return $this->db->query($query,FALSE)->result();
	}

}

/* End of file Tester.php */
/* Location: ./application/controllers/Tester.php */