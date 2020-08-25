<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Llegaron_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "llegaron";
		$this->PRI_INDEX = "id_final";
	}

	public function buscaCodigos($where = [],$values,$values2){
		$value = json_decode($values);
		$value2 = json_decode($values2);
		if ($value2->provs <> 0 || $value2->provs <> "0") {
			$this->db->select("p.codigo,p.nombre,u.nombre as proveedor,f.cedis,f.abarrotes,f.costo,f.tienda,f.villas,f.ultra,f.trincheras,f.mercado,f.tenencia,f.tijeras,f.promocion FROM finales f LEFT JOIN productos p ON f.id_producto = p.id_producto LEFT JOIN usuarios u ON f.id_proveedor = u.id_usuario WHERE (p.nombre LIKE '%".$value->producto."%' OR p.codigo LIKE '%".$value->producto."%') AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(f.fecha_registro) = YEAR(CURDATE()) AND f.id_proveedor = ".$value2->provs." ORDER BY p.codigo");
		} else {
			$this->db->select("p.codigo,p.nombre,u.nombre as proveedor,f.cedis,f.abarrotes,f.costo,f.tienda,f.villas,f.ultra,f.trincheras,f.mercado,f.tenencia,f.tijeras,f.promocion FROM finales f LEFT JOIN productos p ON f.id_producto = p.id_producto LEFT JOIN usuarios u ON f.id_proveedor = u.id_usuario WHERE (p.nombre LIKE '%".$value->producto."%' OR p.codigo LIKE '%".$value->producto."%') AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(f.fecha_registro) = YEAR(CURDATE()) ORDER BY p.codigo");
		}
		
		
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

	public function getPedidos($where = [],$values){
		$value = json_decode($values);
		$this->db->select("(f.cedis+f.abarrotes+f.villas+f.tienda+f.ultra+f.trincheras+f.mercado+f.tenencia+f.tijeras) as totalp,u.id_usuario,f.promocion, f.costo,f.cedis,f.abarrotes,f.villas,f.tienda,f.ultra,f.trincheras,f.mercado,f.tenencia,f.tijeras,f.promocion,p.nombre,p.codigo, u.nombre as proveedor FROM finales f LEFT JOIN productos p ON f.id_producto = p.id_producto LEFT JOIN usuarios u ON f.id_proveedor = u.id_usuario WHERE WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND f.id_proveedor = ".$value->proveedor." ORDER BY p.nombre");
		
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