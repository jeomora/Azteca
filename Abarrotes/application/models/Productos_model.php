<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos";
		$this->PRI_INDEX = "id_producto";
	}

	public function getCuantos($where = []){
		$this->db->select("COUNT(*) as total")
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

	public function getProductos($where = []){
		$this->db->select("p.nombre as producto,p.casa,p.id_producto,p.codigo,p.estatus,p.color,p.colorp,p.exist,p.unidad,p.pieza,f.nombre as familia,i.imagen")
		->from("productos p")
		->join("familias f","p.id_familia = f.id_familia","LEFT")
		->join("images i","p.id_producto = i.id_image","LEFT")
		->where("p.estatus<>", 0);
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
	
	public function getVolCount($where = []){
		$this->db->select("COUNT(*) as total")
		->from($this->TABLE_NAME)
		->where("estatus", 2);
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

	public function getAllCount($where = []){
		$this->db->select("COUNT(*) as total")
		->from($this->TABLE_NAME)
		->where("estatus", 1);
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

	public function getProdFam($where = [],$prove){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$this->db->select("c.observaciones,ff.id_familia,f.id_producto AS sem1,f2.id_producto AS sem2,f3.id_producto AS sem3,f4.id_producto AS sem4, c.id_cotizacion,c.id_proveedor,c.id_producto,c.precio,
			c.num_one,c.num_two,c.descuento,p.id_producto,p.nombre as producto,p.codigo,p.estatus,p.colorp,p.color,p.fecha_registro,ff.nombre as familia FROM productos p 
			LEFT JOIN familias ff ON p.id_familia = ff.id_familia LEFT JOIN cotizaciones c ON p.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = 
			WEEKOFYEAR(CURDATE()) AND c.id_proveedor = ".$prove." LEFT JOIN (SELECT id_producto,id_proveedor FROM faltantes WHERE WEEKOFYEAR(fecha_termino) = 
			WEEKOFYEAR(DATE_ADD(CURDATE(),INTERVAL 7 DAY))) f ON c.id_producto = f.id_producto AND c.id_proveedor = f.id_proveedor LEFT JOIN (SELECT id_producto,id_proveedor 
			FROM faltantes WHERE WEEKOFYEAR(fecha_termino) = WEEKOFYEAR(DATE_SUB(CURDATE(),INTERVAL 0 DAY))) f2 ON c.id_producto = f2.id_producto AND c.id_proveedor = 
			f2.id_proveedor LEFT JOIN (SELECT id_producto,id_proveedor FROM faltantes WHERE WEEKOFYEAR(fecha_termino) = WEEKOFYEAR(DATE_SUB(CURDATE(),INTERVAL 7 DAY))) 
			f3 ON c.id_producto = f3.id_producto AND c.id_proveedor = f3.id_proveedor LEFT JOIN (SELECT id_producto,id_proveedor FROM faltantes WHERE WEEKOFYEAR(fecha_termino)
			 = WEEKOFYEAR(DATE_SUB(CURDATE(),INTERVAL 14 DAY))) f4 ON c.id_producto = f4.id_producto AND c.id_proveedor = f4.id_proveedor WHERE p.estatus <> 0");
		if ($where !== NULL){
			if(is_array($where)){
				foreach($where as $field=>$value){
					if ($value !== NULL) {
						$this->db->where($field, $value);
					}
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++){
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["id_familia"]	=	$comparativa[$i]->id_familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]	=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]		=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]		=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]		=	$comparativa[$i]->color;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["sem1"]		=	$comparativa[$i]->sem1;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["sem2"]		=	$comparativa[$i]->sem2;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["sem3"]		=	$comparativa[$i]->sem3;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["sem4"]		=	$comparativa[$i]->sem4;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["fecha_registro"]		=	$comparativa[$i]->fecha_registro;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio"]		=	$comparativa[$i]->precio == NULL ? 0 : $comparativa[$i]->precio;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_one"]	=	$comparativa[$i]->num_one == NULL ? "" : $comparativa[$i]->num_one;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_two"]	=	$comparativa[$i]->num_two == NULL ? "" : $comparativa[$i]->num_two;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["observaciones"]	=	$comparativa[$i]->observaciones == NULL ? "" : $comparativa[$i]->observaciones;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["descuento"]	=	$comparativa[$i]->descuento == NULL ? "" : $comparativa[$i]->descuento;
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return $comparativaIndexada;
			}
		} else {
			return false;
		}
	}
	public function getProdFamS($where = []){
		$this->db->select("
			productos.id_producto,
			productos.nombre AS producto,
			productos.codigo,
			productos.estatus,
			f.nombre AS familia,
			f.id_familia,
			productos.colorp,
			productos.color,
			productos.fecha_registro")
		->from($this->TABLE_NAME)
		->join("familias f", $this->TABLE_NAME.".id_familia = f.id_familia", "LEFT")
		->where("productos.estatus <> 0")
		->order_by("f.id_familia,productos.nombre", "ASC");
		if ($where !== NULL){
			if(is_array($where)){
				foreach($where as $field=>$value){
					if ($value !== NULL) {
						$this->db->where($field, $value);
					}
				}
			}else{
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++){
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]	=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]		=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]		=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]		=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["fecha_registro"]		=	$comparativa[$i]->fecha_registro;
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return $comparativaIndexada;
			}
		} else {
			return false;
		}
	}

}

/* End of file Productos_model.php */
/* Location: ./application/models/Productos_model.php */
