<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "usuarios";
		$this->PRI_INDEX = "id_usuario";
	} 

	public function showUsers($where=[]){
		$this->db->select("u.id_usuario,u.nombre,u.apellido,u.telefono,u.email,u.estatus,g.nombre as grup,u.password")
		->from($this->TABLE_NAME." u")
		->join("grupo g","u.estatus = g.id_grupo","LEFT")
		->where("u.estatus <>",0)
		->order_by("u.id_usuario","ASC");
		if($where !== NULL){
			if(is_array($where)){
				foreach ($where as $field => $value) {
					$this->db->where($field, $value);
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$result = $this->db->get()->result();
		if($result){
			if(is_array($where)){
				return $result;
			}else{
				return $result;
			}
		}else{
			return false;
		}
	}

	public function showUser($where=[],$id = 0){
		$this->db->select("u.id_usuario,u.nombre,u.apellido,u.telefono,u.email,u.estatus,g.nombre as grup,u.password")
		->from($this->TABLE_NAME." u")
		->join("grupo g","u.estatus = g.id_grupo","LEFT")
		->where("id_usuario",$id)
		->order_by("u.id_usuario","ASC");
		if($where !== NULL){
			if(is_array($where)){
				foreach ($where as $field => $value) {
					$this->db->where($field, $value);
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$result = $this->db->get()->result();
		if($result){
			if(is_array($where)){
				return $result;
			}else{
				return array_shift($result);
			}
		}else{
			return false;
		}
	}

	public function getUsuarios($where=[]){
		$this->db->select("
			usuarios.id_usuario,
			usuarios.nombre,
			usuarios.apellido,
			usuarios.telefono,
			usuarios.estatus,
			usuarios.email")
		->from($this->TABLE_NAME)
		->where("estatus <> ","0")
		->order_by($this->TABLE_NAME.".id_usuario","ASC");
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

	public function getCotizados($where = []){
		$fecha = new DateTime(date('Y-m-d'));
		$intervalo = new DateInterval('P3D'); 
		$fecha->add($intervalo);
		$this->db->select("* from (SELECT COUNT(id_cotizacion) as total,c.id_cotizacion,c.id_proveedor,c.fecha_registro,u.nombre,u.email FROM cotizaciones c LEFT JOIN usuarios u ON c.id_proveedor = u.id_usuario WHERE c.id_proveedor NOT IN (SELECT cotizaciones.id_proveedor FROM cotizaciones WHERE cotizaciones.estatus = 1 AND WEEKOFYEAR(cotizaciones.fecha_registro) = WEEKOFYEAR(CURDATE())) GROUP BY c.id_proveedor,WEEK(c.fecha_registro),nombre ORDER BY c.fecha_registro DESC) dude GROUP BY nombre");
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

	public function login($where=[]){
		if($where !== NULL){
			if(is_array($where)){
				foreach($where as $field=>$value){
					$this->db->where($field, $value);
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$query = $this->db->get($this->TABLE_NAME);
		return $query->num_rows() > 0 ? $query->result() : 0;
	}

	public function getUsuario($where=[]){
		$this->db->select("
			usuarios.id_usuario AS ides,
			usuarios.nombre AS names
			")
		->from($this->TABLE_NAME)
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
	public function getHim($where=[],$ides=""){
		$this->db->select("
			usuarios.id_usuario AS ides,
			usuarios.nombre AS names
			")
		->from($this->TABLE_NAME)
		->where($this->TABLE_NAME.".id_usuario", $ides);
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

/* End of file Usuarios_model.php */
/* Location: ./application/models/Usuarios_model.php */