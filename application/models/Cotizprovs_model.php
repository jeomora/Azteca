<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizprovs_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizprovs";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getCotizaciones($where = []){
		$this->db->select("
			ctr.id_cotizacion,
			ctr.id_proveedor,
			ctr.nombre AS promocion,
			ctr.precio,
			ctr.precio_promocion,
			ctr.num_one,
			ctr.num_two,
			ctr.descuento,
			ctr.fecha_registro,
			ctr.fecha_caduca,
			ctr.existencias,
			ctr.observaciones,
			UPPER(u.nombre) AS proveedor,
			p.nombre AS producto")
		->from($this->TABLE_NAME." ctr")
		->join("usuarios u", "ctr.id_proveedor = u.id_usuario", "LEFT")
		->join("productos p", "ctr.id_producto = p.id_producto", "LEFT")
		->where("ctr.estatus", 1)
		->group_by("ctr.id_producto")
		->order_by("ctr.precio_promocion", "ASC");
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
	public function getAllCotizaciones($where = []){
		$this->db->select("
			ctr.id_cotizacion,
			ctr.id_proveedor,
			ctr.id_producto,
			ctr.precio,
			ctr.precio_promocion,
			ctr.num_one,
			ctr.num_two,
			ctr.descuento,
			ctr.fecha_registro,
			ctr.observaciones,
			UPPER(u.nombre) AS proveedor,
			p.nombre AS producto,p.codigo")
		->from($this->TABLE_NAME." ctr")
		->join("usuarios u", "ctr.id_proveedor = u.id_usuario", "LEFT")
		->join("productos p", "ctr.id_producto = p.id_producto", "LEFT")
		->group_by("ctr.id_producto")
		->order_by("p.nombre", "ASC");
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

	
}





/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */
