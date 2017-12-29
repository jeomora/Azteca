<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_proveedor_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "productos_proveedor";
		$this->PRI_INDEX = "id_producto_proveedor";
	}

	public function getProductos_proveedor($where = []){
		$this->db->select("
			productos_proveedor.id_producto_proveedor,
			productos_proveedor.precio,
			productos_proveedor.descuento,
			productos_proveedor.total_descuento,
			DATE_FORMAT(productos_proveedor.fecha_registro, '%d-%m-%Y') AS fecha,
			UPPER(p.nombre) AS producto,
			p.codigo,
			u.first_name,
			u.last_name,
			UPPER(f.nombre) AS familia")
		->from($this->TABLE_NAME)
		->join("productos p", $this->TABLE_NAME.".id_producto = p.id_producto", "LEFT")
		->join("familias f", "p.id_familia = f.id_familia", "LEFT")
		->join("users u", $this->TABLE_NAME.".id_proveedor = u.id", "LEFT")
		->where($this->TABLE_NAME.".estatus", 1)
		->order_by($this->TABLE_NAME.".precio", "ASC");
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

	public function productos_proveedor($where=[]){
		$this->db->select("
			productos_proveedor.id_producto_proveedor, productos_proveedor.precio,
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

	public function preciosBajosProveedor(){
		$query ="SELECT
			p.nombre AS producto,
			UPPER(CONCAT(proveedor_m.first_name,' ',proveedor_m.last_name)) AS proveedor_minimo,
			prod_fisrt.precio AS precio_minimo,
			prod_fisrt.descuento AS descuento_minimo,
			prod_fisrt.total_descuento AS precio_descuento_minimo,
			UPPER(CONCAT(proveedor_s.first_name,' ',proveedor_s.last_name)) AS proveedor_siguiente,
			prod_next.precio AS precio_siguiente,
			prod_next.descuento AS descuento_siguiente,
			prod_next.total_descuento AS precio_descuento_siguiente
			FROM  productos_proveedor
				LEFT JOIN productos p ON productos_proveedor.id_producto = p.id_producto
				JOIN productos_proveedor prod_fisrt
					ON prod_fisrt.id_producto_proveedor = (SELECT
						prod_prov_one.id_producto_proveedor 
						FROM productos_proveedor prod_prov_one
							WHERE productos_proveedor.id_producto = prod_prov_one.id_producto 
							AND prod_prov_one.precio = (SELECT MIN(prod_prov_two.precio)
							FROM productos_proveedor prod_prov_two
								WHERE prod_prov_two.id_producto = prod_prov_one.id_producto))
				LEFT JOIN productos_proveedor prod_next 
					ON prod_next.id_producto_proveedor =(SELECT
						id_producto_proveedor 
						FROM productos_proveedor
							WHERE productos_proveedor.id_producto = prod_fisrt.id_producto
							AND productos_proveedor.precio >= prod_fisrt.precio
							AND productos_proveedor.id_producto_proveedor <> prod_fisrt.id_producto_proveedor
							ORDER BY productos_proveedor.precio ASC
							LIMIT 1)
				LEFT JOIN users proveedor_m ON prod_fisrt.id_proveedor = proveedor_m.id
				LEFT JOIN users proveedor_s ON prod_next.id_proveedor = proveedor_s.id
				GROUP BY prod_fisrt.id_producto
				ORDER BY prod_fisrt.id_producto DESC, prod_fisrt.precio ASC;";
			return $this->db->query($query,FALSE)->result();
	}

}

/* End of file Productos_proveedor_model.php */
/* Location: ./application/models/Productos_proveedor_model.php */