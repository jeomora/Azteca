<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prolunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "pro_lunes";
		$this->PRI_INDEX = "codigo";
	} 

	public function getProductos($where=[]){
		$this->db->select("p1.codigo,p1.descripcion,p2.alias,p2.nombre,p1.unidad,p1.precio,p1.sistema")
		->from($this->TABLE_NAME." p1")
		->join("prove_lunes p2","p1.id_proveedor = p2.id_proveedor","LEFT")
		->where("p1.estatus","1")
		->order_by("p1.descripcion","ASC");
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
