<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendlunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "pendlunes";
		$this->PRI_INDEX = "id_pendiente";
	}

	public function getThem($where){
		$this->db->select("
			p.codigo,p.descripcion,pp.cedis,pp.abarrotes,pp.tienda,pp.ultra,pp.tenencia,pp.tijeras,pp.pedregal,pp.trincheras,pp.mercado")
		->from($this->TABLE_NAME." pp")
		->join("pro_lunes p", "pp.id_producto = p.codigo", "LEFT");
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
