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
			cotizaciones.nombre AS promocion,
			cotizaciones.precio,
			cotizaciones.precio_factura,
			cotizaciones.num_one,
			cotizaciones.num_two,
			cotizaciones.descuento,
			cotizaciones.fecha_registro,
			cotizaciones.fecha_caduca,
			cotizaciones.existencias,
			cotizaciones.observaciones,
			UPPER(CONCAT(u.first_name,' ',u.last_name)) AS proveedor,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio_factura", "ASC");
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

	public function preciosBajosProveedor(){
		$query ="SELECT
			p.nombre AS producto,
			UPPER(CONCAT(proveedor_m.first_name,' ',proveedor_m.last_name)) AS proveedor_minimo,
			ct.precio AS precio_minimo,
			ct.precio_factura AS precio_factura_minimo,
			ct.nombre AS promocion_minimo,
			ct.observaciones AS observaciones_minimo,
			ct.num_one AS num_one_minimo,
			ct.num_two AS num_two_minimo,
			ct.descuento AS descuento_minimo,
			UPPER(CONCAT(proveedor_s.first_name,' ',proveedor_s.last_name)) AS proveedor_siguiente,
			ps.precio AS precio_siguiente,
			ps.precio_factura AS precio_factura_siguiente,
			ps.nombre AS promocion_siguiente,
			ps.observaciones AS observaciones_siguiente,
			ps.num_one AS num_one_siguiente,
			ps.num_two AS num_two_siguiente,
			ps.descuento AS descuento_siguiente
			FROM  cotizaciones
				LEFT JOIN productos p ON cotizaciones.id_producto = p.id_producto
				JOIN cotizaciones ct
					ON ct.id_cotizacion = (SELECT
						p1.id_cotizacion 
						FROM cotizaciones p1
							WHERE cotizaciones.id_producto = p1.id_producto 
							AND p1.precio = (SELECT MIN(p2.precio)
							FROM cotizaciones p2
								WHERE p2.id_producto = p1.id_producto))
				LEFT JOIN cotizaciones ps 
					ON ps.id_cotizacion =(SELECT
						id_cotizacion 
						FROM cotizaciones
							WHERE cotizaciones.id_producto = ct.id_producto
							AND cotizaciones.precio >= ct.precio
							AND cotizaciones.id_cotizacion <> ct.id_cotizacion
							ORDER BY cotizaciones.precio ASC
							LIMIT 1)
				LEFT JOIN users proveedor_m ON ct.id_proveedor = proveedor_m.id
				LEFT JOIN users proveedor_s ON ps.id_proveedor = proveedor_s.id
				GROUP BY ct.id_producto
				ORDER BY ct.id_producto DESC, ct.precio ASC;";
			return $this->db->query($query,FALSE)->result();
	}

	public function productos_proveedor($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion, cotizaciones.precio,
			p.id_producto, UPPER(p.nombre) AS producto,
			u.id, UPPER(CONCAT(u.first_name,' ',u.last_name)) AS proveedor")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1);
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

	public function comparaPrecios($where=[]){
		$this->db->select("
			cotizaciones.id_cotizacion,
			pro.id, UPPER(CONCAT(pro.first_name,' ',pro.last_name)) AS proveedor,
			WEEKOFYEAR(DATE_ADD(ctz_befor.fecha_actualiza, INTERVAL 1 WEEK)) AS week_befor,
			ctz_befor.nombre AS promocion_befor,
			ctz_befor.precio AS precio_befor,
			prod.codigo AS codigo,
			prod.nombre AS producto,
			fam.id_familia, fam.nombre AS familia,
			WEEKOFYEAR(cotizaciones.fecha_actualiza) AS week_now,
			cotizaciones.nombre AS promocion_now,
			cotizaciones.precio_nuevo AS precio_now")
		->from($this->TABLE_NAME)
		->join("cotizaciones ctz_befor", $this->TABLE_NAME.".id_producto = ctz_befor.id_producto", "LEFT")
		->join("users pro", $this->TABLE_NAME.".id_proveedor = pro.id", "LEFT")
		->join("productos prod", $this->TABLE_NAME.".id_producto = prod.id_producto", "LEFT")
		->join("familias fam", "prod.id_familia = fam.id_familia", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->where("ctz_befor.fecha_actualiza !=", NULL)
		->where("WEEKOFYEAR(DATE_ADD(ctz_befor.fecha_registro, INTERVAL 1 WEEK)) = WEEKOFYEAR({$this->TABLE_NAME}.fecha_actualiza)")
		->group_by($this->TABLE_NAME.".id_cotizacion");
		if($where !== NULL){
			if (is_array($where)) {
				foreach ($where as $field=>$value) {
					$this->db->where($field, $value);
				}
			} else {
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

}

/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */