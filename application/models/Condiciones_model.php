<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Condiciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "condiciones";
		$this->PRI_INDEX = "id_condicion";
	}

	public function getCondiciones($where=[]){
		$this->db->select("cl.id_condicion,cl.no_cajas,p.codigo,p.descripcion,pp.nombre,cl.descri,cl.proveedor")
		->from("pro_lunes p")
		->join("prove_lunes pp", "p.id_proveedor = pp.id_proveedor", "LEFT")
		->join("condi_lunes cl", "p.id_condicion = cl.id_condicion", "LEFT")
		->where("p.id_condicion <> 1")
		->order_by("p.id_condicion", "ASC");
		
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