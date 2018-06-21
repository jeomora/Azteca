<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Precio_sistemaback_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "precio_sistemaback";
		$this->PRI_INDEX = "id_precio";
	}

	public function getPrecioSistema($where=[]){
		$this->db->select("
			precio_sistema.id_precio,
			precio_sistema.precio_sistema,
			precio_sistema.precio_four,
			precio_sistema.fecha_registro,
			pro.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("productos pro", $this->TABLE_NAME.".id_producto = pro.id_producto", "LEFT");
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

/* End of file Detalles_pedidos_model.php */
/* Location: ./application/models/Detalles_pedidos_model.php */