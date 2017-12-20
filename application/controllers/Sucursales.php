<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Sucursales_model", "suc_md");
	}

/*	public function sucursales_view(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/sucursales',
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
		$data["sucursales"] = $this->suc_md->get();
		$this->estructura("Sucursales/table_sucursales", $data);
	}*/

	public function sucursales_view(){
		$data["sucursales"] = $this->suc_md->get();
		$this->load->view("Sucursales/table_sucursales", $data, FALSE);
	}

	public function add_sucursal(){
		$data["title"]="Registrar sucursales";
		$this->load->view("Structure/header_modal", $data);
		$this->load->view("Sucursales/new_sucursal", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function get_update($id){
		$data["title"]="Actualizar datos de la sucursal";
		$this->load->view("Structure/header_modal", $data);
		$data["sucursal"] = $this->suc_md->get(NULL, ['id_sucursal'=>$id])[0];
		$this->load->view("Sucursales/edit_sucursal", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function get_delete($id){
		$data["title"]="Sucursal a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["sucursal"] = $this->suc_md->get(NULL, ['id_sucursal'=>$id])[0];
		$this->load->view("Sucursales/delete_sucursal", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$sucursal = [
			'nombre'	=>	strtoupper($this->input->post('nombre')),
			'telefono'	=>	$this->input->post('telefono')
			];

		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				$data ['id_sucursal']=$this->suc_md->insert($sucursal);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Sucursal registrada correctamente',
					"type"	=> 'success'
				];
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_sucursal'] = $this->suc_md->update($sucursal, $this->input->post('id_sucursal'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Sucursal actualizada correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_sucursal'] = $this->suc_md->update(["estatus" => 0], $this->input->post('id_sucursal'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Sucursal eliminada correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}

	public function newSucursal(){
		$data["title"] = "AGREGAR SUCURSALES";
		$data["class"] = "add_sucursal";
		$data["view"] =  $this->load->view("Sucursales/sucursal_new", NULL, TRUE);
		$this->jsonResponse($data);
	}

}

/* End of file Sucursales.php */
/* Location: ./application/controllers/Sucursales.php */