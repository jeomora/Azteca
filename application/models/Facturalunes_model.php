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

}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
