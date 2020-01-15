<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reales_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "reales";
		$this->PRI_INDEX = "id_real";
	}

	public function getReales($where=[]){
		$this->db->select("r.id_real,p.nombre,r.precio FROM reales r LEFT JOIN productos p ON r.id_producto = p.id_producto WHERE WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(r.fecha_registro) = YEAR(CURDATE())");
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
				return $result;
			}
		} else {
			return false;
		}
	}


}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
