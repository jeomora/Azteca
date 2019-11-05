<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frutas_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "frutas";
		$this->PRI_INDEX = "id_fruta";
	}

	public function getExisTienda($where=[],$id_tienda){
		$this->db->select("v.id_fruta,v.codigo,v.descripcion,e.total FROM frutas v LEFT JOIN ex_fruta e on v.id_fruta = e.id_fruta AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = ".$id_tienda." ")
		->order_by("v.id_fruta", "ASC");;
		
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

	public function getExistencias($where=[]){
		$this->db->select("v.id_fruta,v.codigo, v.descripcion,v.precio,e.total as soli,e2.total as tienda,e3.total as ultra,e4.total as trinch,e5.total as merca,e6.total as tenen,e7.total as tije,e8.total as cedis,e9.total as super,e10.total as villas FROM frutas v LEFT JOIN ex_fruta e on v.id_fruta = e.id_fruta AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = 57 LEFT JOIN ex_fruta e2 on v.id_fruta = e2.id_fruta AND WEEKOFYEAR(e2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e2.id_tienda = 58 LEFT JOIN ex_fruta e3 on v.id_fruta = e3.id_fruta AND WEEKOFYEAR(e3.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e3.id_tienda = 59 LEFT JOIN ex_fruta e4 on v.id_fruta = e4.id_fruta AND WEEKOFYEAR(e4.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e4.id_tienda = 60 LEFT JOIN ex_fruta e5 on v.id_fruta = e5.id_fruta AND WEEKOFYEAR(e5.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e5.id_tienda = 61 LEFT JOIN ex_fruta e6 on v.id_fruta = e6.id_fruta AND WEEKOFYEAR(e6.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e6.id_tienda = 62 LEFT JOIN ex_fruta e7 on v.id_fruta = e7.id_fruta AND WEEKOFYEAR(e7.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e7.id_tienda = 63 LEFT JOIN ex_fruta e8 on v.id_fruta = e8.id_fruta AND WEEKOFYEAR(e8.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e8.id_tienda = 87 LEFT JOIN ex_fruta e9 on v.id_fruta = e9.id_fruta AND WEEKOFYEAR(e9.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e9.id_tienda = 89 LEFT JOIN ex_fruta e10 on v.id_fruta = e10.id_fruta AND WEEKOFYEAR(e10.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e10.id_tienda = 90")
		->order_by("v.id_fruta", "ASC");;
		
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

	public function getExistenciasJ($where=[]){
		$this->db->select("v.id_fruta,v.descripcion,v.codigo,v.precio,e.total as soli,e2.total as tienda,e3.total as ultra,e4.total as trinch,e5.total as merca,e6.total as tenen,e7.total as tije,e8.total as cedis,e9.total as super,e10.total as villas FROM frutas v LEFT JOIN ex_fruta e on v.id_fruta = e.id_fruta AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = 57 LEFT JOIN ex_fruta e2 on v.id_fruta = e2.id_fruta AND WEEKOFYEAR(e2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e2.id_tienda = 58 LEFT JOIN ex_fruta e3 on v.id_fruta = e3.id_fruta AND WEEKOFYEAR(e3.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e3.id_tienda = 59 LEFT JOIN ex_fruta e4 on v.id_fruta = e4.id_fruta AND WEEKOFYEAR(e4.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e4.id_tienda = 60 LEFT JOIN ex_fruta e5 on v.id_fruta = e5.id_fruta AND WEEKOFYEAR(e5.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e5.id_tienda = 61 LEFT JOIN ex_fruta e6 on v.id_fruta = e6.id_fruta AND WEEKOFYEAR(e6.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e6.id_tienda = 62 LEFT JOIN ex_fruta e7 on v.id_fruta = e7.id_fruta AND WEEKOFYEAR(e7.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e7.id_tienda = 63 LEFT JOIN ex_fruta e8 on v.id_fruta = e8.id_fruta AND WEEKOFYEAR(e8.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e8.id_tienda = 87 LEFT JOIN ex_fruta e9 on v.id_fruta = e9.id_fruta AND WEEKOFYEAR(e9.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e9.id_tienda = 89 LEFT JOIN ex_fruta e10 on v.id_fruta = e10.id_fruta AND WEEKOFYEAR(e10.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e10.id_tienda = 90 WHERE v.id_fruta = (SELECT id_fruta  FROM `ex_fruta` where WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY id_fruta ORDER BY SUM(total) DESC LIMIT 1) AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE())")
		->order_by("v.id_fruta", "ASC");;
		
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

	public function getExTns($where=[]){
		$this->db->select("count(*) as total,id_tienda FROM ex_fruta WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) group by id_tienda")
		->order_by("id_tienda", "ASC");;
		
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
