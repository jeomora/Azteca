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
			UPPER(u.nombre) AS proveedor, p.nombre AS producto")
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

	public function getDiferences($where = []){
		$fecha = new DateTime(date('Y-m-d'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$result = $this->db->query("SELECT c.id_cotizacion,p.codigo,p.nombre as descrip,p.precio_sistema, c.precio_promocion, c.id_proveedor,u.nombre, (p.precio_sistema - c.precio_promocion) AS diferencia,
		c.precio, c.fecha_registro, c.estatus, c.observaciones, c.descuento, c.num_one, c.num_two
		FROM prodandprice p LEFT JOIN cotizaciones c ON p.id_producto = c.id_producto and c.estatus = 1  
		LEFT JOIN usuarios u ON c.id_proveedor = u.id_usuario WHERE WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fecha->format('Y-m-d H:i:s'))." AND 
		(p.precio_sistema - c.precio_promocion) > (p.precio_sistema * 0.2) OR 

		WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fecha->format('Y-m-d H:i:s'))." AND (c.precio_promocion - p.precio_sistema) > (p.precio_sistema * 0.2) ORDER BY diferencia DESC")->result();

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
		$this->db->select("c.id_cotizacion,c.id_proveedor,c.precio,c.precio_promocion,c.observaciones,c.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.producto AS producto,prod.id_producto,UPPER(proveedor.nombre) AS proveedor, MAX(c.precio) AS precio_maximo, AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodis prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("usuarios proveedor", "c.id_proveedor = proveedor.id_usuario", "LEFT")
		->group_by("c.id_cotizacion")
		->order_by("prod.id_familia,prod.producto", "ASC");
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
		$this->db->select("c.id_cotizacion,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,ctz_first.precio AS precio_firsto,cajas,piezas,pedido,id_pedido,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.observaciones AS promocion_nxts,ctz_nxts.precio AS precio_nxtso,
			IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,ctz_maxima.precio AS precio_maximo,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("existencias","existencias.id_pedido = (SELECT existencias.id_pedido FROM existencias WHERE id_tienda = ".$tienda." AND existencias.id_producto = ctz_first.id_producto and WEEKOFYEAR(existencias.fecha_registro) = ".$this->weekNumber($fech)." GROUP BY existencias.id_producto)","LEFT")
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
		$this->db->select("c.id_cotizacion,c.id_proveedor,c.precio,c.precio_promocion,c.observaciones,c.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.producto AS producto,prod.id_producto,UPPER(proveedor.nombre) AS proveedor, mx.maxis AS precio_maximo, mx.prome AS precio_promedio,prod.id_familia, prod.familia AS familia,prod.precio_sistema,prod.precio_four")
		->from("prodis prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("maxis mx", "prod.id_producto = mx.id_producto", "LEFT")
		->join("usuarios proveedor", "c.id_proveedor = proveedor.id_usuario", "LEFT")
		->group_by("prod.codigo,c.id_cotizacion")
		->order_by("prod.id_familia,prod.producto,c.precio_promocion", "ASC");
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
		$flag = 1;

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
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto])) {
				if ($flag == 1) {
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nexto"]		=	$comparativa[$i]->precio;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_next"]		=	$comparativa[$i]->precio_promocion;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_next"]	=	$comparativa[$i]->proveedor;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_next"]	=	$comparativa[$i]->observaciones;
					$flag = 2;
				} elseif ($flag == 2) {
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxtso"]		=	$comparativa[$i]->precio;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxts"]		=	$comparativa[$i]->precio_promocion;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_nxts"]	=	$comparativa[$i]->observaciones;
					$flag = 3;
				}
				
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["producto"]			=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["estatus"]			=	$comparativa[$i]->estatus;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_producto"]		=	$comparativa[$i]->id_producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["colorp"]			=	$comparativa[$i]->colorp;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["color"]			=	$comparativa[$i]->color;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_four"]		=	$comparativa[$i]->precio_four;
				$flag = 1;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_firsto"]	=	$comparativa[$i]->precio;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_first"]		=	$comparativa[$i]->precio_promocion;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_first"]	=	$comparativa[$i]->proveedor;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_first"]	=	$comparativa[$i]->observaciones;

				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nexto"]		=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_next"]		=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_next"]	=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_next"]	=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxtso"]		=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_nxts"]		=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["proveedor_nxts"]	=	NULL;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["promocion_nxts"]	=	NULL;
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

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha1 = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$fecha4 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo2 = new DateInterval('P14D');
		$intervalo3 = new DateInterval('P21D');
		$intervalo4 = new DateInterval('P28D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$fecha4->sub($intervalo4);
		$user = $this->session->userdata();

		$this->db->select("fpast.fecha_registro as fpastfecha,prod.obis,prod.unidad,ppast.precio_sistema as ppasts,ppast.precio_four as ppastf,fpast.abarrotes as fpastabarrotes,fpast.cedis as fpastcedis,fpast.mercado as fpastmercado,fpast.villas as fpastpedregal,fpast.tienda fpasttienda,fpast.trincheras fpasttrincheras,fpast.tenencia as fpasttenencia,fpast.ultra as fpastultra,fpast.tijeras as fpasttijeras,r.precio AS reales,exist,prod.abarrotes,prod.cedis,prod.mercado,prod.pedregal,prod.tienda,prod.trincheras,prod.tenencia,prod.ultra,prod.tijeras,ctz_first.id_cotizacion,prod.registrazo,inv.codigo as codigo_factura ,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,proveedor_first.cargo,ctz_first.precio AS precio_firsto,sto.cantidad as stocant,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.fecha_registro AS fecha_nxts,ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,ii.imagen,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->join("images ii","prod.id_producto = ii.id_producto ","LEFT")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.estatus = 1 AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("(select * from prodcaja order by id_prodcaja DESC) fac", "prod.id_producto = fac.id_producto AND ctz_first.id_proveedor = fac.id_proveedor", "LEFT")
		->join("invoice_codes inv", "fac.id_invoice = inv.id_invoice", "LEFT")
		->join("(SELECT * from precio_sistema WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as ppast","prod.id_producto = ppast.id_producto","LEFT" )
		->join("(SELECT * from llegaron WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as fpast","prod.id_producto = fpast.id_producto","LEFT" )
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]		=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo_factura"]		=	$comparativa[$i]->codigo_factura;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["exist"]	=	$comparativa[$i]->exist;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["stocant"]	=	$comparativa[$i]->stocant;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cargo"]	=	$comparativa[$i]->cargo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["reales"]	=	$comparativa[$i]->reales;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["registrazo"]	=	$comparativa[$i]->registrazo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["abarrotes"]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cedis"]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda"]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["trincheras"]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tijeras"]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ultra"]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["mercado"]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pedregal"]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tenencia"]	=	$comparativa[$i]->tenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["obis"]		=	$comparativa[$i]->obis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]		=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppastf"]		=	$comparativa[$i]->ppastf;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppasts"]		=	$comparativa[$i]->ppasts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ffecha"]		=	$comparativa[$i]->fpastfecha;
			

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$comparativa[$i]->fpastabarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$comparativa[$i]->fpastcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$comparativa[$i]->fpastcedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$comparativa[$i]->fpasttienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$comparativa[$i]->fpasttrincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$comparativa[$i]->fpasttijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$comparativa[$i]->fpastultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$comparativa[$i]->fpastmercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$comparativa[$i]->fpastpedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$comparativa[$i]->fpasttenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz9"]	=	"";

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda4"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped4"]	=	00;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda9"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped9"]	=	0;
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
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($fecha->format('Y-m-d H:i:s')))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["idped".$e]	=	$resu[$d]->id_pedido;
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

	public function getAnteriores($where = [],$fech){
		$this->db->select("p.nombre, f.nombre AS familia,p.codigo,p.estatus,p.color,p.colorp,c.id_cotizacion,c.fecha_registro AS cfecha, c.precio, c.precio_promocion, c.descuento, c.estatus, c.num_one, c.num_two, c.observaciones, ps.fecha_registro AS psfecha, ps.precio_sistema, ps.precio_four, u.nombre as proveedor")
		->from("productos p")
		->join("cotizacionesback c", "p.id_producto = c.id_producto AND c.estatus = 1 AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech), "LEFT")
		->join("usuarios u", "c.id_proveedor = u.id_usuario", "LEFT")
		->join("precio_sistemaback ps", "p.id_producto = ps.id_producto AND WEEKOFYEAR(ps.fecha_registro) =".$this->weekNumber($fech), "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->where("p.estatus <>", 0)
		->order_by("p.id_familia,p.nombre,c.precio_promocion", "ASC");
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

	public function fill_ex($where = [],$fech){
		$this->db->select("e.cajas,e.pedido,e.piezas,e.id_pedido,p.id_producto,p.nombre AS producto,e.id_tienda,f.id_familia, f.nombre AS familia, u.nombre AS sucursal, p.codigo, e.fecha_registro, p.color, p.colorp, p.estatus")
		->from("productos p")
		->join("existencias e", "p.id_producto = e.id_producto AND WEEKOFYEAR(e.fecha_registro) = ".$this->weekNumber($fech), "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->join("usuarios u", "e.id_tienda = u.id_usuario", "LEFT")
		->where("p.estatus <>",0)
		->order_by("f.id_familia,p.nombre", "ASC");
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
		$prodss = "";
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if ($prodss <> $comparativa[$i]->producto){
				$prodss = $comparativa[$i]->producto;
				if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
					# code...
				}else{
					$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
					$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["producto"]		=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["estatus"]		=	$comparativa[$i]->estatus;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["colorp"]	=	$comparativa[$i]->colorp;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["color"]	=	$comparativa[$i]->color;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["fecha_registro"]		=	$comparativa[$i]->fecha_registro;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja0"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz0"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped0"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda0"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped0"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja1"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz1"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped1"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda1"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped1"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja2"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz2"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped2"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda2"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped2"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja3"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz3"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped3"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda3"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped3"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja4"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz4"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped4"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda4"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped4"]	=	00;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja5"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz5"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped5"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda5"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped5"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja6"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz6"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped6"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda6"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped6"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja7"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz7"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped7"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda7"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped7"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja8"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz8"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped8"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda8"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped8"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja9"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz9"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped9"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda9"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped9"]	=	0;

				switch ($comparativa[$i]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '58':
						$e = "2";
						break;
					case '59':
						$e = "3";
						break;
					case '60':
						$e = "4";
						break;
					case '61':
						$e = "5";
						break;
					case '62':
						$e = "6";
						break;
					case '63':
						$e = "7";
						break;
					case '89':
						$e = "8";
						break;
					case '90':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja".$e]		=	$comparativa[$i]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz".$e]	=	$comparativa[$i]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped".$e]	=	$comparativa[$i]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda".$e]	=	$comparativa[$i]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped".$e]	=	$comparativa[$i]->id_pedido;
			}else{
				switch ($comparativa[$i]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '58':
						$e = "2";
						break;
					case '59':
						$e = "3";
						break;
					case '60':
						$e = "4";
						break;
					case '61':
						$e = "5";
						break;
					case '62':
						$e = "6";
						break;
					case '63':
						$e = "7";
						break;
					case '89':
						$e = "8";
						break;
					case '90':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja".$e]		=	$comparativa[$i]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz".$e]	=	$comparativa[$i]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped".$e]	=	$comparativa[$i]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda".$e]	=	$comparativa[$i]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped".$e]	=	$comparativa[$i]->id_pedido;
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


	public function fill_exNot($where = [],$fech){
		$this->db->select("e.cajas,e.pedido,e.piezas,e.id_pedido,p.id_producto,p.nombre AS producto,e.id_tienda,f.id_familia, f.nombre AS familia, u.nombre AS sucursal, p.codigo, e.fecha_registro, p.color, p.colorp, p.estatus")
		->from("productos p")
		->join("existencias e", "p.id_producto = e.id_producto AND WEEKOFYEAR(e.fecha_registro) = ".$this->weekNumber($fech), "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->join("usuarios u", "e.id_tienda = u.id_usuario", "LEFT")
		->where("p.estatus <>",0)
		->where("p.id_producto NOT IN (SELECT c.id_producto FROM cotizaciones c WHERE WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." GROUP BY c.id_producto)")
		->order_by("f.id_familia,p.nombre", "ASC");
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
		$prodss = "";
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if ($prodss <> $comparativa[$i]->producto){
				$prodss = $comparativa[$i]->producto;
				if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
					# code...
				}else{
					$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
					$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
					$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["producto"]		=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["estatus"]		=	$comparativa[$i]->estatus;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["colorp"]	=	$comparativa[$i]->colorp;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["color"]	=	$comparativa[$i]->color;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["fecha_registro"]		=	$comparativa[$i]->fecha_registro;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja0"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz0"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped0"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda0"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped0"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja1"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz1"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped1"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda1"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped1"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja2"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz2"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped2"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda2"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped2"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja3"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz3"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped3"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda3"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped3"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja4"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz4"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped4"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda4"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped4"]	=	00;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja5"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz5"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped5"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda5"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped5"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja6"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz6"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped6"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda6"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped6"]	=	0;

				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja7"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz7"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped7"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda7"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped7"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja8"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz8"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped8"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda8"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped8"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja9"]		=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz9"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped9"]	=	"";
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda9"]	=	0;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped9"]	=	0;


				switch ($comparativa[$i]->id_tienda) {
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

					case '87':
						$e = "7";
						break;
					case '89':
						$e = "8";
						break;
					case '90':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja".$e]		=	$comparativa[$i]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz".$e]	=	$comparativa[$i]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped".$e]	=	$comparativa[$i]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda".$e]	=	$comparativa[$i]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped".$e]	=	$comparativa[$i]->id_pedido;
			}else{
				switch ($comparativa[$i]->id_tienda) {
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
					case '87':
						$e = "7";
						break;
					case '89':
						$e = "8";
						break;
					case '90':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja".$e]		=	$comparativa[$i]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz".$e]	=	$comparativa[$i]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped".$e]	=	$comparativa[$i]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda".$e]	=	$comparativa[$i]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped".$e]	=	$comparativa[$i]->id_pedido;
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

	public function getCotzP($where = [],$fech, $directo){
		$this->db->select("u.nombre")
		->from("cotizaciones c")
		->join("productos p", "c.id_producto = p.id_producto", "LEFT")
		->join("usuarios u", " c.id_proveedor = u.id_usuario", "LEFT")
		->where("WEEKOFYEAR(c.fecha_registro)",$this->weekNumber($fech))
		->where("p.estatus", $directo)
		->group_by("id_proveedor")
		->order_by("id_proveedor", "DESC");
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
				return $comparativa;
			}
		} else {
			return false;
		}

	}

	public function getCotzD($where = [],$fech, $directo){
		$this->db->select("c.id_cotizacion,p.codigo,p.id_producto,p.nombre as producto,c.id_proveedor, u.nombre,c.precio,c.precio_promocion,c.fecha_registro,p.estatus,c.observaciones,c.descuento,c.num_one,c.num_two,u.nombre as proveedor,fam.nombre as familia, sist.precio_sistema, c.precio, c.precio_promocion, sist.precio_four")
		->from("productos p")
		->join("cotizaciones c", "p.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech), "LEFT")
		->join("usuarios u", " c.id_proveedor = u.id_usuario", "LEFT")
		->join("familias fam", "p.id_familia = fam.id_familia", "LEFT")
		->join("precio_sistema sist", "p.id_producto = sist.id_producto AND WEEKOFYEAR(sist.fecha_registro) = ".$this->weekNumber($fech)." ", "LEFT")
		->where("p.estatus", $directo)
		->order_by("c.id_producto, c.precio_promocion", "ASC");
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
			if (isset($comparativaIndexada[$comparativa[$i]->id_producto])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_producto]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_producto]["producto"]	=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"]	=	[];
			}
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]	=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["id_proveedor"]		=	$comparativa[$i]->id_proveedor;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["proveedor"]		=	$comparativa[$i]->proveedor;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["precio_promocion"]	=	$comparativa[$i]->precio_promocion;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["precio"]	=	$comparativa[$i]->precio;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["familia"]	=	$comparativa[$i]->familia;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["precio_four"]	=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["num_one"]	=	$comparativa[$i]->num_one;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["num_two"]	=	$comparativa[$i]->num_two;
			$comparativaIndexada[$comparativa[$i]->id_producto]["articulos"][$comparativa[$i]->id_cotizacion]["observaciones"]	=	$comparativa[$i]->observaciones;

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

	public function getCotiz($where=[], $fech, $values){
		$value = json_decode($values);
		$this->db->select("c.id_cotizacion,c.id_proveedor,c.precio,c.precio_promocion,c.observaciones,c.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.producto AS producto,prod.id_producto,UPPER(proveedor.nombre) AS proveedor, MAX(c.precio) AS precio_maximo, AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia,prod.precio_sistema,prod.precio_four from prodis prod left join cotizaciones c on prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1 left join usuarios proveedor on c.id_proveedor = proveedor.id_usuario where (prod.producto LIKE '%".$value->busca."%' OR prod.codigo LIKE '%".$value->busca."%') GROUP BY prod.codigo,c.id_cotizacion")
		->order_by("prod.id_familia,prod.producto,c.precio_promocion", "ASC");
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
		$flag = 1;

		// echo $this->db->last_query();
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->producto])) {
				if ($flag == 1) {
					$comparativaIndexada[$comparativa[$i]->producto]["precio_nexto"]		=	$comparativa[$i]->precio;
					$comparativaIndexada[$comparativa[$i]->producto]["precio_next"]		=	$comparativa[$i]->precio_promocion;
					$comparativaIndexada[$comparativa[$i]->producto]["proveedor_next"]	=	$comparativa[$i]->proveedor;
					$comparativaIndexada[$comparativa[$i]->producto]["promocion_next"]	=	$comparativa[$i]->observaciones;
					$flag = 2;
				} elseif ($flag == 2) {
					$comparativaIndexada[$comparativa[$i]->producto]["precio_nxtso"]		=	$comparativa[$i]->precio;
					$comparativaIndexada[$comparativa[$i]->producto]["precio_nxts"]		=	$comparativa[$i]->precio_promocion;
					$comparativaIndexada[$comparativa[$i]->producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor;
					$comparativaIndexada[$comparativa[$i]->producto]["promocion_nxts"]	=	$comparativa[$i]->observaciones;
					$comparativaIndexada[$comparativa[$i]->producto]["familia"]	=	$comparativa[$i]->familia;
					$flag = 3;
				}
			}else{
				$comparativaIndexada[$comparativa[$i]->producto]				=	[];
				$comparativaIndexada[$comparativa[$i]->producto]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
				$comparativaIndexada[$comparativa[$i]->producto]["producto"]			=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->producto]["estatus"]			=	$comparativa[$i]->estatus;
				$comparativaIndexada[$comparativa[$i]->producto]["id_producto"]		=	$comparativa[$i]->id_producto;
				$comparativaIndexada[$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->producto]["colorp"]			=	$comparativa[$i]->colorp;
				$comparativaIndexada[$comparativa[$i]->producto]["color"]			=	$comparativa[$i]->color;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_four"]		=	$comparativa[$i]->precio_four;
				$flag = 1;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_firsto"]	=	$comparativa[$i]->precio;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_first"]	=	$comparativa[$i]->precio_promocion;
				$comparativaIndexada[$comparativa[$i]->producto]["proveedor_first"]	=	$comparativa[$i]->proveedor;
				$comparativaIndexada[$comparativa[$i]->producto]["promocion_first"]	=	$comparativa[$i]->observaciones;

				$comparativaIndexada[$comparativa[$i]->producto]["precio_nexto"]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_next"]		=	0;
				$comparativaIndexada[$comparativa[$i]->producto]["proveedor_next"]	=	"";
				$comparativaIndexada[$comparativa[$i]->producto]["promocion_next"]	=	"";
				$comparativaIndexada[$comparativa[$i]->producto]["precio_nxtso"]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto]["precio_nxts"]		=	0;
				$comparativaIndexada[$comparativa[$i]->producto]["proveedor_nxts"]	=	"";
				$comparativaIndexada[$comparativa[$i]->producto]["promocion_nxts"]	=	"";
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

	public function getCodesPromos($where=[]){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$this->db->select("p.color,p.id_producto,f.id_familia,p.nombre as producto,p.codigo,ctz_first.observaciones,f.nombre as familia,ii.imagen FROM productos p LEFT JOIN familias f ON p.id_familia = f.id_familia LEFT JOIN images ii ON p.id_producto = ii.id_producto LEFT JOIN cotizaciones ctz_first ON ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario WHERE p.id_producto = ctz_min.id_producto AND (WEEKOFYEAR(ctz_min.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min.fecha_registro) = YEAR(CURDATE())) AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND (WEEKOFYEAR(ctz_min_precio.fecha_registro) = WEEKOFYEAR(CURDATE()) AND YEAR(ctz_min_precio.fecha_registro) = YEAR(CURDATE())) ) ORDER BY uss.orden ASC LIMIT 1) WHERE p.estatus = 1 group by p.codigo");

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

	public function columnas($where=[]){
		$this->db->select("u.id_usuario,u.nombre,g.sumale from usuarios u LEFT JOIN (SELECT COUNT(c.id_proveedor) AS sumale,c.id_proveedor FROM cotizaciones c WHERE WEEKOFYEAR(c.fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY id_proveedor) g ON u.id_usuario = g.id_proveedor WHERE u.id_grupo = 2")
		->order_by("u.nombre", "ASC");
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
		$flag = 0;
		$columna = 7;
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if ($comparativa[$i]->sumale > 0) {
				$comparativaIndexada[$comparativa[$i]->id_usuario]["cuantos"]	= $comparativa[$i]->sumale;
				$comparativaIndexada[$comparativa[$i]->id_usuario]["nombre"]	= $comparativa[$i]->nombre;
				$comparativaIndexada[$comparativa[$i]->id_usuario]["columna"]	= $columna;
				$columna++;
				$flag++;
			}
		}
		$comparativaIndexada["none"]["cuantos"] = -1;
		$comparativaIndexada["none"]["nombre"] = "PD";
		$comparativaIndexada["none"]["columna"]	= $columna;

		if ($comparativa) {
			if (is_array($where)) {
				return $comparativaIndexada;
			} else {
				return $comparativaIndexada;
			}
		} else {
			return false;
		}
	}

	public function comparaCotizaciones3($where=[], $fech, $tienda){
		$this->db->select("r.precio AS reales,c.id_cotizacion,c.id_proveedor,c.precio,c.precio_promocion,c.observaciones,c.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.producto AS producto,prod.id_producto,UPPER(proveedor.nombre) AS proveedor,prod.id_familia, prod.familia AS familia,prod.precio_sistema,prod.precio_four")
		->from("prodis prod")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("usuarios proveedor", "c.id_proveedor = proveedor.id_usuario", "LEFT")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->group_by("prod.codigo,c.id_cotizacion")
		->order_by("prod.id_familia,prod.producto,c.precio_promocion", "ASC");
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
		$colu = $this->columnas(NULL);
		$flag = 1;
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia])) {
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]				=	[];
				$comparativaIndexada[$comparativa[$i]->id_familia]["familia"]	=	$comparativa[$i]->familia;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"]	=	[];
			}
			if (isset($comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto])) {
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["otros"][$comparativa[$i]->id_proveedor]["precio"]		=	$comparativa[$i]->precio_promocion;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["otros"][$comparativa[$i]->id_proveedor]["proveedor"]		=	$comparativa[$i]->id_proveedor;
			}else{
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["producto"]			=	$comparativa[$i]->producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["estatus"]			=	$comparativa[$i]->estatus;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_producto"]		=	$comparativa[$i]->id_producto;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["id_proveedor"]		=	$comparativa[$i]->id_proveedor;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["codigo"]			=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["colorp"]			=	$comparativa[$i]->colorp;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["color"]			=	$comparativa[$i]->color;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio_four"]		=	$comparativa[$i]->precio_four;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["reales"]			=	$comparativa[$i]->reales;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["primero"]			=	$comparativa[$i]->id_proveedor;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["precio"]			=	$comparativa[$i]->precio_promocion;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["provefirst"]		=	$comparativa[$i]->proveedor;
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

	public function getPedidosAllVolumen($where=[],$fech=0,$tienda){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha1 = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$fecha4 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo2 = new DateInterval('P14D');
		$intervalo3 = new DateInterval('P21D');
		$intervalo4 = new DateInterval('P28D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$fecha4->sub($intervalo4);
		$user = $this->session->userdata();

		$this->db->select("finlast.lastfecha,finlast.lastfecha as fpastfecha,prod.obis,prod.unidad,ppast.precio_sistema as ppasts,ppast.precio_four as ppastf,r.precio AS reales,exist,prod.abarrotes,prod.cedis,prod.mercado,prod.pedregal,prod.tienda,prod.trincheras,prod.tenencia,prod.ultra,prod.tijeras,ctz_first.id_cotizacion,prod.registrazo,inv.codigo as codigo_factura ,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,proveedor_first.cargo,ctz_first.precio AS precio_firsto,sto.cantidad as stocant,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.fecha_registro AS fecha_nxts,ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,ii.imagen,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->join("images ii","prod.id_producto = ii.id_producto ","LEFT")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.estatus = 1 AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("(select * from prodcaja order by id_prodcaja DESC) fac", "prod.id_producto = fac.id_producto AND ctz_first.id_proveedor = fac.id_proveedor", "LEFT")
		->join("invoice_codes inv", "fac.id_invoice = inv.id_invoice", "LEFT")
		->join("(SELECT * from precio_sistema WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as ppast","prod.id_producto = ppast.id_producto","LEFT" )
		->join("(SELECT MAX(fecha_registro) as lastfecha,id_producto from llegaron GROUP BY id_producto) as finlast","prod.id_producto = finlast.id_producto","LEFT" )
		->where("prod.estatus",2)
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]		=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["lastfecha"]		=	$comparativa[$i]->lastfecha;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo_factura"]		=	$comparativa[$i]->codigo_factura;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["exist"]	=	$comparativa[$i]->exist;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["stocant"]	=	$comparativa[$i]->stocant;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cargo"]	=	$comparativa[$i]->cargo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["reales"]	=	$comparativa[$i]->reales;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["registrazo"]	=	$comparativa[$i]->registrazo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["abarrotes"]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cedis"]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda"]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["trincheras"]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tijeras"]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ultra"]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["mercado"]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pedregal"]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tenencia"]	=	$comparativa[$i]->tenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["obis"]		=	$comparativa[$i]->obis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]		=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppastf"]		=	$comparativa[$i]->ppastf;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppasts"]		=	$comparativa[$i]->ppasts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ffecha"]		=	$comparativa[$i]->lastfecha;
			

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	0;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz9"]	=	"";

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda4"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped4"]	=	00;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda9"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped9"]	=	0;
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
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('abarrotes,cedis,mercado,villas,tienda,trincheras,tenencia,ultra,tijeras')
				->from('llegaron')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto);
			$resu = $this->db->get()->result();
			if ($resu){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$resu[0]->abarrotes;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$resu[0]->tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$resu[0]->trincheras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$resu[0]->tijeras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$resu[0]->ultra;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$resu[0]->mercado;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$resu[0]->villas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$resu[0]->tenencia;
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

	public function getPedidosAllModerna($where=[],$fech=0,$tienda){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha1 = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$fecha4 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo2 = new DateInterval('P14D');
		$intervalo3 = new DateInterval('P21D');
		$intervalo4 = new DateInterval('P28D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$fecha4->sub($intervalo4);
		$user = $this->session->userdata();

		$this->db->select("finlast.lastfecha,finlast.lastfecha as fpastfecha,prod.obis,prod.unidad,ppast.precio_sistema as ppasts,ppast.precio_four as ppastf,r.precio AS reales,exist,prod.abarrotes,prod.cedis,prod.mercado,prod.pedregal,prod.tienda,prod.trincheras,prod.tenencia,prod.ultra,prod.tijeras,ctz_first.id_cotizacion,prod.registrazo,inv.codigo as codigo_factura ,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,proveedor_first.cargo,ctz_first.precio AS precio_firsto,sto.cantidad as stocant,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.fecha_registro AS fecha_nxts,ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,ii.imagen,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->join("images ii","prod.id_producto = ii.id_producto ","LEFT")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.estatus = 1 AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("(select * from prodcaja order by id_prodcaja DESC) fac", "prod.id_producto = fac.id_producto AND ctz_first.id_proveedor = fac.id_proveedor", "LEFT")
		->join("invoice_codes inv", "fac.id_invoice = inv.id_invoice", "LEFT")
		->join("(SELECT * from precio_sistema WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as ppast","prod.id_producto = ppast.id_producto","LEFT" )
		->join("(SELECT MAX(fecha_registro) as lastfecha,id_producto from llegaron GROUP BY id_producto) as finlast","prod.id_producto = finlast.id_producto","LEFT" )
		->where("prod.estatus",4)
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]		=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["lastfecha"]		=	$comparativa[$i]->lastfecha;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo_factura"]		=	$comparativa[$i]->codigo_factura;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["exist"]	=	$comparativa[$i]->exist;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["stocant"]	=	$comparativa[$i]->stocant;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cargo"]	=	$comparativa[$i]->cargo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["reales"]	=	$comparativa[$i]->reales;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["registrazo"]	=	$comparativa[$i]->registrazo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["abarrotes"]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cedis"]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda"]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["trincheras"]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tijeras"]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ultra"]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["mercado"]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pedregal"]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tenencia"]	=	$comparativa[$i]->tenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["obis"]		=	$comparativa[$i]->obis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]		=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppastf"]		=	$comparativa[$i]->ppastf;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppasts"]		=	$comparativa[$i]->ppasts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ffecha"]		=	$comparativa[$i]->lastfecha;
			

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	0;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz9"]	=	"";

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda4"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped4"]	=	00;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda9"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped9"]	=	0;
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
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('abarrotes,cedis,mercado,villas,tienda,trincheras,tenencia,ultra,tijeras')
				->from('llegaron')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto);
			$resu = $this->db->get()->result();
			if ($resu){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$resu[0]->abarrotes;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$resu[0]->tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$resu[0]->trincheras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$resu[0]->tijeras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$resu[0]->ultra;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$resu[0]->mercado;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$resu[0]->villas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$resu[0]->tenencia;
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

	public function getResVol($where = []){
		$this->db->select("p.id_producto,p.nombre,p.codigo,l.cedis,l.abarrotes,l.villas,l.tienda,l.ultra,l.trincheras,l.mercado,l.tenencia,l.tijeras,l.lastfecha")
		->from("productos p")
		->join("(SELECT MAX(fecha_registro) as lastfecha,id_producto,cedis,abarrotes,villas,tienda,ultra,trincheras,mercado,tenencia,tijeras from llegaron GROUP BY id_producto) as l", "p.id_producto = l.id_producto", "LEFT")
		->where("p.estatus", 2)
		->order_by("l.lastfecha", "ASC");
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

	public function getResGen($where = []){
		$this->db->select("p.id_producto,p.nombre,p.codigo")
		->from("productos p")
		->where("p.estatus ", 1)
		->where("id_producto NOT IN (SELECT id_producto from llegaron where WEEKOFYEAR(fecha_registro) = ".$this->weekNumber().")")
		->order_by("p.nombre", "ASC");
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

	public function getPedidosAllCostena($where=[],$fech=0,$tienda){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha1 = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$fecha4 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo2 = new DateInterval('P14D');
		$intervalo3 = new DateInterval('P21D');
		$intervalo4 = new DateInterval('P28D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$fecha4->sub($intervalo4);
		$user = $this->session->userdata();

		$this->db->select("finlast.lastfecha,finlast.lastfecha as fpastfecha,prod.obis,prod.unidad,ppast.precio_sistema as ppasts,ppast.precio_four as ppastf,r.precio AS reales,exist,prod.abarrotes,prod.cedis,prod.mercado,prod.pedregal,prod.tienda,prod.trincheras,prod.tenencia,prod.ultra,prod.tijeras,ctz_first.id_cotizacion,prod.registrazo,inv.codigo as codigo_factura ,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,proveedor_first.cargo,ctz_first.precio AS precio_firsto,sto.cantidad as stocant,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.fecha_registro AS fecha_nxts,ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,ii.imagen,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->join("images ii","prod.id_producto = ii.id_producto ","LEFT")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.estatus = 1 AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("(select * from prodcaja order by id_prodcaja DESC) fac", "prod.id_producto = fac.id_producto AND ctz_first.id_proveedor = fac.id_proveedor", "LEFT")
		->join("invoice_codes inv", "fac.id_invoice = inv.id_invoice", "LEFT")
		->join("(SELECT * from precio_sistema WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as ppast","prod.id_producto = ppast.id_producto","LEFT" )
		->join("(SELECT MAX(fecha_registro) as lastfecha,id_producto from llegaron GROUP BY id_producto) as finlast","prod.id_producto = finlast.id_producto","LEFT" )
		->where("prod.estatus",5)
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]		=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["lastfecha"]		=	$comparativa[$i]->lastfecha;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo_factura"]		=	$comparativa[$i]->codigo_factura;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["exist"]	=	$comparativa[$i]->exist;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["stocant"]	=	$comparativa[$i]->stocant;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cargo"]	=	$comparativa[$i]->cargo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["reales"]	=	$comparativa[$i]->reales;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["registrazo"]	=	$comparativa[$i]->registrazo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["abarrotes"]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cedis"]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda"]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["trincheras"]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tijeras"]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ultra"]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["mercado"]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pedregal"]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tenencia"]	=	$comparativa[$i]->tenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["obis"]		=	$comparativa[$i]->obis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]		=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppastf"]		=	$comparativa[$i]->ppastf;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppasts"]		=	$comparativa[$i]->ppasts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ffecha"]		=	$comparativa[$i]->lastfecha;
			

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	0;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz9"]	=	"";

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda4"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped4"]	=	00;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda9"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped9"]	=	0;
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
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('abarrotes,cedis,mercado,villas,tienda,trincheras,tenencia,ultra,tijeras')
				->from('llegaron')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto);
			$resu = $this->db->get()->result();
			if ($resu){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$resu[0]->abarrotes;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$resu[0]->tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$resu[0]->trincheras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$resu[0]->tijeras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$resu[0]->ultra;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$resu[0]->mercado;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$resu[0]->villas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$resu[0]->tenencia;
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

	public function getPedidosAllCuetara($where=[],$fech=0,$tienda){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$fecha1 = new DateTime(date('Y-m-d H:i:s'));
		$fecha2 = new DateTime(date('Y-m-d H:i:s'));
		$fecha3 = new DateTime(date('Y-m-d H:i:s'));
		$fecha4 = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$intervalo2 = new DateInterval('P14D');
		$intervalo3 = new DateInterval('P21D');
		$intervalo4 = new DateInterval('P28D');
		$fecha->sub($intervalo);
		$fecha2->sub($intervalo2);
		$fecha3->sub($intervalo3);
		$fecha4->sub($intervalo4);
		$user = $this->session->userdata();

		$this->db->select("finlast.lastfecha,finlast.lastfecha as fpastfecha,prod.obis,prod.unidad,ppast.precio_sistema as ppasts,ppast.precio_four as ppastf,r.precio AS reales,exist,prod.abarrotes,prod.cedis,prod.mercado,prod.pedregal,prod.tienda,prod.trincheras,prod.tenencia,prod.ultra,prod.tijeras,ctz_first.id_cotizacion,prod.registrazo,inv.codigo as codigo_factura ,ctz_first.fecha_registro,prod.estatus,prod.color,prod.colorp,prod.codigo, prod.nombre AS producto,prod.id_producto,
			UPPER(proveedor_first.nombre) AS proveedor_first,proveedor_first.cargo,ctz_first.precio AS precio_firsto,sto.cantidad as stocant,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.observaciones AS promocion_first,ctz_first.observaciones AS observaciones_first,prod.precio_sistema,prod.precio_four,
			UPPER(proveedor_next.nombre) AS proveedor_next,ctz_next.fecha_registro AS fecha_next,ctz_next.observaciones AS promocion_next,
			ctz_next.precio AS precio_nexto,IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			UPPER(proveedor_nxts.nombre) AS proveedor_nxts,ctz_nxts.fecha_registro AS fecha_nxts,ctz_nxts.observaciones AS promocion_nxts,
			ctz_nxts.precio AS precio_nxtso,IF((ctz_nxts.precio_promocion >0), ctz_nxts.precio_promocion, ctz_nxts.precio) AS precio_nxts,
			ctz_maxima.precio AS precio_maximo,ii.imagen,
			AVG(c.precio) AS precio_promedio,prod.id_familia, prod.familia AS familia")
		->from("prodandprice prod")
		->join("reales r","prod.id_producto = r.id_producto AND WEEKOFYEAR(r.fecha_registro) = WEEKOFYEAR(CURDATE())","LEFT")
		->join("images ii","prod.id_producto = ii.id_producto ","LEFT")
		->join("cotizaciones c", "prod.id_producto = c.id_producto AND WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fech)." AND c.estatus = 1", "LEFT")
		->join("cotizaciones ctz_first", "ctz_first.id_cotizacion = (SELECT ctz_min.id_cotizacion FROM cotizaciones ctz_min LEFT JOIN usuarios uss on ctz_min.id_proveedor = uss.id_usuario	WHERE prod.id_producto = ctz_min.id_producto AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber($fech)." AND ctz_min.estatus = 1 AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber($fech).") ORDER BY uss.orden ASC LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_maxima", "ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE ctz_first.id_producto = ctz_max.id_producto AND ctz_max.precio = (SELECT  MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND ctz_max_precio.estatus = 1 AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber($fech).") LIMIT 1)", "LEFT")
		->join("cotizaciones ctz_next", "ctz_next.id_cotizacion = (SELECT cott.id_cotizacion FROM cotizaciones cott WHERE cott.id_producto = ctz_first.id_producto AND cott.estatus = 1 AND cott.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cott.fecha_registro) = ".$this->weekNumber($fech)." AND cott.id_proveedor <> ctz_first.id_proveedor ORDER BY cott.precio ASC LIMIT 1 )", "LEFT")
		->join("cotizaciones ctz_nxts", "ctz_nxts.id_cotizacion = (SELECT cots.id_cotizacion FROM cotizaciones cots WHERE cots.id_producto = ctz_first.id_producto AND cots.estatus = 1 AND cots.precio_promocion >= ctz_next.precio_promocion AND WEEKOFYEAR(cots.fecha_registro) = ".$this->weekNumber($fech)." AND cots.id_proveedor <> ctz_first.id_proveedor AND cots.id_proveedor <> ctz_next.id_proveedor ORDER BY cots.precio ASC LIMIT 1 )" ,"LEFT")
		->join("usuarios proveedor_first", "ctz_first.id_proveedor = proveedor_first.id_usuario", "LEFT")
		->join("usuarios proveedor_next", "ctz_next.id_proveedor = proveedor_next.id_usuario", "LEFT")
		->join("usuarios proveedor_nxts", "ctz_nxts.id_proveedor = proveedor_nxts.id_usuario", "LEFT")
		->join("stocks sto", "prod.id_producto = sto.id_producto", "LEFT")
		->join("(select * from prodcaja order by id_prodcaja DESC) fac", "prod.id_producto = fac.id_producto AND ctz_first.id_proveedor = fac.id_proveedor", "LEFT")
		->join("invoice_codes inv", "fac.id_invoice = inv.id_invoice", "LEFT")
		->join("(SELECT * from precio_sistema WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR('".$fecha->format('Y-m-d H:i:s')."') AND YEAR(fecha_registro) = YEAR('".$fecha->format('Y-m-d H:i:s')."') ) as ppast","prod.id_producto = ppast.id_producto","LEFT" )
		->join("(SELECT MAX(fecha_registro) as lastfecha,id_producto from llegaron GROUP BY id_producto) as finlast","prod.id_producto = finlast.id_producto","LEFT" )
		->where("prod.estatus",6)
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_cotizacion"]	=	$comparativa[$i]->id_cotizacion;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["producto"]		=	$comparativa[$i]->producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["imagen"]		=	$comparativa[$i]->imagen;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["lastfecha"]		=	$comparativa[$i]->lastfecha;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["estatus"]		=	$comparativa[$i]->estatus;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["id_producto"]		=	$comparativa[$i]->id_producto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo_factura"]		=	$comparativa[$i]->codigo_factura;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["codigo"]			=	$comparativa[$i]->codigo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_firsto"]	=	$comparativa[$i]->precio_firsto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_first"]	=	$comparativa[$i]->precio_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_first"]	=	$comparativa[$i]->proveedor_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_first"]	=	$comparativa[$i]->promocion_first;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_sistema"]	=	$comparativa[$i]->precio_sistema;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_four"]		=	$comparativa[$i]->precio_four;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nexto"]	=	$comparativa[$i]->precio_nexto;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_next"]		=	$comparativa[$i]->precio_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_next"]	=	$comparativa[$i]->proveedor_next;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxtso"]	=	$comparativa[$i]->precio_nxtso;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_nxts"]		=	$comparativa[$i]->precio_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["proveedor_nxts"]	=	$comparativa[$i]->proveedor_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_maximo"]	=	$comparativa[$i]->precio_maximo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["precio_promedio"]	=	$comparativa[$i]->precio_promedio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_next"]	=	$comparativa[$i]->promocion_next;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["promocion_nxts"]	=	$comparativa[$i]->promocion_nxts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["colorp"]	=	$comparativa[$i]->colorp;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["color"]	=	$comparativa[$i]->color;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["exist"]	=	$comparativa[$i]->exist;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["stocant"]	=	$comparativa[$i]->stocant;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cargo"]	=	$comparativa[$i]->cargo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["reales"]	=	$comparativa[$i]->reales;

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["registrazo"]	=	$comparativa[$i]->registrazo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["abarrotes"]	=	$comparativa[$i]->abarrotes;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["cedis"]	=	$comparativa[$i]->cedis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda"]	=	$comparativa[$i]->tienda;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["trincheras"]	=	$comparativa[$i]->trincheras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tijeras"]	=	$comparativa[$i]->tijeras;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ultra"]	=	$comparativa[$i]->ultra;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["mercado"]	=	$comparativa[$i]->mercado;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pedregal"]	=	$comparativa[$i]->pedregal;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tenencia"]	=	$comparativa[$i]->tenencia;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["obis"]		=	$comparativa[$i]->obis;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["unidad"]		=	$comparativa[$i]->unidad;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppastf"]		=	$comparativa[$i]->ppastf;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ppasts"]		=	$comparativa[$i]->ppasts;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ffecha"]		=	$comparativa[$i]->lastfecha;
			

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	0;


			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz9"]	=	"";

			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja0"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped0"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped0"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja1"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped1"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped1"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja2"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped2"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped2"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja3"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped3"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped3"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja4"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped4"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda4"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped4"]	=	00;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja5"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped5"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped5"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja6"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped6"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped6"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja7"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped7"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped7"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja8"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped8"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped8"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja9"]		=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped9"]	=	"";
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda9"]	=	0;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped9"]	=	0;
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
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('id_pedido,
				  id_producto,
				  id_tienda,
				  cajas,
				  piezas,
				  pedido,
				  fecha_registro')
				->from('existencias')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto)
				->order_by("id_tienda", "ASC");
			$resu = $this->db->get()->result();
			for ($d=0; $d<sizeof($resu); $d++){
				switch ($resu[$d]->id_tienda) {
					case '87':
						$e = "0";
						break;
					case '57':
						$e = "1";
						break;
					case '90':
						$e = "2";
						break;
					case '58':
						$e = "3";
						break;
					case '59':
						$e = "4";
						break;
					case '60':
						$e = "5";
						break;
					case '61':
						$e = "6";
						break;
					case '62':
						$e = "7";
						break;
					case '63':
						$e = "8";
						break;
					case '89':
						$e = "9";
						break;
					default:
						$e = "10";
						break;
				}
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["caja".$e]		=	$resu[$d]->cajas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["pz".$e]	=	$resu[$d]->piezas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["ped".$e]	=	$resu[$d]->pedido;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["tienda".$e]	=	$resu[$d]->id_tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto]["past"]["idped".$e]	=	$resu[$d]->id_pedido;
			}

			$pedidos = $this->db->select('abarrotes,cedis,mercado,villas,tienda,trincheras,tenencia,ultra,tijeras')
				->from('llegaron')
				->where('WEEKOFYEAR(fecha_registro)',$this->weekNumber($comparativa[$i]->lastfecha))
				->where('id_producto',$comparativa[$i]->id_producto);
			$resu = $this->db->get()->result();
			if ($resu){
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][57]	=	$resu[0]->abarrotes;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][87]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][89]	=	$resu[0]->cedis;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][58]	=	$resu[0]->tienda;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][60]	=	$resu[0]->trincheras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][63]	=	$resu[0]->tijeras;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][59]	=	$resu[0]->ultra;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][61]	=	$resu[0]->mercado;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][90]	=	$resu[0]->villas;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->id_producto][62]	=	$resu[0]->tenencia;
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
}





/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */
