<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_model extends CI_Model {

	public function getMenus(){
		$this->db->select("menus.id_menu, menus.nombre, menus.depende,
			menus.icono, menus.nivel, menus.ruta")
		->from("menus")
		->where("menus.estatus", 1);
		$menus = $this->db->get()->result();
		if($menus){
			foreach ($menus as $a => $value) {
				$submenu = $this->db->select("m2.nombre AS nombre2,
					m2.ruta as ruta2")
				->from("menus m2")
				->where("m2.estatus", 1)
				->where("m2.depende", $value->id_menu)
				->get()->result();
			if($submenu){
				foreach ($submenu as $b => $val) {
						$menus[$a]->submenu[$b] = $submenu[$b];
					}
				}
			}
		}
		return $menus;
	}


}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
