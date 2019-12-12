<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodcaja_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "prodcaja";
		$this->PRI_INDEX = "id_prodcaja";
	}

	public function getProds($where=[]){
		$this->db->select("pc.id_prodcaja,ic.id_invoice as clave,p.codigo,ic.descripcion,ic.codigo as codigo_factura,p.nombre")
		->from($this->TABLE_NAME." pc")
		->join("invoice_code ic","pc.id_invoice = ic.id_invoice","LEFT")
		->join("productos p", "pc.id_producto = p.id_producto", "LEFT");
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