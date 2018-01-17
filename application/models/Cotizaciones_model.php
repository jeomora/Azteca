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
			cotizaciones.precio_promocion,
			cotizaciones.num_one,
			cotizaciones.num_two,
			cotizaciones.descuento,
			WEEKOFYEAR(DATE_ADD(cotizaciones.fecha_registro, INTERVAL 1 WEEK)) AS week_befor,
			cotizaciones.fecha_registro,
			cotizaciones.fecha_caduca,
			cotizaciones.existencias,
			cotizaciones.observaciones,
			UPPER(CONCAT(u.first_name,' ',u.last_name)) AS proveedor,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio_promocion", "ASC");
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

	public function comparaCotizaciones($where=[]){
		$this->db->select("ctz_first.id_cotizacion, WEEKOFYEAR(DATE_ADD(ctz_first.fecha_registro, INTERVAL 1 WEEK)) AS week_befor,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.first_name,' ',proveedor_first.last_name)) AS proveedor_first,
			ctz_first.precio AS precio_first,
			ctz_first.precio_promocion AS precio_promocion_first,
			ctz_first.nombre AS promocion_first,
			ctz_first.observaciones AS observaciones_first,
			UPPER(CONCAT(proveedor_next.first_name,' ',proveedor_next.last_name)) AS proveedor_next,
			WEEKOFYEAR(ctz_next.fecha_registro) AS week_next,
			ctz_next.precio AS precio_next,
			ctz_next.precio_promocion AS precio_promocion_next,
			ctz_next.nombre AS promocion_next,
			ctz_next.observaciones AS observaciones_next,
			ctz_max.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_precio_min.id_cotizacion FROM cotizaciones ctz_precio_min WHERE cotizaciones.id_producto = ctz_precio_min.id_producto 
				AND ctz_precio_min.precio = (SELECT MIN(ctz_min.precio) FROM cotizaciones ctz_min WHERE ctz_min.id_producto = ctz_precio_min.id_producto))")
		->join("cotizaciones ctz_max", "ctz_max.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto 
				AND ctz_max.precio = (SELECT MAX(ctz_precio_max.precio) FROM cotizaciones ctz_precio_max WHERE ctz_precio_max.id_producto = ctz_max.id_producto))")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
				AND cotizaciones.precio >= ctz_first.precio AND cotizaciones.id_cotizacion <> ctz_first.id_cotizacion ORDER BY cotizaciones.precio ASC LIMIT 1)", "LEFT")
		->join("users proveedor_first", "ctz_first.id_proveedor = proveedor_first.id", "LEFT")
		->join("users proveedor_next", "ctz_next.id_proveedor = proveedor_next.id", "LEFT")
		->join("users proveedor_max", "ctz_max.id_proveedor = proveedor_max.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("ctz_first.id_producto")
		->order_by("ctz_first.id_producto", "DESC")
		->order_by("ctz_first.precio", "ASC");
		if ($where !== NULL){
			if(is_array($where)){
				foreach($where as $field=>$value){
					if ($value !== NULL) {
						$this->db->where($field, $value);
					}
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) { 
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return array_shift($comparativaIndexada);
			}
		} else {
			return false;
		}
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
	//Creo que no se usará
	public function comparaPrecios($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion,
			pro.id, UPPER(CONCAT(pro.first_name,' ',pro.last_name)) AS proveedor,
			WEEKOFYEAR(DATE_ADD(ctz_befor.fecha_cambio, INTERVAL 1 WEEK)) AS week_befor,
			ctz_befor.nombre AS promocion_befor,
			ctz_befor.precio AS precio_befor,
			prod.codigo AS codigo,
			prod.nombre AS producto,
			fam.id_familia, fam.nombre AS familia,
			WEEKOFYEAR(cotizaciones.fecha_cambio) AS week_now,
			cotizaciones.nombre AS promocion_now,
			cotizaciones.precio_promocion AS precio_now")
		->from($this->TABLE_NAME)
		->join("cotizaciones ctz_befor", $this->TABLE_NAME.".id_producto = ctz_befor.id_producto", "LEFT")
		->join("users pro", $this->TABLE_NAME.".id_proveedor = pro.id", "LEFT")
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where("ctz_befor.fecha_cambio !=", NULL)
		->where("WEEKOFYEAR(DATE_ADD(ctz_befor.fecha_registro, INTERVAL 1 WEEK)) = WEEKOFYEAR({$this->TABLE_NAME}.fecha_cambio)")
		->group_by($this->TABLE_NAME.".id_cotizacion");
		if($where !== NULL){
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) { 
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_befor"]	=	$comparativa[$i]->precio_befor;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_befor"]	=	$comparativa[$i]->promocion_befor;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_now"]		=	$comparativa[$i]->precio_now;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_now"]	=	$comparativa[$i]->promocion_now;
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return array_shift($comparativaIndexada);
			}
		} else {
			return false;
		}
	}

}

/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */