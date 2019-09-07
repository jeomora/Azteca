<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Velaprod_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "velasprod";
		$this->PRI_INDEX = "id_producto";
	} 

	public function getCount($where=[]){
		$this->db->select("count(*) as noprod")
		->from($this->TABLE_NAME." p1")
		->where("p1.estatus","1");
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

	public function getProductos($where=[]){
		$this->db->select("v.color,v.id_producto,v.nombre,v.codpz,v.codcaja,v.codprov,v.unidad,f.nombre as familia")
		->from("velasprod v")
		->join("velasfam f","v.id_familia = f.id_familia","LEFT")
		->where("estatus",1)
		->order_by("f.id_familia,v.id_producto","ASC");
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
