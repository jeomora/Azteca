<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizaciones";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getLastCot($where = [],$id_proveedor){
		$fecha = new DateTime(date('Y-m-d'));
		$this->db->select("c.id_cotizacion,c.id_proveedor,c.id_producto,c.precio,c.precio_promocion,c.num_one,c.num_two,c.descuento,c.observaciones,c.fecha_registro,p.nombre FROM cotizaciones c LEFT JOIN productos p ON c.id_producto = p.id_producto where c.id_proveedor = ".$id_proveedor." AND DATE(c.fecha_registro) = (SELECT DATE(MAX(fecha_registro)) from cotizaciones where id_proveedor = ".$id_proveedor.") ORDER BY c.fecha_registro DESC");
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