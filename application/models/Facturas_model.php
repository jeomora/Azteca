<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "facturas";
		$this->PRI_INDEX = "id_factura";
	}

	public function getFactos($where=[],$values){
		$stri = json_decode($values);
		$this->db->select($stri);
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

	public function getThem($where=[],$folio,$prove,$id_tienda,$codigo,$precio,$candtidad){
		$this->db->select(" * FROM facturas WHERE folio = '".$folio."' AND id_proveedor = ".$prove." AND WEEKOFYEAR(fecha_registro) = ".$this->weekNumber()." AND id_tienda = ".$id_tienda." AND codigo = '".$codigo."' AND precio = ".$precio." AND cantidad =".$candtidad);
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

	public function getFacturas($where=[],$values){
		$this->db->select("f.folio,f.id_tienda,f.fecha_registro,fecha_factura,u.nombre,f.id_proveedor FROM comparacion c LEFT JOIN facturas f on c.folio = f.folio LEFT JOIN usuarios u ON f.id_proveedor = u.id_usuario WHERE c.id_tienda = ".$values." AND WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY f.folio");
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

	public function getDetails($where=[],$values){
		$value = json_decode($values);
		$this->db->select("ff.".$value->which." as wey,u.nombre as prove,f.fecha_factura,s.color,s.nombre as tienda,c.id_comparacion,c.folio,c.fecha,c.costo, c.devolucion,c.devueltos,c.gift,f.descripcion,f.cantidad ,pp.nombre as pprod ,f.precio,c.cuantos,c.gifted FROM comparacion c LEFT JOIN facturas f on c.folio = f.folio AND c.id_tienda = f.id_tienda AND c.id_proveedor = f.id_proveedor AND c.factura = f.codigo LEFT JOIN suc_lunes s ON f.id_tienda = s.id_sucursal LEFT JOIN usuarios u ON c.id_proveedor = u.id_usuario LEFT JOIN productos pp ON c.producto = pp.codigo LEFT JOIN finales ff ON pp.id_producto = ff.id_producto AND WEEKOFYEAR(ff.fecha_registro) = WEEKOFYEAR(CURDATE()) WHERE c.folio = '".$value->folio."' AND c.id_proveedor = '".$value->proveedor."' AND c.id_tienda = '".$value->tienda."' GROUP BY c.id_comparacion");
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

	public function getDetails2($where=[],$values,$which){
		$value = json_decode($values);
		$this->db->select("ff.".$which." as wey,u.nombre as prove,f.fecha_factura,s.color,s.nombre as tienda,c.id_comparacion,c.folio,c.fecha,c.costo, c.devolucion,c.devueltos,c.gift,f.descripcion,f.cantidad,pp.nombre as pprod ,f.precio,c.cuantos,c.gifted FROM comparacion c LEFT JOIN facturas f on c.folio = f.folio AND c.id_tienda = f.id_tienda AND c.id_proveedor = f.id_proveedor AND c.factura = f.codigo LEFT JOIN suc_lunes s ON f.id_tienda = s.id_sucursal LEFT JOIN usuarios u ON c.id_proveedor = u.id_usuario LEFT JOIN productos pp ON c.producto = pp.codigo LEFT JOIN finales ff ON pp.id_producto = ff.id_producto AND WEEKOFYEAR(ff.fecha_registro) = WEEKOFYEAR(CURDATE()) WHERE c.folio = '".$value->folio."' AND c.id_proveedor = '".$value->id_proveedor."' AND c.id_tienda = '".$value->id_tienda."' GROUP BY c.id_comparacion");
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

	public function getFactProv($where=[]){
		$this->db->select("u.nombre,u.id_usuario FROM facturas f LEFT JOIN usuarios u ON f.id_proveedor = u.id_usuario WHERE WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP by f.id_proveedor");
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

	public function profactu($where=[],$proveedor){
		$this->db->select("f.folio as folio,f.id_tienda as tienda FROM facturas f WHERE f.id_proveedor = ".$proveedor." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY f.folio ORDER BY f.folio");
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

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */