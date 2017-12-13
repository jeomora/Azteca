<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "promociones";
		$this->PRI_INDEX = "id_promocion";
	}

	public function getPromociones($where = []){
		$this->db->select("
			promociones.id_promocion,
			promociones.precio_fijo,
			promociones.precio_descuento,
			promociones.precio_inicio,
			promociones.precio_fin,
			promociones.descuento,
			promociones.fecha_registro,
			promociones.fecha_caduca,
			promociones.existencias,
			promociones.observaciones,
			u.first_name,
			u.last_name,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
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

/* End of file Promociones_model.php */
/* Location: ./application/models/Promociones_model.php */