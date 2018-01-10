<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizaciones";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getCotizaciones($where = []){
		$this->db->select("
			cotizaciones.id_cotizacion,
			cotizaciones.nombre AS promocion,
			cotizaciones.precio,
			cotizaciones.precio_factura,
			cotizaciones.num_one,
			cotizaciones.num_two,
			cotizaciones.descuento,
			cotizaciones.fecha_registro,
			cotizaciones.fecha_caduca,
			cotizaciones.existencias,
			cotizaciones.observaciones,
			u.first_name, u.last_name,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio_factura", "ASC");
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$result = $this->db->get()->result();
		if ($result) {
			if (is_array($where)) {
				return $result;
			} else {
				return array_shift($result);
			}
		} else {
			return false;
		}
	}

	public function preciosBajosProveedor(){
		$query ="SELECT
			p.nombre AS producto,
			UPPER(CONCAT(proveedor_m.first_name,' ',proveedor_m.last_name)) AS proveedor_minimo,
			ct.precio AS precio_minimo,
			ct.precio_factura AS precio_factura_minimo,
			ct.nombre AS promocion_minimo,
			ct.observaciones AS observaciones_minimo,
			ct.num_one AS num_one_minimo,
			ct.num_two AS num_two_minimo,
			ct.descuento AS descuento_minimo,
			UPPER(CONCAT(proveedor_s.first_name,' ',proveedor_s.last_name)) AS proveedor_siguiente,
			ps.precio AS precio_siguiente,
			ps.precio_factura AS precio_factura_siguiente,
			ps.nombre AS promocion_siguiente,
			ps.observaciones AS observaciones_siguiente,
			ps.num_one AS num_one_siguiente,
			ps.num_two AS num_two_siguiente,
			ps.descuento AS descuento_siguiente
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
				GROUP BY ct.id_producto
				ORDER BY ct.id_producto DESC, ct.precio ASC;";
			return $this->db->query($query,FALSE)->result();
	}

	public function productos_proveedor($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion, cotizaciones.precio,
			p.id_producto, UPPER(p.nombre) AS producto,
			u.id, UPPER(CONCAT(u.first_name,' ',u.last_name)) AS proveedor")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1);
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$result = $this->db->get()->result();
		if ($result) {
			if (is_array($where)) {
				return $result;
			} else {
				return array_shift($result);
			}
		} else {
			return false;
		}
	}

	public function comparaPrecios($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion,
			DATE_FORMAT(cotizaciones.fecha_registro, '%d-%m-%Y') as fecha,
			cotizaciones.nombre AS promocion,
			cotizaciones.precio,
			cotizaciones.id_producto,
			p.nombre AS producto,
			f.id_familia, f.nombre AS familia,
			pro.id, UPPER(CONCAT(pro.first_name,' ',pro.last_name)) AS proveedor")
		->from($this->TABLE_NAME)
		->join("users pro", $this->TABLE_NAME.".id_proveedor = pro.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1);
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		
		/*
		$comparativa_indexada = [];

		for ($i=0; $i<sizeof($comparativa); $i++) { 
			if(isset($comparativa_indexada[$i]->id_familia)){

			}else{
				$comparativa_indexada[$comparativa[$i]->id_familia] = [];
				$comparativa_indexada[$comparativa[$i]->id_familia]["familia"] = $comparativa[$i]->familia;
				$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"] = [];
			}

			$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"][$comparativa[$i]->id_cotizacion]["id_cotizacion"]=	$comparativa[$i]->id_cotizacion;
			$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"][$comparativa[$i]->id_cotizacion]["id_producto"]	=	$comparativa[$i]->id_producto;
			$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"][$comparativa[$i]->id_cotizacion]["precio"]		=	$comparativa[$i]->precio;
			$comparativa_indexada[$comparativa[$i]->id_familia]["diferencia"][$comparativa[$i]->id_cotizacion]["promocion"]	=	$comparativa[$i]->promocion;
		}
		return $comparativa_indexada;*/
		
		return $comparativa;

		// if($comparativa_indexada){
		// 	if(is_array($where)){
		// 		return $comparativa_indexada;
		// 	}else{
		// 		return array_shift($comparativa_indexada);
		// 	}
		// }else{
		// 	return false;
		// }
	}

	SELECT 
	ct_old.id_cotizacion AS id_cotizacion_old,
	WEEKOFYEAR(DATE_ADD(ct_old.fecha_actualiza,INTERVAL 1 WEEK)) AS week_befor,
	ct_old.nombre AS promocion_old,
	ct_old.precio AS precio_old,
	pro_old.nombre AS producto_old,
	fam_old.nombre AS familia_old,
	UPPER(CONCAT(pro.first_name,' ',pro.last_name)) AS proveedor,
	ct_new.id_cotizacion AS id_cotizacion_new,
	ct_new.nombre AS promocion_new,
	ct_new.precio AS precio_new,
	WEEKOFYEAR(ct_new.fecha_actualiza) AS week_now
FROM
  cotizaciones ct_new
	JOIN cotizaciones ct_old ON ct_old.id_producto = ct_new.id_producto
	JOIN users pro ON ct_new.id_proveedor = pro.id 
	JOIN productos pro_old ON ct_old.id_producto = pro_old.id_producto
	JOIN productos pro_new ON ct_new.id_producto = pro_new.id_producto
	JOIN familias fam_old ON pro_old.id_familia = pro_old.id_familia
	JOIN familias fam_new ON pro_new.id_familia = fam_new.id_familia
AND ct_old.fecha_actualiza IS NOT NULL
	WHERE ct_new.fecha_actualiza IS NOT NULL 
	AND WEEKOFYEAR(DATE_ADD(ct_old.fecha_actualiza,INTERVAL 1 WEEK)) = WEEKOFYEAR(ct_new.fecha_actualiza)
GROUP BY ct_new.id_cotizacion;







}

/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */