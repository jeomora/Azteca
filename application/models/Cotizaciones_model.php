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
			UPPER(u.nombre) AS proveedor,
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
	public function getAllCotizaciones($where = []){
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
			UPPER(u.nombre) AS proveedor,
			p.nombre AS producto,p.codigo")
		->from($this->TABLE_NAME)
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
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
			p.codigo,p.color,p.colorp,
			p.nombre AS producto,
			fal.fecha_termino,fal.no_semanas")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("faltantes fal", $this->TABLE_NAME.".id_producto = fal.id_producto AND ".$this->TABLE_NAME.".id_proveedor = fal.id_proveedor AND ".$this->TABLE_NAME.".fecha_registro < fal.fecha_registro AND fal.fecha_termino > CURDATE() ","LEFT")
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


/*Posible Consulta
$this->db->select("c.id_cotizacion,
			ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,
			prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.observaciones AS observaciones_first,
			prod.precio_sistema,
			prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,
			ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,
			IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia,")
		->from("prodandprice prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto
			AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto
			AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->group_by("prod.nombre")
		->order_by("prod.id_familia,prod.nombre", "ASC");
*/
	public function getCotz($where = [],$fech){
		$this->db->select("c.id_cotizacion,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.observaciones AS promocion_nxts,ctz_nxts.precio AS precio_nxtso,
			IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = WEEKOFYEAR(CURDATE()) AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "c.id_cotizacion = ctz_first.id_cotizacion AND ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones	ctz_min
		WHERE	c.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = WEEKOFYEAR(CURDATE()) AND ctz_min.precio_promocion =
			(SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND
			ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = WEEKOFYEAR(CURDATE())) LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "c.id_cotizacion = ctz_maxima.id_cotizacion AND ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE
		ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE
		ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = WEEKOFYEAR(CURDATE())) LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "c.id_cotizacion = ctz_next.id_cotizacion AND ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE
		cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND
		WEEKOFYEAR(cott.fecha_registro) = WEEKOFYEAR(CURDATE()) AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "c.id_cotizacion = ctz_nxts.id_cotizacion AND ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE
		cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND
		WEEKOFYEAR(cots.fecha_registro) = WEEKOFYEAR(CURDATE()) AND cots.id_proveedor <> ctz_first.id_proveedor AND
		cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->group_by("prod.nombre")
		->order_by("prod.id_familia,prod.nombre", "ASC");
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
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
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
		->order_by("prod.nombre", "ASC");
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
			cajas,piezas,pedido,prod.id_producto,id_pedido,prod.color,prod.colorp,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo,prod.estatus, prod.nombre AS producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			sist.precio_sistema,
			sist.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("cotizaciones c", $this->TABLE_NAME.".id_cotizacion = c.id_cotizacion AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->join("productos prod", "c.id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE c.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber($fech)." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto AND WEEKOFYEAR(sist.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->join("existencias","existencias.id_pedido = (SELECT existencias.id_pedido FROM existencias WHERE id_tienda = ".$tienda." AND existencias.id_producto = ctz_first.id_producto and WEEKOFYEAR(existencias.fecha_registro) = ".$this->weekNumber($fech)." GROUP BY existencias.id_producto)","LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("c.id_producto")
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["color"]	=	$comparativa[$i]->color;
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
			p.id_producto, UPPER(p.nombre) AS producto,p.color,p.colorp,
			u.id_usuario, UPPER(u.nombre) AS proveedor")
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
			u.nombre as nomb")
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
			UPPER(proveedor_first.nombre) AS proveedor_first,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			ctz_first.id_proveedor AS id_first,
			sist.precio_sistema,
			sist.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.id_proveedor AS id_next,
			ctz_next.observaciones AS promocion_next,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto
			AND ctz_min.precio = (SELECT MIN(ctz_min_precio.precio) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
			AND cotizaciones.precio >= ctz_first.precio AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber()." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor LIMIT 1 )", "LEFT")

		->join("usuarios proveedor_firs,t", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where("WEEKOFYEAR(sist.fecha_registro)", $this->weekNumber())
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
							UPPER(proveedor_first.nombre) AS proveedor_first,
							ctz_first.precio AS precio_firsto,
							IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
							ctz_first.observaciones AS promocion_first,
							ctz_first.nombre AS observaciones_first,
							sist.precio_sistema,
							sist.precio_four,
							UPPER(proveedor_next.nombre) AS proveedor_next,
							ctz_next.fecha_registro AS fecha_next,
							ctz_next.observaciones AS promocion_next,
							ctz_next.precio AS precio_nexto,
							IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
							ctz_maxima.precio AS precio_maximo,
							AVG(cotizaciones.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto", "INNER")
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
		->where("WEEKOFYEAR(sist.fecha_registro)", $this->weekNumber())
		->group_by("cotizaciones.id_producto")
		->order_by("fam.id_familia,prod.id_producto", "ASC");
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
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_first,
			ctz_first.precio_promocion AS precio_promocion_first,
			ctz_first.nombre AS promocion_first,
			ctz_first.descuento AS descuento_first,
			ctz_first.observaciones AS observaciones_first,
			ctz_first.precio_sistema,
			ctz_first.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
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
		$this->db->select("c.id_cotizacion,
			ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,
			prod.id_familia, prod.familia AS familia,
			prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			prod.precio_sistema,
			prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,
			ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,
			IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio")
		->from("prodandprice prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE c.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto
			AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto
			AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->group_by("prod.nombre")
		->order_by("prod.id_familia,prod.nombre", "ASC");
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
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

	public function getCotzV($where = [],$fech){
		$this->db->select("c.id_cotizacion,
			ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			sist.precio_sistema,
			sist.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,
			ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,
			IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio")
		->from("productos prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")

		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE c.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto
			AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto
			AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "LEFT")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto AND WEEKOFYEAR(sist.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->where("prod.estatus","2")
		->group_by("prod.nombre")
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

	public function get_cotdel($where=[],$producto=0,$fechas){
		$this->db->select("cotizaciones.id_cotizacion,cotizaciones.num_one, cotizaciones.num_two, cotizaciones.descuento, cotizaciones.id_proveedor, cotizaciones.precio_sistema,
			cotizaciones.precio_four, cotizaciones.precio, cotizaciones.precio_promocion, cotizaciones.observaciones,
			u.nombre as nomb")
		->from($this->TABLE_NAME)
		->join("usuarios u", $this->TABLE_NAME.".id_proveedor = u.id_usuario", "INNER")
		->where($this->TABLE_NAME.".estatus", 0)
		->where($this->TABLE_NAME.".id_producto",$producto)
		->where("WEEKOFYEAR(".$this->TABLE_NAME.".fecha_registro)", $this->weekNumber($fechas));

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

	public function getLastWeek($where=[],$producto = 0,$fechas){
		$this->db->select("ctz_first.precio_promocion, u.nombre AS nomb")
			->from($this->TABLE_NAME)
			->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$fechas." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$fechas.") LIMIT 1)", "LEFT")
			->join("usuarios u","ctz_first.id_proveedor = u.id_usuario","LEFT")
			->where($this->TABLE_NAME.".estatus", 1)
			->where("WEEKOFYEAR(".$this->TABLE_NAME.".fecha_registro)", $fechas)
			->where($this->TABLE_NAME.".id_producto", $producto)
			->group_by("ctz_first.id_proveedor");

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

	public function getPedidosAll($where=[],$fech=0,$tienda){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->db->select("ctz_first.id_cotizacion,
			ctz_first.fecha_registro,
			prod.id_producto,prod.color,prod.colorp,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo,prod.estatus, prod.nombre AS producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			sist.precio_sistema,
			sist.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,sto.cantidad as stocant,
			AVG(c.precio) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("cotizaciones c", "cotizaciones.id_cotizacion = c.id_cotizacion AND WEEKOFYEAR(c.fecha_registro) =".$this->weekNumber($fech)." " ,"RIGHT")
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE c.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cc.id_cotizacion FROM cotizaciones cc WHERE cc.id_producto = ctz_first.id_producto AND cc.estatus = 1 AND cc.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cc.fecha_registro) = ".$this->weekNumber($fech)." AND cc.id_proveedor <> ctz_first.id_proveedor ORDER BY cc.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto AND WEEKOFYEAR(sist.fecha_registro) = ".$this->weekNumber($fech), "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("c.id_producto")
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["stocant"]	=	$comparativa[$i]->stocant;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja0"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz0"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped0"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda0"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped0"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja1"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz1"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped1"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda1"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped1"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja2"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz2"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped2"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda2"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped2"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja3"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz3"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped3"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda3"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped3"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja4"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz4"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped4"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda4"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped4"]	=	00;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja5"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz5"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped5"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda5"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped5"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja6"]		=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz6"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped6"]	=	"";
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda6"]	=	0;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped6"]	=	0;
			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($fech))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '57':
						$e = "0";
						break;
					case '58':
						$e = "1";
						break;
					case '59':
						$e = "2";
						break;
					case '60':
						$e = "3";
						break;
					case '61':
						$e = "4";
						break;
					case '62':
						$e = "5";
						break;
					case '63':
						$e = "6";
						break;
					default:
						$e = "7";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["idped".$e]	=	$resu[$d]->id_pedido;
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

	public function getPedidosSingle($where=[],$fech=0,$tienda){
		$this->db->select("ctz_first.id_cotizacion,
			ctz_first.fecha_registro,
			prod.id_producto,prod.color,prod.colorp,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo,prod.estatus, prod.nombre AS producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			sist.precio_sistema,
			sist.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio_promocion AS precio_maximo,
			AVG(c.precio_promocion) AS precio_promedio")
		->from($this->TABLE_NAME)
		->join("cotizaciones c", "cotizaciones.id_cotizacion = c.id_cotizacion AND WEEKOFYEAR(c.fecha_registro) =".$this->weekNumber($fech)." " ,"LEFT")
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "INNER")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE c.id_producto = ctz_max.id_producto
			AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cc.id_cotizacion FROM cotizaciones cc WHERE cc.id_producto = ctz_first.id_producto AND cc.estatus = 1 AND cc.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cc.fecha_registro) = 17 AND cc.id_proveedor <> ctz_first.id_proveedor ORDER BY cc.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "INNER")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->group_by("c.id_producto")
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["color"]	=	$comparativa[$i]->color;
			$pedidos = $this->db->select('id_pedido,
			  id_producto,
			  id_tienda,
			  cajas,
			  piezas,
			  pedido,
			  fecha_registro ')
			->from('existencias')
			->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($fech))
			->where('id_producto',$comparativa[$i]->id_producto)
			->where('id_tienda',$tienda)
			->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			if($resu){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["cajas"]		=	$resu[0]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["piezas"]	=	$resu[0]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pedido"]	=	$resu[0]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_pedido"]	=	$resu[0]->id_pedido;
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["cajas"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["piezas"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["pedido"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_cotizacion]["id_pedido"]	=	0;
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


	public function getProveedorBajos($where = [],$fech,$prove){
		$this->db->select("c.id_cotizacion, UPPER(prove_first.nombre) as proves, cs.precio_promocion as proves_promo, cs.precio as proves_precio, cs.observaciones as proves_obs,
			ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,
			UPPER(prove_first.nombre) AS prove_nombre,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,
			ctz_first.nombre AS observaciones_first,
			sist.precio_sistema,
			UPPER(proveedor_next.nombre) AS proveedor_next,
			ctz_next.fecha_registro AS fecha_next,
			ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			sist.precio_four")
		->from("productos prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.id_proveedor =".$prove, "RIGHT")
		->join("cotizaciones cs", "c.id_cotizacion = cs.id_cotizacion AND WEEKOFYEAR(cs.fecha_registro) = ".$this->weekNumber($fech)." AND cs.id_proveedor =".$prove, "RIGHT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE c.id_producto = ctz_min.id_producto
			AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto
			AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios prove_first", "cs.id_proveedor = prove_first.id_usuario", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "LEFT")
		->join("precio_sistema sist", "prod.id_producto = sist.id_producto AND WEEKOFYEAR(sist.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->where("prod.estatus <>","0")
		->group_by("prod.nombre")
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

	public function getfalts($where = []){
		$this->db->select("
			fal.id_faltante,p.id_producto,
			p.codigo,p.color,p.colorp,
			p.nombre AS producto,
			fal.fecha_termino,fal.no_semanas")
		->from("productos p")
		->join("faltantes fal", "p.id_producto = fal.id_producto AND fal.fecha_termino > CURDATE() ","LEFT")
		->group_by("p.id_producto")
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
}





/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */
