<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizaciones";
		$this->PRI_INDEX = "id_cotizacion";
	}

	public function getLastCot($where = [],$id_proveedor){
		$fecha = new DateTime(date('Y-m-d'));
		$this->db->select("c.id_cotizacion,c.id_proveedor,c.id_producto,c.precio,c.precio_promocion,c.num_one,c.num_two,c.descuento,c.observaciones,c.fecha_registro,p.nombre FROM cotizaciones c LEFT JOIN productos p ON c.id_producto = p.id_producto where c.id_proveedor = ".$id_proveedor." AND DATE(c.fecha_registro) = (SELECT DATE(MAX(fecha_registro)) from cotizaciones where id_proveedor = ".$id_proveedor.") ORDER BY c.fecha_registro DESC");
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

	public function getDiferences($where = []){
		$fecha = new DateTime(date('Y-m-d'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$result = $this->db->query("SELECT c.id_cotizacion,p.codigo,p.nombre as descrip,p.precio_sistema, c.precio_promocion, c.id_proveedor,u.nombre, (p.precio_sistema - c.precio_promocion) AS diferencia,
		c.precio, c.fecha_registro, c.estatus, c.observaciones, c.descuento, c.num_one, c.num_two FROM prodandprice p LEFT JOIN cotizaciones c ON p.id_producto = c.id_producto and c.estatus = 1  
		LEFT JOIN usuarios u ON c.id_proveedor = u.id_usuario WHERE WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fecha->format('Y-m-d H:i:s'))." AND 
		(p.precio_sistema - c.precio_promocion) > (p.precio_sistema * 0.2) OR WEEKOFYEAR(c.fecha_registro) = ".$this->weekNumber($fecha->format('Y-m-d H:i:s'))." AND 
		(c.precio_promocion - p.precio_sistema) > (p.precio_sistema * 0.2) ORDER BY diferencia DESC")->result();

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
	
}