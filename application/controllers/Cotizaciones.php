<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Proveedores_model", "prov_mdl");
		$this->load->model("Productos_proveedor_model", "prod_prov_mdl");
	}

	public function cotizaciones_view(){
		$data["cotizaciones"] = $this->prod_prov_mdl->getCotizados();
		$data["scripts"] = ["/assets/scripts/cotizaciones"];
		$this->estructura("Cotizaciones/table_cotizaciones", $data);
		// $this->load->view("Cotizaciones/table_cotizaciones", $data, FALSE);
	}

	public function add_cotizacion(){
		$data["title"]="Registrar cotizaciones";
		$this->load->view("Structure/header_modal", $data);
		$data["productos"] = $this->prod_mdl->get('id_producto, nombre');
		$this->load->view("Cotizaciones/new_cotizacion", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function save_cotizados(){
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$new_asignados[]= array(
				'id_proveedor' => $this->input->post('id_proveedor'),
				'id_producto' => $productos[$i],
				'fecha_registro' => date('Y-m-d H:i:s'),
				'precio' => str_replace(",", "", $this->input->post('precio[]')[$i])
			);
		}
		if($this->prod_prov_mdl->insertm($new_asignados) > 0){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Productos registrados correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se agregaron los productos cotizados',
				"type"	=> 'error'
			];
		}
		$this->jsonResponse($mensaje);
	}

}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */