<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cambios_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cambios";
		$this->PRI_INDEX = "id_cambio";
	}

	public function getCambios($where=[]){
		$this->db->select("id_cambio, usuarios.nombre AS usuario, cambios.fecha_cambio, antes, despues, accion")
		->from("cambios")
		->join("usuarios", $this->TABLE_NAME.".id_usuario = usuarios.id_usuario", "INNER")
		->order_by($this->TABLE_NAME.".fecha_cambio", "DESC");;
		
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
