<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltantes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "faltantes";
		$this->PRI_INDEX = "id_faltante";
	}

	public function getfaltas($where=[],$producto=0,$fechas){
		$this->db->select("fecha_termino,nombre as nomb")
		->from($this->TABLE_NAME)
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario ", "LEFT")
		->where($this->TABLE_NAME.".fecha_termino >", "'".$fechas."'")
		->where($this->TABLE_NAME.".id_producto",$producto);

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
			return $result;
		} else {
			return false;
		}
	}

}

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */