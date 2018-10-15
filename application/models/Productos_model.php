<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos";
		$this->PRI_INDEX = "id_producto";
	}

	public function getProductos($where = []){
		$this->db->select("
			productos.id_producto,
			productos.nombre AS producto,
			productos.codigo,
			f.nombre AS familia")
		->from($this->TABLE_NAME)
		->join("familias f", $this->TABLE_NAME.".id_familia = f.id_familia", "LEFT");
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
		$this->db->select("
			productos.id_producto,
			productos.nombre AS producto,
			productos.codigo,
			productos.color,productos.colorp,
			f.nombre AS familia")
		->from($this->TABLE_NAME)
		->join("familias f", $this->TABLE_NAME.".id_familia = f.id_familia", "LEFT")
		->where("productos.id_producto NOT IN (SELECT cotizaciones.id_producto FROM cotizaciones WHERE 
WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fecha->format('Y-m-d H:i:s'))." AND cotizaciones.estatus = 1)")
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


	public function getProducto($where = []){
		$this->db->select("
			productos.id_producto as ides,
			productos.nombre AS names")
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

	public function getProdFam($where = [],$prove){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
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
				$comparativaIndexada[$comparativa[$i]->id_familia]["id_familia"]	=	$comparativa[$i]->id_familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]	=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]		=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]		=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]		=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["fecha_registro"]		=	$comparativa[$i]->fecha_registro;
			$precios2 = $this->db->select('precio,
				  num_one,
				  num_two,
				  observaciones,
				  descuento')
				->from('cotizaciones')
				->where('WEEKOFYEAR(fecha_registro)',($this->weekNumber()-1))
				->where('id_producto',$comparativa[$i]->id_producto)
				->where('id_proveedor',$prove)
				->group_by("id_proveedor");
				$precios = $this->db->get()->result();
			if($precios){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio"]		=	$precios[0]->precio;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_one"]	=	$precios[0]->num_one;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_two"]	=	$precios[0]->num_two;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["observaciones"]	=	$precios[0]->observaciones;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["descuento"]	=	$precios[0]->descuento;
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio"]		=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_one"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["num_two"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["observaciones"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["descuento"]	=	"";
			}
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
