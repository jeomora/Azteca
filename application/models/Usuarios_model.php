<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "usuarios";
		$this->PRI_INDEX = "id_usuario";
	} 

	public function getUsuarios($where=[]){
		$this->db->select("
			usuarios.id_usuario,
			usuarios.nombre,
			usuarios.apellido,
			usuarios.telefono,
			usuarios.password,
			usuarios.email,
			g.nombre AS grupo")
		->from($this->TABLE_NAME)
		->join("grupos g", $this->TABLE_NAME.".id_grupo = g.id_grupo", "LEFT")
		->order_by($this->TABLE_NAME.".nombre","ASC");
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$this->db->select("id_usuario as ides, nombre as proveedor")
		->from($this->TABLE_NAME)
		->where("usuarios.id_usuario NOT IN (SELECT cotizaciones.id_proveedor FROM cotizaciones WHERE cotizaciones.estatus = 1 AND cotizaciones.fecha_registro >= 2018-06-24 GROUP BY 
			cotizaciones.id_proveedor)")
		->where($this->TABLE_NAME.".id_grupo", 2)
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by("proveedor","ASC");
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