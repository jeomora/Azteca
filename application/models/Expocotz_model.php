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

	public function comparaCotizaciones2($where=[], $fech,$id_proveedor){
		$this->db->select("e.id_cotizacion,e.id_proveedor,e.precio,e.precio_promocion,e.observaciones,e.fecha_registro,e.estatus,e.color,e.colorp,e.codigo, e.producto,e.id_producto,e.proveedor, e.precio_maximo, e.precio_promedio,e.id_familia, e.familia,e.precio_sistema,e.precio_four, expo.precio as xprecio, expo.precio_promocion as xpromo, expo.observaciones as xobs FROM expocotz expo LEFT JOIN expos e ON expo.id_producto = e.id_producto WHERE expo.id_proveedor = ".$id_proveedor." AND WEEKOFYEAR(expo.fecha_registro) = WEEKOFYEAR(CURDATE()) and expo.estatus = 1")
		;
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
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xprecio"]	=	$comparativa[$i]->xprecio;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xpromo"]		=	$comparativa[$i]->xpromo;
				$comparativaIndexada[$comparativa[$i]->id_familia]["articulos"][$comparativa[$i]->producto]["xobs"]		=	$comparativa[$i]->xobs;
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
			}
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
