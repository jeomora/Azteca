<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos";
		$this->PRI_INDEX = "id_producto";
	}

	public function getProductos($where = []){
		$this->db->select("
			productos.id_producto,
			productos.nombre AS producto,
			productos.precio,
			productos.codigo,
			f.nombre AS familia")
		->from($this->TABLE_NAME)
		->join("familias f", $this->TABLE_NAME.".id_familia = f.id_familia", "LEFT")
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

/* End of file Productos_model.php */
/* Location: ./application/models/Productos_model.php */