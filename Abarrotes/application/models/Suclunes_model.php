<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suclunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "suc_lunes";
		$this->PRI_INDEX = "id_sucursal";
	}


	public function getByOrder($where=[]){
		$this->db->select("*")
		->from("suc_lunes")
		->order_by($this->TABLE_NAME.".orden", "ASC");;
		
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
	public function getCount($where=[]){
		$this->db->select("count(*) as total from suc_lunes")
		->order_by($this->TABLE_NAME.".orden", "ASC");;
		
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

/* End of file Sucursales_model.php */
/* Location: ./application/models/Sucursales_model.php */