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
			DATE_FORMAT(pedidos.fecha_registro,'%d-%m-%Y') AS fecha, 
			pedidos.total,
			UPPER(pro.nombre) AS proveedor,
			UPPER(us.nombre) AS user_registra,
			s.nombre AS sucursal")
		->from($this->TABLE_NAME)
		->join("sucursales s", $this->TABLE_NAME.".id_sucursal = s.id_sucursal", "LEFT")
		->join("usuarios pro", $this->TABLE_NAME.".id_proveedor = pro.id_usuario", "LEFT")
		->join("usuarios us", $this->TABLE_NAME.".id_user_registra = us.id_usuario", "LEFT")
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

	public function getWeekPedidos($where=[],$provee="",$fech=""){
		$this->db->select("
			pedidos.id_pedido,
			WEEKOFYEAR(pedidos.fecha_registro) AS fechaw, 
			DATE_FORMAT(pedidos.fecha_registro,'%d-%m-%Y') AS fecha, 
			pedidos.total")
		->from($this->TABLE_NAME)
		->where($this->TABLE_NAME.".id_proveedor", $provee)
		->where($this->TABLE_NAME.".WEEKOFYEAR(pedidos.fecha_registro)", $fech);
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