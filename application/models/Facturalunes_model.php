<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturalunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "factura_lunes";
		$this->PRI_INDEX = "id_factura";
	}

	public function getFactos($where=[],$values){
		//$stri = json_decode($values);
		$this->db->select("f.id_factura,f.folio,f.codigo,f.id_producto,f.cantidad,f.precio,f.id_sucursal,f.id_proveedor,p.codigo as pcod,p.descripcion AS pdesc,p.unidad,p.id_proveedor AS pprove,p.precio AS pprecio,p.sistema,pl.nombre as plnombre, pl.alias as plalias,s.nombre as sucursal,s.color,pr.codigo as prcodigo,descuento,promo,prod,pr.cuantos1, cuantos2, mins,(fl.cedis+fl.abarrotes+fl.villas+fl.tienda+fl.ultra+fl.trincheras+fl.mercado+fl.tenencia+fl.tijeras) as totalp,p.observaciones,fl.costo,fl.cedis,fl.abarrotes,fl.villas,fl.tienda,fl.ultra, fl.trincheras,fl.mercado,fl.tenencia,fl.tijeras")
		->from("factura_lunes f")
		->join("pro_lunes p","f.id_producto = p.codigo","LEFT")
		->join("prove_lunes pl","f.id_proveedor = pl.id_proveedor","LEFT")
		->join("suc_lunes s","f.id_sucursal = s.id_sucursal","LEFT")
		->join("promo_lunes pr","f.id_producto = pr.codigo","LEFT")
		->join("finalunes fl","f.id_producto = fl.id_producto","LEFT")
		->where("folio","".$values."")
		->order_by("f.id_factura");
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

	public function getSemFacts($where=[],$prove){
		$this->db->select("folio,nombre,color,id_tienda FROM factura_lunes f LEFT JOIN suc_lunes s ON f.id_tienda = s.id_sucursal WHERE WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) AND id_proveedor = ".$prove." GROUP BY f.folio,f.id_tienda");
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

	public function getFactClic($where=[],$values,$cual){
		$value = json_decode($values);
		$this->db->select("c.id_catalogo,f.codigo as prods,pl.promo,pl.descuento,pl.prod,pl.cuantos1,pl.cuantos2,pl.mins,pl.ieps,p.observaciones,p.precio as prices,f.folio,f.id_factura,".$cual." as pedido,fl.costo,f.codigo,f.descripcion,f.cantidad,f.precio,f.id_proveedor,f.id_tienda,f.fecha_registro,f.fecha_factura,c.id_producto FROM factura_lunes f LEFT JOIN catalogos c ON f.codigo = c.id_catalogo LEFT JOIN finalunes fl on f.id_producto = fl.id_producto AND WEEKOFYEAR(fl.fecha_registro) = WEEKOFYEAR(CURDATE()) LEFT JOIN pro_lunes p ON f.id_producto = p.codigo LEFT JOIN promo_lunes pl ON f.id_producto = pl.codigo WHERE f.folio = '".$value->folio."' AND f.id_tienda = ".$value->tienda." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE())")
		->order_by("pl.promo ASC,f.codigo,f.precio DESC");
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
		$comparativaIndexada = [];
		for ($i=0; $i<sizeof($result); $i++) {
			if($result[$i]->id_producto <> "" && $result[$i]->id_producto <> null) {
				if (isset($comparativaIndexada[$result[$i]->id_producto])) {
					$comparativaIndexada[$result[$i]->id_producto]["cantidad"]	+= 	$result[$i]->cantidad;
				}else{
					$comparativaIndexada[$result[$i]->id_producto]["cantidad"]	= 	$result[$i]->cantidad;
				}
			}else{
				if (isset($comparativaIndexada[$result[$i]->codigo])) {
					$comparativaIndexada[$result[$i]->codigo]["cantidad"]	+= 	$result[$i]->cantidad;
				}else{
					$comparativaIndexada[$result[$i]->codigo]["cantidad"]	= 	$result[$i]->cantidad;
				}
			}

			
		}
		if ($result) {
			if (is_array($where)) {
				return array($result,$comparativaIndexada);
			} else {
				return array($result,$comparativaIndexada);
			}
		} else {
			return false;
		}
	}

	public function profactu($where=[],$proveedor){
		$this->db->select("f.id_proveedor,f.folio as folio,f.id_tienda as tienda,f.id_tienda FROM factura_lunes f WHERE f.id_proveedor = ".$proveedor." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) GROUP BY f.folio ORDER BY f.folio");
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

	public function getDetails($where=[],$values,$which){
		$value = json_decode($values);
		$this->db->select("prom.promo,prom.descuento,prom.prod,prom.cuantos1,prom.cuantos2,prom.mins,prom.ieps,ff.costo,ff.".$which." as wey,u.nombre as prove,f.fecha_factura,f.fecha_registro,s.color,s.nombre as tienda,f.folio,f.precio,f.descripcion, f.cantidad,pp.descripcion as pprod,f.codigo FROM factura_lunes f LEFT JOIN suc_lunes s ON f.id_tienda = s.id_sucursal LEFT JOIN prove_lunes u ON f.id_proveedor = u.id_proveedor LEFT JOIN pro_lunes pp ON f.id_producto = pp.codigo LEFT JOIN finalunes ff ON f.id_producto = ff.id_producto AND WEEKOFYEAR(ff.fecha_registro) = WEEKOFYEAR(CURDATE()) LEFT JOIN promo_lunes prom ON f.id_producto = prom.codigo AND prom.estatus <> 0 WHERE f.folio = '".$value->folio."' AND f.id_proveedor = '".$value->id_proveedor."' AND f.id_tienda = '".$value->id_tienda."' GROUP BY f.id_factura");
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

	public function getfinals($where=[],$proveedor){
		$this->db->select("codigo,p.descripcion,fs.alias as proveedor,cedis,abarrotes,villas,tienda,ultra,trincheras,mercado,tenencia,tijeras FROM finales f LEFT JOIN pro_lunes p ON f.id_producto = p.codigo LEFT JOIN prove_lunes fs ON p.id_proveedor = fs.id_proveedor WHERE f.id_proveedor = ".$proveedor." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE())");
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

	public function getFacts($where=[], $proveedor){
		$this->db->select("id_tienda,id_producto as producto,SUM(cantidad) as cuants FROM factura_lunes WHERE WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) AND id_proveedor = ".$proveedor." GROUP BY id_producto,id_tienda");
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
				# code...
			}else{
				$comparativaIndexada[$comparativa[$i]->producto]		=	[];
				$comparativaIndexada[$comparativa[$i]->producto][57]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][58]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][59]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][60]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][61]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][62]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][63]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][87]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][89]	=	0;
				$comparativaIndexada[$comparativa[$i]->producto][90]	=	0;
			}

			$comparativaIndexada[$comparativa[$i]->producto][$comparativa[$i]->id_tienda]	=	$comparativa[$i]->cuants;
			
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

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
