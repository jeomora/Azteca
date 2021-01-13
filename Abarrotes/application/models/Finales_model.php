<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finales_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "finales";
		$this->PRI_INDEX = "id_final";
	}


	public function getFinales($where=[],$proveedor){
		$this->db->select("f.promocion,f.costo,p.codigo,p.nombre,f.id_final,f.id_producto,f.id_proveedor,f.fecha_registro,ff.nombre as familia,f.cedis,f.abarrotes,f.villas,f.tienda,f.ultra,f.trincheras,f.mercado,f.tenencia,f.tijeras FROM finales f LEFT JOIN productos p ON f.id_producto = p.id_producto LEFT JOIN familias ff ON p.id_familia = ff.id_familia where WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(f.fecha_registro) = YEAR(CURDATE()) AND f.id_proveedor = ".$proveedor."");
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

	public function getLlegaron($where=[]){
		$this->db->select("f.promocion,f.costo,p.codigo,p.nombre,f.id_final,f.id_producto,f.id_proveedor,f.fecha_registro,ff.nombre as familia,f.cedis,f.abarrotes,f.villas,f.tienda,f.ultra,f.trincheras,f.mercado,f.tenencia,f.tijeras FROM llegaron f LEFT JOIN productos p ON f.id_producto = p.id_producto LEFT JOIN familias ff ON p.id_familia = ff.id_familia where WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(f.fecha_registro) = YEAR(CURDATE())");
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