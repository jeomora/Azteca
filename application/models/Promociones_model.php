<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "promociones";
		$this->PRI_INDEX = "id_promocion";
	}

	public function getPromociones($where = []){
		$this->db->select("
			promociones.id_promocion,
			promociones.nombre AS promocion,
			promociones.precio_fijo,
			promociones.precio_descuento,
			promociones.precio_inicio,
			promociones.precio_fin,
			promociones.descuento,
			promociones.fecha_registro,
			promociones.fecha_caduca,
			promociones.existencias,
			promociones.observaciones,
			u.first_name, u.last_name,
			p.nombre AS producto")
		->from($this->TABLE_NAME)
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio_fijo", "ASC");
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

	public function preciosBajos(){
		$query ="SELECT
			p.nombre AS producto,
			UPPER(CONCAT(proveedor_m.first_name,' ',proveedor_m.last_name)) AS proveedor_minimo,
			pm.precio_fijo AS precio_minimo,
			pm.precio_descuento AS precio_descuento_minimo,
			pm.nombre AS promocion_minimo,
			pm.observaciones AS observaciones_minimo,
			pm.precio_inicio AS precio_inicio_minimo,
			pm.precio_fin AS precio_fin_minimo,
			pm.descuento AS descuento_minimo,
			UPPER(CONCAT(proveedor_s.first_name,' ',proveedor_s.last_name)) AS proveedor_siguiente,
			ps.precio_fijo AS precio_siguiente,
			ps.precio_descuento AS precio_descuento_siguiente,
			ps.nombre AS promocion_siguiente,
			ps.observaciones AS observaciones_siguiente,
			ps.precio_inicio AS precio_inicio_siguiente,
			ps.precio_fin AS precio_fin_siguiente,
			ps.descuento AS descuento_siguiente
			FROM  promociones
				LEFT JOIN productos p ON promociones.id_producto = p.id_producto
				JOIN promociones pm
					ON pm.id_promocion = (SELECT
						p1.id_promocion 
						FROM promociones p1
							WHERE promociones.id_producto = p1.id_producto 
							AND p1.precio_fijo = (SELECT MIN(p2.precio_fijo)
							FROM promociones p2
								WHERE p2.id_producto = p1.id_producto))
				LEFT JOIN promociones ps 
					ON ps.id_promocion =(SELECT
						id_promocion 
						FROM promociones
							WHERE promociones.id_producto = pm.id_producto
							AND promociones.precio_fijo >= pm.precio_fijo
							AND promociones.id_promocion <> pm.id_promocion
							ORDER BY promociones.precio_fijo ASC
							LIMIT 1)
				LEFT JOIN users proveedor_m ON pm.id_proveedor = proveedor_m.id
				LEFT JOIN users proveedor_s ON ps.id_proveedor = proveedor_s.id
				GROUP BY pm.id_producto
				ORDER BY pm.id_producto DESC, pm.precio_fijo ASC;";
			return $this->db->query($query,FALSE)->result();
	}

}

/* End of file Promociones_model.php */
/* Location: ./application/models/Promociones_model.php */