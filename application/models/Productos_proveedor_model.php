<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_proveedor_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos_proveedor";
		$this->PRI_INDEX = "id_producto_proveedor";
	}

	public function getProductos_proveedor($where = []){
		$this->db->select("
			productos_proveedor.id_producto_proveedor,
			productos_proveedor.precio,
			DATE_FORMAT(productos_proveedor.fecha_registro, '%d-%m-%Y') AS fecha,
			UPPER(p.nombre) AS producto,
			p.codigo,
			u.first_name,
			u.last_name,
			UPPER(f.nombre) AS familia")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio", "ASC");
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

	public function productos_proveedor($where=[]){
		$this->db->select("
			productos_proveedor.id_producto_proveedor, productos_proveedor.precio,
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

}

/* End of file Productos_proveedor_model.php */
/* Location: ./application/models/Productos_proveedor_model.php */