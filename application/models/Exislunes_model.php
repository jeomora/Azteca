<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exislunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "ex_lunes";
		$this->PRI_INDEX = "id_existencia";
	}


	public function getCuantas($where=[]){
		$this->db->select("s.id_sucursal,s.nombre,count(xl.id_existencia) as cuantas from suc_lunes s left join ex_lunes xl on s.id_sucursal = xl.id_tienda and WEEKOFYEAR(xl.fecha_registro) = WEEKOFYEAR(CURDATE()) where s.estatus = 1 group by s.id_sucursal ORDER BY s.orden ASC");
		
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

	public function getCuanto($where=[],$tienda){
		$this->db->select("s.id_sucursal,s.nombre,count(xl.id_existencia) as cuantas from suc_lunes s left join ex_lunes xl on s.id_sucursal = xl.id_tienda and WEEKOFYEAR(xl.fecha_registro) = WEEKOFYEAR(CURDATE()) where s.estatus = 1 and s.id_sucursal = ".$tienda." group by s.id_sucursal ORDER BY s.orden ASC");
		
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