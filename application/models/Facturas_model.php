<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "facturas";
		$this->PRI_INDEX = "id_factura";
	}

	public function getFactos($where=[],$values){
		$stri = json_decode($values);
		$this->db->select($stri);
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

	public function getThem($where=[],$folio,$prove,$id_tienda,$codigo,$precio,$candtidad){
		$this->db->select(" * FROM facturas WHERE folio = '".$folio."' AND id_proveedor = ".$prove." AND WEEKOFYEAR(fecha_registro) = ".$this->weekNumber()." AND id_tienda = ".$id_tienda." AND codigo = '".$codigo."' AND precio = ".$precio." AND cantidad =".$candtidad);
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

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */