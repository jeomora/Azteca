<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_model extends CI_Model {


	public function levelOne(){
		return $this->db->select("menus.id_menu, menus.nombre, menus.depende,
			menus.icono, menus.nivel, menus.ruta,
			m2.nombre AS nombre2, m2.ruta AS ruta2,
			m3.nombre AS nombre3, m3.ruta AS ruta3")
			->from("menus")
			->join("menus m2", "menus.depende = m2.id_menu", "LEFT")
			->join("menus m3", "menus.depende = m3.id_menu", "LEFT")
			->where("menus.estatus", 1)
			->get()->result();
	}

	public function levelTwo(){
		return $this->db->select("menus.id_menu, menus.nombre, menus.depende,
			menus.icono, menus.nivel, menus.ruta")
			->from("menus")
			->where("menus.estatus", 1)
			->get()->result();
	}


}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
