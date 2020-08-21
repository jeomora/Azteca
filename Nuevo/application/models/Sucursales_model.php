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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$comparativa[$i]->cedis;
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo = new DateInterval('P14D');
		$intervalo = new DateInterval('P21D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$user = $this->session->userdata();

		$this->db->select("p.unidad,ex3.cajas as ex3cajas,ex3.piezas as ex3piezas,ex2.cajas as ex2cajas,ex2.piezas as ex2piezas,ex.cajas as excajas,ex.piezas as expiezas,thw.abarrotes as thwabarrotes,thw.cedis as thwcedis,thw.mercado as thwmercado,thw.villas as thwpedregal,thw.tienda thwtienda,thw.trincheras thwtrincheras,thw.tenencia as thwtenencia,thw.ultra as thwultra,thw.tijeras as thwtijeras, ow.abarrotes as oabarrotes,ow.cedis as ocedis,ow.mercado as omercado,ow.villas as opedregal,ow.tienda as otienda,ow.trincheras as otrincheras,ow.tenencia as otenencia, ow.ultra as oultra, ow.tijeras as otijeras,tw.abarrotes as tabarrotes,tw.cedis as tcedis,tw.mercado as tmercado,tw.villas as tpedregal,tw.tienda as ttienda,tw.trincheras as ttrincheras,tw.tenencia as ttenencia,tw.ultra as tultra, tw.tijeras as ttijeras,pnd.abarrotes,pnd.cedis,pnd.mercado,pnd.pedregal,pnd.tienda,pnd.trincheras,pnd.tenencia,pnd.ultra,pnd.tijeras, p.color,p.id_producto,f.id_familia,p.nombre as producto,p.codigo,ctz_first.observaciones,f.nombre as familia,ii.imagen FROM productos p LEFT JOIN familias f ON p.id_familia = f.id_familia LEFT JOIN images ii ON p.id_producto = ii.id_producto LEFT JOIN pendientes pnd ON p.id_producto = pnd.id_producto LEFT JOIN cotizaciones ctz_first ON ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario WHERE p.id_producto = ctz_min.id_producto AND (WEEKOFYEAR(ctz_min.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min.fecha_registro) = YEAR(CURDATE())) AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND (WEEKOFYEAR(ctz_min_precio.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min_precio.fecha_registro) = YEAR(CURDATE())) ) ORDER BY uss.orden ASC LIMIT 1) LEFT JOIN (SELECT * FROM finales WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) ow ON p.id_producto = ow.id_producto LEFT JOIN (SELECT * FROM finales WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha2->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha2->format('Y-m-d H:i:s')."') ) tw ON p.id_producto = tw.id_producto LEFT JOIN (SELECT * FROM finales WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha3->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha3->format('Y-m-d H:i:s')."') ) thw ON p.id_producto = thw.id_producto LEFT JOIN (SELECT * FROM existencias WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha3->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha3->format('Y-m-d H:i:s')."') AND id_tienda = ".$user['id_usuario']." ) ex3 ON p.id_producto = ex3.id_producto LEFT JOIN (SELECT * FROM existencias WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha2->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha2->format('Y-m-d H:i:s')."') AND id_tienda = ".$user['id_usuario']." ) ex2 ON p.id_producto = ex2.id_producto LEFT JOIN (SELECT * FROM existencias WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') AND id_tienda = ".$user['id_usuario']." ) ex ON p.id_producto = ex.id_producto WHERE p.estatus = 1");

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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]	=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]	=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["excajas"]	=	$comparativa[$i]->excajas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["expiezas"]	=	$comparativa[$i]->expiezas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ex2cajas"]	=	$comparativa[$i]->ex2cajas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ex2piezas"]	=	$comparativa[$i]->ex2piezas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ex3cajas"]	=	$comparativa[$i]->ex3cajas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ex3piezas"]	=	$comparativa[$i]->ex3piezas;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$comparativa[$i]->tenencia;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][57]	=	$comparativa[$i]->oabarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][87]	=	$comparativa[$i]->ocedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][89]	=	$comparativa[$i]->ocedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][58]	=	$comparativa[$i]->otienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][60]	=	$comparativa[$i]->otrincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][63]	=	$comparativa[$i]->otijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][59]	=	$comparativa[$i]->oultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][61]	=	$comparativa[$i]->omercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][90]	=	$comparativa[$i]->opedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["oneweek"][62]	=	$comparativa[$i]->otenencia;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][57]	=	$comparativa[$i]->tabarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][87]	=	$comparativa[$i]->tcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][89]	=	$comparativa[$i]->tcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][58]	=	$comparativa[$i]->ttienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][60]	=	$comparativa[$i]->ttrincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][63]	=	$comparativa[$i]->ttijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][59]	=	$comparativa[$i]->tultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][61]	=	$comparativa[$i]->tmercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][90]	=	$comparativa[$i]->tpedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["twoweek"][62]	=	$comparativa[$i]->ttenencia;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][57]	=	$comparativa[$i]->thwabarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][87]	=	$comparativa[$i]->thwcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][89]	=	$comparativa[$i]->thwcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][58]	=	$comparativa[$i]->thwtienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][60]	=	$comparativa[$i]->thwtrincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][63]	=	$comparativa[$i]->thwtijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][59]	=	$comparativa[$i]->thwultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][61]	=	$comparativa[$i]->thwmercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][90]	=	$comparativa[$i]->thwpedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["thrweek"][62]	=	$comparativa[$i]->thwtenencia;
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