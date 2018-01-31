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

}

/* End of file Usuarios_model.php */
/* Location: ./application/models/Usuarios_model.php */