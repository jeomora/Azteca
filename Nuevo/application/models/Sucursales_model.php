<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "sucursales";
		$this->PRI_INDEX = "id_sucursal";
	}
	//VOLUMEN
	public function getV($where=[]){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$this->db->select("abarrotes,cedis,mercado,pedregal,tienda,trincheras,tenencia,ultra,tijeras,p.color,p.id_producto,f.id_familia,p.nombre as producto,p.codigo,ctz_first.observaciones,f.nombre as familia,ii.imagen FROM productos p LEFT JOIN familias f ON p.id_familia = f.id_familia LEFT JOIN images ii ON p.id_producto = ii.id_producto LEFT JOIN pendientes pnd ON p.id_producto = pnd.id_producto LEFT JOIN cotizaciones ctz_first ON ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario WHERE p.id_producto = ctz_min.id_producto AND (WEEKOFYEAR(ctz_min.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min.fecha_registro) = YEAR(CURDATE())) AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND (WEEKOFYEAR(ctz_min_precio.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min_precio.fecha_registro) = YEAR(CURDATE())) ) ORDER BY uss.orden ASC LIMIT 1) WHERE p.estatus = 2");

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


		// echo $this->db->last_query();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["observaciones"]	=	$comparativa[$i]->observaciones;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]	=	$comparativa[$i]->imagen;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$comparativa[$i]->tenencia;
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

	//GENERAL
	public function getG($where=[]){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$this->db->select("abarrotes,cedis,mercado,pedregal,tienda,trincheras,tenencia,ultra,tijeras,p.color,p.id_producto,f.id_familia,p.nombre as producto,p.codigo,ctz_first.observaciones,f.nombre as familia,ii.imagen FROM productos p LEFT JOIN familias f ON p.id_familia = f.id_familia LEFT JOIN images ii ON p.id_producto = ii.id_producto LEFT JOIN pendientes pnd ON p.id_producto = pnd.id_producto LEFT JOIN cotizaciones ctz_first ON ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario WHERE p.id_producto = ctz_min.id_producto AND (WEEKOFYEAR(ctz_min.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min.fecha_registro) = YEAR(CURDATE())) AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND (WEEKOFYEAR(ctz_min_precio.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min_precio.fecha_registro) = YEAR(CURDATE())) ) ORDER BY uss.orden ASC LIMIT 1) WHERE p.estatus = 1");

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


		// echo $this->db->last_query();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["observaciones"]	=	$comparativa[$i]->observaciones;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]	=	$comparativa[$i]->imagen;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$comparativa[$i]->tenencia;
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

/* End of file Sucursales_model.php */
/* Location: ./application/models/Sucursales_model.php */