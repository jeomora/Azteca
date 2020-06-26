<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Veladoras_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos";
		$this->PRI_INDEX = "id_producto";
	} 

	public function getCount($where=[]){
		$this->db->select("count(*) as total")
		->from($this->TABLE_NAME)
		->where("estatus <> 0");
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

	public function getProductos($where=[]){
		$this->db->select("p.id_producto,p.nombre,p.codigo,p.estatus,p.color,p.colorp,f.nombre as familia,i.imagen")
		->from($this->TABLE_NAME." p")
		->join("familias f","p.id_familia = f.id_familia","left")
		->join("images i","p.id_producto = i.id_producto","LEFT")
		->where("p.estatus <> 0")
		->order_by("f.id_familia,p.id_producto","ASC");
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

	public function getAll($where=[],$id_prod){
		$this->db->select("p.id_producto,p.nombre,p.codigo,p.estatus,p.color,p.colorp,f.nombre as familia,i.imagen")
		->from($this->TABLE_NAME." p")
		->join("familias f","p.id_familia = f.id_familia","left")
		->join("images i","p.id_producto = i.id_producto","LEFT")
		->where("p.id_producto =".$id_prod);
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

	public function getLast($where=[]){
		$this->db->select("id_veladora,img")
		->from($this->TABLE_NAME)
		->order_by("id_veladora","DESC")
		->limit(1);
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