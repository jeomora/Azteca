<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cambios_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cambios";
		$this->PRI_INDEX = "id_cambio";
	}

	public function getCambios($where=[]){
		$this->db->select("id_cambio, usuarios.nombre AS usuario, DATE_SUB(cambios.fecha_cambio, INTERVAL 5 HOUR) as fecha_cambio, antes, despues, accion")
		->from("cambios")
		->join("usuarios", $this->TABLE_NAME.".id_usuario = usuarios.id_usuario", "INNER")
		->order_by($this->TABLE_NAME.".fecha_cambio", "DESC");;
		
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

	public function getCambios2($where=[]){
		$this->db->select("c.fecha_cambio,DATE_FORMAT(fecha_cambio,'%d/%m') as fec,c.antes,c.despues,c.accion,u.nombre,u.id_grupo,DATE_FORMAT(fecha_cambio,'%H:%i') as hor FROM cambios c LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE u.id_grupo <> 1 ORDER BY id_cambio DESC LIMIT 10");
		
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

	public function getproveedores($where=[]){
		$this->db->select("c.fecha_cambio,c.despues,c.antes,c.accion,u.nombre as nombre")
		->from("cambios c")
		->join("usuarios u","c.id_usuario = u.id_usuario","LEFT")
		->where("u.id_grupo",2)
		->order_by("c.id_cambio","DESC")
		->limit(15);
		
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

	public function gettiendas($where=[]){
		$this->db->select("c.fecha_cambio,c.despues,c.antes,c.accion,u.nombre as nombre")
		->from("cambios c")
		->join("usuarios u","c.id_usuario = u.id_usuario","LEFT")
		->where("u.id_grupo",3)
		->order_by("c.id_cambio","DESC")
		->limit(15);
		
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

	public function getcedis($where=[]){
		$this->db->select("c.fecha_cambio,c.despues,c.antes,c.accion,u.nombre as nombre")
		->from("cambios c")
		->join("usuarios u","c.id_usuario = u.id_usuario","LEFT")
		->where("u.id_grupo",4)
		->order_by("c.id_cambio","DESC")
		->limit(15);
		
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
