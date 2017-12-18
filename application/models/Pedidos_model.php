<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "pedidos";
		$this->PRI_INDEX = "id_pedido";
	}

	public function getPedidos($where=[]){
		$this->db->select("
			pedidos.id_pedido,
			pedidos.fecha_registro,
			pedidos.total,
			pro.first_name, pro.last_name,
			us.first_name, us.last_name")
		->from($this->TABLE_NAME)
		->join("users pro", $this->TABLE_NAME.".id_proveedor = pro.id", "LEFT")
		->join("users us", $this->TABLE_NAME.".id_user_registra = us.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1);
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

/* End of file Pedidos_model.php */
/* Location: ./application/models/Pedidos_model.php */