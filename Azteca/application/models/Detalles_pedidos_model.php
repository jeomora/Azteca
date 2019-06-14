<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalles_pedidos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "detalles_pedidos";
		$this->PRI_INDEX = "id_detalle_pedido";
	}

	public function getDetallePedido($where=[]){
		$this->db->select("
			detalles_pedidos.id_detalle_pedido,
			detalles_pedidos.id_pedido,
			detalles_pedidos.cantidad,
			detalles_pedidos.precio,
			detalles_pedidos.importe,
			pro.nombre AS producto,
			ped.total AS total_pedido")
		->from($this->TABLE_NAME)
		->join("pedidos ped", $this->TABLE_NAME.".id_pedido = ped.id_pedido", "LEFT")
		->join("productos pro", $this->TABLE_NAME.".id_producto = pro.id_producto", "LEFT")
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

/* End of file Detalles_pedidos_model.php */
/* Location: ./application/models/Detalles_pedidos_model.php */