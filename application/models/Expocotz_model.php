<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expocotz_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "expocotz";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getAllCotizaciones($where = []){
		$this->db->select("
			expocotz.id_cotizacion,
			expocotz.id_proveedor,
			expocotz.precio,
			expocotz.precio_promocion,
			expocotz.num_one,
			expocotz.num_two,
			expocotz.descuento,
			expocotz.fecha_registro,
			expocotz.observaciones,
			UPPER(u.nombre) AS proveedor,
			p.nombre AS producto,p.codigo")
		->from("expocotz")
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

	public function comparaCotizaciones2($where=[], $fech){
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
			AVG(c.precio) AS precio_promedio,
			expo.precio as xprecio, expo.precio_promocion as xpromo, expo.observaciones as xobs")
		->from("expocotz expo")
		->join("prodandprice prod", "expo.id_producto = prod.id_producto", "LEFT")
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
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xprecio"]	=	$comparativa[$i]->xprecio;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xpromo"]		=	$comparativa[$i]->xpromo;
			$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xobs"]		=	$comparativa[$i]->xobs;
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

						$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["caja".$e]		=	$resu[$d]->cajas;
						$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["pz".$e]	=	$resu[$d]->piezas;
						$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["ped".$e]	=	$resu[$d]->pedido;
						$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["tienda".$e]	=	$resu[$d]->id_tienda;
						$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["idped".$e]	=	$resu[$d]->id_pedido;
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

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */
