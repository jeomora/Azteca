<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prolunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "pro_lunes";
		$this->PRI_INDEX = "codigo";
	} 

	public function getCount($where=[]){
		$this->db->select("count(*) as noprod")
		->from($this->TABLE_NAME." p1")
		->where("p1.estatus","1");
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

	public function getProductos($where=[]){
		$this->db->select("p1.codigo,p1.descripcion,p2.alias,p2.nombre,p1.unidad,p1.precio,p1.sistema,p1.observaciones,c.id_catalogo,promo")
		->from($this->TABLE_NAME." p1")
		->join("prove_lunes p2","p1.id_proveedor = p2.id_proveedor","LEFT")
		->join("catalogos c", "p1.codigo = c.id_producto","LEFT")
		->join("promo_lunes pl","p1.codigo = pl.codigo","LEFT")
		->where("p1.estatus","1")
		->order_by("p1.descripcion","ASC");
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

	public function getMaxOrden($where=[]){
		$this->db->select("MAX(orden) as ordenes")
		->from($this->TABLE_NAME." p1")
		->order_by("p1.descripcion","ASC");
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


	public function buscaProdis($where=[],$values,$tiendas){
		$arrayName = array(87,0,89,57,90,58,59,60,61,62,63);
		$value = json_decode($values);
		$this->db->select("p.codigo,ss.orden,,p.descripcion,p.unidad,p.fecha_registro,p.precio,p.sistema,p.estatus,e.id_tienda,e.cajas as ecajas, e.piezas as epiezas,e.pedido as epedido,WEEKOFYEAR(p.fecha_sistema) as sis,WEEKOFYEAR(CURDATE()) as cur FROM pro_lunes p LEFT JOIN ex_lunes e ON p.codigo = e.id_producto AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) LEFT JOIN suc_lunes ss on e.id_tienda = ss.id_sucursal WHERE (p.codigo LIKE '%".$value->busca."%' OR p.descripcion LIKE '%".$value->busca."%')")
		->order_by("ss.orden","ASC");
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		$codigo = "";
		$flag = 0;
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->codigo])) {
				if (isset($comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden])) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["pzs"]	=	$comparativa[$i]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["cja"]	=	$comparativa[$i]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["ped"]	=	$comparativa[$i]->epedido;
				}
			}else{
				$flag++;
				$comparativaIndexada[$comparativa[$i]->codigo]					=	[];
				$comparativaIndexada[$comparativa[$i]->codigo]["codigo"]		=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->codigo]["descripcion"]	=	$comparativa[$i]->descripcion;
				$comparativaIndexada[$comparativa[$i]->codigo]["precio"]		=	$comparativa[$i]->precio;
				$comparativaIndexada[$comparativa[$i]->codigo]["sistema"]	=	$comparativa[$i]->sistema;
				$comparativaIndexada[$comparativa[$i]->codigo]["unidad"]		=	$comparativa[$i]->unidad;
				$comparativaIndexada[$comparativa[$i]->codigo]["sis"]		=	$comparativa[$i]->sis;
				$comparativaIndexada[$comparativa[$i]->codigo]["cur"]		=	$comparativa[$i]->cur;
				
				$comparativaIndexada[$comparativa[$i]->codigo]["existencias"]	=	[];
				$comparativaIndexada[$comparativa[$i]->codigo]["exist"]	=	[];
				foreach ($arrayName as $k => $v) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["pzs"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["cja"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["ped"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["pzs"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["cja"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["ped"]	=	0;
				}
				if (isset($comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden])) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["pzs"]	=	$comparativa[$i]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["cja"]	=	$comparativa[$i]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["ped"]	=	$comparativa[$i]->epedido;
				}
			}
			if ($codigo <> $comparativa[$i]->codigo) {
				$codigo = $comparativa[$i]->codigo;
				$this->db->select("e.id_tienda,e.cajas as ecajas,ss.orden, e.piezas as epiezas,e.pedido as epedido, e.fecha_registro FROM ex_lunes e LEFT JOIN suc_lunes ss on e.id_tienda = ss.id_sucursal WHERE e.id_producto = '".$comparativa[$i]->codigo."' and WEEKOFYEAR(e.fecha_registro) < WEEKOFYEAR(CURDATE()) GROUP BY id_tienda ORDER BY fecha_registro DESC");
				$comparativa2 = $this->db->get()->result();
				for ($e=0; $e<sizeof($comparativa2); $e++){
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["pzs"]	=	$comparativa2[$e]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["cja"]	=	$comparativa2[$e]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["ped"]	=	$comparativa2[$e]->epedido;
				}
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


	public function printProdis($where=[],$prove,$tiendas){
		$arrayName = array(87,0,89,57,90,58,59,60,61,62,63);
		$this->db->select("pro.promo,pro.descuento,pro.cuantos1,pro.cuantos2,pro.mins,p.codigo,ss.orden,p.descripcion,p.observaciones,p.unidad,p.fecha_registro,p.precio,p.sistema,p.estatus,e.id_tienda,e.cajas as ecajas, e.piezas as epiezas,e.pedido as epedido,WEEKOFYEAR(p.fecha_sistema) as sis,WEEKOFYEAR(CURDATE()) as cur FROM pro_lunes p LEFT JOIN ex_lunes e ON p.codigo = e.id_producto AND WEEKOFYEAR(e.fecha_registro) = WEEKOFYEAR(CURDATE()) LEFT JOIN suc_lunes ss on e.id_tienda = ss.id_sucursal LEFT JOIN promo_lunes pro ON p.codigo = pro.codigo WHERE p.id_proveedor = ".$prove." AND p.estatus =1 ")
		->order_by("ss.orden,p.orden","ASC");
		if ($where !== NULL) {
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
				$this->db->where($this->PRI_INDEX, $where);
			}
		}
		$comparativa = $this->db->get()->result();
		$comparativaIndexada = [];
		$codigo = "";
		$flag = 0;
		for ($i=0; $i<sizeof($comparativa); $i++) {
			if (isset($comparativaIndexada[$comparativa[$i]->codigo])) {
				if (isset($comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden])) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["pzs"]	=	$comparativa[$i]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["cja"]	=	$comparativa[$i]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["ped"]	=	$comparativa[$i]->epedido;
				}
			}else{
				$flag++;
				$comparativaIndexada[$comparativa[$i]->codigo]					=	[];
				$comparativaIndexada[$comparativa[$i]->codigo]["codigo"]		=	$comparativa[$i]->codigo;
				$comparativaIndexada[$comparativa[$i]->codigo]["descripcion"]	=	$comparativa[$i]->descripcion;
				$comparativaIndexada[$comparativa[$i]->codigo]["precio"]		=	$comparativa[$i]->precio;
				$comparativaIndexada[$comparativa[$i]->codigo]["sistema"]	=	$comparativa[$i]->sistema;
				$comparativaIndexada[$comparativa[$i]->codigo]["unidad"]		=	$comparativa[$i]->unidad;
				$comparativaIndexada[$comparativa[$i]->codigo]["observaciones"]		=	$comparativa[$i]->observaciones;
				$comparativaIndexada[$comparativa[$i]->codigo]["sis"]		=	$comparativa[$i]->sis;
				$comparativaIndexada[$comparativa[$i]->codigo]["cur"]		=	$comparativa[$i]->cur;
				$comparativaIndexada[$comparativa[$i]->codigo]["promo"]		=	$comparativa[$i]->promo;
				$comparativaIndexada[$comparativa[$i]->codigo]["descuento"]		=	$comparativa[$i]->descuento;
				$comparativaIndexada[$comparativa[$i]->codigo]["cuantos2"]		=	$comparativa[$i]->cuantos2;
				$comparativaIndexada[$comparativa[$i]->codigo]["cuantos1"]		=	$comparativa[$i]->cuantos1;
				$comparativaIndexada[$comparativa[$i]->codigo]["mins"]		=	$comparativa[$i]->mins;
				
				$comparativaIndexada[$comparativa[$i]->codigo]["existencias"]	=	[];
				$comparativaIndexada[$comparativa[$i]->codigo]["exist"]	=	[];
				foreach ($arrayName as $k => $v) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["pzs"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["cja"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$k+1]["ped"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["pzs"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["cja"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$k+1]["ped"]	=	0;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][$k+1]["pend"]	=	0;
				}
				if (isset($comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden])) {
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["pzs"]	=	$comparativa[$i]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["cja"]	=	$comparativa[$i]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["existencias"][$comparativa[$i]->orden]["ped"]	=	$comparativa[$i]->epedido;
				}
			}
			if ($codigo <> $comparativa[$i]->codigo) {
				$codigo = $comparativa[$i]->codigo;
				$this->db->select("e.id_tienda,e.cajas as ecajas,ss.orden, e.piezas as epiezas,e.pedido as epedido, e.fecha_registro FROM ex_lunes e LEFT JOIN suc_lunes ss on e.id_tienda = ss.id_sucursal WHERE e.id_producto = '".$comparativa[$i]->codigo."' and WEEKOFYEAR(e.fecha_registro) < WEEKOFYEAR(CURDATE()) GROUP BY id_tienda ORDER BY fecha_registro DESC");
				$comparativa2 = $this->db->get()->result();
				for ($e=0; $e<sizeof($comparativa2); $e++){
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["pzs"]	=	$comparativa2[$e]->epiezas;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["cja"]	=	$comparativa2[$e]->ecajas;
					$comparativaIndexada[$comparativa[$i]->codigo]["exist"][$comparativa2[$e]->orden]["ped"]	=	$comparativa2[$e]->epedido;
				}

				$this->db->select("id_producto,cedis,abarrotes,pedregal,tienda,ultra,trincheras,mercado,tenencia,tijeras FROM pendlunes WHERE id_producto = '".$comparativa[$i]->codigo."' and WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE())");
				$comparativa3 = $this->db->get()->result();
				if ($comparativa3) {
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][1]["pend"]	=	$comparativa3[0]->cedis;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][4]["pend"]	=	$comparativa3[0]->abarrotes;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][5]["pend"]	=	$comparativa3[0]->pedregal;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][6]["pend"]	=	$comparativa3[0]->tienda;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][7]["pend"]	=	$comparativa3[0]->ultra;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][8]["pend"]	=	$comparativa3[0]->trincheras;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][9]["pend"]	=	$comparativa3[0]->mercado;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][10]["pend"]	=	$comparativa3[0]->tenencia;
					$comparativaIndexada[$comparativa[$i]->codigo]["pend"][11]["pend"]	=	$comparativa3[0]->tijeras;
				}
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

/* End of file Proveedores_model.php */
/* Location: ./application/models/Proveedores_model.php */
