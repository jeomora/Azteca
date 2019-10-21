<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verduras_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "verduras";
		$this->PRI_INDEX = "id_verdura";
	}

	public function getExisTienda($where=[],$id_tienda){
		$this->db->select("v.id_verdura,v.descripcion,e.total FROM verduras v LEFT JOIN ex_verdura e on v.id_verdura = e.id_verdura AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = ".$id_tienda." ")
		->order_by("v.id_verdura", "ASC");;
		
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
		$this->db->select("v.id_verdura,v.descripcion,v.precio,e.total as soli,e2.total as tienda,e3.total as ultra,e4.total as trinch,e5.total as merca,e6.total as tenen,e7.total as tije,e8.total as cedis,e9.total as super,e10.total as villas FROM verduras v LEFT JOIN ex_verdura e on v.id_verdura = e.id_verdura AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = 57 LEFT JOIN ex_verdura e2 on v.id_verdura = e2.id_verdura AND WEEKOFYEAR(e2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e2.id_tienda = 58 LEFT JOIN ex_verdura e3 on v.id_verdura = e3.id_verdura AND WEEKOFYEAR(e3.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e3.id_tienda = 59 LEFT JOIN ex_verdura e4 on v.id_verdura = e4.id_verdura AND WEEKOFYEAR(e4.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e4.id_tienda = 60 LEFT JOIN ex_verdura e5 on v.id_verdura = e5.id_verdura AND WEEKOFYEAR(e5.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e5.id_tienda = 61 LEFT JOIN ex_verdura e6 on v.id_verdura = e6.id_verdura AND WEEKOFYEAR(e6.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e6.id_tienda = 62 LEFT JOIN ex_verdura e7 on v.id_verdura = e7.id_verdura AND WEEKOFYEAR(e7.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e7.id_tienda = 63 LEFT JOIN ex_verdura e8 on v.id_verdura = e8.id_verdura AND WEEKOFYEAR(e8.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e8.id_tienda = 87 LEFT JOIN ex_verdura e9 on v.id_verdura = e9.id_verdura AND WEEKOFYEAR(e9.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e9.id_tienda = 89 LEFT JOIN ex_verdura e10 on v.id_verdura = e10.id_verdura AND WEEKOFYEAR(e10.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e10.id_tienda = 90 WHERE v.id_verdura <> 103")
		->order_by("v.id_verdura", "ASC");;
		
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
		$this->db->select("v.id_verdura,v.descripcion,v.precio,e.total as soli,e2.total as tienda,e3.total as ultra,e4.total as trinch,e5.total as merca,e6.total as tenen,e7.total as tije,e8.total as cedis,e9.total as super,e10.total as villas FROM verduras v LEFT JOIN ex_verdura e on v.id_verdura = e.id_verdura AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e.id_tienda = 57 LEFT JOIN ex_verdura e2 on v.id_verdura = e2.id_verdura AND WEEKOFYEAR(e2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e2.id_tienda = 58 LEFT JOIN ex_verdura e3 on v.id_verdura = e3.id_verdura AND WEEKOFYEAR(e3.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e3.id_tienda = 59 LEFT JOIN ex_verdura e4 on v.id_verdura = e4.id_verdura AND WEEKOFYEAR(e4.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e4.id_tienda = 60 LEFT JOIN ex_verdura e5 on v.id_verdura = e5.id_verdura AND WEEKOFYEAR(e5.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e5.id_tienda = 61 LEFT JOIN ex_verdura e6 on v.id_verdura = e6.id_verdura AND WEEKOFYEAR(e6.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e6.id_tienda = 62 LEFT JOIN ex_verdura e7 on v.id_verdura = e7.id_verdura AND WEEKOFYEAR(e7.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e7.id_tienda = 63 LEFT JOIN ex_verdura e8 on v.id_verdura = e8.id_verdura AND WEEKOFYEAR(e8.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e8.id_tienda = 87 LEFT JOIN ex_verdura e9 on v.id_verdura = e9.id_verdura AND WEEKOFYEAR(e9.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e9.id_tienda = 89 LEFT JOIN ex_verdura e10 on v.id_verdura = e10.id_verdura AND WEEKOFYEAR(e10.fecha_registro) = WEEKOFYEAR(CURDATE()) AND e10.id_tienda = 90 WHERE v.id_verdura = 103")
		->order_by("v.id_verdura", "ASC");;
		
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
