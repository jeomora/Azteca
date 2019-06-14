<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "familias";
		$this->PRI_INDEX = "id_familia";
	}

	public function getFamilia($where = []){
		$this->db->select("
			familias.id_familia as ides,
			familias.nombre AS names")
		->from($this->TABLE_NAME)
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

/* End of file Familias_model.php */
/* Location: ./application/models/Familias_model.php */