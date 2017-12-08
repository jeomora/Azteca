<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Proveedores_model", "prov_mdl");
		$this->load->model("Promociones_model", "prom_mdl");
		$this->load->model("Productos_proveedor_model", "prod_prov_mdl");
	}


	public function promociones_view(){
		$data["promociones"] = $this->prom_mdl->get();
		$data["proveedores"] = $this->prov_mdl->getProveedores(['ug.group_id'=>2]);
		$data["scripts"] = ["/assets/scripts/promociones"];
		$this->estructura("Promociones/table_promociones", $data);
	}

	public function add_promocion(){
		$data["title"]="Registrar promociones";
		$this->load->view("Structure/header_modal", $data);
		$data["productos"] = $this->prod_mdl->get();
		$this->load->view("Promociones/new_promocion", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function FunctionName($id){
		# code...
	}

}

/* End of file Promociones.php */
/* Location: ./application/controllers/Promociones.php */