<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendientes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "pendientes";
		$this->PRI_INDEX = "id_pendiente";
	}

	public function getThem($where){
		$this->db->select("
			p.codigo,p.nombre,pp.cedis,pp.abarrotes,pp.tienda,pp.ultra,pp.tenencia,pp.tijeras,pp.pedregal,pp.trincheras,pp.mercado")
		->from($this->TABLE_NAME." pp")
		->join("productos p", "pp.id_producto = p.id_producto", "LEFT");
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
