<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Proveedores_model", "prov_mdl");
	}

	public function cotizaciones_view(){

		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/assets/scripts/cotizaciones',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$this->load->view("Cotizaciones/table_cotizaciones", $data, FALSE);
	}

	public function add_cotizacion(){
		$data["title"]="Registrar cotizaciones";
		$this->load->view("Structure/header_modal", $data);

		$this->load->view("Cotizaciones/new_cotizacion", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function update_cotizacion($id){
		$data["title"]="Actualizar cotización";
		$this->load->view("Structure/header_modal", $data);


		$this->load->view("Structure/footer_modal_edit");
	}

	public function delete_cotizacion($id){
		$data["title"]="Cotización a eliminar";
		$this->load->view("Structure/header_modal", $data);

		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($parm){

	}
}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */