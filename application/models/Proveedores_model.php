<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "users";
		$this->PRI_INDEX = "id";
	}
	
	public function getProveedores($where = []){
		$this->db->select("
			users.id,
			users.username,
			users.email,
			users.first_name,
			users.last_name,
			g.name AS tipo,
			ug.user_id, ug.group_id")
		->from($this->TABLE_NAME)
		->join("users_groups ug", $this->TABLE_NAME.".id = ug.user_id", "LEFT")
		->join("groups g", "ug.group_id = g.id", "LEFT")
		->where($this->TABLE_NAME.".active", 1)
		->where("ug.group_id", 2);
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

/* End of file Proveedores_model.php */
/* Location: ./application/models/Proveedores_model.php */