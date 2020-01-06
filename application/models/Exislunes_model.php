<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exislunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "ex_lunes";
		$this->PRI_INDEX = "id_existencia";
	}


	public function getCuantas($where=[]){
		$this->db->select("s.id_sucursal,s.nombre,count(xl.id_existencia) as cuantas from suc_lunes s left join ex_lunes xl on s.id_sucursal = xl.id_tienda and WEEKOFYEAR(xl.fecha_registro) = WEEKOFYEAR(CURDATE()) where s.estatus = 1 group by s.id_sucursal ORDER BY s.orden ASC");
		
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

	public function getCuanto($where=[],$tienda){
		$this->db->select("s.id_sucursal,s.nombre,count(xl.id_existencia) as cuantas from suc_lunes s left join ex_lunes xl on s.id_sucursal = xl.id_tienda and WEEKOFYEAR(xl.fecha_registro) = WEEKOFYEAR(CURDATE()) where s.estatus = 1 and s.id_sucursal = ".$tienda." group by s.id_sucursal ORDER BY s.orden ASC");
		
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

	public function getCuantasTienda($where=[],$tienda){
		$this->db->select("count(xl.id_existencia) as cuantas from ex_lunes xl where xl.id_tienda = ".$tienda." and (WEEKOFYEAR(xl.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(xl.fecha_registro) = YEAR(CURDATE())) group by xl.id_tienda");
		
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

	public function getVolTienda($where=[],$tienda){
		$this->db->select("COUNT(ex.id_pedido) AS cuantas FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) AND ex.id_tienda = ".$tienda." AND p.estatus = 2");
		
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

	public function getAllTienda($where=[],$tienda){
		$this->db->select("COUNT(ex.id_pedido) AS cuantas FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) AND ex.id_tienda = ".$tienda." AND p.estatus = 1");
		
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

	public function getLunExist($where=[],$tienda){
		$this->db->select("ex.id_existencia,p.codigo,p.descripcion,ex.cajas,ex.piezas,ex.pedido FROM ex_lunes ex LEFT JOIN pro_lunes p on ex.id_producto = p.codigo WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda."");
		
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

	public function getLunExistNot($where=[],$tienda){
		$this->db->select("pp.codigo,pp.descripcion from pro_lunes pp WHERE codigo NOT IN (SELECT p.codigo FROM ex_lunes ex LEFT JOIN pro_lunes p on ex.id_producto = p.codigo WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda.")");
		
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

	public function getVolExist($where=[],$tienda){
		$this->db->select("ex.id_pedido,p.codigo,p.nombre as descripcion,ex.cajas,ex.piezas,ex.pedido FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda." AND p.estatus = 2");
		
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

	public function getAllExist($where=[],$tienda){
		$this->db->select("ex.id_pedido,p.codigo,p.nombre as descripcion,ex.cajas,ex.piezas,ex.pedido FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda." AND p.estatus = 1");
		
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

	public function getVolExistNot($where=[],$tienda){
		$this->db->select("pp.codigo,pp.nombre as descripcion from productos pp WHERE pp.id_producto NOT IN (SELECT p.id_producto FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda.") AND pp.estatus = 2");
		
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

	public function getAllExistNot($where=[],$tienda){
		$this->db->select("pp.codigo,pp.nombre as descripcion from productos pp WHERE pp.id_producto NOT IN (SELECT p.id_producto FROM existencias ex LEFT JOIN productos p on ex.id_producto = p.id_producto WHERE (WEEKOFYEAR(ex.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ex.fecha_registro) = YEAR(CURDATE())) and ex.id_tienda = ".$tienda.") AND pp.estatus = 1");
		
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

	public function getPlantilla($where=[]){
		$this->db->select("p.codigo,p.descripcion,pl.nombre,pl.alias")
		->from("pro_lunes p")
		->join("prove_lunes pl","p.id_proveedor = pl.id_proveedor","LEFT")
		->where("p.estatus",1)
		->order_by("p.id_proveedor","ASC");
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