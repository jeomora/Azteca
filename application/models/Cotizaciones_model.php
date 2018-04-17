<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizaciones";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getCotizaciones($where = []){
		$this->db->select("
			cotizaciones.id_cotizacion,
			cotizaciones.id_proveedor,
			cotizaciones.nombre AS promocion,
			cotizaciones.precio,
			cotizaciones.precio_promocion,
			cotizaciones.num_one,
			cotizaciones.num_two,
			cotizaciones.descuento,
			cotizaciones.fecha_registro,
			cotizaciones.fecha_caduca,
			cotizaciones.existencias,
			cotizaciones.observaciones,
			UPPER(CONCAT(u.nombre,' ',u.apellido)) AS proveedor,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by($this->TABLE_NAME.".id_producto")
		->order_by($this->TABLE_NAME.".precio_promocion", "ASC");
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

	public function getAnterior($where = []){
		$this->db->select("
			cotizaciones.id_cotizacion,
			cotizaciones.id_proveedor,
			cotizaciones.nombre AS promocion,
			cotizaciones.precio,
			cotizaciones.precio_promocion,
			cotizaciones.num_one,
			cotizaciones.num_two,
			cotizaciones.descuento,
			cotizaciones.fecha_registro,
			cotizaciones.fecha_caduca,
			cotizaciones.existencias,
			cotizaciones.observaciones,
			cotizaciones.id_producto,
			p.codigo,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by($this->TABLE_NAME.".id_producto")
		->order_by("p.nombre", "ASC");
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

	public function getCotz($where = [],$fech){
		$this->db->select("cotizaciones.id_cotizacion, 
			ctz_first.fecha_registro,prod.estatus,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fech)." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio ASC LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("fam.id_familia,prod.id_producto", "ASC");
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

	public function mejoresPrecios($where=[], $fech){
		$this->db->select("ctz_first.id_cotizacion, 
			ctz_first.fecha_registro,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fech)." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio ASC LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("prod.id_producto", "ASC");
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
		if ($comparativa) {
			if (is_array($where)) {
				return $comparativa;
			} else {
				return array_shift($comparativa);
			}
		} else {
			return false;
		}
	}

	public function comparaCotizaciones($where=[], $fech, $tienda){
		$this->db->select("ctz_first.id_cotizacion, 
			ctz_first.fecha_registro,
			cajas,piezas,pedido,prod.id_producto,id_pedido,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo,prod.estatus, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fech)." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio ASC LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("existencias","existencias.id_pedido = (SELECT existencias.id_pedido FROM existencias WHERE id_tienda = ".$tienda." AND existencias.id_producto = ctz_first.id_producto and WEEKOFYEAR(existencias.fecha_registro) = ".$this->weekNumber($fech)." GROUP BY existencias.id_producto)","LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("fam.id_familia,prod.nombre", "ASC");
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_pedido"]		=	$comparativa[$i]->id_pedido;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["cajas"]		=	$comparativa[$i]->cajas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["piezas"]		=	$comparativa[$i]->piezas;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pedido"]		=	$comparativa[$i]->pedido;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$stores = array(57, 58, 59, 60, 61, 62, 63);
			for ($d=0; $d < sizeof($stores); $d++) { 
				$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro ')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber())
				->where('id_producto',$comparativa[$i]->id_producto)
				->where('id_tienda',$stores[$d])
				->order_by("id_tienda", "ASC");
				$resu = $this->db->get()->result();
				if($resu){
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja".$d]		=	$resu[0]->cajas;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz".$d]	=	$resu[0]->piezas;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped".$d]	=	$resu[0]->pedido;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda".$d]	=	$resu[0]->id_tienda;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped".$d]	=	$resu[0]->id_pedido;
				}else{
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja".$d]		=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz".$d]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped".$d]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda".$d]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped".$d]	=	0;
				}
				
			}
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return array_shift($comparativaIndexada);
			}
		} else {
			return false;
		}
	}

	

	public function productos_proveedor($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion, cotizaciones.precio,
			p.id_producto, UPPER(p.nombre) AS producto,
			u.id_usuario, UPPER(CONCAT(u.nombre,' ',u.apellido)) AS proveedor")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where("WEEKOFYEAR(".$this->TABLE_NAME.".fecha_registro)", $this->weekNumber());
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

	public function get_cots($where=[],$producto=0,$fechas){
		$this->db->select("cotizaciones.id_cotizacion,cotizaciones.num_one, cotizaciones.num_two, cotizaciones.descuento, cotizaciones.id_proveedor, cotizaciones.precio_sistema, 
			cotizaciones.precio_four, cotizaciones.precio, cotizaciones.precio_promocion, cotizaciones.observaciones, 
			CONCAT(u.nombre,' ',u.apellido) as nomb")
		->from($this->TABLE_NAME)
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario", "INNER")
		->where($this->TABLE_NAME.".estatus", 1)
		->where($this->TABLE_NAME.".id_producto",$producto)
		->where("WEEKOFYEAR(".$this->TABLE_NAME.".fecha_registro)", $this->weekNumber($fechas))
		->group_by("nomb");

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
			return $result;
		} else {
			return false;
		}
	}

	public function pedido($where=[],$productos=""){
		$this->db->select("cotizaciones.id_cotizacion, 
			ctz_first.fecha_registro,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,
			prod.id_producto AS id_prod,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.id_proveedor AS id_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.id_proveedor AS id_next,
			ctz_next.observaciones AS promocion_next,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND ctz_min.precio = (SELECT MIN(ctz_min_precio.precio) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.precio >= ctz_first.precio AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber()." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_firs,t", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where($this->TABLE_NAME.".id_producto", $productos)
		->group_by("cotizaciones.id_producto")
		->order_by("prod.id_producto", "ASC");
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


	public function pedidos_proveedor($where=[],$tienda=""){
		$this->db->select("ctz_first.id_cotizacion, 
							ctz_first.fecha_registro,
							cajas, piezas, pedido, id_pedido, id_tienda,
							fam.id_familia, fam.nombre AS familia,
							prod.codigo, prod.nombre AS producto,
							UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
							ctz_first.precio AS precio_firsto,
							IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
							ctz_first.observaciones AS promocion_first,
							ctz_first.nombre AS observaciones_first,
							ctz_first.precio_sistema,
							ctz_first.precio_four,
							UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
							ctz_next.fecha_registro AS fecha_next,
							ctz_next.observaciones AS promocion_next,
							ctz_next.precio AS precio_nexto,
							IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
							ctz_maxima.precio AS precio_maximo,
							AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND ctz_min.precio = (SELECT MIN(ctz_min_precio.precio) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "INNER")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.precio >= ctz_first.precio AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber()." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("existencias","existencias.id_pedido = (SELECT existencias.id_pedido FROM existencias WHERE id_tienda = ".$tienda." AND existencias.id_cotizacion = ctz_first.id_cotizacion)","LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("prod.id_producto", "ASC");
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
			return $result;
		} else {
			return false;
		}
	}




	public function preciosBajos($where=[]){
		$this->db->select("cotizaciones.id_cotizacion,
			prod.codigo, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_first,
			ctz_first.precio_promocion AS precio_promocion_first,
			ctz_first.nombre AS promocion_first,
			ctz_first.descuento AS descuento_first,
			ctz_first.observaciones AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.precio AS precio_next,
			ctz_next.precio_promocion AS precio_promocion_next,
			ctz_next.nombre AS promocion_next,
			ctz_next.descuento AS descuento_next,
			ctz_next.observaciones AS observaciones_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND ctz_min.precio = (SELECT MIN(ctz_min_precio.precio) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto) LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto) LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.precio >= ctz_first.precio AND cotizaciones.id_cotizacion <> ctz_first.id_cotizacion ORDER BY cotizaciones.precio ASC LIMIT 1)", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_max", "ctz_maxima.id_proveedor = proveedor_max.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("prod.id_producto", "ASC");
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
	public function comparaCotizaciones2($where=[], $fech, $tienda){
		$this->db->select("ctz_first.id_cotizacion, 
			ctz_first.fecha_registro,
			prod.id_producto,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo,prod.estatus, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fech)." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio ASC LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("cotizaciones.id_producto")
		->order_by("fam.id_familia,prod.nombre", "ASC");
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["promocion_next"]	=	$comparativa[$i]->promocion_next;
		}
		if ($comparativaIndexada) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return array_shift($comparativaIndexada);
			}
		} else {
			return false;
		}
	}

}




/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */