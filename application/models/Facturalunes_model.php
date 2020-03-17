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

	public function getFactClic($where=[],$tienda,$folio,$cual){
		$this->db->select("pl.prods,pl.promo,pl.descuento,pl.prod,pl.cuantos1,pl.cuantos2,pl.mins,pl.ieps,p.observaciones,p.precio as prices,f.folio,f.id_factura,".$cual." as pedido,fl.costo,f.codigo,f.descripcion,f.cantidad,f.precio,f.id_proveedor,f.id_tienda,f.fecha_registro,f.fecha_factura,c.id_producto FROM factura_lunes f LEFT JOIN catalogos c ON f.codigo = c.id_catalogo LEFT JOIN finalunes fl on c.id_producto = fl.id_producto AND WEEKOFYEAR(fl.fecha_registro) = WEEKOFYEAR(CURDATE()) LEFT JOIN pro_lunes p ON c.id_producto = p.codigo LEFT JOIN (SELECT pll.promo,pll.descuento,pll.codigo,pll.prod,pll.cuantos1,pll.cuantos2,pll.mins,pll.ieps,plll.id_catalogo as prods FROM promo_lunes pll LEFT JOIN catalogos plll ON pll.prod = plll.id_producto) pl ON c.id_producto = pl.codigo WHERE f.folio = '".$folio."' AND f.id_tienda = ".$tienda." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE())")
		->order_by("f.codigo,f.precio","DESC");
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
			if (isset($comparativaIndexada[$result[$i]->codigo])) {
				$comparativaIndexada[$result[$i]->codigo]["cantidad"]	+= 	$result[$i]->cantidad;
			}else{
				$comparativaIndexada[$result[$i]->codigo]["cantidad"]	= 	$result[$i]->cantidad;

			}

			//$comparativaIndexada[$comparativa[$i]->producto][$comparativa[$i]->id_tienda]	=	$comparativa[$i]->cuants;
			
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

}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
