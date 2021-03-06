<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "usuarios";
		$this->PRI_INDEX = "id_usuario";
	} 
	
	public function getDudes($where=[]){
		$this->db->select("*")
		->from("usuarios u")
		->where("u.id_grupo", 2)
		->where("u.estatus <>", 0)
		->where("u.id_usuario IN (select c.id_proveedor from cotizaciones c where WEEKOFYEAR(c.fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY c.id_proveedor)")
		->group_by("u.id_usuario")
		->order_by("u.nombre","ASC");
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

	public function getUsuarios($where=[]){
		$this->db->select("
			usuarios.id_usuario,
			usuarios.nombre,
			usuarios.apellido,
			usuarios.telefono,
			usuarios.password,
			usuarios.email,
			usuarios.cargo,
			usuarios.detalles,
			usuarios.extension,
			usuarios.pagot,
			g.nombre AS grupo")
		->from($this->TABLE_NAME)
		->join("grupos g", $this->TABLE_NAME.".id_grupo = g.id_grupo", "LEFT")
		//->where("usuarios.id_grupo","2")
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

	public function getD($where=[]){
		$this->db->select("*")
		->from($this->TABLE_NAME)
		->where("(id_usuario = 3 OR id_usuario = 107)")
		->order_by("id_usuario","ASC");
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

	public function getSAH($where=[]){
		$this->db->select("*")
		->from($this->TABLE_NAME)
		->where("id_usuario = 4")
		->order_by("id_usuario","ASC");
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

	public function getH($where=[]){
		$this->db->select("*")
		->from($this->TABLE_NAME)
		->where("(id_usuario = 6 OR id_usuario = 17 OR id_usuario = 51 OR id_usuario = 53 OR id_usuario = 5)")
		->order_by("id_usuario","ASC");
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

	public function getColors($where=[]){
		$this->db->select("u.nombre,u.id_usuario,s.color")
		->from($this->TABLE_NAME." u")
		->join("suc_lunes s", "u.id_usuario = s.id_sucursal", "LEFT")
		->where("u.id_grupo","3")
		->where("u.estatus",1)
		->where("u.id_usuario <> 89")
		->order_by("s.orden","ASC");
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
	public function get19hnos($where = []){
		$this->db->select("id_usuario,id_grupo,nombre,apellido,cargo,orden,conjunto from usuarios where (id_usuario = 6 OR id_usuario = 22)")
		->order_by($this->TABLE_NAME.".id_usuario", "ASC");
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

	public function getCotizados($where = []){
		$fecha = new DateTime(date('Y-m-d'));
		$intervalo = new DateInterval('P2D'); 
		$fecha->add($intervalo);
		$this->db->select("id_usuario as ides, nombre as proveedor")
		->from($this->TABLE_NAME)
		->where("usuarios.id_usuario NOT IN (SELECT cotizaciones.id_proveedor FROM cotizaciones WHERE cotizaciones.estatus = 1 AND WEEKOFYEAR(cotizaciones.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(cotizaciones.fecha_registro) = YEAR(CURDATE()) GROUP BY 
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

	public function getCotizadillos($where = []){
		$this->db->select("u.id_usuario,u.nombre,c.fecha FROM usuarios u LEFT JOIN (SELECT c.id_proveedor,MAX(c.fecha_registro) AS fecha FROM cotizaciones c 
			GROUP BY c.id_proveedor) c ON u.id_usuario = c.id_proveedor WHERE u.id_grupo = 2 AND u.id_usuario NOT IN(SELECT c.id_proveedor FROM cotizaciones 
			c WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(DATE_ADD(CURDATE(), INTERVAL 2 DAY)) GROUP BY c.id_proveedor )")
		->order_by("c.fecha","ASC");
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
