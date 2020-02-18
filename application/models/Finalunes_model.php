<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finalunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "finalunes";
		$this->PRI_INDEX = "id_final";
	}

	public function getPedidos($where = [],$values){
		$this->db->select("(f.cedis+f.abarrotes+f.villas+f.tienda+f.ultra+f.trincheras+f.mercado+f.tenencia+f.tijeras) as totalp,p.observaciones,f.costo,f.cedis,f.abarrotes,f.villas,f.tienda,f.ultra, f.trincheras,f.mercado,f.tenencia,f.tijeras,p.descripcion,p.codigo, u.nombre as proveedor FROM finalunes f LEFT JOIN pro_lunes p ON f.id_producto = p.codigo LEFT JOIN prove_lunes u ON p.id_proveedor = u.id_proveedor WHERE WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND p.id_proveedor = ".$values."");
		
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

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
